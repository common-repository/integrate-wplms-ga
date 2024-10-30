<?php
/*
Plugin Name: WPLMS GA
Plugin URI: http://www.Vibethemes.com
Description: Integrate Google Analytics with WPLMS
Version: 1.0
Author: VibeThemes
Author URI: http://www.vibethemes.com
License: GPL2
Text Domain: wplms-ga
*/
/*
Copyright 2014  VibeThemes  (email : vibethemes@gmail.com)

WPLMS GA program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

WPLMS GA program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WPLMS GA program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once 'includes/class.init.php';

// Add text domain
add_action('plugins_loaded','wplms_ga_translations');
function wplms_ga_translations(){
    $locale = apply_filters("plugin_locale", get_locale(), 'wplms-ga');
    $lang_dir = dirname( __FILE__ ) . '/languages/';
    $mofile        = sprintf( '%1$s-%2$s.mo', 'wplms-ga', $locale );
    $mofile_local  = $lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

    if ( file_exists( $mofile_global ) ) {
        load_textdomain( 'wplms-ga', $mofile_global );
    } else {
        load_textdomain( 'wplms-ga', $mofile_local );
    }  
}
