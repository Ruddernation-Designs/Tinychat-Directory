<?php
/*
* Plugin Name: Tinychat Directory (depreciated!)
* Plugin URI: https://wordpress.org/plugins/tc-room-spy/
* Author: Ruddernation Designs
* Author URI: https://profiles.wordpress.org/ruddernationdesigns
* Description: Please use https://www.ruddernation.com/api 
* Requires at least: WordPress 4.6
* Tested up to: WordPress 5.4, Buddypress  5.0.0
* Version: 1.2.5
* License: MIT
* License URI: https://opensource.org/licenses/MIT
* Date: 05th December 2022
*/
define('COMPARE_VERSION', '1.2.4');
defined( 'ABSPATH' ) or die( 'Clean your boots off!' );
register_activation_hook(__FILE__, 'rndtc_room_spy_install');
function rndtc_room_spy_install() {
	global $wpdb, $wp_version;
	$post_date = date("Y-m-d H:i:s");
	$post_date_gmt = gmdate("Y-m-d H:i:s");
	$sql = "SELECT * FROM ".$wpdb->posts." WHERE post_content LIKE '%[rndtc_room_spy_page]%' AND `post_type` NOT IN('revision') LIMIT 1";
	$page = $wpdb->get_row($sql, ARRAY_A);
	if($page == NULL) {
		$sql ="INSERT INTO ".$wpdb->posts."(
			post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_type)
			VALUES
			('1', '$post_date', '$post_date_gmt', '[rndtc_room_spy_page]', '', 'tcroomspy', '', 'publish', 'closed', 'closed', '', 'tcroomspy', '', '', '$post_date', '$post_date_gmt', '0', '0', 'page')";
		$wpdb->query($sql);
		$post_id = $wpdb->insert_id;
		$wpdb->query("UPDATE $wpdb->posts SET guid = '" . get_permalink($post_id) . "' WHERE ID = '$post_id'");
	} else {
		$post_id = $page['ID'];
	}
	update_option('rndtc_room_spy_url', get_permalink($post_id));
}
add_filter('the_content', 'wp_show_rndtc_room_spy_page', 52);
function wp_show_rndtc_room_spy_page($content = '') {
	if(preg_match("/\[rndtc_room_spy_page]/",$content)) {
		wp_show_rndtc_room_spy();
		return "";
	}
	return $content;
}
function wp_show_rndtc_room_spy() {
	if(!get_option('rndtc_room_spy_enabled', 0)) {
		echo 'Please use the <a href="https://www.ruddernation.com/api" target=/"_blank/">Tinychat API<a>.';
		echo '<br>';
	}
	
}
?>
