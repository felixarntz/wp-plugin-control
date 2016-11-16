<?php
/**
 * @package WPPluginControl
 * @subpackage Admin
 */

function wppc_filter_plugins( $plugins ) {
	if ( current_user_can( 'toggle_plugins' ) ) {
		return $plugins;
	}

	if ( is_network_admin() ) {
		$disabled_plugins = wppc_get_all_plugins_disabled_for_network();
	} else {
		$disabled_plugins = wppc_get_all_plugins_disabled_for_site();
	}

	return array_diff_key( $plugins, array_flip( $disabled_plugins ) );
}
add_filter( 'all_plugins', 'wppc_filter_plugins' );

function wppc_add_site_toggle_link( $actions, $plugin_file, $plugin_data, $context ) {
	global $page, $s;

	if ( ! current_user_can( 'toggle_plugins' ) ) {
		return $actions;
	}

	$disabled_plugins = wppc_get_all_plugins_disabled_for_site();

	if ( in_array( $plugin_file, $disabled_plugins, true ) ) {
		/* translators: %s: plugin name */
		$actions['enable'] = '<a href="' . wp_nonce_url( 'plugins.php?action=enable&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'enable-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( _x( 'Enable %s', 'plugin', 'wp-plugin-control' ), $plugin_data['Name'] ) ) . '">' . _x( 'Enable', 'plugin', 'wp-plugin-control' ) . '</a>';
	} else {
		/* translators: %s: plugin name */
		$actions['enable'] = '<a href="' . wp_nonce_url( 'plugins.php?action=disable&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'disable-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( _x( 'Disable %s', 'plugin', 'wp-plugin-control' ), $plugin_data['Name'] ) ) . '">' . _x( 'Disable', 'plugin', 'wp-plugin-control' ) . '</a>';
	}

	return $actions;
}
add_filter( 'plugin_action_links', 'wppc_add_site_toggle_link', 10, 4 );

function wppc_add_network_toggle_link( $actions, $plugin_file, $plugin_data, $context ) {
	global $page, $s;

	if ( ! current_user_can( 'toggle_plugins' ) ) {
		return $actions;
	}

	$disabled_plugins = wppc_get_all_plugins_disabled_for_network();

	if ( in_array( $plugin_file, $disabled_plugins, true ) ) {
		/* translators: %s: plugin name */
		$actions['enable'] = '<a href="' . wp_nonce_url( 'plugins.php?action=enable&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'enable-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( _x( 'Network Enable %s', 'plugin', 'wp-plugin-control' ), $plugin_data['Name'] ) ) . '">' . _x( 'Network Enable', 'plugin', 'wp-plugin-control' ) . '</a>';
	} else {
		/* translators: %s: plugin name */
		$actions['enable'] = '<a href="' . wp_nonce_url( 'plugins.php?action=disable&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'disable-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( _x( 'Network Disable %s', 'plugin', 'wp-plugin-control' ), $plugin_data['Name'] ) ) . '">' . _x( 'Network Disable', 'plugin', 'wp-plugin-control' ) . '</a>';
	}

	return $actions;
}
add_filter( 'network_admin_plugin_action_links', 'wppc_add_network_toggle_link', 10, 4 );
