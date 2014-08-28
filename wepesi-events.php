<?php
/*
Plugin Name: Wepesi Events
Plugin URI: http://wepesi.com
Description: Events logger.
Author: David Njuguna
Version: 0.1
Author URI: http://davidnjuguna.com
*/

add_action('init', 'wepesi_activity_owner_visit');
function wepesi_activity_owner_visit(){
	$user = wp_get_current_user();
	$primary_blog = get_user_meta( $user->ID, 'primary_blog', true);
	$activity['data'] = $_SERVER;
	$activity['name'] = 'pageview';
	$data = $_SERVER;
	$data['user_is_owner'] = current_user_can('switch_themes');
	$data['primary_blog'] = $primary_blog;
	$data['blog_id'] = get_current_blog_id();
	$data['creation_time'] = microtime();
	$activity = array('name' => 'pageview', 'data' => $data);
	wepesi_log_activity($activity);
}

function wepesi_log_activity($activity){
	$connection = new Mongo();
	$connecting_string =  'mongodb://localhost:27017/wepesi_activity_log';
	$connection=  new Mongo($connecting_string);
	$db = $connection->selectDB('user_activities');
	$collection = $db->selectCollection('activity');
	$collection->insert($activity);
}

?>
