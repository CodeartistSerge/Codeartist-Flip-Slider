<?php
/*
Plugin Name: Code Artist Flip Slider
Plugin URI: http://en.codeartist.ru
Description: Add a VC components and shortcode with flip slider.
Shortcode syntax is [ca_flip_slider ca_flip_imgs="<coma separated ids of the images>" ca_flip_min_height="500px"]
Version: 0.0.1
Author: Serge Degtiarev
Author URI: http://en.codeartist.ru
** based on herrethan's flipcarousel
** https://github.com/herrethan/flipcarousel
*/
add_action( 'wp_enqueue_scripts', 'ca_flip_slider_enqueue' );
function ca_flip_slider_enqueue() {
	wp_enqueue_script(
		'html5shiv',
		plugin_dir_url( __FILE__ ).'js/html5shiv.js',
		array( 'jquery' )
	);
	wp_enqueue_script(
		'flipcarousel',
		plugin_dir_url( __FILE__ ).'js/flip-carousel.js',
		array( 'jquery', 'html5shiv' ),
		time()
	);

	wp_enqueue_style(
		'foundation-icons',
		plugin_dir_url( __FILE__ ).'css/foundation-icons.css'
	);

	wp_enqueue_style(
		'flip-carousel',
		plugin_dir_url( __FILE__ ).'css/flip-carousel.css'
	);
}	 

add_shortcode( 'ca_flip_slider', 'ca_flip_slider');
function ca_flip_slider( $atts, $content) {
	$atts = shortcode_atts( array(
		'ca_flip_imgs' => null,
		'ca_flip_min_height' => '500px',
	), $atts );

	if($atts['ca_flip_imgs'] != null) {
		$atts['ca_flip_imgs'] = explode(',', $atts['ca_flip_imgs']);
		foreach($atts['ca_flip_imgs'] as $img) {
			$r .= '<span class="ca_flip_slider_test_class">'. wp_get_attachment_image($img, 'full') .'</span>';
		}		
	}
	return $r.'
	<script>
		jQuery(".ca_flip_slider_test_class").flipcarousel({
			loader : true,
			itemsperpage: 1,
			pagination : true,
			min_height : "'.$atts["ca_flip_min_height"].'"
		});
	</script>';
}

add_action( 'vc_before_init', 'ca_add_extra_component_to_vc' );
function ca_add_extra_component_to_vc() {
	vc_map( array(
		"name" => __("CA Flip slider"),
		"base" => "ca_flip_slider",
		"category" => __('Content'),
		"icon" => "fa fa-picture-o ca_vc_extend_icon",
		"admin_enqueue_css" => plugin_dir_url( __FILE__ )."css/style.css",
		"params" => array(
			array(
				"type" => "attach_images",
				"heading" => __("Add some images"),
				"param_name" => "ca_flip_imgs",
				"description" => __("it is better if all slides will be the same width & height")
			),
			array(
				"type" => "textfield",
				"heading" => __("Min height of the slider"),
				"param_name" => "ca_flip_min_height",
				"default" => '500px',
				"description" => __("The default is 500px but you can change it to whatever you like.")
			)
		),
	) );
}
?>