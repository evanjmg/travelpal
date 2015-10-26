<?php

/**
 * Provide an admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin/views
 * @since      1.0.0
 * @version    1.0.0
 */


$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->tab_config_slug;

// Display plugin name and version
echo '<h2>' . __('Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN) . '<span class="version">' . MOLONGUI_AUTHORSHIP_VERSION . '</span>' . '</h2>';

// Display "powered by Molongui"
?><p class="powered-by">Powered by<a href="http://molongui.amitzy.com/" title="Molongui">Molongui</a></p><?php

// Display tabs
echo '<h2 class="nav-tab-wrapper">';
	foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption )
	{
		$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
		echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
	}
echo '</h2>';