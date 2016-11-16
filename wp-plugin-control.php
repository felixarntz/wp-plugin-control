<?php
/*
Plugin Name: WP Plugin Control
Plugin URI:  https://wordpress.org/plugins/wp-plugin-control/
Description: Improves plugin control on multisite by supporting enabling and disabling of plugins per site or network.
Version:     1.0.0
Author:      Felix Arntz
Author URI:  https://leaves-and-love.net
License:     GNU General Public License v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: wp-plugin-control
Network:     true
Tags:        plugins, control, multisite, network
*/

function wppc_plugin_loaded() {
	define( 'WPPC_PATH', plugin_dir_path( __FILE__ ) );
	define( 'WPPC_URL', plugin_dir_url( __FILE__ ) );

	require_once WPPC_PATH . 'wp-plugin-control/functions.php';
	require_once WPPC_PATH . 'wp-plugin-control/admin/network-plugins.php';
	require_once WPPC_PATH . 'wp-plugin-control/admin/network-plugins-list-table.php';
}
add_action( 'plugins_loaded', 'wppc_plugin_loaded' );
