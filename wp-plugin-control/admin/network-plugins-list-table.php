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

function wppc_add_toggle_link( $actions, $plugin_file, $plugin_data, $context ) {
	global $page, $s;

	if ( ! current_user_can( 'toggle_plugins' ) ) {
		return $actions;
	}

	if ( is_network_admin() ) {
		$disabled_plugins = wppc_get_all_plugins_disabled_for_network();

		$enable_text        = _x( 'Network Enable', 'plugin', 'wp-plugin-control' );
		$disable_text       = _x( 'Network Disable', 'plugin', 'wp-plugin-control' );
		/* translators: %s: plugin name */
		$enable_aria_label  = _x( 'Network Enable %s', 'plugin', 'wp-plugin-control' );
		/* translators: %s: plugin name */
		$disable_aria_label = _x( 'Network Disable %s', 'plugin', 'wp-plugin-control' );
	} else {
		$disabled_plugins = wppc_get_all_plugins_disabled_for_site();

		$enable_text        = _x( 'Enable', 'plugin', 'wp-plugin-control' );
		$disable_text       = _x( 'Disable', 'plugin', 'wp-plugin-control' );
		/* translators: %s: plugin name */
		$enable_aria_label  = _x( 'Enable %s', 'plugin', 'wp-plugin-control' );
		/* translators: %s: plugin name */
		$disable_aria_label = _x( 'Disable %s', 'plugin', 'wp-plugin-control' );
	}

	if ( in_array( $plugin_file, $disabled_plugins, true ) ) {
		$actions['enable'] = '<a href="' . wp_nonce_url( 'plugins.php?action=enable&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'enable-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( $enable_aria_label, $plugin_data['Name'] ) ) . '">' . $enable_text . '</a>';
	} else {
		$actions['disable'] = '<a href="' . wp_nonce_url( 'plugins.php?action=disable&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'disable-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( $disable_aria_label, $plugin_data['Name'] ) ) . '">' . $disable_text . '</a>';
	}

	return $actions;
}
add_filter( 'plugin_action_links', 'wppc_add_toggle_link', 10, 4 );
add_filter( 'network_admin_plugin_action_links', 'wppc_add_toggle_link', 10, 4 );
