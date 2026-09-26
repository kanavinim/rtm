<?php

namespace Ipol\OzonPay\WordPress;

class TemplateBuilder {
	/**
	 * @param string $funcionname
	 *
	 * @return false|string
	 *
	 * for load html content in WP plugin settings page
	 */
	static public function render( $templatename = '', $templateVars = [] ) {
		if ( $templatename == '' ) {
			return '';
		}
		$fpath = OzonPayPlugin::getPluginDir() . DIRECTORY_SEPARATOR . 'assets'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR . $templatename . '.tpl';
		if ( ! file_exists( $fpath ) && ! is_readable( $fpath ) ) {
			return '';
		}

		return file_get_contents( $fpath );
	}

	/**
	 * @param $number
	 * @param $titles
	 *
	 * @return string
	 */
	static public function declination( $number, $titles ) {
		$cases = array( 2, 0, 1, 1, 1, 2 );

		return $number . " " . $titles[ ( $number % 100 > 4 && $number % 100 < 20 ) ? 2 : $cases[ min( $number % 10, 5 ) ] ];
	}
}