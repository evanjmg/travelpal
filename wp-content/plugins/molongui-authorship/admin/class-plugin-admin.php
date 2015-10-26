<?php

namespace Molongui\Authorship\Admin;

use Molongui\Authorship\Includes\Plugin_Key;
use Molongui\Authorship\Includes\Plugin_Update;
//use Molongui\Authorship\Includes\Plugin_Upsell;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin
 * @since      1.0.0
 * @version    1.2.10
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

class Plugin_Admin
{
	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $version;

	/**
	 * The URI slug of this plugin.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_slug;

	/**
	 * The link to the main settings page of the software.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $menu_slug;

	/**
	 * Holds all the configuration set into /premium/config/update.php configuration file.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $config;

	/**
	 * Path to admin images folder.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $path_img;

	/**
	 * TABS
	 */

	/**
	 * Array used to store all settings page tabs that are defined below.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_settings_tabs = array();

	/**
	 * Names used to store each tab settings into 'options' table in the database.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $tab_config_options_key  = MOLONGUI_AUTHORSHIP_CONFIG_KEY;
	private $tab_about_options_key   = 'molongui_authorship_about';

	/**
	 * The URI slugs of each tab of the admin settings page.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $tab_config_slug     = 'settings';
	private $tab_about_slug      = 'about';
	private $tab_activate_slug   = 'activate';
	private $tab_deactivate_slug = 'deactivate';

	/**
	 * LICENSE and UPDATE
	 */

	/**
	 * ID applied to the displayed checkbox.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public $deactivate_checkbox_id;

	/**
	 * Used to hold an instance of "Plugin_Key" class
	 *
	 * @access   private
	 * @var      Plugin_Key
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_key;

	/**
	 * Update data used across this class
	 *
	 * @access   public
	 * @var      mixed
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public $update_license;
	public $update_basename;
	public $update_product_id;
	public $update_renew_license_url;
	public $update_instance_id;
	public $update_domain;
	public $update_sw_version;
	public $update_plugin_or_theme;
	public $update_extra;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @access   public
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function __construct( $plugin_name, $version )
	{
		// Init vars
		$this->plugin_name = $plugin_name;
		$this->plugin_slug = $plugin_name;
		$this->version     = $version;
		$this->path_img    = plugin_dir_url( __FILE__ ) . 'img/';

		// Load the required dependencies
		$this->load_dependencies();

		if( MOLONGUI_AUTHORSHIP_LICENSE == 'premium' )
		{
			// Load update configuration
			$this->config = include dirname( plugin_dir_path( __FILE__ ) ) . "/premium/config/update.php";

			// Load premium dependencies
			$this->load_premium_dependencies();

			// Handle license stuff
			$this->manage_license();
		}
	}


	/**
	 * Load the required dependencies for this class.
	 *
	 * Load other classes definitions used by this class.
	 *
	 * @access   private
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private function load_dependencies()
	{
		/**
		 * The class responsible for getting a list with all the plugins developed by Molongui.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/includes/common/class-plugin-upsell.php' );
	}


	/**
	 * Load the required premium dependencies for this class.
	 *
	 * Load other classes definitions used by this class.
	 *
	 * @access   private
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private function load_premium_dependencies()
	{
		/**
		 * The class responsible for defining all actions related with the license handling.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/update/class-plugin-key.php' );

		/**
		 * The class responsible for defining update functionality of the plugin.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/update/class-plugin-update.php' );
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.0
	 */
	public function enqueue_styles()
	{
		// Enqueue color-picker styles
		wp_enqueue_style( 'wp-color-picker' );

		// Enqueue plugin styles

		if( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
		{
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/molongui-authorship-admin.a26f.min.css', array(), $this->version, 'all' );
		}
		else
		{
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'premium/admin/css/molongui-authorship-premium-admin.a26f.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.0
	 */
	public function enqueue_scripts()
	{
		// Enqueue plugin scripts

		if( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
		{
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/molongui-authorship-admin.dd9e.min.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
		}
		else
		{
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'premium/admin/js/molongui-authorship-premium-admin.dd9e.min.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
		}
	}


	/**
	 * Displays an upgrade notice.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function display_go_premium_notice()
	{
		global $current_screen;

		if ( $current_screen->id != MOLONGUI_AUTHORSHIP_SUBMENU . '_page_molongui-authorship' ) return;

		echo '<div id="message" class="notice premium">';
			echo '<p>';
				printf( __( 'There is a premium version of this plugin. Grab a %spremium licence%s to unlock all features and have direct support.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
						'<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank" >',
				        '</a>' );
			echo '</p>';
		echo '</div>';
	}


	/**
	 * Handle license stuff for premium plugins.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function manage_license()
	{
		// Set deactivate checkbox ID
		$this->deactivate_checkbox_id = 'deactivate_plugin_checkbox';

		// Set all software update data here
		$this->update_license           = get_option( $this->config['db']['license_key'] );
		$this->update_basename          = MOLONGUI_AUTHORSHIP_BASE_NAME; // same as plugin slug. if a theme use a theme name like 'twentyeleven'
		$this->update_product_id        = get_option( $this->config['db']['product_id_key'] ); // Software Title
		$this->update_renew_license_url = 'http://molongui.amitzy.com/my-account'; // URL to renew a license
		$this->update_instance_id       = get_option( $this->config['db']['instance_key'] ); // Instance ID (unique to each blog activation)
		$this->update_domain            = site_url(); // blog domain name
		$this->update_sw_version        = $this->version; // The software version
		$this->update_plugin_or_theme   = $this->config['sw']['type'];

		// Displays an inactive message if no license has been activated
		add_action( 'admin_notices', array( &$this, 'display_inactive_notice' ) );

		// Instantiate the class that handles the license key (it is used by other functions in this class)
		$this->plugin_key = new Plugin_Key($this->update_product_id, $this->update_instance_id, $this->update_domain, $this->update_sw_version, $this->config['server']['url']);

		// Check for software updates
		$options = get_option( $this->config['db']['license_key'] );

		if( !empty( $options ) && $options !== false )
		{
			new Plugin_Update(
				$this->config['server']['url'],
				$this->update_basename,
				$this->update_product_id,
				$this->update_license[ $this->config['db']['activation_key'] ],
				$this->update_license[ $this->config['db']['activation_email'] ],
				$this->update_renew_license_url,
				$this->update_instance_id,
				$this->update_domain,
				$this->update_sw_version,
				$this->update_plugin_or_theme,
				MOLONGUI_AUTHORSHIP_TEXT_DOMAIN
			);
		}
	}

	/**
	 * Displays an inactive notice when the software is inactive.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function display_inactive_notice()
	{
		if ( !current_user_can( 'manage_options' ) ) return;
		if ( get_option( $this->config['db']['activated_key'] ) != 'Activated' )
		{
			echo '<div id="message" class="error">';
				echo '<p>';
					printf( __( 'Molongui Authorship license has not been activated, so the plugin is inactive! %sClick here%s to activate the license key and
					the plugin.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<a href="' . esc_url( admin_url( $this->menu_slug . '&tab=' . $this->tab_activate_slug ) ) . '">', '</a>' );
				echo '</p>';
			echo '</div>';
		}
	}

	/**
	 * Add extra "action links" to the admin plugins page.
	 *
	 * @see      http://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_%28plugin_file_name%29
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function add_action_links( $links )
	{
		$more_links = array(
			'settings' => '<a href="' . admin_url( $this->menu_slug ) . '">' . __( 'Settings', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</a>',
			'docs'     => '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '/docs" target="blank" >' . __( 'Docs', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</a>'
		);

		if( MOLONGUI_AUTHORSHIP_LICENSE == 'free' )
		{
			$more_links['gopro'] = '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '/" target="blank" style="font-weight:bold;">' . __( 'Go Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</a>';
		}

		return array_merge(
			$more_links,
			$links
		);
	}

	/**
	 * Add menu link to the admin menu at the admin area.
	 *
	 * This function registers the menu link to the settings page and the settings page itself.
	 *
	 * @access   public
	 * @see      https://codex.wordpress.org/Function_Reference/add_menu_page
	 * @see      https://codex.wordpress.org/Function_Reference/add_submenu_page
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function add_menu_item()
	{
		if( MOLONGUI_AUTHORSHIP_MENU == "topmenu" )
		{
			add_menu_page( 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ), '', '20' );
			$this->menu_slug = 'admin.php?page=';
		}
		else
		{
			switch ( MOLONGUI_AUTHORSHIP_SUBMENU )
			{
				case 'dashboard':
					add_submenu_page( 'index.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'index.php?page=' . $this->plugin_slug;
				break;

				case 'posts':
					add_submenu_page( 'edit.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'edit.php?page=' . $this->plugin_slug;
				break;

				case 'media':
					add_submenu_page( 'upload.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'upload.php?page=' . $this->plugin_slug;
				break;

				case 'pages':
					add_submenu_page( 'edit.php?post_type=page', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'edit.php?post_type=page&page=' . $this->plugin_slug;
				break;

				case 'comments':
					add_submenu_page( 'edit-comments.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'edit-comments.php?page=' . $this->plugin_slug;
				break;

				case 'appearance':
					add_submenu_page( 'themes.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'themes.php?page=' . $this->plugin_slug;
				break;

				case 'plugins':
					add_submenu_page( 'plugins.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'plugins.php?page=' . $this->plugin_slug;
				break;

				case 'users':
					add_submenu_page( 'users.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'users.php?page=' . $this->plugin_slug;
				break;

				case 'tools':
					add_submenu_page( 'tools.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'tools.php?page=' . $this->plugin_slug;
				break;

				case 'settings':
					add_submenu_page( 'options-general.php', 'Molongui Authorship', 'Molongui Authorship', 'manage_options', $this->plugin_slug, array( $this, 'render_page_settings' ) );
					$this->menu_slug = 'options-general.php?page=' . $this->plugin_slug;
				break;
			}
		}
	}

	/**
	 * Display the Settings Page for the admin area.
	 *
	 * This function renders a tabbed settings page. In order to customize it, edit
	 * 'views/html-admin-page-settings.php' file. This function should not be modified.
	 *
	 * On free plugins, the wrapper is divided into two sections: main and sidebar.
	 * Main contains the settings to edit and the sidebar shows some ads. On "about"
	 * tab, no sidebar is shown.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.6
	 */
	public function render_page_settings()
	{
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->tab_config_slug;
		?>
		<div id="molongui-settings" class="wrap molongui license-<?php echo MOLONGUI_AUTHORSHIP_LICENSE; ?>">
			<?php include( plugin_dir_path( __FILE__ ) . 'views/html-admin-page-settings.php' ); ?>
			<div class="<?php if ( (MOLONGUI_AUTHORSHIP_LICENSE != 'premium') && ( $tab != $this->tab_about_slug ) ) echo 'main'; ?>">
				<form method="post" action="options.php">
					<?php wp_nonce_field( 'update-options' ); ?>
					<?php settings_fields( $tab ); ?>
					<?php do_settings_sections( $tab ); ?>
					<?php if( $tab != $this->tab_about_slug ) submit_button(); ?>
				</form>
			</div>
			<?php if ( (MOLONGUI_AUTHORSHIP_LICENSE != 'premium') && ( $tab != $this->tab_about_slug ) ) include( plugin_dir_path( __FILE__ ) . 'views/html-admin-page-sidebar.php' ); ?>
		</div>
	<?php
	}

	/**
	 * Load all the plugin settings.
	 *
	 * This function loads the plugin settings from the database and then merges them with some
	 * default values for when they are not set.
	 *
	 * As settings page is divided into tabs, each tab has its own options group.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.10
	 */
	function load_plugin_settings()
	{
		// Load settings
		$this->tab_config = (array)get_option( $this->tab_config_options_key );
		$this->tab_about  = (array)get_option( $this->tab_about_options_key );

		// Load settings for premium plugins
		if ( MOLONGUI_AUTHORSHIP_LICENSE == 'premium' )
		{
			$this->tab_activate   = (array)get_option( $this->config['db']['license_key'] );
			$this->tab_deactivate = (array)get_option( $this->config['db']['license_key'] );
		}

		// Default option values
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
			'molongui_authorship_icons_size'              => 'normal',
			'molongui_authorship_icons_color'             => 'inherit',
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

		// Insert new settings
		$update = array_merge( $default_options, $this->tab_config );

		// Update settings into database
		update_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY, $update );
	}

	/**
	 * Register settings page tabs.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	function add_page_tabs()
	{
		$this->register_tab_config();
		if ( MOLONGUI_AUTHORSHIP_LICENSE == 'premium' )
		{
			$this->register_tab_activate();
			$this->register_tab_deactivate();
		}
		$this->register_tab_about();
	}

	/**
	 * Register "Activate License" settings page tab.
	 *
	 * These functions register the "Activate License" tab.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	function register_tab_activate()
	{
		// Set tab label
		$this->plugin_settings_tabs[$this->tab_activate_slug] = __( 'License Activation', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );

		// Define available configurable settings
		register_setting( $this->tab_activate_slug, $this->config['db']['license_key'], array( $this, 'validate_options' ) );
		add_settings_section( $this->config['db']['activation_key'], __( 'License Activation', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_activate_section_title' ), $this->tab_activate_slug );
		add_settings_field( $this->config['db']['activation_key'], __( 'License Key', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_activate_key_field' ), $this->tab_activate_slug, $this->config['db']['activation_key'], array( $this->config['db']['activation_key'] ) );
		add_settings_field( $this->config['db']['activation_email'], __( 'License email', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_activate_email_field' ), $this->tab_activate_slug, $this->config['db']['activation_key'], array( $this->config['db']['activation_email'] ) );
	}
	// Display the section title on activate tab
	public function display_tab_activate_section_title()
	{
	}
	// Display license key input field
	public function display_tab_activate_key_field()
	{
		echo "<input id='activation_key' name='" . $this->config['db']['license_key'] . "[" . $this->config['db']['activation_key'] ."]' size='25' type='text'
		     value='" . $this->update_license[$this->config['db']['activation_key']] . "' />";

		if ( $this->update_license[$this->config['db']['activation_key']] )
		{
			echo "<span class='icon-pos'><img src='" . $this->path_img . "success.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		}
		else
		{
			echo "<span class='icon-pos'><img src='" . $this->path_img . "warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		}
	}
	// Display activation mail input field
	public function display_tab_activate_email_field()
	{
		echo "<input id='activation_email' name='" . $this->config['db']['license_key'] . "[" .$this->config['db']['activation_email'] ."]' size='25' type='text'
		     value='" . $this->update_license[$this->config['db']['activation_email']] . "' />";
		if ( $this->update_license[$this->config['db']['activation_email']] )
		{
			echo "<span class='icon-pos'><img src='" . $this->path_img . "success.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		}
		else
		{
			echo "<span class='icon-pos'><img src='" . $this->path_img . "warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		}
	}
	// Sanitizes and validates all input and output for Dashboard
	public function validate_options( $input )
	{
		// Load existing options, validate, and update with changes from input before returning
		$options = $this->update_license;

		$options[$this->config['db']['activation_key']]   = trim( $input[$this->config['db']['activation_key']] );
		$options[$this->config['db']['activation_email']] = trim( $input[$this->config['db']['activation_email']] );

		/**
		 * Plugin Activation
		 */
		$api_key   = trim( $input[$this->config['db']['activation_key']] );
		$api_email = trim( $input[$this->config['db']['activation_email']] );

		$activation_status = get_option( $this->config['db']['activated_key'] );
		$checkbox_status   = get_option( $this->config['db']['deactivate_checkbox_key'] );
		$current_api_key   = $this->update_license[$this->config['db']['activation_key']];

		// DEBUG: For testing activation status_extra data
		// echo "<pre>"; print_r(array($_REQUEST, $input, $options, $api_key, $current_api_key, $api_email, $activation_status, $checkbox_status)); echo "</pre>"; die;

		// Just on "activate" license page...
		if ( $_REQUEST['option_page'] != $this->tab_deactivate_slug )
		{
			if ( $activation_status == 'Deactivated' || $activation_status == '' || $api_key == '' || $api_email == '' || $checkbox_status == 'on' || $current_api_key != $api_key  )
			{
				/**
				 * If this is a new key, and an existing key already exists in the database,
				 * deactivate the existing key before activating the new key.
				 */
				if ( !empty($current_api_key) && ($current_api_key != $api_key) ) $this->replace_license_key( $current_api_key );

				$args = array(
					'licence_key' => $api_key,
					'email'       => $api_email,
				);

				$activate_results = json_decode( $this->plugin_key->activate( $args ), true );

				if ( $activate_results['activated'] == true && !isset( $activate_results['code'] ) && !isset( $activate_results['error'] ) )
				{
					add_settings_error( 'activate_text', 'activate_msg', __( 'Plugin activated. ', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . "{$activate_results['message']}.", 'updated' );
					update_option($this->config['db']['activated_key'], 'Activated' );
					update_option($this->config['db']['deactivate_checkbox_key'], 'off' );
				}

				if ( $activate_results == false )
				{
					add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Try again later.',MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'error' );
					$options[$this->config['db']['activation_key']]   = '';
					$options[$this->config['db']['activation_email']] = '';
					update_option($this->update_license[$this->config['db']['activated_key']], 'Deactivated' );
				}

				if ( isset( $activate_results['code'] ) )
				{
					// Show error message
					switch ( $activate_results['code'] )
					{
						case '100':
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '101':
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '102':
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '103':
							add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '104':
							add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '105':
							add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '106':
							add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
					}

					// Clear license settings in database
					$options[ $this->config['db']['activation_key'] ]   = '';
					$options[ $this->config['db']['activation_email'] ] = '';
					update_option( $this->config['db']['activated_key'], 'Deactivated' );
				}
			} // End Plugin Activation
		}

		// DEBUG: For testing activation status_extra data
		// echo "<pre>"; print_r(array($_REQUEST, $input, $args, $activate_results)); echo "</pre>"; die;

		return $options;
	}
	// Deactivate the current license key before activating the new license key
	public function replace_license_key( $current_api_key )
	{
		$args = array(
			'email'       => $this->update_license[$this->config['db']['activation_email']],
			'licence_key' => $current_api_key,
		);

		// Reset license key activation
		$reset = $this->plugin_key->deactivate( $args );

		if ( $reset == true ) return true;

		return add_settings_error( 'not_deactivated_text', 'not_deactivated_error', __( 'The license could not be
		deactivated. Use the License Deactivation tab to manually deactivate the license before activating a new
		license.',MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'updated' );
	}

	/**
	 * Register "Deactivate License" settings page tab.
	 *
	 * These functions register the "Deactivate License" tab.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	function register_tab_deactivate()
	{
		// Set tab label
		$this->plugin_settings_tabs[$this->tab_deactivate_slug] = __( 'License Deactivation', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );

		// Define available configurable settings
		register_setting( $this->tab_deactivate_slug, $this->config['db']['deactivate_checkbox_key'], array( $this, 'deactivate_license_key' ) );
		add_settings_section( $this->config['db']['deactivate_checkbox_key'], __( 'License Deactivation', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this,
		                      'display_tab_deactivate_section_title' ), $this->tab_deactivate_slug );
		add_settings_field( $this->config['db']['deactivate_checkbox_key'], __( 'Deactivate License Key', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this,
		                      'display_tab_deactivate_checkbox'), $this->tab_deactivate_slug, $this->config['db']['deactivate_checkbox_key'] );
	}
	// Deactivates the license key to allow key to be used on another blog
	public function deactivate_license_key( $input )
	{
		// Just on "deactivate" license page...
		if ( $_REQUEST['option_page'] == $this->tab_deactivate_slug )
		{
			$activation_status = get_option( $this->config['db']['activated_key'] );

			$args = array(
				'licence_key' => $this->update_license[ $this->config['db']['activation_key'] ],
				'email'       => $this->update_license[ $this->config['db']['activation_email'] ],
			);

			// DEBUG: For testing activation status_extra data
			// $activate_results = json_decode( $this->plugin_key->status( $args ), true );
			// echo "<pre>"; print_r(array($_POST, $activate_results)); echo "</pre>"; exit();

			$options = ( $input == 'on' ? 'on' : 'off' );

			if( $options == 'on' && $activation_status == 'Activated' && $this->update_license[ $this->config['db']['activation_key'] ] != '' &&
			    $this->update_license[ $this->config['db']['activation_email'] ] != ''
			)
			{
				// Deactivates license key activation on Molongui's server
				$activate_results = json_decode( $this->plugin_key->deactivate( $args ), true );

				// DEBUG: Used to display results for development
				// echo "<pre>"; print_r(array($_POST, $activate_results)); echo "</pre>"; exit();

				if( $activate_results['deactivated'] == true )
				{
					$update = array(
						$this->config['db']['activation_key']   => '',
						$this->config['db']['activation_email'] => ''
					);

					$merge_options = array_merge( $this->update_license, $update );

					update_option( $this->config['db']['license_key'], $merge_options );
					update_option( $this->config['db']['activated_key'], 'Deactivated' );

					add_settings_error( 'deactivate_text', 'deactivate_msg', __( 'Plugin license deactivated. ', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) .
					                                                         "{$activate_results['activations_remaining']}.", 'updated' );

					return $options;
				}

				if( isset( $activate_results['code'] ) )
				{
					// Show error message
					switch( $activate_results['code'] )
					{
						case '100':
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}",
							                    'error' );
							break;
						case '101':
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}",
							                    'error' );
							break;
						case '102':
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error',
							                    "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '103':
							add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error',
							                    "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '104':
							add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error',
							                    "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '105':
							add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error',
							                    "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '106':
							add_settings_error( 'sub_not_active_text', 'sub_not_active_error',
							                    "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
					}

					// Clear license settings because it cannot be deactivated
					$clear = array(
						$this->config['db']['activation_key']   => '',
						$this->config['db']['activation_email'] => ''
					);

					update_option( $this->config['db']['license_key'], $clear );
					update_option( $this->config['db']['activated_key'], 'Deactivated' );
				}
			} else
			{
				// Show error message: no active license to deactivate
				add_settings_error( 'deactivate_no_license_text', 'deactivate_no_license_error',
				                    __( 'There is no active license to deactivate.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'error' );

				return $options = 'off';
			}
		}
	}
	// Display the section title on deactivate tab
	public function display_tab_deactivate_section_title()
	{
	}
	// Display license deactivation checkbox
	public function display_tab_deactivate_checkbox()
	{
		echo '<input type="checkbox" id="' . $this->deactivate_checkbox_id . '" name="'. $this->config['db']['deactivate_checkbox_key'] .'"value="on"';
		echo checked( get_option( $this->config['db']['deactivate_checkbox_key'] ), 'on' ); //echo checked( get_option( $this->deactivate_checkbox_id ), 'on' );
		echo '/>';
		?>
		<span class="description"><?php _e( 'Deactivates the License Key so it can be used on another blog.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span>
	<?php
	}

	/**
	 * Register "About" settings page tab.
	 *
	 * These functions register the "About" tab.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	function register_tab_about()
	{
		// Set tab label
		$this->plugin_settings_tabs[ $this->tab_about_slug ] = __( 'About', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );

		// Define available configurable settings
		register_setting( $this->tab_about_slug, $this->tab_about_options_key );
		add_settings_section( 'section_about', __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'render_tab_about' ), $this->tab_about_slug );
		// add here as many settings as needed...
	}
	function render_tab_about()
	{
		include( plugin_dir_path( __FILE__ ) . 'views/html-admin-page-about.php' );
	}

	/**
	 * Register "Config" settings page tab.
	 *
	 * These functions register the "Config" tab.
	 *
	 * @access   public
	 * @see      https://codex.wordpress.org/Function_Reference/register_setting
	 * @see      https://codex.wordpress.org/Function_Reference/add_settings_section
	 * @see      https://codex.wordpress.org/Function_Reference/add_settings_field
	 * @since    1.0.0
	 * @version  1.2.9
	 */
	function register_tab_config()
	{
		// Set tab label
		$this->plugin_settings_tabs[ $this->tab_config_slug ] = __( 'Settings', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );

		// Define available configurable settings
		register_setting( $this->tab_config_slug, $this->tab_config_options_key, array( $this, 'validate_tab_config_settings' )  );
		add_settings_section( 'section_config_1', __( 'Display options', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_section_1_desc' ), $this->tab_config_slug );
		add_settings_field( 'molongui_authorship_display', __( 'Show box by default', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_yesno' ), $this->tab_config_slug, 'section_config_1', array( 'molongui_authorship_display', '' ) );
		add_settings_field( 'molongui_authorship_position', __( 'Box position', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_position' ), $this->tab_config_slug, 'section_config_1', array( 'molongui_authorship_position', '' ) );
		add_settings_field( 'molongui_authorship_hide_if_no_bio', __( 'Hide box if no description', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_yesno' ), $this->tab_config_slug, 'section_config_1', array( 'molongui_authorship_hide_if_no_bio', '' ) );
		add_settings_field( 'molongui_authorship_layout', $this->premium_option( __( 'Box layout', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_select_layout' ), $this->tab_config_slug, 'section_config_1', array( 'molongui_authorship_layout' ) );
		add_settings_section( 'section_config_2', __( 'Box Styling', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_section_2_desc' ), $this->tab_config_slug );
		add_settings_field( 'molongui_authorship_box_shadow', __( 'Box shadow', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_box_shadow' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_box_shadow', '' ) );
		add_settings_field( 'molongui_authorship_box_border', __( 'Box border', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_box_border' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_box_border', '' ) );
		add_settings_field( 'molongui_authorship_box_border_color', __( 'Box border color', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_color_picker' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_box_border_color', '' ) );
		add_settings_field( 'molongui_authorship_img_style', __( 'Image style', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_image_style' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_img_style', '' ) );
		add_settings_field( 'molongui_authorship_name_size', __( 'Name font size', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_font_size' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_name_size', '' ) );
		add_settings_field( 'molongui_authorship_name_color', $this->premium_option( __( 'Name color', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_color_picker' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_name_color', '' ) );
		add_settings_field( 'molongui_authorship_meta_size', __( 'Meta font size', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_font_size' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_meta_size', '' ) );
		add_settings_field( 'molongui_authorship_meta_color', $this->premium_option( __( 'Meta color', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_color_picker' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_meta_color', '' ) );
		add_settings_field( 'molongui_authorship_bio_size', __( 'Bio font size', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_font_size' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_bio_size', '' ) );
		add_settings_field( 'molongui_authorship_bio_color', $this->premium_option( __( 'Bio color', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_color_picker' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_bio_color', '' ) );
		add_settings_field( 'molongui_authorship_bio_align', $this->premium_option( __( 'Bio text align', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_select_text_align' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_bio_align', '' ) );
		add_settings_field( 'molongui_authorship_bio_style', $this->premium_option( __( 'Bio text style', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_select_text_style' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_bio_style', '' ) );
		add_settings_field( 'molongui_authorship_icons_show', __( 'Show social icons', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_yesno' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_icons_show', '' ) );
		add_settings_field( 'molongui_authorship_icons_size', __( 'Social icons size', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_font_size' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_icons_size', '' ) );
		add_settings_field( 'molongui_authorship_icons_color', $this->premium_option( __( 'Social icons color', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_color_picker' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_icons_color', '' ) );
		add_settings_field( 'molongui_authorship_icons_style', $this->premium_option( __( 'Social icons style', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_select_icons' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_icons_style' ) );
		add_settings_field( 'molongui_authorship_bottom_bg', $this->premium_option( __( 'Bottom background color', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_color_picker' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_bottom_bg' ) );
		add_settings_field( 'molongui_authorship_bottom_border', $this->premium_option( __( 'Bottom border', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_select_box_border' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_bottom_border', '' ) );
		add_settings_field( 'molongui_authorship_bottom_border_color', $this->premium_option( __( 'Bottom border color', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ), array( &$this, 'display_tab_config_color_picker' ), $this->tab_config_slug, 'section_config_2', array( 'molongui_authorship_bottom_border_color' ) );
		add_settings_section( 'section_config_3', __( 'Social networks', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_section_3_desc' ), $this->tab_config_slug );
		add_settings_field( 'molongui_authorship_show_social_networks', __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_social_networks' ), $this->tab_config_slug, 'section_config_3', array( 'molongui_authorship_show_social_networks', '' ) );
		add_settings_section( 'section_config_4', __( 'Metadata', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_section_4_desc' ), $this->tab_config_slug );
		add_settings_field( 'molongui_authorship_add_opengraph_meta', __( 'Add Open Graph', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_yesno' ), $this->tab_config_slug, 'section_config_4', array( 'molongui_authorship_add_opengraph_meta', '' ) );
		add_settings_field( 'molongui_authorship_add_google_meta', __( 'Add Google Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_yesno' ), $this->tab_config_slug, 'section_config_4', array( 'molongui_authorship_add_google_meta', '' ) );
		add_settings_field( 'molongui_authorship_add_facebook_meta', __( 'Add Facebook Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_yesno' ), $this->tab_config_slug, 'section_config_4', array( 'molongui_authorship_add_facebook_meta', '' ) );
		add_settings_section( 'section_config_5', __( 'Uninstalling', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_section_5_desc' ), $this->tab_config_slug );
		add_settings_field( 'molongui_authorship_keep_config', __( 'Keep configuration?', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_yesno' ), $this->tab_config_slug, 'section_config_5', array( 'molongui_authorship_keep_config', '' ) );
		add_settings_field( 'molongui_authorship_keep_data', __( 'Keep data?', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), array( &$this, 'display_tab_config_select_yesno' ), $this->tab_config_slug, 'section_config_5', array( 'molongui_authorship_keep_data', '' ) );
	}
	function display_tab_config_section_1_desc()
	{
		_e( 'Set display options that will apply by default to all posts. They can be changed for each post on its edit page.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
		if ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
		{
			echo '<br>';
			printf( __( '%sPremium%s options are only available in the %spremium version%s of this plugin. Default options will be used in the free version.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
					'<span class="molongui-premium-word">',
					'</span>',
			        '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank" >',
			        '</a>' );
		}
	}
	function display_tab_config_select_yesno( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="1"' . selected( $options[$args[0]], '1', false) . '>' . __( 'Yes', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="0"' . selected( $options[$args[0]], '0', false) . '>' . __( 'No',  MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '</select>';

		$html .= '<label for="' . $args[0] . '">&nbsp;' . $args[1] . '</label>';

		echo $html;
	}
	function display_tab_config_select_position( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="above"' . selected( $options[$args[0]], 'above', false) . '>' . __( 'Above content', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="below"' . selected( $options[$args[0]], 'below', false) . '>' . __( 'Below content', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '</select>';

		$html .= '<label for="' . $args[0] . '">&nbsp;' . $args[1] . '</label>';

		echo $html;
	}
	function display_tab_config_select_layout( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<div class="box-layout">';
		$html .= '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="default"' . selected( $options[$args[0]], 'default', false) . '>' . __( 'Default', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="default-rtl"' . selected( $options[$args[0]], 'default-rtl', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Default RTL [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Default RTL', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '<option value="layout-1"' . selected( $options[$args[0]], 'layout-1', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Layout 1 [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Layout 1', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '<option value="layout-1-rtl"' . selected( $options[$args[0]], 'layout-1-rtl', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Layout 1 RTL [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Layout 1 RTL', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '<option value="layout-2"' . selected( $options[$args[0]], 'layout-2', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Layout 2 [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Layout 2', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '<option value="layout-2-rtl"' . selected( $options[$args[0]], 'layout-2-rtl', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Layout 2 RTL [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Layout 2 RTL', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '<option value="layout-3"' . selected( $options[$args[0]], 'layout-3', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Layout 3 [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Layout 3', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '<option value="layout-3-rtl"' . selected( $options[$args[0]], 'layout-3-rtl', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Layout 3 RTL [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Layout 3 RTL', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '</select>';

		if ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
		{
	//		$html .= '<label class="molongui-premium-notice" for="' . $args[0] . '">&nbsp;<span>' . __( "Premium", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</span>' . sprintf( __( "This option is available only in the %spremium version%s of the plugin. Default style will be used in the free version.", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), "<a href=\"" . MOLONGUI_AUTHORSHIP_WEB . "\" target=\"_blank\">", "</a>" ) . '</label>';
		}
		$html .= '</div>';

		echo $html;
	}
	function display_tab_config_section_2_desc()
	{
		_e( 'Customize the author box by choosing the font-sizes, the layout, the social media icons and the colors that you like the most to make it fit the best with your site.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
	}
	function display_tab_config_select_box_shadow( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="left"'  . selected( $options[$args[0]], 'left', false)  . '>' . __( 'Left', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )   . '</option>';
		$html .= '<option value="right"' . selected( $options[$args[0]], 'right', false) . '>' . __( 'Right', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="none"'  . selected( $options[$args[0]], 'none', false)  . '>' . __( 'None', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )  . '</option>';
		$html .= '</select>';

		$html .= '<label for="' . $args[0] . '">&nbsp;' . $args[1] . '</label>';

		echo $html;
	}
	function display_tab_config_select_box_border( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="thin"'  . selected( $options[$args[0]], 'thin', false)  . '>' . __( 'Thin', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )  . '</option>';
		$html .= '<option value="thick"' . selected( $options[$args[0]], 'thick', false) . '>' . __( 'Thick', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="none"'  . selected( $options[$args[0]], 'none', false)  . '>' . __( 'None', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )  . '</option>';
		$html .= '</select>';

		$html .= '<label for="' . $args[0] . '">&nbsp;' . $args[1] . '</label>';

		echo $html;
	}
	function display_tab_config_color_picker( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<input type="text" class="colorpicker" name="' . $this->tab_config_options_key . '[' . $args[0] . ']" value="' . $options[$args[0]] . '">';

		echo $html;
	}
	function display_tab_config_select_image_style( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="rounded"' . selected( $options[$args[0]], 'rounded', false) . '>' . __( 'Rounded', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="circled"' . selected( $options[$args[0]], 'circled', false) . '>' . __( 'Circled', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="none"'    . selected( $options[$args[0]], 'none', false)    . '>' . __( 'None', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )    . '</option>';
		$html .= '</select>';

		$html .= '<label for="' . $args[0] . '">&nbsp;' . $args[1] . '</label>';

		echo $html;
	}
	function display_tab_config_select_font_size( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="biggest"'  . selected( $options[$args[0]], 'biggest',  false) . '>' . __( 'Biggest', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="bigger"'   . selected( $options[$args[0]], 'bigger',   false) . '>' . __( 'Bigger', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="big"'      . selected( $options[$args[0]], 'big',      false) . '>' . __( 'Big', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="normal"'   . selected( $options[$args[0]], 'normal',   false) . '>' . __( 'Normal', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="small"'    . selected( $options[$args[0]], 'small',    false) . '>' . __( 'Small', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="smaller"'  . selected( $options[$args[0]], 'smaller',  false) . '>' . __( 'Smaller', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="smallest"' . selected( $options[$args[0]], 'smallest', false) . '>' . __( 'Smallest', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '</select>';

		$html .= '<label for="' . $args[0] . '">&nbsp;' . $args[1] . '</label>';

		echo $html;
	}
	function display_tab_config_select_text_align( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="justify"' . selected( $options[$args[0]], 'justify', false) . '>' . __( 'Justify', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="left"'    . selected( $options[$args[0]], 'left', false)    . '>' . __( 'Left', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )    . '</option>';
		$html .= '<option value="right"'   . selected( $options[$args[0]], 'right', false)   . '>' . __( 'Right', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )   . '</option>';
		$html .= '</select>';

		$html .= '<label for="' . $args[0] . '">&nbsp;' . $args[1] . '</label>';

		echo $html;
	}
	function display_tab_config_select_text_style( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="normal"' . selected( $options[$args[0]], 'normal', false) . '>' . __( 'Normal', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )      . '</option>';
		$html .= '<option value="italic"' . selected( $options[$args[0]], 'italic', false) . '>' . __( 'Italic', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )      . '</option>';
		$html .= '<option value="bold"'   . selected( $options[$args[0]], 'bold', false)   . '>' . __( 'Bold', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )        . '</option>';
		$html .= '<option value="itbo"'   . selected( $options[$args[0]], 'itbo', false)   . '>' . __( 'Italic-Bold', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '</select>';

		$html .= '<label for="' . $args[0] . '">&nbsp;' . $args[1] . '</label>';

		echo $html;
	}
	function display_tab_config_select_icons( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="default"' . selected( $options[$args[0]], 'default', false) . '>' . __( 'Default', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '<option value="squared"' . selected( $options[$args[0]], 'squared', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Squared [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Squared', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '<option value="circled"' . selected( $options[$args[0]], 'circled', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Circled [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Circled', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '<option value="boxed"'   . selected( $options[$args[0]], 'boxed', false)   . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Boxed [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Boxed', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) )   . '</option>';
		$html .= '<option value="branded"' . selected( $options[$args[0]], 'branded', false) . '>' . ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ? __( 'Branded [PREMIUM]', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Branded', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . '</option>';
		$html .= '</select>';

		$html .= '<div id="social-media-icons-style-preview">';
		$html .= '<img id="social-media-icons-style-img-default" class="' . ( $options[$args[0]] == "default" ? "" : "hidden" ) . '" src="' . $this->path_img . 'social-media-icons-default.png" alt="">';
		$html .= '<img id="social-media-icons-style-img-squared" class="' . ( $options[$args[0]] == "squared" ? "" : "hidden" ) . '" src="' . $this->path_img . 'social-media-icons-squared.png" alt="">';
		$html .= '<img id="social-media-icons-style-img-circled" class="' . ( $options[$args[0]] == "circled" ? "" : "hidden" ) . '" src="' . $this->path_img . 'social-media-icons-circled.png" alt="">';
		$html .= '<img id="social-media-icons-style-img-boxed"   class="' . ( $options[$args[0]] == "boxed" ? "" : "hidden" )   . '" src="' . $this->path_img . 'social-media-icons-boxed.png" alt="">';
		$html .= '<img id="social-media-icons-style-img-branded" class="' . ( $options[$args[0]] == "branded" ? "" : "hidden" ) . '" src="' . $this->path_img . 'social-media-icons-branded.png" alt="">';
		$html .= '</div>';

		$html .= '<div id="social-media-icons-style-notice" class="molongui-premium-notice ' . ( ( $options[$args[0]] != "default" && MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ) ? "" : "hidden" ) . '">' . '<span>' . __( "Premium", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</span>' . sprintf( __( "This option is available only in the %spremium version%s of the plugin. Default style will be used in the free version.", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), "<a href=\"" . MOLONGUI_AUTHORSHIP_WEB . "\" target=\"_blank\">", "</a>" ) . '</div>';

		echo $html;
	}
/*	function display_tab_config_radio_icons( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<div class="social-media-style">';
		$html .= '<input type="radio" id="' . $args[0] . '_default" name="' . $this->tab_config_options_key . '[' . $args[0] . ']" value="default"' . ( isset($options[$args[0]]) && $options[$args[0]] == "default" ? 'checked="checked"' : '') . ' class="social-icons" />';
		$html .= '&nbsp;';
		$html .= '<img src="' . $this->path_img . 'social-media-icons-default.png"></img>';
		$html .= '<br>';
		$html .= '<input type="radio" disabled id="' . $args[0] . '_branded" name="' . $this->tab_config_options_key . '[' . $args[0] . ']" value="branded"' . ( isset($options[$args[0]]) && $options[$args[0]] == "branded" ? 'checked="checked"' : '') . ' class="social-icons" />';
		$html .= '&nbsp;';
		$html .= '<img class="premium" src="' . $this->path_img . 'social-media-icons-branded.png"></img>';
		$html .= '<div class="premium"><span>' . __( 'Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</span>' . sprintf( __( "This option is available only in the %spremium version%s of the plugin.", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), "<a href=\"" . MOLONGUI_AUTHORSHIP_WEB . "\" target=\"_blank\">", "</a>" ) . "</div>";
		$html .= '<br>';
		$html .= '<input type="radio" disabled id="' . $args[0] . '_circled" name="' . $this->tab_config_options_key . '[' . $args[0] . ']" value="circled"' . ( isset($options[$args[0]]) && $options[$args[0]] == "circled" ? 'checked="checked"' : '') . ' class="social-icons" />';
		$html .= '&nbsp;';
		$html .= '<img class="premium" src="' . $this->path_img . 'social-media-icons-circled.png"></img>';
		$html .= '<div class="premium"><span>' . __( 'Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</span>' . sprintf( __( "This option is available only in the %spremium version%s of the plugin.", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), "<a href=\"" . MOLONGUI_AUTHORSHIP_WEB . "\" target=\"_blank\">", "</a>" ) . "</div>";
		$html .= '<br>';
		$html .= '<input type="radio" disabled id="' . $args[0] . '_squared" name="' . $this->tab_config_options_key . '[' . $args[0] . ']" value="squared"' . ( isset($options[$args[0]]) && $options[$args[0]] == "squared" ? 'checked="checked"' : '') . ' class="social-icons" />';
		$html .= '&nbsp;';
		$html .= '<img class="premium" src="' . $this->path_img . 'social-media-icons-squared.png"></img>';
		$html .= '<div class="premium"><span>' . __( 'Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</span>' . sprintf( __( "This option is available only in the %spremium version%s of the plugin.", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), "<a href=\"" . MOLONGUI_AUTHORSHIP_WEB . "\" target=\"_blank\">", "</a>" ) . "</div>";
		$html .= '</div>';

		echo $html;
	}*/
	function display_tab_config_select_scheme( $args )
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$html  = '<div class="box-color-scheme">';
		$html .= '<select id="' . $args[0] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . ']">';
		$html .= '<option value="default"' . selected( $options[$args[0]], 'default', false) . '>' . __( 'Default', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</option>';
		$html .= '</select>';

		if ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
		{
			$html .= '<label class="molongui-premium-notice" for="' . $args[0] . '">&nbsp;<span>' . __( "Premium", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</span>' . sprintf( __( "This option is available only in the %spremium version%s of the plugin. Default style will be used in the free version.", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), "<a href=\"" . MOLONGUI_AUTHORSHIP_WEB . "\" target=\"_blank\">", "</a>" ) . '</label>';
		}
		$html .= '</div>';

		echo $html;
	}
	function display_tab_config_section_3_desc()
	{
		_e( 'There are many social networking websites out there and this plugin allows you to link to the most popular. Not to glut the edit page, choose the ones you want to be able to configure.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
	}
	function display_tab_config_social_networks($args)
	{
		// Get already stored options
		$options = get_option( $this->tab_config_options_key );

		// DEBUG: Uncomment on development
		// print_r( $options );

		$sns = array(
			array(
				'name' => 'Twitter',
				'slug' => 'tw',
			),
			array(
				'name' => 'Facebook',
				'slug' => 'fb',
			),
			array(
				'name' => 'Linkedin',
				'slug' => 'in',
			),
			array(
				'name' => 'Google+',
				'slug' => 'gp',
			),
			array(
				'name' => 'Youtube',
				'slug' => 'yt',
			),
			array(
				'name' => 'Pinterest',
				'slug' => 'pi',
			),
			array(
				'name' => 'Tumblr',
				'slug' => 'tu',
			),
			array(
				'name' => 'Instagram',
				'slug' => 'ig',
			),
			array(
				'name' => 'Xing',
				'slug' => 'xi',
			),
			array(
				'name' => 'Renren',
				'slug' => 're',
			),
			array(
				'name' => 'Vk',
				'slug' => 'vk',
			),
			array(
				'name' => 'Flickr',
				'slug' => 'fl',
			),
			array(
				'name' => 'Vine',
				'slug' => 'vi',
			),
			array(
				'name' => 'Meetup',
				'slug' => 'me',
			),
			array(
				'name' => 'Weibo',
				'slug' => 'we',
			),
			array(
				'name' => 'Deviantart',
				'slug' => 'de',
			),
			array(
				'name' => 'Stumbleupon',
				'slug' => 'st',
			),
			array(
				'name' => 'MySpace',
				'slug' => 'my',
			),
			array(
				'name' => 'Yelp',
				'slug' => 'ye',
			),
			array(
				'name' => 'Mixi',
				'slug' => 'mi',
			),
			array(
				'name' => 'SoundCloud',
				'slug' => 'so',
			),
			array(
				'name' => 'Last fm',
				'slug' => 'la',
			),
			array(
				'name' => 'Foursquare',
				'slug' => 'fo',
			),
			array(
				'name' => 'Spotify',
				'slug' => 'sp',
			),
			array(
				'name' => 'Vimeo',
				'slug' => 'vm',
			),
		);

		$html = '<ul>';

		foreach ( $sns as $sn )
		{
			$html .= '<li style="float:left; width:150px;">';
			$html .= '<input type="checkbox" id="' . $args[0] . '_' . $sn['slug'] . '" name="' . $this->tab_config_options_key . '[' . $args[0] . '_' . $sn['slug'] . ']" value="1"' . ( ( isset($options[$args[0].'_'.$sn['slug']]) && $options[$args[0].'_'.$sn['slug']] == 1 ) ? 'checked="checked"' : '')  . '/>';
			$html .= '<label for="' . $args[0] . '_' . $sn['slug'] . '">&nbsp;' . __( $sn['name'], MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
			$html .= '</li>';
		}

		$html .= '</ul>';

		echo $html;
	}
	function display_tab_config_section_4_desc()
	{
		_e( 'Choose whether to add profile metadata for author details (useful for rich snippets).', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
	}
	function validate_tab_config_settings( $input )
	{
		// DEBUG: For testing activation status_extra data
		// echo "<pre>"; print_r( array( $_REQUEST, $input ) ); echo "</pre>"; die;

		if( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
		{
			$input['molongui_authorship_layout']              = 'default';
			$input['molongui_authorship_name_color']          = 'inherit';
			$input['molongui_authorship_meta_color']          = 'inherit';
			$input['molongui_authorship_bio_color']           = 'inherit';
			$input['molongui_authorship_bio_align']           = 'justify';
			$input['molongui_authorship_bio_style']           = 'normal';
			$input['molongui_authorship_icons_style']         = 'default';
			$input['molongui_authorship_icons_color']         = 'inherit';
			$input['molongui_authorship_bottom_bg']           = 'transparent';
			$input['molongui_authorship_bottom_border']       = 'thin';
			$input['molongui_authorship_bottom_border_color'] = '#B6B6B6';
		}

		return $input;
	}
	function display_tab_config_section_5_desc()
	{
		_e( 'Choose whether to keep plugin configuration and plugin related data upon plugin uninstalling.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
	}


	/**
	 * Display "Premium" superscript.
	 *
	 * This function adds a superscript to mark a Premium option on free plugins.
	 *
	 * @access   public
	 * @since    1.2.5
	 * @version  1.2.5
	 */
	function premium_option( $label )
	{
		if ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium')
		{
			$label .= ' <sup><span class="molongui-premium-superscript"><a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank" >' . __( "Premium", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</a></span></sup>';
			return $label;
		}
		else
		{
			return $label;
		}
	}


}