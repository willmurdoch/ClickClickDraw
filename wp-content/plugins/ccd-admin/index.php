<?php
/*
Plugin Name: Click Click Draw Admin Theme
Plugin URI: http://example.com/my-crazy-admin-theme
Description: My WordPress Admin Theme - Upload and Activate.
Author: Brownstein Group
Version: 1.0
Author URI: http://example.com
*/

function my_admin_theme_style() {
    wp_enqueue_style('my-admin-theme', plugins_url('wp-admin.css', __FILE__));
}
function admin_bar_style() {
  wp_enqueue_style('my-admin-theme', plugins_url('wp-admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'my_admin_theme_style');
add_action('login_enqueue_scripts', 'my_admin_theme_style');
add_action( 'admin_enqueue_scripts', 'admin_bar_style' );
add_action( 'wp_enqueue_scripts', 'admin_bar_style' );

add_filter('gettext', 'change_howdy', 10, 3);
function change_howdy($translated, $text, $domain) {

    if ('default' != $domain)
        return $translated;

    if (false !== strpos($translated, 'Howdy'))
        return str_replace('Howdy', 'Hi', $translated);

    return $translated;
}
?>
