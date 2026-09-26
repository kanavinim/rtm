<?php
/*
 * Plugin Name: Blur Text
 * Plugin URI: http://www.linsoftware.com/blur-text/
 * Description: Blur Text with a shortcode.  Unblur with a click or hover.
 * Version: 1.0.0
 * Author: Linnea Wilhelm, Lin Software
 * Author URI: http://www.linsoftware.com
 */

include_once('includes/LinsoftSubscribeNotice.php');


// adds the following css classes, depending on the atts
// blur
// blur_hover
// blur_click
// blur_nofallback

function blur_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'toggle' => 'none',
		'fallback' => 'background_color',
		'color' => 'black',
	), $atts );

	$class_names = 'blur';
	if(strcmp($a['toggle'], 'hover')== 0) {
		$class_names .= '_hover';
	} elseif (strcmp($a['toggle'], 'click')== 0) {
		$class_names .= '_click';
	}

	if(strcmp($a['fallback'], 'none')== 0) {
		$class_names .= ' blur_nofallback';
	} elseif(strcmp($a['fallback'], 'hide')== 0) {
		$class_names .= ' blur_hide';
	}


	return '<span class="' . $class_names . '" style="color:'. $a['color'] .'">' . $content . '</span>';
}

add_shortcode( 'blur', 'blur_shortcode' );


function blur_scripts() {
	wp_enqueue_script( 'blur_linsoft', plugins_url( '/blur-text.js', __FILE__ ) ,
		array( 'jquery' ), '1.0.0' );
}

add_action( 'wp_enqueue_scripts', 'blur_scripts' );