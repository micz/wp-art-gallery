<?php
/*
Plugin Name: WP Art Gallery
Plugin URI: http://micz.it/wordpress-plugin-art-gallery/
Description: A full immersive gallery.
Author: Mic [m@micz.it]
Version: 1.0.1
Text Domain: wp-art-gallery
Domain Path: /lang
Author URI: http://micz.it
License: GPLv2 or later
*/

/* Copyright 2017 Mic (email: m@micz.it)
Plugin Info: http://micz.it/wordpress-plugin-500px-jsgallery/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

// Fix the __FILE__ problem with symlinks.
// Now just use ___FILE___ instead of __FILE__

$___FILE___ = __FILE__;

if ( isset( $plugin ) ) {
$___FILE___ = $plugin;
}
else if ( isset( $mu_plugin ) ) {
$___FILE___ = $mu_plugin;
}
else if ( isset( $network_plugin ) ) {
$___FILE___ = $network_plugin;
}

define( '___FILE_wpag___', $___FILE___ );

include_once('functions.php');
include_once('wp-art-gallery.class.php');
$wpmiczartgallery='';

function wpmiczartgal_plugin_init(){
  global $wpmiczartgallery;
  $wpmiczartgallery=new WPArtGallery();
}

/**
 * ENQUEUE SCRIPTS
 */
function wpmiczartgal_enqueue_scripts() {
  global $wpmiczartgallery;
  $wpmiczartgal_enqueue_scripts=false;
  if($wpmiczartgallery->options[WPArtGallery::_pages]!=''){ //if the user has set a single page, enqueue only on that page
    $wpmiczartgal_enqueue_scripts=is_page(explode(',',$wpmiczartgallery->options[WPArtGallery::_pages]))||wpmz_is_parent_page(explode(',',$wpmiczartgallery->options[WPArtGallery::_pages]));
  }else{
    $wpmiczartgal_enqueue_scripts=is_page();
  }
    if($wpmiczartgal_enqueue_scripts):
      //Gallery CSS
		 wp_enqueue_style(
				  'wpmiczartgal-main-style',
				  plugins_url('css/wp-art-gallery.css' , ___FILE_wpag___ ),
				  array(),
				  WPArtGallery::version
	    );
	    wp_enqueue_style(
              'swipebox-style',
              plugins_url('css/swipebox.css' , ___FILE_wpag___ ),
              array(),
              WPArtGallery::version
        );
	    $custom_css_exists=file_exists(get_stylesheet_directory().'/wp-art-gallery/wp-art-gallery.css'); //check if a custom css exists in the current theme directory
        if($custom_css_exists){ //Conditionally loading a theme css
          wp_enqueue_style(
              'wpmiczartgal-theme-style',
              get_stylesheet_directory_uri().'/wp-art-gallery/wp-art-gallery.css',
              array(),
              WPArtGallery::version
          );
          true;
        }
        wp_enqueue_script(
            'swipebox',
            plugins_url( 'js/jquery.swipebox.js' , ___FILE_wpag___ ),
            array('jquery'),
            WPArtGallery::version
        );
        wp_enqueue_script(
            'vibrant',
            plugins_url( 'js/vibrant.min.js' , ___FILE_wpag___ ),
            array(),
            WPArtGallery::version
        );
        wp_enqueue_script(
            'wpmiczartgal-main',
            plugins_url( 'js/wp-art-gallery.js' , ___FILE_wpag___ ),
            array('jquery','swipebox'),
            WPArtGallery::version //script version
        );
        $wpmiczartgallery->scripts_loaded=true;
    endif;
}

function wpmiczartgal_plugin_activation(){
	$wpmiczartgaltmp=new WPArtGallery();
	$wpmiczartgaltmp->activate();
}


add_action('wp_enqueue_scripts', 'wpmiczartgal_enqueue_scripts');
add_action('init', 'wpmiczartgal_plugin_init');
?>