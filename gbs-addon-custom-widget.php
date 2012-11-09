<?php
/*
Plugin Name: Group Buying Addon - Custom Widget
Version: 1.1
Plugin URI: http://groupbuyingsite.com/marketplace
Description: Custom Widget for GBS.
Author: Sprout Venture
Author URI: http://sproutventure.com/wordpress
Plugin Author: Dan Cameron
Contributors: Dan Cameron 
Text Domain: group-buying
Domain Path: /lang

*/

add_action('plugins_loaded', 'gb_load_custom_widget');
function gb_load_custom_widget() {
	if (class_exists('Group_Buying_Controller')) {
		require_once('groupBuyingWidget.class.php');
		Group_Buying_Widget_Addon::init();
	}
}