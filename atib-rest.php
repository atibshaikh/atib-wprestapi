<?php
/**
 * Plugin Name:       Atib REST
 * Plugin URI:        https://atibshaikh.com
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Author:            Atib Shaikh
 * Author URI:        https://atibshaikh.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://atibshaikh.com
 * Text Domain:       atib-basics-plugin
 */

if (!defined('ABSPATH')) {
     die('you are not allow to access'); 
}

//echo $test = plugin_dir_path( __FILE__ );


// define plugin constant

define('MYPLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('MYPLUGIN_URL', plugin_dir_url( __FILE__ ) );

if(is_admin()){
    require_once plugin_dir_path( __FILE__ ) .'admin/admin.php';
    $admin = new Admin_setup();
}

if(!is_admin()){
    require_once plugin_dir_path( __FILE__ )  .'public/public.php';
    $public = new Public_cpt();
} 


/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_template');

function my_custom_template($single) {

    global $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'youtube_videos' ) {
        if ( file_exists( MYPLUGIN_PATH . 'public/templates/single-youtube_videos.php' ) ) {
            return MYPLUGIN_PATH . 'public/templates/single-youtube_videos.php';
        }
    }

    return $single;

}
 
 add_action('init',  'regiter_custom_post_type_videos' );

//create custom post type for videos
function regiter_custom_post_type_videos(){

    /**
     * Post Type: Videos.
     */

    $labels = [
        "name" => __( "Youtube Videos", "twentytwentyone" ),
        "singular_name" => __( "Youtube Video", "twentytwentyone" ),
    ];

    $args = [
        "label" => __( "Youtube Videos", "twentytwentyone" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => true,
        "rewrite" => [ "slug" => "youtube_videos", "with_front" => true ],
        "query_var" => true,
        "supports" => [ "title", "editor", "thumbnail", "excerpt", "comments", "revision" ],
        "show_in_graphql" => false,
    ];

    register_post_type( "youtube_videos", $args );

    }

function activate_plugin_name(){


    
}

function deactivate_plugin_name(){

}


//this are this filter to change ACF directory path and use acf in our plugin
// 1. customize ACF path
add_filter( 'acf/settings/path', 'acf_setting_path' );

function acf_setting_path( $path ) {
  
  $path = plugin_dir_path( __FILE__ ) . 'admin/acf/';
  return $path;

}


// 2. customize ACF dir
add_filter( 'acf/settings/dir', 'acf_settings_dir' );

function acf_settings_dir( $dir ) {
  
  $dir = plugin_dir_url( __FILE__ ) . 'admin/acf/';
  return $dir;

}
require MYPLUGIN_PATH . 'admin/acf/acf.php';

//import acf field to use in our plugin
if( function_exists('acf_add_local_field_group') ):
  
  $fields = json_decode( file_get_contents( MYPLUGIN_PATH .'acf-export-book.php' ), true );

  // echo '<pre>';
  // print_r($fields[0]);
  // echo '</pre>';

  acf_add_local_field_group( $fields[0] );

endif;



register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

//register_activation_hook( __FILE__, 'create_inital_setup' );




