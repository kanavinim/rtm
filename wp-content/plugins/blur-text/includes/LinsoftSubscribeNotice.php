<?php

/**
 * Created by PhpStorm.
 * User: Linnea
 * Date: 9/17/2015
 * Time: 7:53 AM
 */
class LinsoftSubscribeNotice {


	private static $plugin_name;
	private static $number;
	private static $option_name;

	function  __construct( $plugin_name, $message_number ) {
		self::$plugin_name = $plugin_name;
		self::$number      = $message_number;
		self::$option_name = $plugin_name . '_notice_' . $message_number;
		add_action( 'admin_notices', array( 'LinsoftSubscribeNotice', 'display_notice' ) );
		add_action( 'admin_enqueue_scripts', array( 'LinsoftSubscribeNotice', 'enq_js' ) );
		add_action( 'wp_ajax_blurtext', array( 'LinsoftSubscribeNotice', 'ajax' ) );
	}


	public static function ajax() {
		if ( isset( $_POST['lins_optin'] ) ) {
			if ( $_POST['lins_optin'] == '0' ) {
				// user opted out of emails
				self::mark_as_displayed();
			} else {
				if ( isset( $_POST['lins_email'] ) ) {
					// user has opted into mailing list
					$email_safe = urlencode( $_POST['lins_email'] );
					$url        = "http://www.linsoftware.com/svc/azlc_email.php";
					$res        = wp_remote_post( $url, array(
							'method'      => 'POST',
							'timeout'     => 45,
							'redirection' => 5,
							'httpversion' => '1.0',
							'blocking'    => true,
							'headers'     => array(),
							'body'        => array( 'e' => $email_safe, 's' => 'blur_text' ),
							'cookies'     => array()
						)
					);
					// don't display this notice again
					self::mark_as_displayed();
				}
			}
		}
	}

	public static function display_notice() {
		if ( self::should_display() ) {
			self::print_notice();
		}
	}

	public static function enq_js() {
		wp_enqueue_script( 'LinsoftSubscribe', plugins_url( '/LinsoftSubscribeNotice.js', __FILE__ ),
			array( 'jquery' ), '1.0.0' );

	}

	public static function print_notice() {
		$class = "updated notice is-dismissible blur-text";
		echo "<div class=\"$class\"> <p>Thank you for installing the <b>Blur Text Plugin.</b>  <A
href='http://www.linsoftware.com/'>LinSoftware</A> would
like
		 to send you occasional emails about Wordpress plugins.</p>
		<p> Would you like to get updates at <input type='text' name='lins_email'
		id='ls_email' size='30' value='" . self::get_email() . "'> ?";
		echo "<button type='button' id='lins_submit' style='display:inline-block;margin-left:10px;margin-right:10px'>YES, keep me in
the
loop</button><a
href=''
id='lins_nt'>No
thanks.</a></p></div>";
	}

	public static function get_email() {
		if ( is_multisite() ) {
			return get_site_option( 'admin_email' );
		} else {
			return get_option( 'admin_email' );
		}
	}

	public static function should_display() {

		if ( is_multisite() ) {
			return ! get_site_option( self::$option_name );
		} else {
			return ! get_option( self::$option_name );
		}
	}

	public static function mark_as_displayed() {
		if ( is_multisite() ) {
			update_site_option( self::$option_name, '1' );
		} else {
			update_option( self::$option_name, '1' );
		}
	}

}


new LinsoftSubscribeNotice( 'blur-text', 1 );
