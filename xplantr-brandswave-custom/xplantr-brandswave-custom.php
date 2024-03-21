<?php
/**
 * @package xplantr_brandsWave_custom
 * @version 1.0.1
 */
/*
Plugin Name: Xplantr BrandsWave Custom
Plugin URI: http://wordpress.org/plugins/
Description: This is not just a plugin,
Author: TR
Version: 1.0.1
*/

/**
 * The functionality provided by the plugin are 
 * 1. bkash withdraw option for dokan vendor
 * 2. ssl sms on withdraw
 * 3. make weight field in product required
 * 4. create no-cod shipping class if not exists
 * 5. based on no-cod shipping class, disable Cash on delivery shipping method.
 * 6. add a custom options page for some wp-options fields
 * 7. add a show password icon in login form
 */
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
define('Xplantr_BrandsWave_Custom',plugin_dir_path(__FILE__));

require Xplantr_BrandsWave_Custom . 'inc/bkash-withdraw-option-for-dokan-vendor.php';
require Xplantr_BrandsWave_Custom . 'inc/ssl-sms-on-withdraw.php';
require Xplantr_BrandsWave_Custom . 'inc/brandswave-custom-options.php';
require Xplantr_BrandsWave_Custom . 'inc/brandswave-vendor-product-field.php';

/**
 * Create co-cod shipping class if not exists
 */
function xpdw_initialize_withdraw_method(){
    if(!term_exists( 'no-cod', 'product_shipping_class')){
        wp_insert_term(
            'No Cod',   // the term 
            'product_shipping_class', // the taxonomy
            array(
                'description' => 'Dont apply cash on delivery',
                'slug'        => 'no-cod',
            )
        );
    }
}
add_action('init', 'xpdw_initialize_withdraw_method');

/**
 * custom admin css and js
 */
add_action('admin_enqueue_scripts', 'cstm_css_and_js');
function cstm_css_and_js($hook) {
    if('product' != get_post_type()){
        return;
    }
    wp_enqueue_script('xp_admin_custom', plugins_url('assets/xp-admin-custom.js',__FILE__ ));
}
/**
 * custom css and js
 */
add_action('wp_enqueue_scripts', 'cstm_css_and_js_for_brandswave');
function cstm_css_and_js_for_brandswave($hook) {
    wp_enqueue_style('xp_custom', plugins_url('assets/xp-custom.css',__FILE__ ));
    wp_enqueue_script('xp_custom_script', plugins_url('assets/xp-custom-script.js',__FILE__ ));
}


/**
 * Based on shipping class Remove cash on delivery option from the checkout page
 */
add_filter( 'woocommerce_available_payment_gateways', 'xpl_hide_cod_if_any_product_has_shipping_class' );

function xpl_hide_cod_if_any_product_has_shipping_class( $gateways ) {
    
	// do nothing in /wp-admin
	if( is_admin() ) {
		return $gateways;
	}
	
	foreach ( WC()->cart->get_cart_contents() as $key => $values ) {
		if ( 'no-cod' === $values[ 'data' ]->get_shipping_class() ) {
			if( isset( $gateways[ 'cod' ] ) ) {
				unset( $gateways[ 'cod' ] );
				break;
			}
		}
	}

	return $gateways;
	
}

/**
 * add 'cash on delivery not available' message in cart page
 */
add_filter( 'woocommerce_cart_item_name', 'xpl_shipping_class_in_item_name', 20, 3);
function xpl_shipping_class_in_item_name( $item_name, $cart_item, $cart_item_key ) {
    // Only in cart page (remove the line below to allow the display in checkout too)
    if( ! ( is_cart() || is_checkout() ) ) return $item_name;

    $product = $cart_item['data']; // Get the WC_Product object instance
    $shipping_class_id = $product->get_shipping_class_id(); // Shipping class ID
    $shipping_class_term = get_term( $shipping_class_id, 'product_shipping_class' );

    if( empty( $shipping_class_id ) )
        return $item_name; // Return default product title (in case of)
    
    if ( $shipping_class_term->slug == 'no-cod' ) {
        $message = 'No Cash On Delivery';
    }


    return $item_name . '<br>
        <p class="item-shipping_class" style="color:blue; font-size: 0.875em;">' . $message .'</p>';
}





add_action('woocommerce_login_form', 'show_pass_login_form');
add_action('woocommerce_register_form', 'show_pass_login_form');
function show_pass_login_form(){
    global $wp;
    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    if (str_contains($current_url, 'my-account')) {
        
    }else{
        echo '<span class="show-password-input display-password popup"></span>';
    }
}
add_filter( 'woocommerce_after_shipping_rate', 'change_shipping_label', 10, 2 );
function change_shipping_label( ){
    $my_option_data = get_option( 'brandwave_text_area' );
    
    echo '
    <style>
        .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted red;
        color: red;
        visibility: visible;
            opacity: 1;
            top: -20px;
        }
        .page-template-page-shopping-cart .tooltip.custom--tt--for--checkout{
            top: 0;
            right: -10px;
        }

        #shipping_method_clone label{
            padding-right: 20px;
        }

        .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        right: 20px;
        }

        .tooltip:hover .tooltiptext {
        visibility: visible;
        }
        
    </style>
    <div class="tooltip custom--tt--for--checkout">!
            <span class="tooltiptext">'.$my_option_data .'</span>
        </div>';
}