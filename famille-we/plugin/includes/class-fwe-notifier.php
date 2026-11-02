<?php
/**
 * Notifications : in-app, e-mail, SMS et WhatsApp (via Twilio), et vérification
 * automatique quotidienne des retards de cotisation.
 *
 * Chaque envoi est journalisé (canal + statut) pour que le bureau voie ce qui
 * est réellement parti. Sans configuration Twilio, les canaux SMS/WhatsApp sont
 * journalisés « Non configuré » au lieu d'échouer silencieusement.
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

/**
 * Envoi et journalisation des notifications.
 */
class FWE_Notifier {

	/** Branche la tâche planifiée. */
	public static function init() {
		add_action( FWE_Install::CRON_HOOK, array( __CLASS__, 'run_late_check' ) );
	}

	/**
	 * Canaux utilisés pour les alertes de retard : application + e-mail
	 * (si activé) + SMS + WhatsApp (statut journalisé même si non configurés).
	 */
	public static function alert_channels() {
		$channels = array( 'in_app' );
		if ( fwe_setting( 'notify_email' ) ) {
			$channels[] = 'email';
		}
		$channels[] = 'sms';
		$channels[] = 'whatsapp';
		return $channels;
	}

	/**
	 * Envoie une notification à un membre sur plusieurs canaux.
	 *
	 * @param object   $member   Membre.
	 * @param string   $type     Type (retard, rappel, evenement, ag, soutien, annonce).
	 * @param string   $title    Titre.
	 * @param string   $message  Message.
	 * @param string[] $channels Canaux.
	 * @param string   $ref      Référence de déduplication.
	 * @return array canal => statut.
	 */
	public static function send( $member, $type, $title, $message, array $channels = array( 'in_app' ), $ref = '' ) {
		$results = array();
		foreach ( array_unique( $channels ) as $channel ) {
			list( $status, $detail ) = self::deliver( $channel, $member, $title, $message );
			FWE_Repo::add_notification(
				array(
					'member_id' => (int) $member->id,
					'type'      => $type,
					'title'     => $title,
					'message'   => $message,
					'channel'   => $channel,
					'status'    => $status,
					'detail'    => $detail,
					'ref'       => $ref,
				)
			);
			$results[ $channel ] = $status;
		}
		return $results;
	}

	/**
	 * Envoi sur un canal.
	 *
	 * @param string $channel Canal.
	 * @param object $member  Membre.
	 * @param string $title   Titre.
	 * @param string $message Message.
	 * @return array [statut, détail].
	 */
	private static function deliver( $channel, $member, $title, $message ) {
		$settings = fwe_settings();

		switch ( $channel ) {
			case 'in_app':
				return array( 'envoye', '' );

			case 'email':
				if ( ! is_email( $member->email ) ) {
					return array( 'ignore', __( 'Pas d’adresse e-mail', 'famille-we' ) );
				}
				$body = $message . "\n\n" . sprintf(
					/* translators: %s: URL de l'espace membre. */
					__( 'Votre espace membre : %s', 'famille-we' ),
					fwe_espace_url()
				) . "\n\n— " . $settings['association_name'];
				$sent = wp_mail( $member->email, '[' . $settings['association_name'] . '] ' . $title, $body );
				return $sent ? array( 'envoye', '' ) : array( 'echec', __( 'Le serveur n’a pas pu envoyer l’e-mail', 'famille-we' ) );

			case 'sms':
			case 'whatsapp':
				$enabled = 'sms' === $channel ? $settings['enable_sms'] : $settings['enable_whatsapp'];
				$from    = 'sms' === $channel ? $settings['twilio_sms_from'] : $settings['twilio_whatsapp_from'];
				if ( ! $enabled || ! $settings['twilio_sid'] || ! $settings['twilio_token'] || ! $from ) {
					return array( 'non_configure', __( 'Activez et configurez Twilio dans les réglages', 'famille-we' ) );
				}
				$to = fwe_normalize_phone( $member->phone );
				if ( ! $to ) {
					return array( 'ignore', __( 'Pas de numéro de téléphone', 'famille-we' ) );
				}
				$from = fwe_normalize_phone( $from );
				if ( 'whatsapp' === $channel ) {
					$to   = 'whatsapp:' . $to;
					$from = 'whatsapp:' . $from;
				}
				return self::twilio( $to, $from, $title . "\n" . $message );
		}
		return array( 'echec', __( 'Canal inconnu', 'famille-we' ) );
	}

	/**
	 * Appel à l'API Twilio Messages.
	 *
	 * @param string $to   Destinataire.
	 * @param string $from Expéditeur.
	 * @param string $body Texte.
	 * @return array [statut, détail].
	 */
	private static function twilio( $to, $from, $body ) {
		$sid      = fwe_setting( 'twilio_sid' );
		$response = wp_remote_post(
			'https://api.twilio.com/2010-04-01/Accounts/' . rawurlencode( $sid ) . '/Messages.json',
			array(
				'timeout' => 15,
				'headers' => array(
					// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- authentification HTTP Basic requise par Twilio.
					'Authorization' => 'Basic ' . base64_encode( $sid . ':' . fwe_setting( 'twilio_token' ) ),
				),
				'body'    => array(
					'To'   => $to,
					'From' => $from,
					'Body' => $body,
				),
			)
		);
		if ( is_wp_error( $response ) ) {
			return array( 'echec', substr( $response->get_error_message(), 0, 250 ) );
		}
		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( $code >= 200 && $code < 300 ) {
			return array( 'envoye', '' );
		}
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		return array( 'echec', substr( is_array( $data ) && ! empty( $data['message'] ) ? $data['message'] : 'HTTP ' . $code, 0, 250 ) );
	}

	/**
	 * Message de retard adressé à un membre.
	 *
	 * @param object $member Membre.
	 * @param array  $dues   Situation.
	 * @return array [titre, message].
	 */
	public static function late_message( $member, array $dues ) {
		$settings = fwe_settings();
		$title    = sprintf(
			/* translators: %d: nombre de mois. */
			_n( 'Cotisation en retard (%d mois)', 'Cotisations en retard (%d mois)', $dues['months_late'], 'famille-we' ),
			$dues['months_late']
		);
		$message = sprintf(
			/* translators: 1: prénom, 2: nombre de mois, 3: liste des mois, 4: montant. */
			__( 'Bonjour %1$s, il manque %2$d mois de cotisation (%3$s), soit %4$s.', 'famille-we' ),
			$member->first_name,
			$dues['months_late'],
			FWE_Dues::periods_text( $dues['late'] ),
			fwe_money( $dues['amount_late'] )
		);
		if ( $settings['zelle_contact'] ) {
			$message .= ' ' . sprintf(
				/* translators: 1: trésorier, 2: contact Zelle. */
				__( 'Merci de régulariser par Zelle auprès de %1$s (%2$s).', 'famille-we' ),
				$settings['treasurer_name'] ? $settings['treasurer_name'] : __( 'la trésorerie', 'famille-we' ),
				$settings['zelle_contact']
			);
		} else {
			$message .= ' ' . __( 'Merci de régulariser auprès de la trésorerie.', 'famille-we' );
		}
		return array( $title, $message );
	}

	/**
	 * Vérification des retards : alerte chaque membre actif dont le retard
	 * dépasse le seuil, au plus une fois par mois et par membre.
	 *
	 * @param bool $force Lancer même si l'alerte automatique est désactivée.
	 * @return int Nombre de membres alertés.
	 */
	public static function run_late_check( $force = false ) {
		if ( ! $force && ! fwe_setting( 'auto_notify' ) ) {
			return 0;
		}
		$members = FWE_Repo::list_members( array( 'status' => 'actif' ) );
		$summary = FWE_Dues::summary_for( $members );
		$ref     = 'retard-' . fwe_current_period();
		$count   = 0;

		foreach ( $members as $member ) {
			$dues = $summary[ (int) $member->id ];
			if ( 'critique' !== $dues['state'] || FWE_Repo::notification_ref_exists( $member->id, $ref ) ) {
				continue;
			}
			list( $title, $message ) = self::late_message( $member, $dues );
			self::send( $member, 'retard', $title, $message, self::alert_channels(), $ref );
			++$count;
		}

		update_option( 'fwe_last_check', current_time( 'mysql' ) );
		return $count;
	}
}
