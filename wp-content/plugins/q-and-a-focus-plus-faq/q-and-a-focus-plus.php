<?php

/*

Plugin Name: Q and A Focus Plus FAQ

Plugin URI: http://lanexatek.com/downloads/wordpress-plugins/qa-focus-plus

Description: A full featured FREE FAQ and Knowledge Base plugin for WordPress. Based on the free Q &amp; A FAQ and Knowledge Base by Raygun Design LLC, Q &amp; A Focus Plus includes numerous modifications, enhancements and new features such as comments, tag support and ratings.

Author: Lanexatek Creations

Version: 1.3.9.7

Text Domain: qa-focus-plus

Author URI: http://lanexatek.com

License: GPLv2

Copyright 2014 Lanexatek Creations

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

*/ 

define( 'QAFP_PATH', plugin_dir_path( __FILE__ ) );

define( 'QAFP_LOCATION', plugin_basename(__FILE__) );

define( 'QAFP_VERSION', '1.3.9.7' );

define ( 'QAFP_URL', plugins_url( '' ,  __FILE__ ) );

//our main functions file

require ( QAFP_PATH . 'inc/functions.php'); 

// Get the admin page if necessary

if ( is_admin() ) { 

	require( QAFP_PATH . 'admin/q-a-focus-plus-admin.php' );
 
} else {
	/* Load scripts for front */
	function qafp_front_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'q-a-focus-plus', QAFP_URL . '/js/q-a-focus-plus.min.js', false, QAFP_VERSION, true ); 
		wp_enqueue_style( 'q-a-focus-plus', QAFP_URL . '/css/q-a-focus-plus.min.css', false, QAFP_VERSION, 'screen' );
	}
	add_action( 'wp_enqueue_scripts', 'qafp_front_scripts' );
}


// load plugin text domain for translation

function qafp_lang_init() {

    load_plugin_textdomain('qa-focus-plus', false, basename(dirname(__FILE__)) . '/lang');

}

add_action('init','qafp_lang_init');