<?php
/**
 * Espace membre public : shortcodes [famille_we_espace] et [famille_we_evenements],
 * connexion, inscription, profil, contributions de soutien.
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front-end du plugin.
 */
class FWE_Public {

	/** Hooks. */
	public static function init() {
		add_shortcode( 'famille_we_espace', array( __CLASS__, 'shortcode_espace' ) );
		add_shortcode( 'famille_we_evenements', array( __CLASS__, 'shortcode_events' ) );
		add_action( 'template_redirect', array( __CLASS__, 'no_cache' ) );

		add_action( 'admin_post_nopriv_fwe_login', array( __CLASS__, 'handle_login' ) );
		add_action( 'admin_post_nopriv_fwe_register', array( __CLASS__, 'handle_register' ) );
		add_action( 'admin_post_fwe_login', array( __CLASS__, 'back_to_espace' ) );
		add_action( 'admin_post_fwe_register', array( __CLASS__, 'back_to_espace' ) );

		foreach ( array( 'join', 'update_profile', 'contribute', 'mark_read' ) as $action ) {
			add_action( 'admin_post_fwe_' . $action, array( __CLASS__, 'handle_' . $action ) );
			add_action( 'admin_post_nopriv_fwe_' . $action, array( __CLASS__, 'back_to_espace' ) );
		}
	}

	/** L'espace membre est personnel : pas de mise en cache de page. */
	public static function no_cache() {
		$page_id = (int) fwe_setting( 'page_id' );
		if ( $page_id && is_page( $page_id ) ) {
			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', true );
			}
			nocache_headers();
		}
	}

	/**
	 * Inclut une vue publique.
	 *
	 * @param string $name Nom.
	 * @param array  $vars Variables.
	 */
	private static function view( $name, array $vars = array() ) {
		$vars['s'] = fwe_settings();
		extract( $vars, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- variables de gabarit.
		include FWE_DIR . 'public/views/' . $name . '.php';
	}

	/**
	 * Redirige vers l'espace membre et termine.
	 *
	 * @param array  $args   Paramètres.
	 * @param string $anchor Ancre (#section).
	 */
	private static function go( array $args = array(), $anchor = '' ) {
		wp_safe_redirect( fwe_espace_url( $args ) . ( $anchor ? '#' . $anchor : '' ) );
		exit;
	}

	/** Retour à l'espace (actions non pertinentes dans l'état de connexion courant). */
	public static function back_to_espace() {
		self::go();
	}

	/**
	 * Vérifie le nonce d'un formulaire public.
	 *
	 * @param string $action Action.
	 */
	private static function check_nonce( $action ) {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- nonce.
		$nonce = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, $action ) ) {
			self::go( array( 'fwe_err' => 'invalid' ) );
		}
	}

	/** Fiche membre de l'utilisateur connecté (ou null). */
	private static function current_member() {
		return is_user_logged_in() ? FWE_Accounts::link_user( wp_get_current_user() ) : null;
	}

	/* ---------------------------------------------------------------------
	 * Shortcodes
	 * ------------------------------------------------------------------ */

	/** [famille_we_espace] */
	public static function shortcode_espace() {
		FWE_Assets::enqueue();
		ob_start();
		echo '<div class="fwe-app alignwide" data-testid="fwe-espace">';
		fwe_render_notice();

		if ( ! is_user_logged_in() ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- choix d'onglet.
			$tab = isset( $_GET['tab'] ) && 'inscription' === $_GET['tab'] ? 'register' : 'login';
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- affichage.
			$err = isset( $_GET['fwe_err'] ) ? sanitize_key( wp_unslash( $_GET['fwe_err'] ) ) : '';
			if ( in_array( $err, array( 'register_closed', 'password_short', 'password_mismatch', 'email_exists', 'required' ), true ) ) {
				$tab = 'register';
			}
			self::view( 'auth', array( 'tab' => $tab ) );
		} else {
			$user   = wp_get_current_user();
			$member = self::current_member();
			if ( ! $member ) {
				self::view( 'join', array( 'user' => $user ) );
			} elseif ( 'actif' !== $member->status ) {
				self::view( 'pending', array( 'member' => $member ) );
			} else {
				self::view( 'espace', self::espace_data( $member ) );
			}
		}

		echo '</div>';
		return ob_get_clean();
	}

	/**
	 * [famille_we_evenements limit="6"]
	 *
	 * @param array $atts Attributs.
	 */
	public static function shortcode_events( $atts ) {
		$atts = shortcode_atts( array( 'limit' => 6 ), $atts, 'famille_we_evenements' );
		FWE_Assets::enqueue();
		ob_start();
		echo '<div class="fwe-app alignwide">';
		self::view( 'events-list', array( 'events' => FWE_Repo::list_events( 'upcoming', absint( $atts['limit'] ) ) ) );
		echo '</div>';
		return ob_get_clean();
	}

	/**
	 * Données du tableau de bord membre.
	 *
	 * @param object $member Membre.
	 */
	private static function espace_data( $member ) {
		$current  = fwe_current_period();
		$dues     = FWE_Dues::for_member( $member );
		$payments = FWE_Repo::payments_for_member( $member->id );
		$paid_map = array();
		foreach ( $payments as $payment ) {
			$paid_map[ $payment->period ] = $payment;
		}
		$year       = substr( $current, 0, 4 );
		$year_total = 0.0;
		foreach ( $payments as $payment ) {
			if ( 0 === strpos( $payment->period, $year ) ) {
				$year_total += (float) $payment->amount;
			}
		}

		$cases       = FWE_Repo::list_cases( 'ouvert' );
		$my_by_case  = array();
		foreach ( FWE_Repo::contributions_for_member( $member->id ) as $contribution ) {
			$key                = (int) $contribution->case_id;
			$my_by_case[ $key ] = ( isset( $my_by_case[ $key ] ) ? $my_by_case[ $key ] : 0 ) + (float) $contribution->amount;
		}

		return array(
			'member'        => $member,
			'current'       => $current,
			'dues'          => $dues,
			'payments'      => array_slice( $payments, 0, 12 ),
			'paid_map'      => $paid_map,
			'history'       => fwe_periods_between( fwe_period_add( $current, -11 ), $current ),
			'start'         => FWE_Dues::start_period_for( $member ),
			'year_total'    => $year_total,
			'events'        => FWE_Repo::list_events( 'upcoming', 6 ),
			'cases'         => $cases,
			'case_totals'   => FWE_Repo::case_totals( wp_list_pluck( $cases, 'id' ) ),
			'my_by_case'    => $my_by_case,
			'notifications' => FWE_Repo::member_notifications( $member->id, 15 ),
			'unread'        => FWE_Repo::unread_count( $member->id ),
		);
	}

	/* ---------------------------------------------------------------------
	 * Actions
	 * ------------------------------------------------------------------ */

	/** Connexion. */
	public static function handle_login() {
		self::check_nonce( 'fwe_login' );
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- vérifié ci-dessus.
		$login    = isset( $_POST['log'] ) ? sanitize_text_field( wp_unslash( $_POST['log'] ) ) : '';
		$password = isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- mot de passe.
		$remember = ! empty( $_POST['rememberme'] );
		// phpcs:enable
		$user = wp_signon(
			array(
				'user_login'    => $login,
				'user_password' => $password,
				'remember'      => $remember,
			),
			is_ssl()
		);
		if ( is_wp_error( $user ) ) {
			self::go( array( 'fwe_err' => 'login_failed' ) );
		}
		self::go();
	}

	/** Inscription en ligne. */
	public static function handle_register() {
		self::check_nonce( 'fwe_register' );
		$args = array( 'tab' => 'inscription' );
		if ( ! fwe_setting( 'allow_registration' ) ) {
			self::go( $args + array( 'fwe_err' => 'register_closed' ) );
		}
		// Champ piège anti-robots : rempli = robot.
		if ( '' !== fwe_post_text( 'fwe_website' ) ) {
			self::go();
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- vérifié ci-dessus.
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$pass1 = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- mot de passe.
		$pass2 = isset( $_POST['pass2'] ) ? (string) wp_unslash( $_POST['pass2'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- mot de passe.
		// phpcs:enable
		$first = fwe_post_text( 'first_name' );
		$last  = fwe_post_text( 'last_name' );

		if ( '' === $first || '' === $last || ! is_email( $email ) ) {
			self::go( $args + array( 'fwe_err' => 'required' ) );
		}
		if ( strlen( $pass1 ) < 8 ) {
			self::go( $args + array( 'fwe_err' => 'password_short' ) );
		}
		if ( $pass1 !== $pass2 ) {
			self::go( $args + array( 'fwe_err' => 'password_mismatch' ) );
		}
		if ( email_exists( $email ) ) {
			self::go( $args + array( 'fwe_err' => 'email_exists' ) );
		}

		$user_id = wp_insert_user(
			array(
				'user_login'   => FWE_Accounts::unique_login( $email ),
				'user_email'   => $email,
				'user_pass'    => $pass1,
				'first_name'   => $first,
				'last_name'    => $last,
				'display_name' => $first . ' ' . $last,
				'role'         => 'fwe_membre',
			)
		);
		if ( is_wp_error( $user_id ) ) {
			self::go( $args + array( 'fwe_err' => 'invalid' ) );
		}

		$profile = array(
			'phone' => fwe_post_text( 'phone' ),
			'city'  => fwe_post_text( 'city' ),
			'state' => fwe_choice( 'state', fwe_post_text( 'state' ), 'TX' ),
		);
		$member  = FWE_Repo::get_member_by_email( $email );
		if ( $member && empty( $member->user_id ) ) {
			// Le bureau avait déjà enregistré ce membre : on rattache le compte.
			$update = array( 'user_id' => $user_id );
			foreach ( $profile as $key => $value ) {
				if ( '' === (string) $member->$key && '' !== $value ) {
					$update[ $key ] = $value;
				}
			}
			FWE_Repo::save_member( $update, $member->id );
			$status = $member->status;
		} else {
			FWE_Repo::save_member(
				$profile + array(
					'user_id'    => $user_id,
					'first_name' => $first,
					'last_name'  => $last,
					'email'      => $email,
					'join_date'  => current_time( 'Y-m-d' ),
					'status'     => 'en_attente',
				)
			);
			$status = 'en_attente';
			self::notify_bureau_new_member( $first . ' ' . $last, $email );
		}

		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id, true, is_ssl() );
		self::go( array( 'fwe_msg' => 'actif' === $status ? 'registered' : 'registered_pending' ) );
	}

	/**
	 * Prévient l'administrateur du site d'une nouvelle demande d'adhésion.
	 *
	 * @param string $name  Nom.
	 * @param string $email E-mail.
	 */
	private static function notify_bureau_new_member( $name, $email ) {
		$settings = fwe_settings();
		wp_mail(
			get_option( 'admin_email' ),
			'[' . $settings['association_name'] . '] ' . __( 'Nouvelle demande d’adhésion', 'famille-we' ),
			/* translators: 1: nom, 2: e-mail, 3: URL. */
			sprintf( __( "%1\$s (%2\$s) souhaite rejoindre la famille.\n\nValider l'adhésion : %3\$s", 'famille-we' ), $name, $email, admin_url( 'admin.php?page=fwe-membres&status=en_attente' ) )
		);
	}

	/** Un utilisateur WordPress existant demande à devenir membre. */
	public static function handle_join() {
		self::check_nonce( 'fwe_join' );
		if ( self::current_member() ) {
			self::go();
		}
		$user  = wp_get_current_user();
		$first = $user->first_name ? $user->first_name : $user->display_name;
		FWE_Repo::save_member(
			array(
				'user_id'    => $user->ID,
				'first_name' => $first,
				'last_name'  => $user->last_name,
				'email'      => $user->user_email,
				'phone'      => fwe_post_text( 'phone' ),
				'city'       => fwe_post_text( 'city' ),
				'state'      => fwe_choice( 'state', fwe_post_text( 'state' ), 'TX' ),
				'join_date'  => current_time( 'Y-m-d' ),
				'status'     => 'en_attente',
			)
		);
		self::notify_bureau_new_member( trim( $first . ' ' . $user->last_name ), $user->user_email );
		self::go( array( 'fwe_msg' => 'joined' ) );
	}

	/** Mise à jour du profil par le membre. */
	public static function handle_update_profile() {
		self::check_nonce( 'fwe_update_profile' );
		$member = self::current_member();
		if ( ! $member ) {
			self::go( array( 'fwe_err' => 'not_member' ) );
		}
		$data = array(
			'phone' => fwe_post_text( 'phone' ),
			'city'  => fwe_post_text( 'city' ),
			'state' => fwe_choice( 'state', fwe_post_text( 'state' ), $member->state ),
		);
		foreach ( array( 'first_name', 'last_name' ) as $key ) {
			$value = fwe_post_text( $key );
			if ( '' !== $value ) {
				$data[ $key ] = $value;
			}
		}
		FWE_Repo::save_member( $data, $member->id );
		self::go( array( 'fwe_msg' => 'profile_saved' ), 'fwe-profil' );
	}

	/** Le membre déclare une contribution à un cas de soutien. */
	public static function handle_contribute() {
		self::check_nonce( 'fwe_contribute' );
		$member = self::current_member();
		if ( ! $member || 'actif' !== $member->status ) {
			self::go( array( 'fwe_err' => 'not_member' ) );
		}
		$case   = FWE_Repo::get_case( fwe_post_int( 'case_id' ) );
		$amount = fwe_parse_amount( fwe_post_text( 'amount' ) );
		if ( ! $case || 'ouvert' !== $case->status || (int) $case->member_id === (int) $member->id ) {
			self::go( array( 'fwe_err' => 'case_closed' ), 'fwe-soutien' );
		}
		if ( $amount < 1 || $amount > 10000 ) {
			self::go( array( 'fwe_err' => 'invalid' ), 'fwe-soutien' );
		}
		FWE_Repo::add_contribution( $case->id, $member->id, $amount, 'declaree' );
		self::go( array( 'fwe_msg' => 'contribution_declared' ), 'fwe-soutien' );
	}

	/** Marque les notifications comme lues. */
	public static function handle_mark_read() {
		self::check_nonce( 'fwe_mark_read' );
		$member = self::current_member();
		if ( $member ) {
			FWE_Repo::mark_all_read( $member->id );
		}
		self::go( array( 'fwe_msg' => 'notifications_read' ), 'fwe-notifications' );
	}
}
