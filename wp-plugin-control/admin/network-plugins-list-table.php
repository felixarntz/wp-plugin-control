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

		$enable_link_text   = _x( 'Network Enable', 'plugin', 'wp-plugin-control' );
		$disable_link_text  = _x( 'Network Disable', 'plugin', 'wp-plugin-control' );
		/* translators: %s: plugin name */
		$enable_aria_label  = _x( 'Network Enable %s', 'plugin', 'wp-plugin-control' );
		/* translators: %s: plugin name */
		$disable_aria_label = _x( 'Network Disable %s', 'plugin', 'wp-plugin-control' );
	} else {
		$disabled_plugins = wppc_get_all_plugins_disabled_for_site();

		$enable_link_text   = _x( 'Enable', 'plugin', 'wp-plugin-control' );
		$disable_link_text  = _x( 'Disable', 'plugin', 'wp-plugin-control' );
		/* translators: %s: plugin name */
		$enable_aria_label  = _x( 'Enable %s', 'plugin', 'wp-plugin-control' );
		/* translators: %s: plugin name */
		$disable_aria_label = _x( 'Disable %s', 'plugin', 'wp-plugin-control' );
	}

	if ( in_array( $plugin_file, $disabled_plugins, true ) ) {
		$action = 'enable';
		$link_text = $enable_link_text;
		$aria_label = $enable_aria_label;
	} else {
		$action = 'disable';
		$link_text = $disable_link_text;
		$aria_label = $disable_aria_label;
	}

	$actions[ $action ] = '<a href="' . wp_nonce_url( 'plugins.php?action=' . $action . '&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, $action . '-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( $aria_label, $plugin_data['Name'] ) ) . '">' . $link_text . '</a>';

	return $actions;
}
add_filter( 'plugin_action_links', 'wppc_add_toggle_link', 10, 4 );
add_filter( 'network_admin_plugin_action_links', 'wppc_add_toggle_link', 10, 4 );

function wppc_maybe_add_disabled_styles() {
	if ( ! current_user_can( 'toggle_plugins' ) ) {
		return;
	}

	if ( is_network_admin() ) {
		$disabled_plugins = wppc_get_all_plugins_disabled_for_network();
	} else {
		$disabled_plugins = wppc_get_all_plugins_disabled_for_site();
	}

	if ( empty( $disabled_plugins ) ) {
		return;
	}

	// This is hacky, but Core does not allow adding classes to plugin rows.
	echo '<style type="text/css">';
	foreach ( $disabled_plugins as $i => $plugin_file ) {
		if ( $i > 0 ) {
			echo ', ';
		}
		echo 'tr[data-plugin="' . $plugin_file . '"]';
	}
	echo ' { opacity: 0.5; }</style>';
}
add_action( 'admin_head-plugins.php', 'wppc_maybe_add_disabled_styles' );
