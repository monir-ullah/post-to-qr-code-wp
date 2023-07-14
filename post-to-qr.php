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
    $height_and_width = get_option( 'ptoqr_image_heigth_and_width' );
   // $width = get_option( 'ptoqr_image_width' );
    $height_and_width = $height_and_width ? $height_and_width : 100;
   // $width = $width ? $width : 100;
    $image_size = apply_filters( 'ptoqr_change_image_size', "{$height_and_width}x{$height_and_width}" );
    $img = '<img src = "https://api.qrserver.com/v1/create-qr-code/?size=' . $image_size .'&data=' . $ptoqr_post_url . '">';  
    return $content .= $img;

}
add_filter( 'the_content', 'ptoqr_initial_func' );

function ptoqr_change_image_size_func($size){
    $ptoqr_image_heigth_and_width = get_option( 'ptoqr_image_heigth_and_width' );
    //$width = get_option( 'ptoqr_image_heigth_and_width' );
    $full_value = $ptoqr_image_heigth_and_width . 'x' . $ptoqr_image_heigth_and_width;
    echo $full_value;
    return $full_value;
}

add_filter( 'ptoqr_change_image_size', 'ptoqr_change_image_size_func' );




// Post to QR COde Plugin Settigs Section

add_action( 'admin_init', 'ptoqr_setting_option' );

function ptoqr_setting_option(){
    // Plugins Setting Sectiton Title
    add_settings_section( 
        'ptoqr_seting_option_page',
        __('Post to QR Code Setting Option'),
        'atoqr_add_settings_section_title',
        'general'
    );

    // Setting Field Width
    add_settings_field( 
        'ptoqr_image_heigth_and_width',
        __('QR Code Width ','ptoqr'),
        'ptoqr_setting_input_filed_function',
        'general',
        'ptoqr_seting_option_page',
        array('ptoqr_image_heigth_and_width')
    );

    register_setting( 'general', 'ptoqr_image_heigth_and_width');


    // Setting Filed Height
    /*
    add_settings_field( 
        'ptoqr_image_heigth',
        __('QR Code Hight','ptoqr'),
        'ptoqr_setting_input_filed_function',
        'general',
        'ptoqr_seting_option_page',
        array('ptoqr_image_heigth')
    );
    register_setting( 'general', 'ptoqr_image_heigth');
    */
}

function atoqr_add_settings_section_title(){
    echo "Post to QR Code All Settings Here";
}


// Settings Input Field Funcwtion

function ptoqr_setting_input_filed_function($args){
    $filed_option = get_option( $args[0] );
    printf('<input type="number" name="%s" id="%s" value="%s">',$args[0],$args[0], $filed_option );  
}

