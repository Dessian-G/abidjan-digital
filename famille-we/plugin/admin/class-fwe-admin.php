<?php
/**
 * Administration (menu « Famille WÊ ») : pages et traitement des formulaires.
 *
 * Toutes les actions passent par admin-post.php, vérifient la capacité
 * « fwe_manage » (administrateurs + rôle « Bureau Famille WÊ ») et un nonce.
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

/**
 * Back-office du plugin.
 */
class FWE_Admin {

	/** Slug de page => vue. */
	const PAGES = array(
		'famille-we'        => 'dashboard',
		'fwe-membres'       => 'members',
		'fwe-cotisations'   => 'cotisations',
		'fwe-retards'       => 'retards',
		'fwe-evenements'    => 'events',
		'fwe-soutien'       => 'support',
		'fwe-notifications' => 'notifications',
		'fwe-reglages'      => 'settings',
	);

	/** Hooks. */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
		add_filter( 'admin_body_class', array( __CLASS__, 'body_class' ) );

		$actions = array(
			'save_member',
			'delete_member',
			'approve_member',
			'invite_member',
			'toggle_payment',
			'record_payment',
			'save_event',
			'delete_event',
			'save_case',
			'delete_case',
			'add_contribution',
			'confirm_contribution',
			'delete_contribution',
			'send_reminder',
			'run_check',
			'send_announcement',
			'save_settings',
		);
		foreach ( $actions as $action ) {
			add_action( 'admin_post_fwe_' . $action, array( __CLASS__, 'handle_' . $action ) );
		}
	}

	/* ---------------------------------------------------------------------
	 * Menus & ressources
	 * ------------------------------------------------------------------ */

	/** Page du plugin actuellement affichée (ou ''). */
	public static function current_page() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- lecture du slug de page.
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		return isset( self::PAGES[ $page ] ) ? $page : '';
	}

	/** Menu d'administration. */
	public static function menu() {
		$counts  = FWE_Repo::count_members_by_status();
		$pending = (int) $counts['en_attente'];
		$bubble  = $pending ? ' <span class="awaiting-mod">' . $pending . '</span>' : '';
		$render  = array( __CLASS__, 'render' );

		add_menu_page( __( 'Famille WÊ', 'famille-we' ), __( 'Famille WÊ', 'famille-we' ) . $bubble, 'fwe_manage', 'famille-we', $render, 'dashicons-groups', 26 );
		add_submenu_page( 'famille-we', __( 'Tableau de bord', 'famille-we' ), __( 'Tableau de bord', 'famille-we' ), 'fwe_manage', 'famille-we', $render );
		add_submenu_page( 'famille-we', __( 'Membres', 'famille-we' ), __( 'Membres', 'famille-we' ) . $bubble, 'fwe_manage', 'fwe-membres', $render );
		add_submenu_page( 'famille-we', __( 'Cotisations', 'famille-we' ), __( 'Cotisations', 'famille-we' ), 'fwe_manage', 'fwe-cotisations', $render );
		add_submenu_page( 'famille-we', __( 'Retards', 'famille-we' ), __( 'Retards', 'famille-we' ), 'fwe_manage', 'fwe-retards', $render );
		add_submenu_page( 'famille-we', __( 'Événements', 'famille-we' ), __( 'Événements', 'famille-we' ), 'fwe_manage', 'fwe-evenements', $render );
		add_submenu_page( 'famille-we', __( 'Soutien', 'famille-we' ), __( 'Soutien', 'famille-we' ), 'fwe_manage', 'fwe-soutien', $render );
		add_submenu_page( 'famille-we', __( 'Notifications', 'famille-we' ), __( 'Notifications', 'famille-we' ), 'fwe_manage', 'fwe-notifications', $render );
		add_submenu_page( 'famille-we', __( 'Réglages', 'famille-we' ), __( 'Réglages', 'famille-we' ), 'fwe_manage', 'fwe-reglages', $render );
	}

	/** Styles/scripts sur les pages du plugin uniquement. */
	public static function assets() {
		$page = self::current_page();
		if ( ! $page ) {
			return;
		}
		FWE_Assets::enqueue();
		if ( 'fwe-evenements' === $page ) {
			wp_enqueue_media();
		}
	}

	/**
	 * Classe CSS sur le body des pages du plugin.
	 *
	 * @param string $classes Classes.
	 */
	public static function body_class( $classes ) {
		return self::current_page() ? $classes . ' fwe-admin-body' : $classes;
	}

	/** Affiche la page courante. */
	public static function render() {
		if ( ! current_user_can( 'fwe_manage' ) ) {
			wp_die( esc_html__( 'Accès refusé.', 'famille-we' ), 403 );
		}
		$page   = self::current_page();
		$method = 'page_' . self::PAGES[ $page ? $page : 'famille-we' ];
		echo '<div class="wrap fwe-admin" data-testid="fwe-admin-' . esc_attr( self::PAGES[ $page ? $page : 'famille-we' ] ) . '">';
		echo '<hr class="wp-header-end">';
		fwe_render_notice();
		self::$method();
		echo '</div>';
	}

	/**
	 * Inclut une vue.
	 *
	 * @param string $name Nom du fichier (sans .php).
	 * @param array  $vars Variables.
	 */
	public static function view( $name, array $vars = array() ) {
		$vars['s'] = fwe_settings();
		extract( $vars, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- variables de gabarit.
		include FWE_DIR . 'admin/views/' . $name . '.php';
	}

	/**
	 * URL d'une page du plugin.
	 *
	 * @param string $page Slug.
	 * @param array  $args Paramètres.
	 */
	public static function url( $page, array $args = array() ) {
		return add_query_arg( $args, admin_url( 'admin.php?page=' . $page ) );
	}

	/**
	 * Champs cachés communs d'un formulaire d'action.
	 *
	 * @param string $action Action (sans préfixe fwe_).
	 * @param string $nonce  Action du nonce (par défaut fwe_{action}).
	 */
	public static function form_fields( $action, $nonce = '' ) {
		echo '<input type="hidden" name="action" value="fwe_' . esc_attr( $action ) . '">';
		wp_nonce_field( $nonce ? $nonce : 'fwe_' . $action );
	}

	/** URL de admin-post.php. */
	public static function post_url() {
		return admin_url( 'admin-post.php' );
	}

	/* ---------------------------------------------------------------------
	 * Pages
	 * ------------------------------------------------------------------ */

	/** Tableau de bord. */
	private static function page_dashboard() {
		$settings = fwe_settings();
		$current  = fwe_current_period();
		$counts   = FWE_Repo::count_members_by_status();
		$active   = FWE_Repo::list_members( array( 'status' => 'actif' ) );
		$summary  = FWE_Dues::summary_for( $active );

		$late = array();
		foreach ( $active as $member ) {
			$dues = $summary[ (int) $member->id ];
			if ( $dues['months_late'] > 0 ) {
				$late[] = array(
					'member' => $member,
					'dues'   => $dues,
				);
			}
		}
		usort(
			$late,
			function ( $a, $b ) {
				return $b['dues']['months_late'] - $a['dues']['months_late'];
			}
		);

		$chart_periods = fwe_periods_between( fwe_period_add( $current, -11 ), $current );
		$chart_totals  = FWE_Repo::totals_by_period( $chart_periods[0], $current );
		$cases         = FWE_Repo::list_cases( 'ouvert' );

		self::view(
			'dashboard',
			array(
				'current'        => $current,
				'counts'         => $counts,
				'active_count'   => count( $active ),
				'late'           => $late,
				'critical_count' => count(
					array_filter(
						$late,
						function ( $row ) {
							return 'critique' === $row['dues']['state'];
						}
					)
				),
				'late_amount'    => array_sum(
					array_map(
						function ( $row ) {
							return $row['dues']['amount_late'];
						},
						$late
					)
				),
				'month_total'    => FWE_Repo::sum_payments( $current, $current ),
				'month_expected' => count( $active ) * (float) $settings['monthly_amount'],
				'year_total'     => FWE_Repo::sum_payments( substr( $current, 0, 4 ) . '-01', substr( $current, 0, 4 ) . '-12' ),
				'chart_periods'  => $chart_periods,
				'chart_totals'   => $chart_totals,
				'events'         => FWE_Repo::list_events( 'upcoming', 4 ),
				'upcoming_count' => count( FWE_Repo::list_events( 'upcoming' ) ),
				'cases'          => $cases,
				'case_totals'    => FWE_Repo::case_totals( wp_list_pluck( $cases, 'id' ) ),
				'pending'        => FWE_Repo::list_members( array( 'status' => 'en_attente' ) ),
			)
		);
	}

	/** Membres : liste ou formulaire. */
	private static function page_members() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtres d'affichage.
		$view = isset( $_GET['view'] ) ? sanitize_key( wp_unslash( $_GET['view'] ) ) : '';
		if ( in_array( $view, array( 'new', 'edit' ), true ) ) {
			$member = null;
			if ( 'edit' === $view && ! empty( $_GET['id'] ) ) {
				$member = FWE_Repo::get_member( absint( $_GET['id'] ) );
			}
			self::view(
				'member-form',
				array(
					'member' => $member,
					'dues'   => $member ? FWE_Dues::for_member( $member ) : null,
					'user'   => ( $member && $member->user_id ) ? get_userdata( (int) $member->user_id ) : null,
				)
			);
			return;
		}

		$filters = array(
			'search' => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '',
			'state'  => isset( $_GET['state'] ) ? fwe_choice( 'state', sanitize_text_field( wp_unslash( $_GET['state'] ) ), '' ) : '',
			'status' => isset( $_GET['status'] ) ? fwe_choice( 'member_status', sanitize_key( wp_unslash( $_GET['status'] ) ), '' ) : '',
		);
		// phpcs:enable
		$members = FWE_Repo::list_members( $filters );
		self::view(
			'members',
			array(
				'members' => $members,
				'summary' => FWE_Dues::summary_for( $members ),
				'filters' => $filters,
				'counts'  => FWE_Repo::count_members_by_status(),
			)
		);
	}

	/** Cotisations : grille annuelle + saisie. */
	private static function page_cotisations() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtres d'affichage.
		$current = fwe_current_period();
		$year    = isset( $_GET['year'] ) ? absint( $_GET['year'] ) : (int) substr( $current, 0, 4 );
		$year    = ( $year >= 2000 && $year <= 2100 ) ? $year : (int) substr( $current, 0, 4 );
		$preset  = isset( $_GET['member_id'] ) ? absint( $_GET['member_id'] ) : 0;
		// phpcs:enable
		$members = array_values(
			array_filter(
				FWE_Repo::list_members(),
				function ( $m ) {
					return 'en_attente' !== $m->status;
				}
			)
		);
		self::view(
			'cotisations',
			array(
				'year'     => $year,
				'current'  => $current,
				'members'  => $members,
				'summary'  => FWE_Dues::summary_for( $members ),
				'payments' => FWE_Repo::payments_in_year( $year ),
				'totals'   => FWE_Repo::totals_by_period( sprintf( '%04d-01', $year ), sprintf( '%04d-12', $year ) ),
				'preset'   => $preset,
			)
		);
	}

	/** Retards. */
	private static function page_retards() {
		$active  = FWE_Repo::list_members( array( 'status' => 'actif' ) );
		$summary = FWE_Dues::summary_for( $active );
		$rows    = array();
		foreach ( $active as $member ) {
			if ( $summary[ (int) $member->id ]['months_late'] > 0 ) {
				$rows[] = array(
					'member' => $member,
					'dues'   => $summary[ (int) $member->id ],
				);
			}
		}
		usort(
			$rows,
			function ( $a, $b ) {
				return $b['dues']['months_late'] - $a['dues']['months_late'];
			}
		);
		self::view(
			'retards',
			array(
				'rows'       => $rows,
				'reminders'  => FWE_Repo::last_reminders(),
				'last_check' => get_option( 'fwe_last_check', '' ),
				'next_check' => wp_next_scheduled( FWE_Install::CRON_HOOK ),
			)
		);
	}

	/** Événements. */
	private static function page_events() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- affichage.
		$edit_id = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : 0;
		self::view(
			'events',
			array(
				'event'    => $edit_id ? FWE_Repo::get_event( $edit_id ) : null,
				'upcoming' => FWE_Repo::list_events( 'upcoming' ),
				'past'     => FWE_Repo::list_events( 'past', 20 ),
			)
		);
	}

	/** Soutien : liste, formulaire ou détail d'un cas. */
	private static function page_support() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- affichage.
		$case_id = isset( $_GET['case'] ) ? absint( $_GET['case'] ) : 0;
		$edit_id = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : 0;
		// phpcs:enable
		$members = FWE_Repo::list_members( array( 'status' => 'actif' ) );

		if ( $case_id ) {
			$case = FWE_Repo::get_case( $case_id );
			if ( $case ) {
				$totals = FWE_Repo::case_totals( array( $case_id ) );
				self::view(
					'support-case',
					array(
						'case'          => $case,
						'totals'        => $totals[ $case_id ],
						'contributions' => FWE_Repo::contributions_for_case( $case_id ),
						'members'       => $members,
					)
				);
				return;
			}
		}

		$cases = FWE_Repo::list_cases();
		self::view(
			'support',
			array(
				'cases'   => $cases,
				'totals'  => FWE_Repo::case_totals( wp_list_pluck( $cases, 'id' ) ),
				'members' => $members,
				'edit'    => $edit_id ? FWE_Repo::get_case( $edit_id ) : null,
			)
		);
	}

	/** Notifications : annonce + journal. */
	private static function page_notifications() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtres d'affichage.
		$filters = array(
			'channel' => isset( $_GET['channel'] ) ? fwe_choice( 'channel', sanitize_key( wp_unslash( $_GET['channel'] ) ), '' ) : '',
			'status'  => isset( $_GET['nstatus'] ) ? fwe_choice( 'notif_status', sanitize_key( wp_unslash( $_GET['nstatus'] ) ), '' ) : '',
		);
		// phpcs:enable
		self::view(
			'notifications',
			array(
				'filters'       => $filters,
				'notifications' => FWE_Repo::list_notifications( $filters ),
			)
		);
	}

	/** Réglages. */
	private static function page_settings() {
		self::view(
			'settings',
			array(
				'pages' => get_pages( array( 'post_status' => 'publish' ) ),
			)
		);
	}

	/* ---------------------------------------------------------------------
	 * Actions (admin-post.php)
	 * ------------------------------------------------------------------ */

	/**
	 * Vérifie droits + nonce.
	 *
	 * @param string $nonce_action Action du nonce.
	 */
	private static function guard( $nonce_action ) {
		if ( ! current_user_can( 'fwe_manage' ) ) {
			wp_die( esc_html__( 'Accès refusé.', 'famille-we' ), 403 );
		}
		check_admin_referer( $nonce_action );
	}

	/**
	 * Redirige vers une page du plugin et termine.
	 *
	 * @param string $page Slug.
	 * @param array  $args Paramètres.
	 */
	private static function go( $page, array $args = array() ) {
		wp_safe_redirect( self::url( $page, $args ) );
		exit;
	}

	/** Page de retour demandée par le formulaire (champ « back »). */
	private static function back_page( $default ) {
		$back = fwe_post_text( 'back' );
		return isset( self::PAGES[ $back ] ) ? $back : $default;
	}

	/** Enregistre un membre (création ou modification). */
	public static function handle_save_member() {
		self::guard( 'fwe_save_member' );
		$id   = fwe_post_int( 'id' );
		$view = $id ? array(
			'view' => 'edit',
			'id'   => $id,
		) : array( 'view' => 'new' );

		$data = array(
			'first_name' => fwe_post_text( 'first_name' ),
			'last_name'  => fwe_post_text( 'last_name' ),
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- vérifié par guard().
			'email'      => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
			'phone'      => fwe_post_text( 'phone' ),
			'city'       => fwe_post_text( 'city' ),
			'state'      => fwe_choice( 'state', fwe_post_text( 'state' ), 'TX' ),
			'join_date'  => fwe_clean_date( fwe_post_text( 'join_date' ), current_time( 'Y-m-d' ) ),
			'status'     => fwe_choice( 'member_status', fwe_post_text( 'status' ), 'actif' ),
			'notes'      => fwe_post_textarea( 'notes' ),
		);

		if ( '' === $data['first_name'] || '' === $data['last_name'] ) {
			self::go( 'fwe-membres', $view + array( 'fwe_err' => 'required' ) );
		}
		if ( $data['email'] ) {
			$existing = FWE_Repo::get_member_by_email( $data['email'] );
			if ( $existing && (int) $existing->id !== $id ) {
				self::go( 'fwe-membres', $view + array( 'fwe_err' => 'email_exists' ) );
			}
		}

		$id = FWE_Repo::save_member( $data, $id );

		if ( fwe_post_int( 'create_account' ) ) {
			$member = FWE_Repo::get_member( $id );
			if ( $member && ! $member->user_id ) {
				$result = FWE_Accounts::invite( $member );
				if ( is_wp_error( $result ) ) {
					self::go(
						'fwe-membres',
						array(
							'view'    => 'edit',
							'id'      => $id,
							'fwe_err' => $result->get_error_code(),
						)
					);
				}
				self::go( 'fwe-membres', array( 'fwe_msg' => 'member_invited' ) );
			}
		}
		self::go( 'fwe-membres', array( 'fwe_msg' => 'member_saved' ) );
	}

	/** Supprime un membre. */
	public static function handle_delete_member() {
		$id = fwe_post_int( 'id' );
		self::guard( 'fwe_delete_member_' . $id );
		FWE_Repo::delete_member( $id );
		self::go( 'fwe-membres', array( 'fwe_msg' => 'member_deleted' ) );
	}

	/** Valide une adhésion en attente. */
	public static function handle_approve_member() {
		$id = fwe_post_int( 'id' );
		self::guard( 'fwe_approve_member_' . $id );
		$member = FWE_Repo::get_member( $id );
		if ( $member ) {
			FWE_Repo::save_member(
				array(
					'status'    => 'actif',
					'join_date' => $member->join_date ? $member->join_date : current_time( 'Y-m-d' ),
				),
				$id
			);
			$settings = fwe_settings();
			FWE_Notifier::send(
				$member,
				'annonce',
				__( 'Adhésion validée', 'famille-we' ),
				/* translators: %s: nom de l'association. */
				sprintf( __( 'Bienvenue ! Votre adhésion à la %s est validée par le bureau.', 'famille-we' ), $settings['association_name'] ),
				array( 'in_app' )
			);
		}
		self::go( self::back_page( 'fwe-membres' ), array( 'fwe_msg' => 'member_approved' ) );
	}

	/** Crée l'accès en ligne d'un membre et l'invite. */
	public static function handle_invite_member() {
		$id = fwe_post_int( 'id' );
		self::guard( 'fwe_invite_member_' . $id );
		$member = FWE_Repo::get_member( $id );
		if ( ! $member ) {
			self::go( 'fwe-membres', array( 'fwe_err' => 'invalid' ) );
		}
		$result = FWE_Accounts::invite( $member );
		if ( is_wp_error( $result ) ) {
			self::go(
				'fwe-membres',
				array(
					'view'    => 'edit',
					'id'      => $id,
					'fwe_err' => $result->get_error_code(),
				)
			);
		}
		self::go( 'fwe-membres', array( 'fwe_msg' => 'member_invited' ) );
	}

	/** Bascule payé / non payé pour un mois (clic sur la grille). */
	public static function handle_toggle_payment() {
		self::guard( 'fwe_toggle_payment' );
		$cell  = fwe_post_text( 'cell' );
		$year  = fwe_post_int( 'year' );
		$parts = explode( '|', $cell );
		if ( 2 !== count( $parts ) || ! fwe_valid_period( $parts[1] ) || ! FWE_Repo::get_member( absint( $parts[0] ) ) ) {
			self::go( 'fwe-cotisations', array( 'fwe_err' => 'invalid' ) );
		}
		$member_id = absint( $parts[0] );
		$period    = $parts[1];
		if ( FWE_Repo::get_payment( $member_id, $period ) ) {
			FWE_Repo::delete_payment( $member_id, $period );
			$args = array( 'fwe_msg' => 'payment_removed' );
		} else {
			FWE_Repo::record_payment( $member_id, $period, (float) fwe_setting( 'monthly_amount' ), current_time( 'Y-m-d' ), 'zelle' );
			$args = array(
				'fwe_msg' => 'payment_saved',
				'n'       => 1,
			);
		}
		self::go( 'fwe-cotisations', $args + array( 'year' => $year ? $year : substr( $period, 0, 4 ) ) );
	}

	/** Enregistre un paiement couvrant un ou plusieurs mois consécutifs. */
	public static function handle_record_payment() {
		self::guard( 'fwe_record_payment' );
		$back      = self::back_page( 'fwe-cotisations' );
		$member_id = fwe_post_int( 'member_id' );
		$from      = fwe_post_text( 'from_period' );
		$months    = min( 24, max( 1, fwe_post_int( 'months' ) ) );
		$amount    = fwe_parse_amount( fwe_post_text( 'amount' ) );
		$amount    = $amount > 0 ? $amount : (float) fwe_setting( 'monthly_amount' );
		$paid_at   = fwe_clean_date( fwe_post_text( 'paid_at' ), current_time( 'Y-m-d' ) );
		$method    = fwe_choice( 'method', fwe_post_text( 'method' ), 'zelle' );
		$note      = fwe_post_text( 'note' );

		if ( ! $member_id || ! FWE_Repo::get_member( $member_id ) || ! fwe_valid_period( $from ) ) {
			self::go( $back, array( 'fwe_err' => 'required' ) );
		}

		$added = 0;
		foreach ( fwe_periods_between( $from, fwe_period_add( $from, $months - 1 ) ) as $period ) {
			if ( FWE_Repo::record_payment( $member_id, $period, $amount, $paid_at, $method, $note ) ) {
				++$added;
			}
		}
		if ( ! $added ) {
			self::go( $back, array( 'fwe_err' => 'already_paid' ) );
		}
		self::go(
			$back,
			array(
				'fwe_msg' => 'payment_saved',
				'n'       => $added,
				'year'    => substr( $from, 0, 4 ),
			)
		);
	}

	/** Enregistre un événement (et prévient les membres si demandé). */
	public static function handle_save_event() {
		self::guard( 'fwe_save_event' );
		$id    = fwe_post_int( 'id' );
		$title = fwe_post_text( 'title' );
		$start = fwe_post_text( 'starts_at' );
		if ( '' === $title || ! preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', $start ) ) {
			self::go( 'fwe-evenements', array( 'fwe_err' => 'required' ) + ( $id ? array( 'edit' => $id ) : array() ) );
		}
		$data = array(
			'title'       => $title,
			'type'        => fwe_choice( 'event_type', fwe_post_text( 'type' ), 'general' ),
			'starts_at'   => str_replace( 'T', ' ', substr( $start, 0, 16 ) ) . ':00',
			'location'    => fwe_post_text( 'location' ),
			'description' => fwe_post_textarea( 'description' ),
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- vérifié par guard().
			'image_url'   => isset( $_POST['image_url'] ) ? esc_url_raw( wp_unslash( $_POST['image_url'] ) ) : '',
		);
		$id   = FWE_Repo::save_event( $data, $id );

		if ( fwe_post_int( 'notify' ) ) {
			$is_ag = 'ag' === $data['type'];
			/* translators: %s: titre de l'événement. */
			$title_tpl = $is_ag ? __( 'Convocation — %s', 'famille-we' ) : __( 'Nouvel événement — %s', 'famille-we' );
			$message   = fwe_format_date( $data['starts_at'], true ) . ( $data['location'] ? ' · ' . $data['location'] : '' );
			if ( $data['description'] ) {
				$message .= "\n" . wp_trim_words( $data['description'], 40 );
			}
			$channels = fwe_setting( 'notify_email' ) ? array( 'in_app', 'email' ) : array( 'in_app' );
			foreach ( FWE_Repo::list_members( array( 'status' => 'actif' ) ) as $member ) {
				FWE_Notifier::send( $member, $is_ag ? 'ag' : 'evenement', sprintf( $title_tpl, $data['title'] ), $message, $channels );
			}
		}
		self::go( 'fwe-evenements', array( 'fwe_msg' => 'event_saved' ) );
	}

	/** Supprime un événement. */
	public static function handle_delete_event() {
		$id = fwe_post_int( 'id' );
		self::guard( 'fwe_delete_event_' . $id );
		FWE_Repo::delete_event( $id );
		self::go( 'fwe-evenements', array( 'fwe_msg' => 'event_deleted' ) );
	}

	/** Enregistre un cas de soutien. */
	public static function handle_save_case() {
		self::guard( 'fwe_save_case' );
		$id        = fwe_post_int( 'id' );
		$member_id = fwe_post_int( 'member_id' );
		$member    = $member_id ? FWE_Repo::get_member( $member_id ) : null;
		$type      = fwe_choice( 'support_type', fwe_post_text( 'type' ), 'naissance' );
		if ( ! $member ) {
			self::go( 'fwe-soutien', array( 'fwe_err' => 'required' ) + ( $id ? array( 'edit' => $id ) : array() ) );
		}
		$title = fwe_post_text( 'title' );
		if ( '' === $title ) {
			$title = fwe_label( 'support_type', $type ) . ' — ' . fwe_member_name( $member );
		}
		$data   = array(
			'member_id'     => $member_id,
			'type'          => $type,
			'title'         => $title,
			'description'   => fwe_post_textarea( 'description' ),
			'target_amount' => max( 0, fwe_parse_amount( fwe_post_text( 'target_amount' ) ) ),
			'status'        => fwe_choice( 'case_status', fwe_post_text( 'status' ), 'ouvert' ),
		);
		$is_new = ! $id;
		$id     = FWE_Repo::save_case( $data, $id );

		if ( $is_new && fwe_post_int( 'notify' ) ) {
			$channels = fwe_setting( 'notify_email' ) ? array( 'in_app', 'email' ) : array( 'in_app' );
			$message  = $data['description'] ? wp_trim_words( $data['description'], 50 ) : __( 'La famille se mobilise : chaque contribution compte.', 'famille-we' );
			$message .= ' ' . __( 'Vous pouvez contribuer depuis votre espace membre.', 'famille-we' );
			foreach ( FWE_Repo::list_members( array( 'status' => 'actif' ) ) as $m ) {
				if ( (int) $m->id === $member_id ) {
					continue;
				}
				/* translators: %s: titre du cas. */
				FWE_Notifier::send( $m, 'soutien', sprintf( __( 'Soutien — %s', 'famille-we' ), $data['title'] ), $message, $channels );
			}
		}
		self::go(
			'fwe-soutien',
			array(
				'case'    => $id,
				'fwe_msg' => 'case_saved',
			)
		);
	}

	/** Supprime un cas de soutien. */
	public static function handle_delete_case() {
		$id = fwe_post_int( 'id' );
		self::guard( 'fwe_delete_case_' . $id );
		FWE_Repo::delete_case( $id );
		self::go( 'fwe-soutien', array( 'fwe_msg' => 'case_deleted' ) );
	}

	/** Ajoute une contribution reçue (saisie par le bureau). */
	public static function handle_add_contribution() {
		self::guard( 'fwe_add_contribution' );
		$case_id   = fwe_post_int( 'case_id' );
		$member_id = fwe_post_int( 'member_id' );
		$amount    = fwe_parse_amount( fwe_post_text( 'amount' ) );
		if ( ! FWE_Repo::get_case( $case_id ) || $amount <= 0 ) {
			self::go(
				'fwe-soutien',
				array(
					'case'    => $case_id,
					'fwe_err' => 'required',
				)
			);
		}
		FWE_Repo::add_contribution( $case_id, $member_id, $amount, fwe_choice( 'contribution_status', fwe_post_text( 'status' ), 'confirmee' ), fwe_post_text( 'note' ) );
		self::go(
			'fwe-soutien',
			array(
				'case'    => $case_id,
				'fwe_msg' => 'contribution_added',
			)
		);
	}

	/** Confirme la réception d'une contribution. */
	public static function handle_confirm_contribution() {
		$id = fwe_post_int( 'id' );
		self::guard( 'fwe_contribution_' . $id );
		$contribution = FWE_Repo::get_contribution( $id );
		if ( $contribution ) {
			FWE_Repo::confirm_contribution( $id );
		}
		self::go(
			'fwe-soutien',
			array(
				'case'    => $contribution ? (int) $contribution->case_id : 0,
				'fwe_msg' => 'contribution_confirmed',
			)
		);
	}

	/** Supprime une contribution. */
	public static function handle_delete_contribution() {
		$id = fwe_post_int( 'id' );
		self::guard( 'fwe_contribution_' . $id );
		$contribution = FWE_Repo::get_contribution( $id );
		FWE_Repo::delete_contribution( $id );
		self::go(
			'fwe-soutien',
			array(
				'case'    => $contribution ? (int) $contribution->case_id : 0,
				'fwe_msg' => 'contribution_deleted',
			)
		);
	}

	/** Envoie manuellement un rappel de retard à un membre. */
	public static function handle_send_reminder() {
		$id = fwe_post_int( 'id' );
		self::guard( 'fwe_send_reminder_' . $id );
		$member = FWE_Repo::get_member( $id );
		if ( ! $member ) {
			self::go( 'fwe-retards', array( 'fwe_err' => 'invalid' ) );
		}
		$dues = FWE_Dues::for_member( $member );
		if ( $dues['months_late'] < 1 ) {
			self::go( 'fwe-retards', array( 'fwe_err' => 'invalid' ) );
		}
		list( $title, $message ) = FWE_Notifier::late_message( $member, $dues );
		FWE_Notifier::send( $member, 'rappel', $title, $message, FWE_Notifier::alert_channels(), 'rappel-' . time() );
		self::go( 'fwe-retards', array( 'fwe_msg' => 'reminder_sent' ) );
	}

	/** Lance immédiatement la vérification des retards. */
	public static function handle_run_check() {
		self::guard( 'fwe_run_check' );
		$count = FWE_Notifier::run_late_check( true );
		self::go(
			'fwe-retards',
			array(
				'fwe_msg' => 'check_done',
				'n'       => $count,
			)
		);
	}

	/** Envoie une annonce aux membres actifs. */
	public static function handle_send_announcement() {
		self::guard( 'fwe_send_announcement' );
		$title   = fwe_post_text( 'title' );
		$message = fwe_post_textarea( 'message' );
		if ( '' === $title || '' === $message ) {
			self::go( 'fwe-notifications', array( 'fwe_err' => 'required' ) );
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- vérifié par guard().
		$requested = isset( $_POST['channels'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['channels'] ) ) : array();
		$channels  = array_values( array_unique( array_merge( array( 'in_app' ), array_intersect( $requested, array_keys( fwe_labels( 'channel' ) ) ) ) ) );
		$state     = fwe_choice( 'state', fwe_post_text( 'target' ), '' );
		$members   = FWE_Repo::list_members(
			array(
				'status' => 'actif',
				'state'  => $state,
			)
		);
		foreach ( $members as $member ) {
			FWE_Notifier::send( $member, 'annonce', $title, $message, $channels );
		}
		self::go(
			'fwe-notifications',
			array(
				'fwe_msg' => 'announcement_sent',
				'n'       => count( $members ),
			)
		);
	}

	/** Enregistre les réglages. */
	public static function handle_save_settings() {
		self::guard( 'fwe_save_settings' );
		$current = fwe_settings();
		$amount  = fwe_parse_amount( fwe_post_text( 'monthly_amount' ) );
		$start   = fwe_post_text( 'start_period' );
		$token   = fwe_post_text( 'twilio_token' );
		$page_id = fwe_post_int( 'page_id' );

		fwe_update_settings(
			array(
				'association_name'     => fwe_post_text( 'association_name' ) ? fwe_post_text( 'association_name' ) : $current['association_name'],
				'region'               => fwe_post_text( 'region' ),
				'monthly_amount'       => $amount > 0 ? $amount : $current['monthly_amount'],
				'late_threshold'       => min( 24, max( 1, fwe_post_int( 'late_threshold' ) ) ),
				'start_period'         => fwe_valid_period( $start ) ? $start : $current['start_period'],
				'treasurer_name'       => fwe_post_text( 'treasurer_name' ),
				'zelle_contact'        => fwe_post_text( 'zelle_contact' ),
				'zelle_note'           => fwe_post_textarea( 'zelle_note' ),
				'allow_registration'   => fwe_post_int( 'allow_registration' ) ? 1 : 0,
				'auto_notify'          => fwe_post_int( 'auto_notify' ) ? 1 : 0,
				'notify_email'         => fwe_post_int( 'notify_email' ) ? 1 : 0,
				'enable_sms'           => fwe_post_int( 'enable_sms' ) ? 1 : 0,
				'enable_whatsapp'      => fwe_post_int( 'enable_whatsapp' ) ? 1 : 0,
				'twilio_sid'           => fwe_post_text( 'twilio_sid' ),
				'twilio_token'         => '' !== $token ? $token : $current['twilio_token'],
				'twilio_sms_from'      => fwe_post_text( 'twilio_sms_from' ),
				'twilio_whatsapp_from' => fwe_post_text( 'twilio_whatsapp_from' ),
				'delete_on_uninstall'  => fwe_post_int( 'delete_on_uninstall' ) ? 1 : 0,
				'page_id'              => ( $page_id && 'page' === get_post_type( $page_id ) ) ? $page_id : $current['page_id'],
			)
		);
		self::go( 'fwe-reglages', array( 'fwe_msg' => 'settings_saved' ) );
	}
}
