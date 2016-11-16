<?php
/**
 * @package WPPluginControl
 * @subpackage Admin
 */

function wppc_action_enable_plugin() {
	global $status, $page;

	//TODO: check current screen

	if ( ! current_user_can( 'toggle_plugins' ) ) {
		wp_die( __( 'Sorry, you are not allowed to enable plugins.', 'wp-plugin-control' ) );
	}

	$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';

	check_admin_referer( 'enable-plugin_' . $plugin );

	if ( is_network_admin() ) {
		$result = wppc_control_plugin_for_network( $plugin, true );
	} else {
		$result = wppc_control_plugin_for_site( $plugin, true );
	}

	$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
	$s = isset($_REQUEST['s']) ? urlencode( wp_unslash( $_REQUEST['s'] ) ) : '';
	$result = $result ? 'true' : 'false';

	wp_redirect( self_admin_url( "plugins.php?enable=$result&plugin_status=$status&paged=$page&s=$s" ) );
	exit;
}
add_action( 'admin_action_enable', 'wppc_action_enable_plugin' );

function wppc_action_disable_plugin() {
	global $status, $page;

	//TODO: check current screen

	if ( ! current_user_can( 'toggle_plugins' ) ) {
		wp_die( __( 'Sorry, you are not allowed to disable plugins.', 'wp-plugin-control' ) );
	}

	$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';

	check_admin_referer( 'disable-plugin_' . $plugin );

	if ( is_network_admin() ) {
		$result = wppc_control_plugin_for_network( $plugin, false );
	} else {
		$result = wppc_control_plugin_for_site( $plugin, false );
	}

	$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
	$s = isset($_REQUEST['s']) ? urlencode( wp_unslash( $_REQUEST['s'] ) ) : '';
	$result = $result ? 'true' : 'false';

	wp_redirect( self_admin_url( "plugins.php?disable=$result&plugin_status=$status&paged=$page&s=$s" ) );
	exit;
}
add_action( 'admin_action_disable', 'wppc_action_disable_plugin' );

function wppc_action_notice() {
	//TODO: check current screen

	if ( isset( $_GET['enable'] ) ) {
		if ( 'true' === $_GET['enable'] ) {
			echo '<div id="message" class="updated notice is-dismissible"><p>' . __( 'Plugin <strong>enabled</strong>.', 'wp-plugin-control' ) . '</p></div>';
		} else {
			echo '<div id="message" class="error"><p>' . __( 'Plugin could not be enabled.', 'wp-plugin-control' ) . '</p></div>';
		}
	} elseif ( isset( $_GET['disable'] ) ) {
		if ( 'true' === $_GET['disable'] ) {
			echo '<div id="message" class="updated notice is-dismissible"><p>' . __( 'Plugin <strong>disabled</strong>.', 'wp-plugin-control' ) . '</p></div>';
		} else {
			echo '<div id="message" class="error"><p>' . __( 'Plugin could not be disabled.', 'wp-plugin-control' ) . '</p></div>';
		}
	}
}
add_action( 'admin_notices', 'wppc_action_notice' );
add_action( 'network_admin_notices', 'wppc_action_notice' );