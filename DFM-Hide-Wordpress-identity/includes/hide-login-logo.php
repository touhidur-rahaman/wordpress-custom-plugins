<?php

function register_hook_callbacks()
{
    add_action('admin_enqueue_scripts', 'load_resources');
    add_action('login_enqueue_scripts', 'load_resources');
    add_action('admin_enqueue_scripts', 'load_resources');

    add_action( 'admin_bar_menu', 'remove_unwanted_admin_menu', 999 );

    add_filter('login_headerurl', 'my_custom_login_url');
}

register_hook_callbacks();


function my_custom_login_url($url)
{
    return '/';
}

function load_resources()
{

    if (is_login()) {
        wp_enqueue_style(PREFIX . 'custom-login-logo', plugins_url('css/login.css', dirname(__FILE__)), array(), VERSION);

        $logo_path = get_option('dfm_custom_admin_logo');
        $prominent_color = get_option('dfm_custom_admin_color');
        $login_header_color = get_option('dfm_custom_login_header_color');

        !empty($logo_path) && wp_add_inline_style(PREFIX . 'custom-login-logo', 
        
            "#login h1 a {
                background-image:url( '" . $logo_path . "');
            }"
        );
        !empty($prominent_color) && wp_add_inline_style(PREFIX . 'custom-login-logo', "
            #login input#wp-submit {
                background: ".$prominent_color.";
            }
            span.dashicons.dashicons-visibility, .login #backtoblog a  {
                color: ". $prominent_color. ";
            }"
        );
        !empty($login_header_color) && wp_add_inline_style(PREFIX . 'custom-login-logo', "
            .login h1 {
                background: ".$login_header_color.";
            }
            "
        );
    } elseif (is_admin()) {
        wp_enqueue_media();
    }
}

function remove_unwanted_admin_menu( $wp_admin_bar ) {
    $my_account = $wp_admin_bar->get_node('my-account');
    $new_text = str_replace( 'Howdy,', '', $my_account->title );
    $wp_admin_bar->add_node( array(
        'id'    => 'my-account',
        'title' => $new_text,
    ) );

    $wp_admin_bar->remove_node( 'wp-logo' );
}


