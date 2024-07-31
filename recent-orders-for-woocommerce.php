<?php
/*
Plugin Name: Recent Orders Widget for WooCommerce
Plugin URI: https://wpfactory.com/item/recent-orders-for-woocommerce/
Description: Display current customer's recent orders list on frontend in WooCommerce.
Version: 1.3.4
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: recent-orders-widget-for-woocommerce
Domain Path: /langs
WC tested up to: 9.1
Requires Plugins: woocommerce
*/

defined( 'ABSPATH' ) || exit;

if ( 'recent-orders-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	$plugin = 'recent-orders-for-woocommerce-pro/recent-orders-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

defined( 'ALG_WC_RECENT_ORDERS_VERSION' ) || define( 'ALG_WC_RECENT_ORDERS_VERSION', '1.3.4' );

defined( 'ALG_WC_RECENT_ORDERS_FILE' ) || define( 'ALG_WC_RECENT_ORDERS_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-recent-orders.php' );

if ( ! function_exists( 'alg_wc_recent_orders' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Recent_Orders to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_recent_orders() {
		return Alg_WC_Recent_Orders::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_recent_orders' );
