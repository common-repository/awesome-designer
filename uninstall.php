<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://orionorigin.com
 * @since      3.0
 *
 * @package    Wpd
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
global $wpdb;
delete_option( 'api_awesome_designer');
delete_option( 'max_up_awesome_designer');
delete_option( 'min_up_awesome_designer' );
delete_option( 'col_mob_awesome_designer');
// Drop a custom db table
global $wpdb;
$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
$wpdb->query( "DROP TABLE IF EXISTS ".$table_name );
$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart';
$wpdb->query( "DROP TABLE IF EXISTS ".$table_name );
$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart_cat';
$wpdb->query( "DROP TABLE IF EXISTS ".$table_name );
$table_name = $wpdb->prefix . 'the_awe_des_awesome_font';
$wpdb->query( "DROP TABLE IF EXISTS ".$table_name );

require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
$wp_fs_d = new WP_Filesystem_Direct( new StdClass() );
$upload = wp_upload_dir();   
$upload_dir = $upload['basedir'] . '/the-awe-des-awesome-clipart';
$wp_fs_d->delete( $upload_dir,true );

$upload_dir = $upload['basedir'] . '/the-awe-des-awesome-product';
$wp_fs_d->delete( $upload_dir,true );
$upload_dir = $upload['basedir'] . '/the-awe-des-awesome-commande';
$wp_fs_d->delete( $upload_dir,true );
$upload_dir = $upload['basedir'] . '/the-awe-des-awesome-upload';
$wp_fs_d->delete( $upload_dir,true );

?>