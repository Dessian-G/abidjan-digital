<?php
/**
 * Désinstallation : les données ne sont supprimées que si l'option
 * « Supprimer les données à la désinstallation » est cochée dans les réglages.
 *
 * @package FamilleWE
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$fwe_settings = get_option( 'fwe_settings', array() );

wp_clear_scheduled_hook( 'fwe_daily_check' );

if ( ! is_array( $fwe_settings ) || empty( $fwe_settings['delete_on_uninstall'] ) ) {
	return;
}

global $wpdb;
foreach ( array( 'members', 'cotisations', 'events', 'support_cases', 'contributions', 'notifications' ) as $fwe_table ) {
	$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'fwe_' . $fwe_table ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery -- nom de table interne.
}

delete_option( 'fwe_settings' );
delete_option( 'fwe_db_version' );
delete_option( 'fwe_last_check' );
remove_role( 'fwe_membre' );
remove_role( 'fwe_bureau' );
$fwe_admin = get_role( 'administrator' );
if ( $fwe_admin ) {
	$fwe_admin->remove_cap( 'fwe_manage' );
}
