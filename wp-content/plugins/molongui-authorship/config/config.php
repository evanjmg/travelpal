<?php

/**
 * Plugin configuration.
 *
 * Contains the plugin main configuration parameters and declares them as global constants.
 *
 * This file is loaded like:
 *      require_once( plugin_dir_path( __FILE__ ) . 'config/config.php' );
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/config
 * @since      1.0.0
 * @version    1.2.10
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Constants.
 *
 * Add as many as needed by the plugin.
 *
 *  NAME                // The human readable plugin name.
 *  LICENSE             // The plugin license type: {free | premium}.
 *  VERSION             // The plugin version.
 *  NAME_ID             //
 *  SLUG                //
 *  TEXT_DOMAIN         // The plugin text-domain used for localisation. If changed, change it also at "/molongui-authorship.php".
 *  WEB                 // The plugin author's web.
 *  MENU                // Admin page (backend) menu type: {topmenu | submenu}.
 *  SUBMENU             // Admin page submenu where to place plugin settings page: {dashboard | posts | media | pages | comments | appearance | plugins | users | tools | settings}.
 *  CONFIG_KEY          // DB key used to store plugin configuration.
 *  UPGRADABLE          // Whether the plugin has a premium version or not {yes | no}.
 *
 * @since   1.0.0
 * @version 1.2.10
 */

$config = array(
	'NAME'          => 'Molongui Authorship',
	'LICENSE'       => 'free',
	'VERSION'       => '1.2.10',
	'ID'            => 'molongui-authorship',
	'SLUG'          => 'molongui-authorship',
	'TEXT_DOMAIN'   => 'molongui-authorship',
	'WEB'           => 'http://molongui.amitzy.com/product/authorship',
	'MENU'          => 'submenu',
	'SUBMENU'       => 'settings',
	'CONFIG_KEY'    => 'molongui_authorship_config',
	'UPGRADABLE'    => 'yes',
);

/**
 * Global constant namespace.
 *
 * String added before each constant to avoid collisions in the global PHP namespace.
 *
 * @var     string
 */
$constant_prefix = 'MOLONGUI_AUTHORSHIP_';


//DO NOT EDIT FROM HERE ON...

/**
 * Define each constant if not already set
 *
 * @since   1.0.0
 * @version 1.0.0
 */
foreach( $config as $param => $value )
{
	$param = $constant_prefix . $param;
	if( !defined( $param ) ) define( $param, $value );
}

/**
 * Define paths.
 *
 * Defines plugin paths.
 *
 *  DIR                 // The plugin's local path. Something like: /var/www/hmtl/wp-content/plugins/plugin-name
 *  URL                 // The plugin's public path. Something like: http://domain.com/wp-content/plugins/plugin-name
 *  BASE_NAME           // The plugin's basename. Something like: plugin-name/plugin-name.php
 *
 * @since   1.0.0
 * @version 1.0.0
 */

if( !defined( $constant_prefix . 'DIR' ) )       define( $constant_prefix . 'DIR'       , dirname( plugin_dir_path( __FILE__ ) ) );
if( !defined( $constant_prefix . 'URL' ) )       define( $constant_prefix . 'URL'       , plugins_url( '', plugin_dir_path( __FILE__ ) ) );
if( !defined( $constant_prefix . 'BASE_NAME' ) ) define( $constant_prefix . 'BASE_NAME' , plugin_basename( dirname( plugin_dir_path( __FILE__ ) ) ). '/' . $config['ID'] . '.php' );