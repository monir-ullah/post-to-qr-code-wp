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

// Country List
$ptoqr_country_list = array(

    __( 'Afganistan', 'posts-to-qrcode' ),
    __( 'Bangladesh', 'posts-to-qrcode' ),
    __( 'Bhutan', 'posts-to-qrcode' ),
    __( 'India', 'posts-to-qrcode' ),
    __( 'Maldives', 'posts-to-qrcode' ),
    __( 'Nepal', 'posts-to-qrcode' ),
    __( 'Pakistan', 'posts-to-qrcode' ),
    __( 'Sri Lanka', 'posts-to-qrcode' ),

);

function ptoqr_country_hook_enable(){

    global $ptoqr_country_list;
    $ptoqr_country_list = apply_filters( 'ptoqr_country_list_hook', $ptoqr_country_list );
}
add_action( 'init', 'ptoqr_country_hook_enable' );
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
        'atoqr_add_settings_section_title_func',
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


    // Setting Select Country  Option

    add_settings_field( 
        'ptoqr_country_select',
        __('Select Country'),
        'ptoqr_country_select_func',
        'general',
        'ptoqr_seting_option_page', 
    );

    register_setting( 'general', 'ptoqr_country_select' );

    // Setting Checkbox Country  Option

    add_settings_field( 
        'ptoqr_country_checkbox',
        __('Select Country from List'),
        'ptoqr_country_checkbox_func',
        'general',
        'ptoqr_seting_option_page', 
    );

    register_setting( 'general', 'ptoqr_country_checkbox' );

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

function atoqr_add_settings_section_title_func(){
    echo "Post to QR Code All Settings Here";
}


// Settings Input Field Funcwtion

function ptoqr_setting_input_filed_function($args){
    $filed_option = get_option( $args[0] );
    printf('<input type="number" name="%s" id="%s" value="%s">',$args[0],$args[0], $filed_option );  
}

// Select Filed Function

function ptoqr_country_select_func(){
    global $ptoqr_country_list;
    $select_option = get_option( 'ptoqr_country_select');
    printf('<select name="ptoqr_country_select" id="ptoqr_country_select">');
    foreach($ptoqr_country_list as $country){
        $selected = '';
        if($select_option == $country  ){
            $selected = 'selected';
        }
        printf('<option value="%s" %s >%s</option>',$country,$selected,$country);
    }
   echo '</select>';

}

// Checkbox Field

function ptoqr_country_checkbox_func(){
    global $ptoqr_country_list;
   printf('<p>Choose Countries from belloow</p></br>');
    
    $seleced_checkbox = get_option( 'ptoqr_country_checkbox');
    foreach($ptoqr_country_list as $country){
        $checked = '';
        if( is_array($seleced_checkbox) && in_array($country, $seleced_checkbox)){
            $checked = 'checked';
        }
        // here name="ptoqr_country_checkbox[]" must need . because of it's a array
        printf(' <input type="checkbox" name="ptoqr_country_checkbox[]" value="%s" %s> %s </br>', $country, $checked, $country);
    }
    
}

// Contry hook testing
function qtoqr_country_llist_testing($countries){
    array_push($countries, __('Oman','ptoqr') );
    return $countries;
}
add_action('ptoqr_country_list_hook', 'qtoqr_country_llist_testing');