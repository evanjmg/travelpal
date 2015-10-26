<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @since      1.0.0
 * @version    1.2.0
 */

// If uninstall not called from WordPress, then exit.
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Get plugin options
$options = get_option( MOLONGUI_AUTHORSHIP_CONFIG_KEY );

// Delete plugin settings if not configured otherwise
if ( $options['molongui_authorship_keep_config'] == 0 )
{
	global $wpdb;

	$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'molongui_authorship_%';");
}

// Delete plugin data if not configured otherwise
if ( $options['molongui_authorship_keep_data'] == 0 )
{
	global $wpdb;

	// Get all "molongui_guestauthor" custom-posts
	$ids = $wpdb->get_results(
		"
		SELECT ID
		FROM {$wpdb->prefix}posts
		WHERE post_type LIKE 'molongui_guestauthor'
		",
		ARRAY_A
	);

	// Convert numerically indexed array of associative arrays (ARRAY_A) to comma separated string
	foreach ( $ids as $key => $id )
	{
		if ( $key == 0 ) $postids = $id['ID'];
		else $postids = $postids . ', ' . $id['ID'];
	}

	// Delete all "postmeta" entries related with those custom-posts
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id IN ( $postids );" );

	// Delete all "molongui_guestauthor" custom-posts
	$wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE ID IN ( $postids );" );

	// Delete all "postmeta" entries related with "Molongui Authorship"
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = '_molongui_guest_author';" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = 'molongui_guest_author_id';" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = 'molongui_author_box_display';" );
}