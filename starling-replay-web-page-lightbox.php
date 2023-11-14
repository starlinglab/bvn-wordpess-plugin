<?php
/*
Plugin Name: Replay Web Page with Lightbox
Description: Display WACZ with lightbox player.
Version: 0.2
Author: Starling Lab
*/

// Allow wacz uploads
function wacz_mime_types_starling_lightbox( $mimes ) {
    
    // New allowed mime types.
    $mimes['wacz'] = 'application/wacz';
    $mimes['warc'] = 'application/warc';
    $mimes['json'] = 'application/json';
   
    return $mimes;
}
add_filter( 'upload_mimes', 'wacz_mime_types_starling_lightbox' );

//Add script to head
function add_script_to_head_starling_lightbox() {
    $plugin_dir_url = plugins_url('', __FILE__);
//    wp_enqueue_script('custom-script', get_template_directory_uri() . '/custom-script.js', array(), '1.0', false);
    wp_enqueue_script('starling-replay-ui', 'https://cdn.jsdelivr.net/npm/replaywebpage@1.8.13/ui.js', array(), '1.0', false);
    wp_enqueue_script('starling-replay-lightbox-ui', plugins_url('', __FILE__) . '/index-96c988fa.js', array(), '1.0', false);
    wp_register_style('starling-lightbox-css', plugins_url('', __FILE__) . '/index-31ef780a.css', array(), '1.0', 'all');
    wp_enqueue_style('starling-lightbox-css');
   
}

add_action('wp_enqueue_scripts', 'add_script_to_head_starling_lightbox');


// Write HTML code for the replay site
function display_wacz_lightbox($atts) {
    // Extract and sanitize the URL from the shortcode attribute

    $url="";
    if (isset($atts['remote_url'])) {
      $url = esc_url($atts['remote_url']);
    }
    if (isset($atts['media_id'])) {
      $url = esc_url(wp_get_attachment_url($atts['media_id']));
    }

    // Current plugin directory 
    $plugin_dir_url = plugins_url('', __FILE__);

    //Attributes
    $height = isset($atts['height']) ? $atts['height'] : "400px";
    $width = isset($atts['width']) ? $atts['width'] : "100%";


    $pathInfo = pathinfo($url);
    $directoryPath = $pathInfo['dirname'];
    $filename = $pathInfo['basename'];

    // Generate and return the replay-web-page control
    $ret = '<wacz-lightbox filename="' . $filename . '"  path="' . $directoryPath . '/"';
    $ret .= ' replayBase="' . $plugin_dir_url . "/replay/" . '"';
    $ret .= ' style="height:' . $height . ';width:' . $width . '"';
    $ret .= '></wacz-lightbox>';

    return $ret;    
}
// Register the shortcode to use in posts or pages
add_shortcode('wacz_lightbox_url', 'display_wacz_lightbox');
