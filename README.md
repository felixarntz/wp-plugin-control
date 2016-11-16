# WP Plugin Control

Improves plugin control on multisite by supporting enabling and disabling of plugins per site or network.

## Background

The default multisite behavior in Core is that themes can be enabled and disabled per site. When a theme is disabled for a site, it is invisible there.
This is however different for plugins. While it usually makes sense that plugins are more global and should be available on all sites, there can be cases where this is undesirable.
If a plugin is made for one specific site, it might make sense to restrict it to that site and disable it everywhere else.

This plugin implements such a mechanism where plugins can be enabled and disabled per site and per network.
Enabling and disabling in this case is equivalent to toggling visibility: If a plugin is disabled for a site, the regular site administrator will not be able to see it in the plugins list.

A few pieces of information:
* Whether a plugin is enabled or disabled doesn't impact its active status in any way. When an active plugin is disabled, it will not be deactivated automatically, thus remain active.
* The plugin also supports enabling or disabling plugins for an entire network. Enabling or disabling plugins per site takes precedence though.
* Any user with the capability `toggle_plugins` can enable or disable plugins. By default, only the network administrator has this capability. Any user with that capability will still be able to see all plugins, regardless of whether they are disabled or not.

## Requirements

* WordPress >= 4.6
* Multisite enabled
