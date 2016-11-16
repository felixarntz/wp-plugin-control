<?php
/**
 * @package WPPluginControl
 */

function wppc_toggle_plugin_for_site( $plugin_file, $enable = true, $site_id = null ) {
	if ( empty( $site_id ) ) {
		$site_id = get_current_blog_id();
	}

	$plugin_control = wppc_get_plugin_control_for_site( $site_id );

	$plugin_control[ $plugin_file ] = (bool) $enable;

	return update_blog_option( $site_id, 'wp_plugin_control', $plugin_control );
}

function wppc_get_plugin_control_for_site( $site_id = null ) {
	if ( empty( $site_id ) ) {
		$site_id = get_current_blog_id();
	}

	return get_blog_option( $site_id, 'wp_plugin_control', array() );
}

function wppc_toggle_plugin_for_network( $plugin_file, $enable = true, $network_id = null ) {
	if ( empty( $network_id ) ) {
		$network_id = get_current_network_id();
	}

	$plugin_control = wppc_get_plugin_control_for_network( $network_id );

	$plugin_control[ $plugin_file ] = (bool) $enable;

	return update_network_option( $network_id, 'wp_plugin_control', $plugin_control );
}

function wppc_get_plugin_control_for_network( $network_id = null ) {
	if ( empty( $network_id ) ) {
		$network_id = get_current_network_id();
	}

	return get_network_option( $network_id, 'wp_plugin_control', array() );
}

function wppc_get_plugins_enabled_for_site( $site_id = null ) {
	$plugin_control = wppc_get_plugin_control_for_site( $site_id );

	return array_keys( array_filter( $plugin_control ) );
}

function wppc_get_plugins_disabled_for_site( $site_id = null ) {
	$plugin_control = wppc_get_plugin_control_for_site( $site_id );

	return array_keys( array_filter( $plugin_control, 'wppc_not_filter' ) );
}

function wppc_get_plugins_enabled_for_network( $network_id = null ) {
	$plugin_control = wppc_get_plugin_control_for_network( $network_id );

	return array_keys( array_filter( $plugin_control ) );
}

function wppc_get_plugins_disabled_for_network( $network_id = null ) {
	$plugin_control = wppc_get_plugin_control_for_network( $network_id );

	return array_keys( array_filter( $plugin_control, 'wppc_not_filter' ) );
}

function wppc_get_all_plugins_disabled_for_site( $site_id = null ) {
	$site = get_site( $site_id );
	if ( ! $site ) {
		return array();
	}

	$disabled_plugins = wppc_get_plugins_disabled_for_network( $site->network_id );
	$disabled_plugins = array_diff( $disabled_plugins, wppc_get_plugins_enabled_for_site( $site->id ) );
	$disabled_plugins = array_merge( $disabled_plugins, wppc_get_plugins_disabled_for_site( $site->id ) );

	return apply_filters( 'wppc_all_plugins_disabled_for_site', $disabled_plugins, $site->id );
}

function wppc_get_all_plugins_disabled_for_network( $network_id = null ) {
	$network = get_network( $network_id );
	if ( ! $network ) {
		return array();
	}

	$disabled_plugins = wppc_get_plugins_disabled_for_network( $network->id );

	return apply_filters( 'wppc_all_plugins_disabled_for_network', $disabled_plugins, $network->id );
}

function wppc_not_filter( $value ) {
	return ! $value;
}
