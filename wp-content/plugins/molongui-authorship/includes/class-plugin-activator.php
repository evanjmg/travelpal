<?php

namespace Molongui\Authorship\Includes;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @author     Amitzy
 * @category   Molongui
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/includes
 * @since      1.0.0
 * @version    1.2.9
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

class Plugin_Activator
{
	/**
	 * Fires all required actions during plugin activation.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.0
	 */
	public static function activate()
	{
		// Remove rewrite rules and then recreate rewrite rules.
		flush_rewrite_rules();

		// Insert default plugin options into database.
		self::add_default_options();

		// If premium plugin, add license options into database.
		if ( MOLONGUI_AUTHORSHIP_LICENSE == 'premium' ) self::add_license_options();

		// Replace WP User Profile "description" field.
		self::replace_description_field();
	}


	/**
	 * Initialize default option values.
	 *
	 * This functions stores default plugin settings into options table at Wordpress database.
	 *
	 * @access   public
	 * @since    1.2.0
	 * @version  1.2.9
	 */
	public static function add_default_options()
	{
		// Define default options
		$default_options = array(
			'molongui_authorship_display'                 => '1',
			'molongui_authorship_position'                => 'below',
			'molongui_authorship_layout'                  => 'default',
			'molongui_authorship_hide_if_no_bio'          => 'no',
			'molongui_authorship_box_shadow'              => 'left',
			'molongui_authorship_box_border'              => 'none',
			'molongui_authorship_box_border_color'        => 'inherit',
			'molongui_authorship_img_style'               => 'none',
			'molongui_authorship_name_size'               => 'smaller',
			'molongui_authorship_name_color'              => 'inherit',
			'molongui_authorship_meta_size'               => 'smallest',
			'molongui_authorship_meta_color'              => 'inherit',
			'molongui_authorship_bio_size'                => 'smallest',
			'molongui_authorship_bio_color'               => 'inherit',
			'molongui_authorship_bio_align'               => 'justify',
			'molongui_authorship_bio_style'               => 'normal',
			'molongui_authorship_icons_show'              => '1',
			'molongui_authorship_icons_size'              => 'inherit',
			'molongui_authorship_icons_style'             => 'default',
			'molongui_authorship_bottom_bg'               => 'inherit',
			'molongui_authorship_bottom_border'           => 'none',
			'molongui_authorship_bottom_border_color'     => '#B6B6B6',
			'molongui_authorship_show_social_networks_tw' => '1',
			'molongui_authorship_show_social_networks_fb' => '1',
			'molongui_authorship_show_social_networks_in' => '1',
			'molongui_authorship_show_social_networks_gp' => '1',
			'molongui_authorship_show_social_networks_yt' => '1',
			'molongui_authorship_show_social_networks_pi' => '1',
			'molongui_authorship_show_social_networks_tu' => '0',
			'molongui_authorship_show_social_networks_ig' => '1',
			'molongui_authorship_show_social_networks_xi' => '1',
			'molongui_authorship_show_social_networks_re' => '0',
			'molongui_authorship_show_social_networks_vk' => '0',
			'molongui_authorship_show_social_networks_fl' => '0',
			'molongui_authorship_show_social_networks_vi' => '0',
			'molongui_authorship_show_social_networks_me' => '0',
			'molongui_authorship_show_social_networks_we' => '0',
			'molongui_authorship_show_social_networks_de' => '0',
			'molongui_authorship_show_social_networks_st' => '0',
			'molongui_authorship_show_social_networks_my' => '0',
			'molongui_authorship_show_social_networks_ye' => '0',
			'molongui_authorship_show_social_networks_mi' => '0',
			'molongui_authorship_show_social_networks_so' => '0',
			'molongui_authorship_show_social_networks_la' => '0',
			'molongui_authorship_show_social_networks_fo' => '0',
			'molongui_authorship_show_social_networks_sp' => '0',
			'molongui_authorship_show_social_networks_vm' => '0',
			'molongui_authorship_add_opengraph_meta'      => '1',
			'molongui_authorship_add_google_meta'         => '1',
			'molongui_authorship_add_facebook_meta'       => '1',
			'molongui_authorship_keep_config'             => 'yes',
			'molongui_authorship_keep_data'               => 'yes',
		);

		// If no plugin options are found in database (first install), insert them
		if ( !get_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY ) )
		{
			add_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY, $default_options );
		}
		else
		{
			// Get existing settings
			$config = (array)get_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY );

			// Insert new settings
			$update = array_merge( $default_options, $config );

			// Update settings into database
			update_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY, $update );
		}
	}


	/**
	 * Set default plugin license options.
	 *
	 * This functions stores default plugin license settings into options table at Wordpress database.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public static function add_license_options()
	{
		global $wpdb;

		$config = include dirname( plugin_dir_path( __FILE__ ) ) . "/premium/config/update.php";

		$global_options = array(
			$config['db']['activation_key']   => '',
			$config['db']['activation_email'] => '',
		);

		update_option( $config['db']['license_key'], $global_options );

		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/update/class-plugin-password.php' );

		$plugin_password = new Plugin_Password();

		// Generate a unique installation $instance id
		$instance = $plugin_password->generate_password( 12, false );

		$single_options = array(
			$config['db']['product_id_key']          => $config['sw']['id'],
			$config['db']['instance_key']            => $instance,
			$config['db']['deactivate_checkbox_key'] => 'on',
			$config['db']['activated_key']           => 'Deactivated',
		);

		foreach ( $single_options as $key => $value )
		{
			update_option( $key, $value );
		}

		$curr_ver = get_option( $config['db']['version_key'] );

		// Check if the current plugin version is lower than the version being installed
		if ( version_compare( MOLONGUI_AUTHORSHIP_VERSION, $curr_ver, '>' ) )
		{
			// Update the version into database
			update_option( $config['db']['version_key'], MOLONGUI_AUTHORSHIP_VERSION );
		}
	}


	/**
	 * Replace WP User Profile "description" field.
	 *
	 * Some themes display its own "author box" if "description" field is not empty. This function avoids that by
	 * copying "description" field contents to custom "molongui_author_bio" user meta field and emptying the first
	 * one.
	 *
	 * As long as "description" field cannot be removed from profile page without hacking into WP code, it is hidden
	 * using CSS styling.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public static function replace_description_field()
	{
		$users = get_users();

		foreach ( $users as $user )
		{
			if ( $user->description ) update_user_meta( $user->ID, 'molongui_author_bio', $user->description );
			update_user_meta( $user->ID, 'description', '' );
		}
	}

}
