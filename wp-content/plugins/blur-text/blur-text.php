<?php
/*
 * Plugin Name: Blur Text
 * Plugin URI: https://www.linsoftware.com/blur-text/
 * Description: Blur Text with a shortcode.  Unblur with a click or hover.
 * Version: 2.0.0
 * Author: Linnea Huxford, LinSoftware
 * Author URI: https://www.linsoftware.com
 */

/**
 * Adds a shortcode for blurring text.
 *
 * Supported Attributes:
 * blur
 * blur_hover
 * blur_click
 *
 * @param $atts array Attributes for the shortcode.
 * @param $content string The content inside the shortcode.
 *
 * @return string The content with the shortcode applied.
 */
function blur_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'toggle' => 'none',
		'fallback' => 'background_color',
		'color' => 'black',
	), $atts );

	$color = esc_attr( $a['color'] );
	$class_names = [];
	if(strcmp($a['toggle'], 'hover')== 0) {
		$class_names[] = 'blur_hover';
	} else {
		$class_names[] = 'blur_click';
	}

	if(strcmp($a['fallback'], 'none')== 0) {
		$class_names[] = 'blur_nofallback';
	} elseif(strcmp($a['fallback'], 'hide')== 0) {
		$class_names[] = 'blur_hide';
	}

	$class_names = join( ' ', array_map( 'sanitize_html_class', $class_names ) );

	return '<span class="' . $class_names . '" style="color:'. $color .'">' . $content . '</span>';
}

add_shortcode( 'blur', 'blur_shortcode' );


function blur_scripts() {
	wp_enqueue_script( 'blur_linsoft', plugins_url( '/blur-text.js', __FILE__ ) ,
		array( 'jquery' ), '2.0.0' );
}

add_action( 'wp_enqueue_scripts', 'blur_scripts' );
