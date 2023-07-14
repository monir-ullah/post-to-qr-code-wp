<?php

/*
Plugin Name: Post To QR Code
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Monir Ullah
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
Text Domain: ptoqr
Domain Path: /languages
*/

//Load translation

function ptoqr_load_plugin_textdomain(){
    load_plugin_textdomain('ptoqr', FALSE, basename(__FILE__)) . '/languages/';
}
add_action('plugins_loaded', 'ptoqr_load_plugin_textdomain');


// initial function 
function ptoqr_initial_func($content){
    $ptoqr_post_id = get_post();
    $ptoqr_post_url = get_permalink($ptoqr_post_id->ID);
    $image_size = apply_filters( 'ptoqr_change_image_size', '250x250' );
    $img = '<img src = "https://api.qrserver.com/v1/create-qr-code/?size=' . $image_size .'&data=' . $ptoqr_post_url . '">';
    
    return $content .= $img;

}
add_filter( 'the_content', 'ptoqr_initial_func' );

function ptoqr_change_image_size_func($size){
    echo "Hello World";
    return '150x150';
}

add_filter( 'ptoqr_change_image_size', 'ptoqr_change_image_size_func' );