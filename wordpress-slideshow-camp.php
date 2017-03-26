<?php
/*
Plugin Name: Wordpress Slideshow plugins
Plugin URI: http://poolgab.com
Description: A simple WordPress plugin to show slideshow
Version: 1.0
Author: dungendevelop
Author URI: http://poolgab.com
License: GPL2
*/
/*
Copyright 2017  VibeThemes  (email : anshuman.sahu143@gmail.com)

Wordpress Slideshow plugin program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

Wordpress Slideshow plugin program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with wplms_customizer program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


include_once 'classes/wp-s-camp.php';



if(class_exists('Wp_Slideshow_Class'))
{	
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('Wp_Slideshow_Class', 'activate'));
    register_deactivation_hook(__FILE__, array('Wp_Slideshow_Class', 'deactivate'));

    // instantiate the plugin class
    $wpsc = Wp_Slideshow_Class::init();
}

function wp_s_camp_enqueue_styles(){
    //wp_enqueue_style( 'wp-s-camp-css', plugins_url( 'assets/css/wp_slide_front.css' , __FILE__ ));
    
}

add_action('wp_head','wp_s_camp_enqueue_styles');

add_action('wp_enqueue_scripts','wp_s_camp_custom_js');
function wp_s_camp_custom_js(){
	//wp_enqueue_script( 'wp-s-camp-js', plugins_url( 'assets/js/wp_slide_front.js' , __FILE__ ));
}

add_action( 'plugins_loaded', 'wp_s_camp_language_setup' );
function wp_s_camp_language_setup(){
    $locale = apply_filters("plugin_locale", get_locale(), 'bmbp');
    
    $lang_dir = dirname( __FILE__ ) . '/languages/';
    $mofile        = sprintf( '%1$s-%2$s.mo', 'wp-slide-camp', $locale );
    $mofile_local  = $lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

    if ( file_exists( $mofile_global ) ) {
        load_textdomain( 'wp-slide-camp', $mofile_global );
    } else {
        load_textdomain( 'wp-slide-camp', $mofile_local );
    }   
}