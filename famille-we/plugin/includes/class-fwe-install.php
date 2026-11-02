<?php
/**
 * Installation : tables, rôles, page de l'espace membre, tâche planifiée.
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

/**
 * Activation / désactivation / mises à jour du schéma.
 */
class FWE_Install {

	const CRON_HOOK = 'fwe_daily_check';

	/** Activation du plugin. */
	public static function activate() {
		self::create_tables();
		self::add_roles();

		if ( ! is_array( get_option( 'fwe_settings' ) ) ) {
			$defaults = fwe_defaults();
			// Le suivi démarre au mois d'installation : aucun « faux retard » à l'activation.
			$defaults['start_period'] = fwe_current_period();
			add_option( 'fwe_settings', $defaults );
		}

		self::ensure_page();

		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', self::CRON_HOOK );
		}

		update_option( 'fwe_db_version', FWE_DB_VERSION );
	}

	/** Désactivation : on garde les données, on retire la tâche planifiée. */
	public static function deactivate() {
		wp_clear_scheduled_hook( self::CRON_HOOK );
	}

	/** Met à jour le schéma si la version a changé. */
	public static function maybe_upgrade() {
		if ( get_option( 'fwe_db_version' ) !== FWE_DB_VERSION ) {
			self::create_tables();
			self::add_roles();
			update_option( 'fwe_db_version', FWE_DB_VERSION );
		}
	}

	/** Crée ou met à jour les tables. */
	public static function create_tables() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$c = $wpdb->get_charset_collate();
		$p = $wpdb->prefix . 'fwe_';

		dbDelta(
			"CREATE TABLE {$p}members (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned DEFAULT NULL,
			first_name varchar(100) NOT NULL DEFAULT '',
			last_name varchar(100) NOT NULL DEFAULT '',
			email varchar(190) NOT NULL DEFAULT '',
			phone varchar(40) NOT NULL DEFAULT '',
			city varchar(100) NOT NULL DEFAULT '',
			state varchar(2) NOT NULL DEFAULT 'TX',
			join_date date DEFAULT NULL,
			status varchar(20) NOT NULL DEFAULT 'actif',
			notes text,
			created_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY user_id (user_id),
			KEY email (email),
			KEY status (status)
		) $c;"
		);

		dbDelta(
			"CREATE TABLE {$p}cotisations (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			member_id bigint(20) unsigned NOT NULL,
			period char(7) NOT NULL,
			amount decimal(10,2) NOT NULL DEFAULT 0,
			paid_at date DEFAULT NULL,
			method varchar(20) NOT NULL DEFAULT 'zelle',
			note varchar(255) NOT NULL DEFAULT '',
			recorded_by bigint(20) unsigned DEFAULT NULL,
			created_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY member_period (member_id,period),
			KEY period (period)
		) $c;"
		);

		dbDelta(
			"CREATE TABLE {$p}events (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			title varchar(200) NOT NULL DEFAULT '',
			type varchar(20) NOT NULL DEFAULT 'general',
			starts_at datetime DEFAULT NULL,
			location varchar(255) NOT NULL DEFAULT '',
			description text,
			image_url varchar(500) NOT NULL DEFAULT '',
			created_by bigint(20) unsigned DEFAULT NULL,
			created_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY starts_at (starts_at)
		) $c;"
		);

		dbDelta(
			"CREATE TABLE {$p}support_cases (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			member_id bigint(20) unsigned DEFAULT NULL,
			type varchar(20) NOT NULL DEFAULT 'naissance',
			title varchar(200) NOT NULL DEFAULT '',
			description text,
			target_amount decimal(10,2) NOT NULL DEFAULT 0,
			status varchar(20) NOT NULL DEFAULT 'ouvert',
			created_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY status (status)
		) $c;"
		);

		dbDelta(
			"CREATE TABLE {$p}contributions (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			case_id bigint(20) unsigned NOT NULL,
			member_id bigint(20) unsigned DEFAULT NULL,
			amount decimal(10,2) NOT NULL DEFAULT 0,
			status varchar(20) NOT NULL DEFAULT 'declaree',
			note varchar(255) NOT NULL DEFAULT '',
			created_at datetime DEFAULT NULL,
			confirmed_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY case_id (case_id),
			KEY member_id (member_id)
		) $c;"
		);

		dbDelta(
			"CREATE TABLE {$p}notifications (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			member_id bigint(20) unsigned DEFAULT NULL,
			type varchar(20) NOT NULL DEFAULT 'annonce',
			title varchar(200) NOT NULL DEFAULT '',
			message text,
			channel varchar(20) NOT NULL DEFAULT 'in_app',
			status varchar(20) NOT NULL DEFAULT 'envoye',
			detail varchar(255) NOT NULL DEFAULT '',
			ref varchar(60) NOT NULL DEFAULT '',
			is_read tinyint(1) NOT NULL DEFAULT 0,
			created_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY member_id (member_id),
			KEY ref (ref)
		) $c;"
		);
	}

	/** Rôles et capacités. */
	public static function add_roles() {
		add_role( 'fwe_membre', __( 'Membre Famille WÊ', 'famille-we' ), array( 'read' => true ) );
		add_role(
			'fwe_bureau',
			__( 'Bureau Famille WÊ', 'famille-we' ),
			array(
				'read'         => true,
				'upload_files' => true,
				'fwe_manage'   => true,
			)
		);

		$bureau = get_role( 'fwe_bureau' );
		if ( $bureau ) {
			$bureau->add_cap( 'fwe_manage' );
		}
		$admin = get_role( 'administrator' );
		if ( $admin ) {
			$admin->add_cap( 'fwe_manage' );
		}
	}

	/** Crée la page « Espace Famille WÊ » si elle n'existe pas. */
	public static function ensure_page() {
		$page_id = (int) fwe_setting( 'page_id' );
		if ( $page_id && get_post( $page_id ) && 'trash' !== get_post_status( $page_id ) ) {
			return $page_id;
		}
		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => __( 'Espace Famille WÊ', 'famille-we' ),
				'post_name'    => 'espace-famille-we',
				'post_content' => "<!-- wp:shortcode -->\n[famille_we_espace]\n<!-- /wp:shortcode -->",
			)
		);
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			fwe_update_settings( array( 'page_id' => (int) $page_id ) );
			return (int) $page_id;
		}
		return 0;
	}
}
