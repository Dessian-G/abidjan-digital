<?php
/**
 * Plugin Name:       Famille WÊ — Espace association
 * Description:       Tableau de bord de l'association Famille WÊ (Dallas, Texas & Oklahoma) : membres, cotisations mensuelles en dollars, retards avec alerte automatique, événements et AG, soutien solidaire (naissance, mariage, décès) et notifications.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Abidjan Digital.ia
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       famille-we
 * Domain Path:       /languages
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

define( 'FWE_VERSION', '1.0.0' );
define( 'FWE_DB_VERSION', '1' );
define( 'FWE_FILE', __FILE__ );
define( 'FWE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FWE_URL', plugin_dir_url( __FILE__ ) );

require_once FWE_DIR . 'includes/functions.php';
require_once FWE_DIR . 'includes/class-fwe-install.php';
require_once FWE_DIR . 'includes/class-fwe-repo.php';
require_once FWE_DIR . 'includes/class-fwe-dues.php';
require_once FWE_DIR . 'includes/class-fwe-notifier.php';
require_once FWE_DIR . 'includes/class-fwe-accounts.php';
require_once FWE_DIR . 'includes/class-fwe-assets.php';
require_once FWE_DIR . 'public/class-fwe-public.php';
require_once FWE_DIR . 'admin/class-fwe-admin.php';

register_activation_hook( __FILE__, array( 'FWE_Install', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'FWE_Install', 'deactivate' ) );

add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain( 'famille-we', false, dirname( plugin_basename( FWE_FILE ) ) . '/languages' );
		FWE_Install::maybe_upgrade();
		FWE_Accounts::init();
		FWE_Notifier::init();
		FWE_Assets::init();
		FWE_Public::init();
		if ( is_admin() ) {
			FWE_Admin::init();
		}
	}
);
