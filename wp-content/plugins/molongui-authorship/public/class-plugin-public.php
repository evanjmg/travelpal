<?php

namespace Molongui\Authorship;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Amitzy
 * @category   Molongui
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public
 * @since      1.0.0
 * @version    1.2.8
 */

class Plugin_Public
{
	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name     The ID of this plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access  private
	 * @var     string    $version         The current version of this plugin.
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param   string    $plugin_name     The name of the plugin.
	 * @param   string    $version         The version of this plugin.
	 * @since   1.0.0
	 * @version 1.2.3
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if( MOLONGUI_AUTHORSHIP_LICENSE == 'premium' )
		{
			// Load premium dependencies
			$this->load_premium_dependencies();
		}
	}


	/**
	 * Load the required premium dependencies for this class.
	 *
	 * Load other classes definitions used by this class.
	 *
	 * @access   private
	 * @since    1.2.2
	 * @version  1.2.2
	 */
	private function load_premium_dependencies()
	{
		/**
		 * The class responsible for defining all actions related with the license handling.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/shortcodes.php' );
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 1.2.0
	 */
	public function enqueue_styles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
		{
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/molongui-authorship.1d86.min.css', array(), $this->version, 'all' );
		}
		else
		{
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'premium/public/css/molongui-authorship-premium.e9e6.min.css', array(), $this->version, 'all' );
		}
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 1.2.0
	 */
	public function enqueue_scripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

/*		if( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
		{
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/molongui-authorship.min.js', array( 'jquery' ), $this->version, false );
		}
		else
		{
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'premium/public/js/molongui-authorship-premium.xxxx.min.js', array( 'jquery' ), $this->version, false );
		}
*/
	}


	/**
	 * Display guest author if any.
	 *
	 * Hook into the_author() function to override author name
	 * if there is a guest author set.
	 *
	 * @access  public
	 * @param   object      $author         The post author.
	 * @return  string
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function filter_author_name( $author )
	{
		global $post;

		if ( $guest = get_post_meta( $post->ID, 'molongui_guest_author_id', true ) ) $author = get_the_title( $guest );

		return $author;
	}


	/**
	 * Modify guest author link.
	 *
	 * Hook into the_author_link() function to override author link
	 * if there is a guest author set.
	 *
	 * @access  public
	 * @param   object      $link       The author link.
	 * @return  string
	 * @since   1.0.0
	 * @version 1.1.4
	 */
	public function filter_author_link( $link  )
	{
		global $post;

		if ( $guest = get_post_meta( $post->ID, 'molongui_guest_author_id', true ) )
		{
			if ( $guest_link = get_post_meta( $guest, 'molongui_guest_author_link', true ) ) return ( $guest_link );
			else return '#';
		}
		else return $link;
	}


	/**
	 * Render the author box.
	 *
	 * Add the author box on single post, author and archive pages.
	 *
	 * @access  public
	 * @param   string      $content        The post content.
	 * @return  string      $content        The modified post content.
	 * @since   1.0.0
	 * @version 1.2.8
	 */
	public function render_author_box( $content )
	{
		if ( is_single() or is_author() or is_archive() or is_page() )
		{
			global $post;

			// Get plugin options
			$options = get_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY );

			// Get post authorship box display configuration
			$author_box_display = get_post_meta( $post->ID, 'molongui_author_box_display', true );

			// If no post configuration and default option is to not display the authorship box, exit and do not display anything
			if ( empty( $author_box_display ) && $options['molongui_authorship_display'] == 0 ) return $content;

			// If post configured to not display the authorship box, exit and do not display it
			if ( $author_box_display == 'hide' ) return $content;

			// Get author data
			if ( $author_id = get_post_meta( $post->ID, 'molongui_guest_author_id', true ) )
			{
				// Guest author
				$author              = get_post( $author_id );
				$author_name         = $author->post_title;
				$author_mail         = get_post_meta( $author_id, 'molongui_guest_author_mail', true );
				$author_link         = get_post_meta( $author_id, 'molongui_guest_author_link', true );
				$author_img          = ( has_post_thumbnail( $author_id ) ? get_the_post_thumbnail( $author_id, "thumbnail", array( 'class' => 'mabt-radius-'.$options['molongui_authorship_img_style'] ) ) : '' );
				$author_job          = get_post_meta( $author_id, 'molongui_guest_author_job', true );
				$author_company      = get_post_meta( $author_id, 'molongui_guest_author_company', true );
				$author_company_link = get_post_meta( $author_id, 'molongui_guest_author_company_link', true );
				$author_bio          = $author->post_content;
				$author_tw           = get_post_meta( $author_id, 'molongui_guest_author_twitter', true );
				$author_fb           = get_post_meta( $author_id, 'molongui_guest_author_facebook', true );
				$author_in           = get_post_meta( $author_id, 'molongui_guest_author_linkedin', true );
				$author_gp           = get_post_meta( $author_id, 'molongui_guest_author_gplus', true );
				$author_yt           = get_post_meta( $author_id, 'molongui_guest_author_youtube', true );
				$author_pi           = get_post_meta( $author_id, 'molongui_guest_author_pinterest', true );
				$author_tu           = get_post_meta( $author_id, 'molongui_guest_author_tumblr', true );
				$author_ig           = get_post_meta( $author_id, 'molongui_guest_author_instagram', true );
				$author_xi           = get_post_meta( $author_id, 'molongui_guest_author_xing', true );
				$author_re           = get_post_meta( $author_id, 'molongui_guest_author_renren', true );
				$author_vk           = get_post_meta( $author_id, 'molongui_guest_author_vk', true );
				$author_fl           = get_post_meta( $author_id, 'molongui_guest_author_flickr', true );
				$author_vi           = get_post_meta( $author_id, 'molongui_guest_author_vine', true );
				$author_me           = get_post_meta( $author_id, 'molongui_guest_author_meetup', true );
				$author_we           = get_post_meta( $author_id, 'molongui_guest_author_weibo', true );
				$author_de           = get_post_meta( $author_id, 'molongui_guest_author_deviantart', true );
				$author_st           = get_post_meta( $author_id, 'molongui_guest_author_stubmleupon', true );
				$author_my           = get_post_meta( $author_id, 'molongui_guest_author_myspace', true );
				$author_ye           = get_post_meta( $author_id, 'molongui_guest_author_yelp', true );
				$author_mi           = get_post_meta( $author_id, 'molongui_guest_author_mixi', true );
				$author_so           = get_post_meta( $author_id, 'molongui_guest_author_soundcloud', true );
				$author_la           = get_post_meta( $author_id, 'molongui_guest_author_lastfm', true );
				$author_fo           = get_post_meta( $author_id, 'molongui_guest_author_foursquare', true );
				$author_sp           = get_post_meta( $author_id, 'molongui_guest_author_spotify', true );
				$author_vm           = get_post_meta( $author_id, 'molongui_guest_author_vimeo', true );
			}
			else
			{
				// Registered author
				$author_id           = $post->post_author;
				$author              = get_user_by( 'id', $author_id );
				$author_name         = $author->display_name;
				$author_mail         = $author->user_email;
				$author_link         = ( get_the_author_meta( 'molongui_author_link', $author_id ) ? get_the_author_meta( 'molongui_author_link', $author_id ) : get_author_posts_url( $author_id ) );
				$author_img          = ( get_the_author_meta( 'molongui_author_image_id', $author_id ) ? wp_get_attachment_image( get_the_author_meta( 'molongui_author_image_id', $author_id ), "thumbnail", false, array( 'class' => 'mabt-radius-'.$options['molongui_authorship_img_style'] ) ) : "" );
				$author_job          = get_the_author_meta( 'molongui_author_job', $author_id );
				$author_company      = get_the_author_meta( 'molongui_author_company', $author_id );
				$author_company_link = get_the_author_meta( 'molongui_author_company_link', $author_id );
				$author_bio          = get_the_author_meta( 'molongui_author_bio', $author_id );
				$author_tw           = get_the_author_meta( 'molongui_author_twitter', $author_id );
				$author_fb           = get_the_author_meta( 'molongui_author_facebook', $author_id );
				$author_in           = get_the_author_meta( 'molongui_author_linkedin', $author_id );
				$author_gp           = get_the_author_meta( 'molongui_author_gplus', $author_id );
				$author_yt           = get_the_author_meta( 'molongui_author_youtube', $author_id );
				$author_pi           = get_the_author_meta( 'molongui_author_pinterest', $author_id );
				$author_tu           = get_the_author_meta( 'molongui_author_tumblr', $author_id );
				$author_ig           = get_the_author_meta( 'molongui_author_instagram', $author_id );
				$author_xi           = get_the_author_meta( 'molongui_author_xing', $author_id );
				$author_re           = get_the_author_meta( 'molongui_author_renren', $author_id );
				$author_vk           = get_the_author_meta( 'molongui_author_vk', $author_id );
				$author_fl           = get_the_author_meta( 'molongui_author_flickr', $author_id );
				$author_vi           = get_the_author_meta( 'molongui_author_vine', $author_id );
				$author_me           = get_the_author_meta( 'molongui_author_meetup', $author_id );
				$author_we           = get_the_author_meta( 'molongui_author_weibo', $author_id );
				$author_de           = get_the_author_meta( 'molongui_author_deviantart', $author_id );
				$author_st           = get_the_author_meta( 'molongui_author_stubmleupon', $author_id );
				$author_my           = get_the_author_meta( 'molongui_author_myspace', $author_id );
				$author_ye           = get_the_author_meta( 'molongui_author_yelp', $author_id );
				$author_mi           = get_the_author_meta( 'molongui_author_mixi', $author_id );
				$author_so           = get_the_author_meta( 'molongui_author_soundcloud', $author_id );
				$author_la           = get_the_author_meta( 'molongui_author_lastfm', $author_id );
				$author_fo           = get_the_author_meta( 'molongui_author_foursquare', $author_id );
				$author_sp           = get_the_author_meta( 'molongui_author_spotify', $author_id );
				$author_vm           = get_the_author_meta( 'molongui_author_vimeo', $author_id );
			}

			// If there is not significant info to show, do not display the author box
			if ( !$author_id or ( !$author_bio and $options['molongui_authorship_hide_if_no_bio'] ) ) return $content;

			// If there is no image, try to load the associated Gravatar (https://codex.wordpress.org/Function_Reference/get_avatar)
			if ( empty( $author_img ) && !empty( $author_mail ) )
			{
				$author_img = get_avatar( $author_mail, '150', 'blank', false, array( 'class' => 'mabt-radius-'.$options['molongui_authorship_img_style'] ) );
			}

			// The markup
			ob_start();
			if ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' or
			     !isset( $options['molongui_authorship_layout'] ) or
			     empty( $options['molongui_authorship_layout'] ) or
			     $options['molongui_authorship_layout'] == 'default' )
			{
				include( plugin_dir_path( __FILE__ ) . 'views/html-default-author-box.php' );
			}
			else
			{
				include( plugin_dir_path( __FILE__ ) . '../premium/public/views/html-' . $options['molongui_authorship_layout'] . '-author-box.php' );
			}
			$author_box = ob_get_clean();

			// Add "Author Box" to the post content
			switch ( $options['molongui_authorship_position'] )
			{
				case "above":
					$content = $author_box . $content;
				break;

				case "below":
				case "default":
					$content .= $author_box;
				break;
			}
		}

		return $content;
	}


	/**
	 * Show author metadata into the html head.
	 *
	 * Adds authorship meta to the head of the HTML document.
	 *
	 * @access  public
	 * @return  string      $meta        The meta tags to include into the html head.
	 * @since   1.0.0
	 * @version 1.2.8
	 */
	public function add_author_meta()
	{
		global $post;

		// Get author data
		if ( $author_id = get_post_meta( $post->ID, 'molongui_guest_author_id', true ) )
		{
			// Guest author
			$author         = get_post( $author_id );
			$author_name    = $author->post_title;
			$author_link    = get_post_meta( $author_id, 'molongui_guest_author_link', true );
			$author_img     = ( has_post_thumbnail( $author_id ) ? get_the_post_thumbnail( $author_id, "thumbnail" ) : '' );
			$author_fb      = get_post_meta( $author_id, 'molongui_guest_author_facebook', true );
			$author_gp      = get_post_meta( $author_id, 'molongui_guest_author_gplus', true );
		}
		else
		{
			// Registered author
			$author_id      = $post->post_author;
			$author         = get_user_by( 'id', $author_id );
			$author_name    = $author->user_nicename;
			$author_link    = ( get_the_author_meta( 'molongui_author_link' ) ? get_the_author_meta( 'molongui_author_link' ) : get_the_author_link() );
			$author_fb      = get_the_author_meta( 'molongui_author_facebook' );
			$author_gp      = get_the_author_meta( 'molongui_author_gplus' );
		}

		if ( !isset( $author ) ) return;

		// Get plugin options
		$options = get_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY );

		if ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' ) $meta = "\n<!-- Molongui Authorship " . $this->version . ", visit: http://wordpress.org/plugins/molongui-authorship/ -->\n";
		else $meta = "\n<!-- Molongui Authorship " . $this->version . ", visit: " . MOLONGUI_AUTHORSHIP_WEB . " -->\n";

		// Show the OpenGraph metadata on "Author archive" page if enabled
		if ( $options['molongui_authorship_add_opengraph_meta'] == 1 && is_author() )
		{
			$meta .= $this->add_opengraph_meta( $author_name, $author_link );
		}

		// Show Google author meta
		if ( $options['molongui_authorship_add_google_meta'] == 1 && isset( $author_gp ) && $author_gp <> '' ) $meta .= $this->add_google_author_meta( $author_gp );

		// Show Facebook author meta
		if ( $options['molongui_authorship_add_facebook_meta'] == 1 && isset( $author_fb ) && $author_fb <> '' ) $meta .= $this->add_facebook_author_meta( $author_fb );

		$meta .= "<!-- /Molongui Authorship -->\n\n";

		echo $meta;
	}


	/**
	 * Get the Open Graph for the current (guest) author.
	 *
	 * @access  public
	 * @return  string      $meta        The meta tags to include into the html head.
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function add_opengraph_meta( $author_name, $author_link )
	{
		$og = '';
		$og .= sprintf( '<meta property="og:url" content="%s" />', $author_link ) . "\n";
		$og .= sprintf( '<meta property="og:type" content="%s" />', 'profile' ) . "\n";
		$og .= sprintf( '<meta property="profile:username" content="%s" />', $author_name ) . "\n";

		return $og;
	}

	/**
	 * Get the Google author Meta.
	 *
	 * @access  public
	 * @return  string      $meta        The meta tags to include into the html head.
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function add_google_author_meta( $author_gp )
	{
		return '<link rel="author" href="' . ( (strpos( $author_gp, 'http' ) === false ) ? 'https://plus.google.com/' : '' ) . $author_gp . '" />' . "\n";
	}


	/**
	 * Get the Facebook author Meta.
	 *
	 * @access  public
	 * @return  string      $meta        The meta tags to include into the html head.
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function add_facebook_author_meta( $author_fb )
	{
		return '<meta property="article:author" content="' . ( (strpos( $author_fb, 'http' ) === false ) ? 'https://www.facebook.com/' : '' ) . $author_fb . '" />' . "\n";
	}

}