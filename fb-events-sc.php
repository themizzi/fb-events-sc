<?php
/**
 * Plugin Name: FB Events SC
 * Plugin URI: http://themizzi/fb_events_sc
 * Description: Facebook Events Shortcode
 * Version: 1.0.1
 * Author: Joe Mizzi
 * Author URI: http://themizzi.com
 * License: GPL2
 */

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

if (is_admin()) {
	require_once dirname( __FILE__ ) . '/class-fb-events-sc-settings-page.php';
}

require_once dirname( __FILE__ ) . '/class-fb-events-sc-short-code.php';