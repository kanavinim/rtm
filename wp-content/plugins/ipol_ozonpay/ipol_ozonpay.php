<?php
/*
 * Plugin Name:  Payment gateway - Acquiring OZON Pay for WooCommerce
 * Plugin URI:  https://wordpress.org/plugins/ipol_ozonpay/
 * Description: Payment gateway - Acquiring OZON Pay for WooCommerce
 * Version: 1.1.0
 * Author: Ipol
 * Author URI: https://www.ipol.ru/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Network: true
 */

spl_autoload_register(function($className) {
	if (strpos($className, 'Ipol\OzonPay') === 0) {
		$classPath = implode(DIRECTORY_SEPARATOR, explode('\\', substr($className,13)));
		$filePath = __DIR__.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.$classPath.'.php';
		if (is_readable($filePath) && file_exists($filePath))
			require_once $filePath;
	}
});

if(!defined('ABSPATH') || !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
	exit;

$wpPluginDir = WP_PLUGIN_DIR;

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

(\Ipol\OzonPay\WordPress\OzonPayPlugin::getInstance())->run();

