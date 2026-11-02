<?php
/**
 * Fonctions utilitaires : réglages, périodes (mois), montants, libellés, notices.
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

/**
 * Réglages par défaut du plugin.
 */
function fwe_defaults() {
	return array(
		'association_name'     => 'Famille WÊ',
		'region'               => 'Dallas, Texas & Oklahoma',
		'monthly_amount'       => 20,
		'late_threshold'       => 3,
		'start_period'         => gmdate( 'Y-m' ),
		'treasurer_name'       => '',
		'zelle_contact'        => '',
		'zelle_note'           => '',
		'allow_registration'   => 1,
		'auto_notify'          => 1,
		'notify_email'         => 1,
		'enable_sms'           => 0,
		'enable_whatsapp'      => 0,
		'twilio_sid'           => '',
		'twilio_token'         => '',
		'twilio_sms_from'      => '',
		'twilio_whatsapp_from' => '',
		'delete_on_uninstall'  => 0,
		'page_id'              => 0,
	);
}

/**
 * Réglages courants fusionnés avec les valeurs par défaut.
 */
function fwe_settings() {
	$saved = get_option( 'fwe_settings', array() );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), fwe_defaults() );
}

/**
 * Lit un réglage.
 *
 * @param string $key Clé.
 */
function fwe_setting( $key ) {
	$settings = fwe_settings();
	return isset( $settings[ $key ] ) ? $settings[ $key ] : null;
}

/**
 * Met à jour un ou plusieurs réglages.
 *
 * @param array $values Valeurs à fusionner.
 */
function fwe_update_settings( array $values ) {
	update_option( 'fwe_settings', array_merge( fwe_settings(), $values ) );
}

/* -------------------------------------------------------------------------
 * Périodes « AAAA-MM »
 * ---------------------------------------------------------------------- */

/** Mois courant (fuseau horaire du site). */
function fwe_current_period() {
	return wp_date( 'Y-m' );
}

/**
 * Vérifie le format AAAA-MM.
 *
 * @param mixed $period Valeur.
 */
function fwe_valid_period( $period ) {
	return is_string( $period ) && 1 === preg_match( '/^\d{4}-(0[1-9]|1[0-2])$/', $period );
}

/**
 * Ajoute (ou retire) des mois à une période.
 *
 * @param string $period Période AAAA-MM.
 * @param int    $months Nombre de mois.
 */
function fwe_period_add( $period, $months ) {
	list( $y, $m ) = array_map( 'intval', explode( '-', $period ) );
	$total = $y * 12 + ( $m - 1 ) + (int) $months;
	return sprintf( '%04d-%02d', intdiv( $total, 12 ), ( $total % 12 ) + 1 );
}

/**
 * Liste des périodes entre deux mois inclus.
 *
 * @param string $from Début.
 * @param string $to   Fin.
 */
function fwe_periods_between( $from, $to ) {
	$out = array();
	if ( ! fwe_valid_period( $from ) || ! fwe_valid_period( $to ) || $from > $to ) {
		return $out;
	}
	for ( $p = $from; $p <= $to; $p = fwe_period_add( $p, 1 ) ) {
		$out[] = $p;
	}
	return $out;
}

/** Noms de mois en français. */
function fwe_month_names( $short = false ) {
	if ( $short ) {
		return array( 1 => 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.' );
	}
	return array( 1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre' );
}

/**
 * « novembre 2026 » ou « nov. 2026 ».
 *
 * @param string $period Période.
 * @param bool   $short  Format court.
 */
function fwe_period_label( $period, $short = false ) {
	if ( ! fwe_valid_period( $period ) ) {
		return '';
	}
	list( $y, $m ) = array_map( 'intval', explode( '-', $period ) );
	$names         = fwe_month_names( $short );
	return $names[ $m ] . ' ' . $y;
}

/**
 * Date « 12 nov. 2026 » à partir d'une date/datetime MySQL.
 *
 * @param string $value     Date.
 * @param bool   $with_time Ajouter l'heure.
 */
function fwe_format_date( $value, $with_time = false ) {
	if ( empty( $value ) || 0 === strpos( $value, '0000' ) ) {
		return '—';
	}
	$ts = strtotime( $value );
	if ( false === $ts ) {
		return '—';
	}
	$names = fwe_month_names( true );
	$out   = (int) gmdate( 'j', $ts ) . ' ' . $names[ (int) gmdate( 'n', $ts ) ] . ' ' . gmdate( 'Y', $ts );
	if ( $with_time ) {
		$out .= ' · ' . gmdate( 'H\hi', $ts );
	}
	return $out;
}

/* -------------------------------------------------------------------------
 * Montants (dollars américains)
 * ---------------------------------------------------------------------- */

/**
 * Formate un montant en USD : $20, $1,240, $12.50.
 *
 * @param float $amount Montant.
 */
function fwe_money( $amount ) {
	$amount   = (float) $amount;
	$decimals = ( abs( $amount - round( $amount ) ) < 0.005 ) ? 0 : 2;
	return '$' . number_format( $amount, $decimals, '.', ',' );
}

/**
 * Nettoie un montant saisi (accepte « 20 », « 20.5 », « 20,50 », « $20 »).
 *
 * @param mixed $raw Valeur brute.
 */
function fwe_parse_amount( $raw ) {
	$clean = str_replace( array( '$', ' ', ',' ), array( '', '', '.' ), (string) $raw );
	return is_numeric( $clean ) ? round( (float) $clean, 2 ) : 0.0;
}

/* -------------------------------------------------------------------------
 * Libellés
 * ---------------------------------------------------------------------- */

/**
 * Groupe de libellés traduisibles.
 *
 * @param string $group Groupe.
 */
function fwe_labels( $group ) {
	switch ( $group ) {
		case 'event_type':
			return array(
				'festival' => __( 'Festival', 'famille-we' ),
				'ag'       => __( 'Assemblée générale', 'famille-we' ),
				'general'  => __( 'Événement', 'famille-we' ),
			);
		case 'support_type':
			return array(
				'naissance' => __( 'Naissance', 'famille-we' ),
				'mariage'   => __( 'Mariage', 'famille-we' ),
				'deces'     => __( 'Décès', 'famille-we' ),
			);
		case 'member_status':
			return array(
				'actif'      => __( 'Actif', 'famille-we' ),
				'en_attente' => __( 'En attente', 'famille-we' ),
				'inactif'    => __( 'Inactif', 'famille-we' ),
			);
		case 'state':
			return array(
				'TX' => __( 'Texas', 'famille-we' ),
				'OK' => __( 'Oklahoma', 'famille-we' ),
			);
		case 'method':
			return array(
				'zelle'   => __( 'Zelle', 'famille-we' ),
				'especes' => __( 'Espèces', 'famille-we' ),
				'cheque'  => __( 'Chèque', 'famille-we' ),
				'autre'   => __( 'Autre', 'famille-we' ),
			);
		case 'channel':
			return array(
				'in_app'   => __( 'Application', 'famille-we' ),
				'email'    => __( 'E-mail', 'famille-we' ),
				'sms'      => __( 'SMS', 'famille-we' ),
				'whatsapp' => __( 'WhatsApp', 'famille-we' ),
			);
		case 'notif_status':
			return array(
				'envoye'        => __( 'Envoyé', 'famille-we' ),
				'echec'         => __( 'Échec', 'famille-we' ),
				'non_configure' => __( 'Non configuré', 'famille-we' ),
				'ignore'        => __( 'Coordonnée manquante', 'famille-we' ),
			);
		case 'notif_type':
			return array(
				'retard'    => __( 'Retard', 'famille-we' ),
				'rappel'    => __( 'Rappel', 'famille-we' ),
				'evenement' => __( 'Événement', 'famille-we' ),
				'ag'        => __( 'AG', 'famille-we' ),
				'soutien'   => __( 'Soutien', 'famille-we' ),
				'annonce'   => __( 'Annonce', 'famille-we' ),
			);
		case 'case_status':
			return array(
				'ouvert' => __( 'Ouvert', 'famille-we' ),
				'clos'   => __( 'Clos', 'famille-we' ),
			);
		case 'contribution_status':
			return array(
				'declaree'  => __( 'À confirmer', 'famille-we' ),
				'confirmee' => __( 'Confirmée', 'famille-we' ),
			);
	}
	return array();
}

/**
 * Libellé d'une valeur.
 *
 * @param string $group Groupe.
 * @param string $key   Valeur.
 */
function fwe_label( $group, $key ) {
	$labels = fwe_labels( $group );
	return isset( $labels[ $key ] ) ? $labels[ $key ] : (string) $key;
}

/**
 * Valeur autorisée ou valeur par défaut.
 *
 * @param string $group   Groupe de libellés.
 * @param mixed  $value   Valeur.
 * @param string $default Défaut.
 */
function fwe_choice( $group, $value, $default ) {
	$value = is_string( $value ) ? $value : '';
	return array_key_exists( $value, fwe_labels( $group ) ) ? $value : $default;
}

/**
 * Pastille (badge).
 *
 * @param string $text   Texte.
 * @param string $tone   success|warning|danger|neutral|primary|secondary.
 * @param string $testid data-testid.
 */
function fwe_badge( $text, $tone = 'neutral', $testid = '' ) {
	return sprintf(
		'<span class="fwe-badge fwe-badge--%1$s"%3$s>%2$s</span>',
		esc_attr( $tone ),
		esc_html( $text ),
		$testid ? ' data-testid="' . esc_attr( $testid ) . '"' : ''
	);
}

/**
 * Pastille de situation de cotisations.
 *
 * @param array  $dues   Résumé FWE_Dues::compute().
 * @param string $testid data-testid.
 */
function fwe_dues_badge( array $dues, $testid = '' ) {
	if ( 'inactif' === $dues['state'] ) {
		return fwe_badge( __( 'Non suivi', 'famille-we' ), 'neutral', $testid );
	}
	if ( 'a_jour' === $dues['state'] ) {
		return fwe_badge( __( 'À jour', 'famille-we' ), 'success', $testid );
	}
	/* translators: %d: nombre de mois. */
	$text = sprintf( _n( '%d mois de retard', '%d mois de retard', $dues['months_late'], 'famille-we' ), $dues['months_late'] );
	return fwe_badge( $text, 'critique' === $dues['state'] ? 'danger' : 'warning', $testid );
}

/* -------------------------------------------------------------------------
 * Membres
 * ---------------------------------------------------------------------- */

/**
 * Nom complet d'un membre.
 *
 * @param object $member Membre.
 */
function fwe_member_name( $member ) {
	if ( ! $member ) {
		return __( 'Membre supprimé', 'famille-we' );
	}
	return trim( $member->first_name . ' ' . $member->last_name );
}

/**
 * Initiales pour l'avatar.
 *
 * @param object $member Membre.
 */
function fwe_initials( $member ) {
	$first = function_exists( 'mb_substr' ) ? mb_substr( (string) $member->first_name, 0, 1 ) : substr( (string) $member->first_name, 0, 1 );
	$last  = function_exists( 'mb_substr' ) ? mb_substr( (string) $member->last_name, 0, 1 ) : substr( (string) $member->last_name, 0, 1 );
	return strtoupper( $first . $last );
}

/**
 * Normalise un numéro de téléphone au format E.164 (US par défaut).
 *
 * @param string $phone Numéro saisi.
 */
function fwe_normalize_phone( $phone ) {
	$phone = trim( (string) $phone );
	if ( '' === $phone ) {
		return '';
	}
	$plus   = 0 === strpos( $phone, '+' );
	$digits = preg_replace( '/\D+/', '', $phone );
	if ( '' === $digits ) {
		return '';
	}
	if ( $plus ) {
		return '+' . $digits;
	}
	if ( 10 === strlen( $digits ) ) {
		return '+1' . $digits;
	}
	if ( 11 === strlen( $digits ) && '1' === $digits[0] ) {
		return '+' . $digits;
	}
	return '+' . $digits;
}

/* -------------------------------------------------------------------------
 * Pages & messages
 * ---------------------------------------------------------------------- */

/**
 * URL de l'espace membre (page contenant le shortcode).
 *
 * @param array $args Paramètres de requête.
 */
function fwe_espace_url( array $args = array() ) {
	$page_id = (int) fwe_setting( 'page_id' );
	$url     = ( $page_id && 'publish' === get_post_status( $page_id ) ) ? get_permalink( $page_id ) : home_url( '/' );
	return $args ? add_query_arg( $args, $url ) : $url;
}

/** Messages affichés après une action (codes passés dans l'URL). */
function fwe_messages() {
	return array(
		// Succès.
		'member_saved'           => __( 'Membre enregistré.', 'famille-we' ),
		'member_deleted'         => __( 'Membre supprimé.', 'famille-we' ),
		'member_approved'        => __( 'Adhésion validée : le membre est maintenant actif.', 'famille-we' ),
		'member_invited'         => __( 'Accès créé : une invitation a été envoyée par e-mail.', 'famille-we' ),
		'payment_saved'          => __( 'Paiement enregistré.', 'famille-we' ),
		'payment_removed'        => __( 'Paiement annulé.', 'famille-we' ),
		'event_saved'            => __( 'Événement enregistré.', 'famille-we' ),
		'event_deleted'          => __( 'Événement supprimé.', 'famille-we' ),
		'case_saved'             => __( 'Cas de soutien enregistré.', 'famille-we' ),
		'case_deleted'           => __( 'Cas de soutien supprimé.', 'famille-we' ),
		'contribution_added'     => __( 'Contribution ajoutée.', 'famille-we' ),
		'contribution_confirmed' => __( 'Contribution confirmée.', 'famille-we' ),
		'contribution_deleted'   => __( 'Contribution supprimée.', 'famille-we' ),
		'reminder_sent'          => __( 'Rappel envoyé au membre.', 'famille-we' ),
		'check_done'             => __( 'Vérification des retards terminée.', 'famille-we' ),
		'announcement_sent'      => __( 'Annonce envoyée.', 'famille-we' ),
		'settings_saved'         => __( 'Réglages enregistrés.', 'famille-we' ),
		'registered'             => __( 'Bienvenue dans la Famille WÊ ! Votre compte est actif.', 'famille-we' ),
		'registered_pending'     => __( 'Inscription reçue ! Le bureau va valider votre adhésion très bientôt.', 'famille-we' ),
		'joined'                 => __( 'Demande d’adhésion envoyée au bureau.', 'famille-we' ),
		'profile_saved'          => __( 'Profil mis à jour.', 'famille-we' ),
		'contribution_declared'  => __( 'Merci ! Votre contribution est enregistrée ; le trésorier la confirmera à réception.', 'famille-we' ),
		'notifications_read'     => __( 'Notifications marquées comme lues.', 'famille-we' ),
		// Erreurs.
		'required'               => __( 'Merci de remplir les champs obligatoires.', 'famille-we' ),
		'invalid'                => __( 'Données invalides, merci de vérifier le formulaire.', 'famille-we' ),
		'email_exists'           => __( 'Un compte existe déjà avec cette adresse e-mail.', 'famille-we' ),
		'no_email'               => __( 'Ajoutez d’abord une adresse e-mail à ce membre.', 'famille-we' ),
		'already_paid'           => __( 'Ces mois étaient déjà réglés : aucun paiement ajouté.', 'famille-we' ),
		'login_failed'           => __( 'E-mail ou mot de passe incorrect.', 'famille-we' ),
		'register_closed'        => __( 'Les inscriptions en ligne sont fermées. Contactez le bureau.', 'famille-we' ),
		'password_short'         => __( 'Le mot de passe doit contenir au moins 8 caractères.', 'famille-we' ),
		'password_mismatch'      => __( 'Les deux mots de passe ne correspondent pas.', 'famille-we' ),
		'case_closed'            => __( 'Ce cas de soutien est clos.', 'famille-we' ),
		'not_member'             => __( 'Votre compte n’est pas encore rattaché à un membre actif.', 'famille-we' ),
	);
}

/**
 * Affiche la notice correspondant aux paramètres fwe_msg / fwe_err de l'URL.
 */
function fwe_render_notice() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- affichage seulement.
	$messages = fwe_messages();
	$out      = '';
	foreach ( array(
		'fwe_msg' => 'success',
		'fwe_err' => 'danger',
	) as $param => $tone ) {
		if ( empty( $_GET[ $param ] ) ) {
			continue;
		}
		$code = sanitize_key( wp_unslash( $_GET[ $param ] ) );
		if ( ! isset( $messages[ $code ] ) ) {
			continue;
		}
		$text = $messages[ $code ];
		if ( isset( $_GET['n'] ) && in_array( $code, array( 'payment_saved', 'check_done', 'announcement_sent' ), true ) ) {
			$n = absint( $_GET['n'] );
			if ( 0 === $n && 'check_done' === $code ) {
				$text = __( 'Vérification terminée : aucun nouveau membre à alerter.', 'famille-we' );
			} elseif ( 0 === $n && 'announcement_sent' === $code ) {
				$text = __( 'Aucun membre actif ne correspond : l’annonce n’a pas été envoyée.', 'famille-we' );
			} elseif ( 'payment_saved' === $code ) {
				/* translators: %d: nombre de mois. */
				$text = sprintf( _n( '%d mois de cotisation enregistré.', '%d mois de cotisation enregistrés.', $n, 'famille-we' ), $n );
			} elseif ( 'check_done' === $code ) {
				/* translators: %d: nombre de membres. */
				$text = sprintf( _n( 'Vérification terminée : %d membre alerté.', 'Vérification terminée : %d membres alertés.', $n, 'famille-we' ), $n );
			} else {
				/* translators: %d: nombre de membres. */
				$text = sprintf( _n( 'Annonce envoyée à %d membre.', 'Annonce envoyée à %d membres.', $n, 'famille-we' ), $n );
			}
		}
		$out .= sprintf(
			'<div class="fwe-notice fwe-notice--%1$s" role="status" data-testid="fwe-notice-%2$s">%3$s</div>',
			esc_attr( $tone ),
			esc_attr( $code ),
			esc_html( $text )
		);
	}
	// phpcs:enable
	echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé ci-dessus.
}

/**
 * Lit un champ POST texte.
 *
 * @param string $key Clé.
 */
function fwe_post_text( $key ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce vérifié par l'appelant.
	return isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
}

/**
 * Lit un champ POST multi-lignes.
 *
 * @param string $key Clé.
 */
function fwe_post_textarea( $key ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce vérifié par l'appelant.
	return isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : '';
}

/**
 * Lit un entier POST.
 *
 * @param string $key Clé.
 */
function fwe_post_int( $key ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce vérifié par l'appelant.
	return isset( $_POST[ $key ] ) ? absint( wp_unslash( $_POST[ $key ] ) ) : 0;
}

/**
 * Icône Lucide (SVG inline, trait 2px, extrémités arrondies).
 *
 * @param string $name  Nom de l'icône.
 * @param int    $size  Taille en pixels.
 */
function fwe_icon( $name, $size = 20 ) {
	static $paths = array(
		'users'    => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'user'     => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
		'wallet'   => '<path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>',
		'alert'    => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
		'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
		'heart'    => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
		'bell'     => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
		'check'    => '<path d="M20 6 9 17l-5-5"/>',
		'clock'    => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'plus'     => '<path d="M5 12h14"/><path d="M12 5v14"/>',
		'send'     => '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>',
		'copy'     => '<rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>',
		'baby'     => '<path d="M9 12h.01"/><path d="M15 12h.01"/><path d="M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5"/><path d="M19 6.3a9 9 0 0 1 1.8 3.9 2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1"/>',
		'gem'      => '<path d="M6 3h12l4 6-10 13L2 9Z"/><path d="M11 3 8 9l4 13 4-13-3-6"/><path d="M2 9h20"/>',
		'flower'   => '<circle cx="12" cy="12" r="3"/><path d="M12 16.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 1 1 12 7.5a4.5 4.5 0 1 1 4.5 4.5 4.5 4.5 0 1 1-4.5 4.5"/><path d="M12 7.5V9"/><path d="M7.5 12H9"/><path d="M16.5 12H15"/><path d="M12 16.5V15"/>',
		'logout'   => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
		'trend'    => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
		'megaphone' => '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
		'arrow'    => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'left'     => '<path d="m15 18-6-6 6-6"/>',
		'right'    => '<path d="m9 18 6-6-6-6"/>',
		'trash'    => '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>',
		'pencil'   => '<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>',
		'search'   => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="fwe-icon" xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		(int) $size,
		$paths[ $name ]
	);
}

/**
 * Icône associée à un type de soutien.
 *
 * @param string $type Type.
 */
function fwe_support_icon( $type ) {
	$map = array(
		'naissance' => 'baby',
		'mariage'   => 'gem',
		'deces'     => 'flower',
	);
	return fwe_icon( isset( $map[ $type ] ) ? $map[ $type ] : 'heart' );
}

/**
 * Valide une date AAAA-MM-JJ.
 *
 * @param string $date    Date.
 * @param string $default Défaut.
 */
function fwe_clean_date( $date, $default = '' ) {
	return ( is_string( $date ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) && strtotime( $date ) ) ? $date : $default;
}
