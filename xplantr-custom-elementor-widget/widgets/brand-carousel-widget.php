<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Xplantr_brand_carousel extends \Elementor\Widget_Base
{


	public function get_style_depends()
	{
		return ['brand-carousel-widget-style', 'brand-carousel-swipercss'];
	}
	public function get_script_depends()
	{
		return ['brand-carousel-swiperjs', 'brand-carousel-widget-script'];
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'xplantr_brand_carousel';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('Xplantr Brand Carousel', 'xplantr-custom-elementor-widget');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-code';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return ['general'];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords()
	{
		return ['Xplantr', 'brand', 'carousel'];
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url()
	{
		return 'https://developers.elementor.com/docs/widgets/';
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Content', 'xplantr-custom-elementor-widget'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// $this->add_control(
		// 	'url',
		// 	[
		// 		'label' => esc_html__('URL to embed', 'xplantr-custom-elementor-widget'),
		// 		'type' => \Elementor\Controls_Manager::TEXT,
		// 		'input_type' => 'url',
		// 		'placeholder' => esc_html__('https://your-link.com', 'xplantr-custom-elementor-widget'),
		// 	],
		// );
		$this->add_control(
			'slideduration',
			[
				'label' => esc_html__('Duration between slides (in ms)', 'xplantr-custom-elementor-widget'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 3000,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		// $html = wp_oembed_get($settings['url']);

		$brands = get_terms(array(
			'taxonomy' => 'product_brand',
			'hide_empty' => false,
		));
		$slides = '';
		foreach ($brands as $brand) {
			$brand_img = wp_get_attachment_url(get_term_meta($brand->term_id, 'thumbnail_id', true));
			if (!empty($brand_img)) {
				$brand_name = $brand->name;
				$brand_link = get_term_link($brand->term_id);
				$slides .= '<div class="swiper-slide"><a href="' . $brand_link . '"><img src="' . $brand_img . '" alt="' . $brand_name . '"></a></div>';
			}
		}
		?>


		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />


		<div class="swiper mySwiper">
			<div class="swiper-wrapper">
				<?php echo $slides; ?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-pagination"></div>
		</div>

		<script>
			document.addEventListener("DOMContentLoaded", function() {
				var swiper = new Swiper(".mySwiper", {
					slidesPerView: 1,
					spaceBetween: 10,
					autoplay: {
						delay: <?php echo $settings['slideduration'];?>,
					},
					loop: true,
					navigation: {
						nextEl: ".swiper-button-next",
						prevEl: ".swiper-button-prev",
					},
					breakpoints: {
						640: {
							slidesPerView: 2,
							spaceBetween: 20,
						},
						768: {
							slidesPerView: 4,
							spaceBetween: 40,
						},
						1024: {
							slidesPerView: 5,
							spaceBetween: 50,
						},
					},
				});
			});
		</script>

<?php
	}
}
