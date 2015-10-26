<?php

namespace Molongui\Authorship\Includes;

use WP_Query; /* https://roots.io/upping-php-requirements-in-your-wordpress-themes-and-plugins/ */

/**
 * The Guest Author Class.
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin/classes
 * @since      1.0.0
 * @version    1.2.9
 */
class Guest_Author
{
	/**
	 * Hook into the appropriate actions when the class is constructed.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function __construct()
	{
		add_filter( 'manage_molongui_guestauthor_posts_columns', array( $this, 'add_list_columns' ) );
		add_action( 'manage_molongui_guestauthor_posts_custom_column', array( $this, 'fill_list_columns' ), 5, 2 );
	}


	/**
	 * Add columns to the list shown on the Manage {molongui_guestauthor} Posts screen.
	 *
	 * @see      https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns
	 *
	 * @param    array      $columns    An array of column name => label.
	 * @return   array      $columns    Modified array of column name => label.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.1.4
	 */
	public function add_list_columns( $columns )
	{
		// Unset some default columns to display them in a different position
		unset( $columns['title'] );
		unset( $columns['date'] );

		return array_merge($columns,
		                   array('guestAuthorPic'  => __( 'Photo', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'title'		   => __( 'Name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'guestAuthorJob'  => __( 'Job position', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'guestAuthorCia'  => __( 'Company', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'guestAuthorMail' => __( 'e-mail', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'guestAuthorUrl'  => __( 'Url', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'date'            => __( 'Date' ),
		                   )
		);
	}


	/**
	 * Fill out custom author column shown on the Manage Posts/Pages screen.
	 *
	 * @see      https://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column
	 *
	 * @param    array      $column     An array of column name => label.
	 * @param    int        $ID         Post ID.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.1.4
	 */
	public function fill_list_columns( $column, $ID )
	{
		if ( $column == 'guestAuthorPic' )  echo get_the_post_thumbnail( $ID, array( 60, 60) );
		if ( $column == 'guestAuthorJob' )  echo get_post_meta( $ID, 'molongui_guest_author_job', true );
		if ( $column == 'guestAuthorCia' )  echo get_post_meta( $ID, 'molongui_guest_author_company', true );
		if ( $column == 'guestAuthorMail' ) echo get_post_meta( $ID, 'molongui_guest_author_mail', true );
		if ( $column == 'guestAuthorUrl' )  echo '<a href="' . get_post_meta( $ID, 'molongui_guest_author_link', true ) . '">' . get_post_meta( $ID, 'molongui_guest_author_link', true ) . '</a>';
	}


	/**
	 * Register "Guest Author" custom post-type
	 *
	 * This functions registers a new post-type called "molongui_guestauthor".
	 * This post-type holds guest authors specific data.
	 *
	 * @see      https://codex.wordpress.org/Function_Reference/register_post_type
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function register_guest_author_posttype()
	{
		$labels = array(
			'name'					=> _x( 'Guest authors', 'post type general name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'singular_name'			=> _x( 'Guest author', 'post type singular name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'menu_name'				=> __( 'Guest authors', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'name_admin_bar'		=> __( 'Guest authors', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'all_items'				=> __( 'All Guest authors', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'add_new'				=> _x( 'Add New', 'molongui_guestauthor', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'add_new_item'			=> __( 'Add New Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'edit_item'				=> __( 'Edit Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'new_item'				=> __( 'New Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'view_item'				=> __( 'View Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'search_items'			=> __( 'Search Guest authors', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'not_found'				=> __( 'No guest authors found', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'not_found_in_trash'	=> __( 'No guest authors found in the Trash', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'parent_item_colon'		=> '',
		);

		$args = array(
			'labels'				=> $labels,
			'description'			=> 'Holds our guest author and guest authors specific data',
			'public'				=> true,
			'exclude_from_search'	=> false,
			'publicly_queryable'	=> false,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_in_menu'			=> true,
			'show_in_admin_bar '	=> true,
			'menu_position'			=> 5,	// 5 = Below posts
			'menu_icon'				=> 'dashicons-id',
			'supports'		 		=> array( 'title', 'editor', 'thumbnail' ),
			'register_meta_box_cb'	=> '',
			'has_archive'			=> true,
			'rewrite'				=> array('slug' => 'guest-author'),
		);

		register_post_type( 'molongui_guestauthor', $args );

		// DEBUG: Uncomment below lines to debug on deployment
		// print_r( register_post_type( 'molongui_guest_author', $args ) ); exit;
	}


	/**
	 * Change title placeholder for "guest author" custom post-type.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function change_default_title( $title )
	{
		global $current_screen;

		if ( 'molongui_guestauthor' == $current_screen->post_type ) $title = __( 'Enter guest author name here', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );

		return $title;
	}


	/**
	 * Remove media buttons from "guest author" custom post-type.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_media_buttons()
	{
		global $current_screen;

		if( 'molongui_guestauthor' == $current_screen->post_type ) remove_action( 'media_buttons', 'media_buttons' );
	}


	/**
	 * Modify author column shown on the Manage Posts/Pages screen.
	 *
	 * @see      https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_posts_columns
	 * @see      https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_pages_columns
	 *
	 * @param    array      $column     An array of column name => label.
	 * @return   array      $column     Modified array of column name => label.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function change_author_column( $columns )
	{
		global $post;

		$post_type  = get_post_type( $post );
		$post_types = array( 'post', 'page' );

		// Modify author column only at Manage Posts screen
		if ( in_array( $post_type, $post_types ))
		{
			// Remove default author column from the columns list
			unset( $columns['author'] );

			// Add new author column in the same place where default was (after title)
			$new_columns = array();
			foreach ( $columns as $key => $column )
			{
				$new_columns[$key] = $column;
				if ( $key == 'title' ) $new_columns['realAuthor'] = __( 'Author' );
			}

			return $new_columns;
		}
		else
		{
			return $columns;
		}
	}


	/**
	 * Fill out custom author column shown on the Manage Posts/Pages screen.
	 *
	 * @see      https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
	 *
	 * @param    string     $column     Column name.
	 * @param    int        $ID         Post ID.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.5
	 */
	function fill_author_column( $column, $ID )
	{
		if ( $column == 'realAuthor' )
		{
			// Get Guest Author ID if one is set
			$guest = get_post_meta( $ID, '_molongui_guest_author', true );
			if ( isset( $guest ) && $guest == 1 )
			{
				$author_id = get_post_meta( $ID, 'molongui_guest_author_id', true );
				echo '<a href="' . admin_url() . 'post.php?post=' . $author_id . '&action=edit">' . get_the_title( $author_id ) . '</a>';
			}
			// If it is not guest author, get the registered author
			else
			{
				$post = get_post( $ID );
				echo '<a href="' . admin_url() . 'user-edit.php?user_id=' . $post->post_author . '">' . get_the_author() . '</a>';
			}
		}
	}


	/**
	 * Remove default "Author" meta box.
	 *
	 * Removes the "Author" meta box from post's and page's edit page.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.2
	 */
	public function remove_author_metabox()
	{
		remove_meta_box('authordiv', 'post', 'normal');
		remove_meta_box('authordiv', 'page', 'normal');
	}


	/**
	 * Adds the meta box container.
	 *
	 * @see      https://codex.wordpress.org/Function_Reference/add_meta_box
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.4
	 */
	public function add_meta_boxes( $post_type )
	{
		// Limit meta box to certain post types
		$post_types = array('post', 'page');

		// Add author meta box to "post" and "page" post-types
		if ( in_array( $post_type, $post_types ))
		{
			add_meta_box(
				'authorboxdiv'
				,__( 'Author' )
				,array( $this, 'render_author_meta_box_content' )
				,$post_type
				,'side'
				,'high'
			);

			// Add selector to choose whether to show authorship box or not
			add_meta_box(
				'showboxdiv'
				,__( 'Authorship box' )
				,array( $this, 'render_showbox_meta_box_content' )
				,$post_type
				,'side'
				,'high'
			);
		}

		// Add custom meta boxes to "Guest Author" custom post-type
		if ( in_array( $post_type, array('molongui_guestauthor') ))
		{
			// Add job profile meta box
			add_meta_box(
				'authorjobdiv'
				,__( 'Job profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )
				,array( $this, 'render_job_meta_box_content' )
				,$post_type
				,'side'
				,'core'
			);

			// Add social media meta box
			add_meta_box(
				'authorsocialdiv'
				,__( 'Social Media', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )
				,array( $this, 'render_social_meta_box_content' )
				,$post_type
				,'normal'
				,'high'
			);
		}

	}


	/**
	 * Render Author Meta Box content.
	 *
	 * @param    WP_Post    $post  The post object.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function render_author_meta_box_content( $post )
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'molongui_authorship', 'molongui_authorship_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$guest = get_post_meta( $post->ID, '_molongui_guest_author', true );

		// Add some js
		?><script type="text/javascript">
			function showAuthorContent()
			{
				// Get DOM elements
				var radios     = document.getElementsByName("guest-author");
				var registered = document.getElementById("registered_author_data");
				var guest      = document.getElementById("guest_author_data");

				// Show content based on selection
				if ( radios[0].checked )
				{
					registered.style.display = 'block';
					registered.className     = "";
					guest.style.display      = 'none';
				}
				if ( radios[1].checked )
				{
					registered.style.display = 'none';
					guest.style.display      = 'block';
					guest.className          = "";
				}
			}
		</script><?php

		// Display the form, loading stored values if available
		?>
		<div class="molongui-metabox">
			<div class="molongui-field">
				<p class="label"><?php _e( 'As author, you can choose between a registered user and a guest author:', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></p>
			</div>
			<div class="molongui-field">
				<label for="registered-author">
					<input type="radio" name="guest-author" id="registered-author" value="0" onclick="showAuthorContent()" <?php if ( $guest != 1 ) echo 'checked'; ?>>
					<span class="registered-author"><?php _e( 'Registered author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span>
				</label>
			</div>
			<div class="molongui-field">
				<label for="guest-author">
					<input type="radio" name="guest-author" id="guest-author" value="1" onclick="showAuthorContent()" <?php if ( $guest == 1 ) echo 'checked'; ?>>
					<span class="guest-author"><?php _e( 'Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span>
				</label>
			</div>
			<div class="molongui-field">
				<div id="registered_author_data" class="<?php echo ( $guest != 0 ? 'hidden' : '' ); ?>">
					<?php
					echo '<label class="screen-reader-text" for="post_author_override">' . __('Author') . '</label>';
					wp_dropdown_users( array(
						                   'who' => 'authors',
						                   'name' => 'post_author_override',
						                   'selected' => empty($post->ID) ? $user_ID : $post->post_author,
						                   'include_selected' => true
					                   ) );
					?>
				</div>
				<div id="guest_author_data" class="<?php echo ( $guest != 1 ? 'hidden' : '' ); ?>">
					<?php echo $this->get_guest_authors(); ?>
				</div>
			</div>
		</div>
		<?php
	}


	/**
	 * Render selector to choose to show the authorship box or not.
	 *
	 * @param    WP_Post    $post  The post object.
	 * @access   public
	 * @since    1.1.0
	 * @version  1.2.0
	 */
	public function render_showbox_meta_box_content( $post )
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'molongui_authorship', 'molongui_authorship_nonce' );

		// Get plugin options
		$options = get_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY );

		// Use get_post_meta to retrieve an existing value from the database.
		$author_box_display = get_post_meta( $post->ID, 'molongui_author_box_display', true );

		// If no existing value, set default as global configuration defines
		if ( empty( $author_box_display ) && $options['molongui_authorship_display'] == 1 ) $author_box_display = 'show';
		if ( empty( $author_box_display ) && $options['molongui_authorship_display'] == 0 ) $author_box_display = 'hide';

			// Display the form, loading stored values if available
		?>
		<div class="molongui-metabox">
			<div class="molongui-field">
				<p class="label"><?php _e( 'Show the authorship box for this post?', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></p>
			</div>
			<select name="molongui_author_box_display">
				<option value="show" <?php echo ( $author_box_display == 'show' ? 'selected' : '' ); ?>><?php _e( 'Show', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></option>
				<option value="hide" <?php echo ( $author_box_display == 'hide' ? 'selected' : '' ); ?>><?php _e( 'Hide', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></option>
			</select>
		</div>
		<?php
	}


	/**
	 * Render job profile meta box content.
	 *
	 * @param    WP_Post    $post  The post object.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.6
	 */
	public function render_job_meta_box_content( $post )
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'molongui_authorship', 'molongui_authorship_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$guest_author_mail         = get_post_meta( $post->ID, 'molongui_guest_author_mail', true );
		$guest_author_link         = get_post_meta( $post->ID, 'molongui_guest_author_link', true );
		$guest_author_job          = get_post_meta( $post->ID, 'molongui_guest_author_job', true );
		$guest_author_company      = get_post_meta( $post->ID, 'molongui_guest_author_company', true );
		$guest_author_company_link = get_post_meta( $post->ID, 'molongui_guest_author_company_link', true );

		// Display the form, loading stored values if available
		echo '<div class="molongui-metabox">';
			echo '<div class="molongui-field">';
				echo '<p class="label"><label class="" for="molongui_guest_author_mail">' . __( 'Email address', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
				echo '<div class="input-wrap"><input type="text" placeholder="' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_mail" name="molongui_guest_author_mail" value="' . ( $guest_author_mail ? $guest_author_mail : '' ) . '" class="text"></div>';
			echo '</div>';
			echo '<div class="molongui-field">';
				echo '<p class="label"><label class="" for="molongui_guest_author_link">' . __( 'Blog link', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( 'Insert here the URL of the page you want the user name to link to.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
				echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'http://www.example.com/', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_link" name="molongui_guest_author_link" value="' . ( $guest_author_link ? $guest_author_link : '' ) . '" class="text"></div>';
			echo '</div>';
			echo '<div class="molongui-field">';
				echo '<p class="label"><label class="" for="molongui_guest_author_job">' . __( 'Job position', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
				echo '<div class="input-wrap"><input type="text" placeholder="' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_job" name="molongui_guest_author_job" value="' . ( $guest_author_job ? $guest_author_job : '' ) . '" class="text"></div>';
			echo '</div>';
			echo '<div class="molongui-field">';
				echo '<p class="label"><label class="" for="molongui_guest_author_company">' . __( 'Company', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
				echo '<div class="input-wrap"><input type="text" placeholder="' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_company" name="molongui_guest_author_company" value="' . ( $guest_author_company ? $guest_author_company : '' ) . '" class="text"></div>';
			echo '</div>';
			echo '<div class="molongui-field">';
				echo '<p class="label"><label class="" for="molongui_guest_author_company_link">' . __( 'Company link', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( 'Insert here the URL of the page you want the company name to link to.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
				echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'http://www.example.com/', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_company_link" name="molongui_guest_author_company_link" value="' . ( $guest_author_company_link ? $guest_author_company_link : '' ) . '" class="text"></div>';
			echo '</div>';
		echo '</div>';
	}


	/**
	 * Render social media meta box content.
	 *
	 * @param    WP_Post    $post  The post object.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.9
	 */
	public function render_social_meta_box_content( $post )
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'molongui_authorship', 'molongui_authorship_nonce' );

		// Get plugin config settings
		$options = get_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY );

		// Use get_post_meta to retrieve an existing value from the database.
		$guest_author_twitter     = get_post_meta( $post->ID, 'molongui_guest_author_twitter', true );
		$guest_author_facebook    = get_post_meta( $post->ID, 'molongui_guest_author_facebook', true );
		$guest_author_linkedin    = get_post_meta( $post->ID, 'molongui_guest_author_linkedin', true );
		$guest_author_googleplus  = get_post_meta( $post->ID, 'molongui_guest_author_gplus', true );
		$guest_author_youtube     = get_post_meta( $post->ID, 'molongui_guest_author_youtube', true );
		$guest_author_pinterest   = get_post_meta( $post->ID, 'molongui_guest_author_pinterest', true );
		$guest_author_tumblr      = get_post_meta( $post->ID, 'molongui_guest_author_tumblr', true );
		$guest_author_instagram   = get_post_meta( $post->ID, 'molongui_guest_author_instagram', true );
		$guest_author_xing        = get_post_meta( $post->ID, 'molongui_guest_author_xing', true );
		$guest_author_renren      = get_post_meta( $post->ID, 'molongui_guest_author_renren', true );
		$guest_author_vk          = get_post_meta( $post->ID, 'molongui_guest_author_vk', true );
		$guest_author_flickr      = get_post_meta( $post->ID, 'molongui_guest_author_flickr', true );
		$guest_author_vine        = get_post_meta( $post->ID, 'molongui_guest_author_vine', true );
		$guest_author_meetup      = get_post_meta( $post->ID, 'molongui_guest_author_meetup', true );
		$guest_author_weibo       = get_post_meta( $post->ID, 'molongui_guest_author_weibo', true );
		$guest_author_deviantart  = get_post_meta( $post->ID, 'molongui_guest_author_deviantart', true );
		$guest_author_stumbleupon = get_post_meta( $post->ID, 'molongui_guest_author_stumbleupon', true );
		$guest_author_myspace     = get_post_meta( $post->ID, 'molongui_guest_author_myspace', true );
		$guest_author_yelp        = get_post_meta( $post->ID, 'molongui_guest_author_yelp', true );
		$guest_author_mixi        = get_post_meta( $post->ID, 'molongui_guest_author_mixi', true );
		$guest_author_soundcloud  = get_post_meta( $post->ID, 'molongui_guest_author_soundcloud', true );
		$guest_author_lastfm      = get_post_meta( $post->ID, 'molongui_guest_author_lastfm', true );
		$guest_author_foursquare  = get_post_meta( $post->ID, 'molongui_guest_author_foursquare', true );
		$guest_author_spotify     = get_post_meta( $post->ID, 'molongui_guest_author_spotify', true );
		$guest_author_vimeo       = get_post_meta( $post->ID, 'molongui_guest_author_vimeo', true );


		// Display the form, loading stored values if available
		echo '<div class="molongui-metabox">';

			if ( isset( $options['molongui_authorship_show_social_networks_tw'] ) && $options['molongui_authorship_show_social_networks_tw'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_twitter">' . __( 'Twitter', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.twitter.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_twitter" name="molongui_guest_author_twitter" value="' . ( $guest_author_twitter ? $guest_author_twitter : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_fb'] ) && $options['molongui_authorship_show_social_networks_fb'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_facebook">' . __( 'Facebook', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.facebook.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_facebook" name="molongui_guest_author_facebook" value="' . ( $guest_author_facebook ? $guest_author_facebook : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_in'] ) && $options['molongui_authorship_show_social_networks_in'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_linkedin">' . __( 'Linkedin', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					if ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
					{
						echo '<div class="description"><span class="premium">' . __( "Premium", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</span>' . sprintf( __( "This option is available only in the %spremium version%s of the plugin.", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), "<a href=\"" . MOLONGUI_AUTHORSHIP_WEB . "\" target=\"_blank\">", "</a>" ) ."</div>";
						echo '<div class="input-wrap"><input type="text" disabled placeholder="' . __( 'Premium feature', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_linkedin" name="molongui_guest_author_linkedin" value="' . ( $guest_author_linkedin ? $guest_author_linkedin : '' ) . '" class="text"></div>';
					}
					else
					{
						echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.linkedin.com/pub/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_linkedin" name="molongui_guest_author_linkedin" value="' . ( $guest_author_linkedin ? $guest_author_linkedin : '' ) . '" class="text"></div>';
					}
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_gp'] ) && $options['molongui_authorship_show_social_networks_gp'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_gplus">' . __( 'Google +', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://plus.google.com/+user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_gplus" name="molongui_guest_author_gplus" value="' . ( $guest_author_googleplus ? $guest_author_googleplus : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_yt'] ) && $options['molongui_authorship_show_social_networks_yt'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_youtube">' . __( 'Youtube', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.youtube.com/user/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_youtube" name="molongui_guest_author_youtube" value="' . ( $guest_author_youtube ? $guest_author_youtube : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_pi'] ) && $options['molongui_authorship_show_social_networks_pi'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_pinterest">' . __( 'Pinterest', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.pinterest.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_pinterest" name="molongui_guest_author_pinterest" value="' . ( $guest_author_pinterest ? $guest_author_pinterest : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_tu'] ) && $options['molongui_authorship_show_social_networks_tu'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_tumblr">' . __( 'Tumblr', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://user_name.tumblr.com', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_tumblr" name="molongui_guest_author_tumblr" value="' . ( $guest_author_tumblr ? $guest_author_tumblr : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_ig'] ) && $options['molongui_authorship_show_social_networks_ig'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_instagram">' . __( 'Instagram', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://instagram.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_instagram" name="molongui_guest_author_instagram" value="' . ( $guest_author_instagram ? $guest_author_instagram : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_xi'] ) && $options['molongui_authorship_show_social_networks_xi'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_xing">' . __( 'Xing', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					if ( MOLONGUI_AUTHORSHIP_LICENSE != 'premium' )
					{
						echo '<div class="description"><span class="premium">' . __( "Premium", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</span>' . sprintf( __( "This option is available only in the %spremium version%s of the plugin.", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), "<a href=\"" . MOLONGUI_AUTHORSHIP_WEB . "\" target=\"_blank\">", "</a>" ) ."</div>";
						echo '<div class="input-wrap"><input type="text" disabled placeholder="' . __( 'Premium feature', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_xing" name="molongui_guest_author_xing" value="' . ( $guest_author_xing ? $guest_author_xing : '' ) . '" class="text"></div>';
					}
					else
					{
						echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.xing.com/profile/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_xing" name="molongui_guest_author_xing" value="' . ( $guest_author_xing ? $guest_author_xing : '' ) . '" class="text"></div>';
					}
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_re'] ) && $options['molongui_authorship_show_social_networks_re'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_renren">' . __( 'Renren', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.renren.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_renren" name="molongui_guest_author_renren" value="' . ( $guest_author_renren ? $guest_author_renren : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_vk'] ) && $options['molongui_authorship_show_social_networks_vk'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_vk">' . __( 'Vk', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.vk.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_vk" name="molongui_guest_author_vk" value="' . ( $guest_author_vk ? $guest_author_vk : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_fl'] ) && $options['molongui_authorship_show_social_networks_fl'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_flickr">' . __( 'Flickr', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.flickr.com/photos/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_flickr" name="molongui_guest_author_flickr" value="' . ( $guest_author_flickr ? $guest_author_flickr : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_vi'] ) && $options['molongui_authorship_show_social_networks_vi'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_vine">' . __( 'Vine', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://vine.co/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_vine" name="molongui_guest_author_vine" value="' . ( $guest_author_vine ? $guest_author_vine : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_me'] ) && $options['molongui_authorship_show_social_networks_me'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_meetup">' . __( 'Meetup', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://www.meetup.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_meetup" name="molongui_guest_author_meetup" value="' . ( $guest_author_meetup ? $guest_author_meetup : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_we'] ) && $options['molongui_authorship_show_social_networks_we'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_weibo">' . __( 'Weibo', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'http://www.weibo.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_weibo" name="molongui_guest_author_weibo" value="' . ( $guest_author_weibo ? $guest_author_weibo : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_de'] ) && $options['molongui_authorship_show_social_networks_de'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_deviantart">' . __( 'DeviantArt', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'http://user_name.deviantart.com', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_deviantart" name="molongui_guest_author_deviantart" value="' . ( $guest_author_deviantart ? $guest_author_deviantart : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_st'] ) && $options['molongui_authorship_show_social_networks_st'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_stumbleupon">' . __( 'StumbleUpon', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://stumbleupon.com/stumbler/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_stumbleupon" name="molongui_guest_author_stumbleupon" value="' . ( $guest_author_stumbleupon ? $guest_author_stumbleupon : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_my'] ) && $options['molongui_authorship_show_social_networks_my'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_myspace">' . __( 'MySpace', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://myspace.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_myspace" name="molongui_guest_author_myspace" value="' . ( $guest_author_myspace ? $guest_author_myspace : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_ye'] ) && $options['molongui_authorship_show_social_networks_ye'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_yelp">' . __( 'Yelp', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'http://www.yelp.com/biz/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_yelp" name="molongui_guest_author_yelp" value="' . ( $guest_author_yelp ? $guest_author_yelp : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_mi'] ) && $options['molongui_authorship_show_social_networks_mi'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_mixi">' . __( 'Mixi', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'http://mixi.jp/view_community.pl?id=12345', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_mixi" name="molongui_guest_author_mixi" value="' . ( $guest_author_mixi ? $guest_author_mixi : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_so'] ) && $options['molongui_authorship_show_social_networks_so'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_soundcloud">' . __( 'SoundCloud', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://soundcloud.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_soundcloud" name="molongui_guest_author_soundcloud" value="' . ( $guest_author_soundcloud ? $guest_author_soundcloud : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_la'] ) && $options['molongui_authorship_show_social_networks_la'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_lastfm">' . __( 'Last.fm', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'http://www.last.fm/user/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_lastfm" name="molongui_guest_author_lastfm" value="' . ( $guest_author_lastfm ? $guest_author_lastfm : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_fo'] ) && $options['molongui_authorship_show_social_networks_fo'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_foursquare">' . __( 'Foursquare', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://foursquare.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_foursquare" name="molongui_guest_author_foursquare" value="' . ( $guest_author_foursquare ? $guest_author_foursquare : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_sp'] ) && $options['molongui_authorship_show_social_networks_sp'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_renren">' . __( 'Spotify', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://play.spotify.com/user/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_spotify" name="molongui_guest_author_spotify" value="' . ( $guest_author_spotify ? $guest_author_spotify : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $options['molongui_authorship_show_social_networks_vm'] ) && $options['molongui_authorship_show_social_networks_vm'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<p class="label"><label class="" for="molongui_guest_author_vimeo">' . __( 'Vimeo', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</p>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://vimeo.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="molongui_guest_author_vimeo" name="molongui_guest_author_vimeo" value="' . ( $guest_author_vimeo ? $guest_author_vimeo : '' ) . '" class="text"></div>';
				echo '</div>';
			}

		echo '</div>';
	}


	/**
	 * Get a list of guest authors.
	 *
	 * @access   private
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private function get_guest_authors( )
	{
		// Get post
		global $post;

		// Query guest authors
		$args   = array( 'post_type' => 'molongui_guestauthor' );
		$guests = new WP_Query( $args );

		// Get current post guest author (if any)
		$guest_author = get_post_meta( $post->ID, 'molongui_guest_author_id', true );

		// Mount html markup
		$output = '';
		if( $guests->have_posts() )
		{
			$output .= '<select name="molongui_guest_author_id">';
			foreach( $guests->posts as $guest )
			{
				$output .= '<option value="' . $guest->ID . '"' . ( $guest_author == $guest->ID ? 'selected' : '' ) . '>' . $guest->post_title . '</option>';
			}
			$output .= '</select>';
		}

		return ( $output );
	}


	/**
	 * Save the meta when the post is saved.
	 *
	 * @param    int    $post_id  The ID of the post being saved.
	 * @return   void
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.9
	 */
	public function save( $post_id )
	{
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( !isset( $_POST['molongui_authorship_nonce'] ) ) return $post_id;
		$nonce = $_POST['molongui_authorship_nonce'];

		// Verify that the nonce is valid.
		if ( !wp_verify_nonce( $nonce, 'molongui_authorship' ) ) return $post_id;

		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] )
		{
			if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
		}
		else
		{
			if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		global $current_screen;

		if( 'molongui_guestauthor' == $current_screen->post_type )
		{
			// Update data
			update_post_meta( $post_id, 'molongui_guest_author_mail', sanitize_text_field( $_POST['molongui_guest_author_mail'] ) );
			update_post_meta( $post_id, 'molongui_guest_author_link', sanitize_text_field( $_POST['molongui_guest_author_link'] ) );
			update_post_meta( $post_id, 'molongui_guest_author_job', sanitize_text_field( $_POST['molongui_guest_author_job'] ) );
			update_post_meta( $post_id, 'molongui_guest_author_company', sanitize_text_field( $_POST['molongui_guest_author_company'] ) );
			update_post_meta( $post_id, 'molongui_guest_author_company_link', sanitize_text_field( $_POST['molongui_guest_author_company_link'] ) );
			if ( isset( $_POST['molongui_guest_author_twitter'] ) )     update_post_meta( $post_id, 'molongui_guest_author_twitter', sanitize_text_field( $_POST['molongui_guest_author_twitter'] ) );
			if ( isset( $_POST['molongui_guest_author_facebook'] ) )    update_post_meta( $post_id, 'molongui_guest_author_facebook', sanitize_text_field( $_POST['molongui_guest_author_facebook'] ) );
			if ( isset( $_POST['molongui_guest_author_linkedin'] ) )    update_post_meta( $post_id, 'molongui_guest_author_linkedin', sanitize_text_field( $_POST['molongui_guest_author_linkedin'] ) );
			if ( isset( $_POST['molongui_guest_author_gplus'] ) )       update_post_meta( $post_id, 'molongui_guest_author_gplus', sanitize_text_field( $_POST['molongui_guest_author_gplus'] ) );
			if ( isset( $_POST['molongui_guest_author_youtube'] ) )     update_post_meta( $post_id, 'molongui_guest_author_youtube', sanitize_text_field( $_POST['molongui_guest_author_youtube'] ) );
			if ( isset( $_POST['molongui_guest_author_pinterest'] ) )   update_post_meta( $post_id, 'molongui_guest_author_pinterest', sanitize_text_field( $_POST['molongui_guest_author_pinterest'] ) );
			if ( isset( $_POST['molongui_guest_author_tumblr'] ) )      update_post_meta( $post_id, 'molongui_guest_author_tumblr', sanitize_text_field( $_POST['molongui_guest_author_tumblr'] ) );
			if ( isset( $_POST['molongui_guest_author_instagram'] ) )   update_post_meta( $post_id, 'molongui_guest_author_instagram', sanitize_text_field( $_POST['molongui_guest_author_instagram'] ) );
			if ( isset( $_POST['molongui_guest_author_xing'] ) )        update_post_meta( $post_id, 'molongui_guest_author_xing', sanitize_text_field( $_POST['molongui_guest_author_xing'] ) );
			if ( isset( $_POST['molongui_guest_author_renren'] ) )      update_post_meta( $post_id, 'molongui_guest_author_renren', sanitize_text_field( $_POST['molongui_guest_author_renren'] ) );
			if ( isset( $_POST['molongui_guest_author_vk'] ) )          update_post_meta( $post_id, 'molongui_guest_author_vk', sanitize_text_field( $_POST['molongui_guest_author_vk'] ) );
			if ( isset( $_POST['molongui_guest_author_flickr'] ) )      update_post_meta( $post_id, 'molongui_guest_author_flickr', sanitize_text_field( $_POST['molongui_guest_author_flickr'] ) );
			if ( isset( $_POST['molongui_guest_author_vine'] ) )        update_post_meta( $post_id, 'molongui_guest_author_vine', sanitize_text_field( $_POST['molongui_guest_author_vine'] ) );
			if ( isset( $_POST['molongui_guest_author_meetup'] ) )      update_post_meta( $post_id, 'molongui_guest_author_meetup', sanitize_text_field( $_POST['molongui_guest_author_meetup'] ) );
			if ( isset( $_POST['molongui_guest_author_weibo'] ) )       update_post_meta( $post_id, 'molongui_guest_author_weibo', sanitize_text_field( $_POST['molongui_guest_author_weibo'] ) );
			if ( isset( $_POST['molongui_guest_author_deviantart'] ) )  update_post_meta( $post_id, 'molongui_guest_author_deviantart', sanitize_text_field( $_POST['molongui_guest_author_deviantart'] ) );
			if ( isset( $_POST['molongui_guest_author_stumbleupon'] ) ) update_post_meta( $post_id, 'molongui_guest_author_stumbleupon', sanitize_text_field( $_POST['molongui_guest_author_stumbleupon'] ) );
			if ( isset( $_POST['molongui_guest_author_myspace'] ) )     update_post_meta( $post_id, 'molongui_guest_author_myspace', sanitize_text_field( $_POST['molongui_guest_author_myspace'] ) );
			if ( isset( $_POST['molongui_guest_author_yelp'] ) )        update_post_meta( $post_id, 'molongui_guest_author_yelp', sanitize_text_field( $_POST['molongui_guest_author_yelp'] ) );
			if ( isset( $_POST['molongui_guest_author_mixi'] ) )        update_post_meta( $post_id, 'molongui_guest_author_mixi', sanitize_text_field( $_POST['molongui_guest_author_mixi'] ) );
			if ( isset( $_POST['molongui_guest_author_soundcloud'] ) )  update_post_meta( $post_id, 'molongui_guest_author_soundcloud', sanitize_text_field( $_POST['molongui_guest_author_soundcloud'] ) );
			if ( isset( $_POST['molongui_guest_author_lastfm'] ) )      update_post_meta( $post_id, 'molongui_guest_author_lastfm', sanitize_text_field( $_POST['molongui_guest_author_lastfm'] ) );
			if ( isset( $_POST['molongui_guest_author_foursquare'] ) )  update_post_meta( $post_id, 'molongui_guest_author_foursquare', sanitize_text_field( $_POST['molongui_guest_author_foursquare'] ) );
			if ( isset( $_POST['molongui_guest_author_spotify'] ) )     update_post_meta( $post_id, 'molongui_guest_author_spotify', sanitize_text_field( $_POST['molongui_guest_author_spotify'] ) );
			if ( isset( $_POST['molongui_guest_author_vimeo'] ) )       update_post_meta( $post_id, 'molongui_guest_author_vimeo', sanitize_text_field( $_POST['molongui_guest_author_vimeo'] ) );
		}
		else
		{
			// Update data
			update_post_meta( $post_id, '_molongui_guest_author', $_POST['guest-author'] );						// Guest author?
			if ( $_POST['guest-author'] == 0 ) delete_post_meta( $post_id, 'molongui_guest_author_id' );		// Guest author ID
			else update_post_meta( $post_id, 'molongui_guest_author_id', $_POST['molongui_guest_author_id'] );	// Guest author ID
			update_post_meta( $post_id, 'molongui_author_box_display', $_POST['molongui_author_box_display'] );	// Show author box?
		}

	}


}