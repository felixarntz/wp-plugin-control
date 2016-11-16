<?php
/**
 * @package WPPluginControl
 * @subpackage Admin
 */

function wppc_action_toggle_plugin( $enable = true ) {
	global $status, $page;

	$screen = get_current_screen();

	if ( is_network_admin() ) {
		if ( 'plugins-network' !== $screen->id ) {
			return;
		}

		$toggle_callback = 'wppc_toggle_plugin_for_network';
	} else {
		if ( 'plugins' !== $screen->id ) {
			return;
		}

		$toggle_callback = 'wppc_toggle_plugin_for_site';
	}

	if ( $enable ) {
		$action = 'enable';
		$failure_text = __( 'Sorry, you are not allowed to enable plugins.', 'wp-plugin-control' );
	} else {
		$action = 'disable';
		$failure_text = __( 'Sorry, you are not allowed to disable plugins.', 'wp-plugin-control' );
	}

	if ( ! current_user_can( 'toggle_plugins' ) ) {
		wp_die( $failure_text );
	}

	$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';

	check_admin_referer( $action . '-plugin_' . $plugin );

	$result = call_user_func( $toggle_callback, $plugin, $enable );

	$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
	$s = isset($_REQUEST['s']) ? urlencode( wp_unslash( $_REQUEST['s'] ) ) : '';
	$result = $result ? 'true' : 'false';

	wp_redirect( self_admin_url( "plugins.php?$action=$result&plugin_status=$status&paged=$page&s=$s" ) );
	exit;
}

function wppc_action_enable_plugin() {
	wppc_action_toggle_plugin( true );
}
add_action( 'admin_action_enable', 'wppc_action_enable_plugin' );

function wppc_action_disable_plugin() {
	wppc_action_toggle_plugin( false );
}
add_action( 'admin_action_disable', 'wppc_action_disable_plugin' );

function wppc_action_notice() {
	$screen = get_current_screen();
	if ( ( is_network_admin() && 'plugins-network' !== $screen->id ) || ( ! is_network_admin() && 'plugins' !== $screen->id ) ) {
		return;
	}

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
