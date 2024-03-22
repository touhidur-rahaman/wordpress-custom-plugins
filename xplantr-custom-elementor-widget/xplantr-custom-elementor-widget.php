<?php

/**
 * Plugin Name: Xplantr Custom Elementor Widget
 * Description: Auto embed any embbedable content from external URLs into Elementor.
 * Version:     1.0.0
 * Author:      TR
 * Text Domain: xplantr-custom-elementor-widget
 *
 * Requires Plugins: elementor
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function register_widget_styles() {
	wp_enqueue_style( 'brand-carousel-swipercss', plugins_url( 'assets/vendor/swiper/swiper-bundle.min.css', __FILE__ ) );
    wp_enqueue_script( 'brand-carousel-swiperjs', plugins_url( 'assets/vendor/swiper/swiper-bundle.min.js', __FILE__ ) );
	
    wp_enqueue_style( 'brand-carousel-widget-style', plugins_url( 'assets/css/brand-carousel-widget.css', __FILE__ ) );
    wp_enqueue_script( 'brand-carousel-widget-script', plugins_url( 'assets/js/brand-carousel-widget.js', __FILE__ ) );

}
add_action( 'wp_enqueue_scripts', 'register_widget_styles' );

/**
 * Register oEmbed Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_xlpantr_brand_carousel_widget($widgets_manager)
{

    require_once(__DIR__ . '/widgets/brand-carousel-widget.php');

    $widgets_manager->register(new \Xplantr_brand_carousel());
}
add_action('elementor/widgets/register', 'register_xlpantr_brand_carousel_widget');
