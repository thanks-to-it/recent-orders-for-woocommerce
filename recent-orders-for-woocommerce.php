<?php
/*
Plugin Name: Customer Recent Orders History for WooCommerce
Plugin URI: https://wordpress.org/plugins/recent-orders-widget-for-woocommerce/
Description: Display the customer's recent order list on the frontend in WooCommerce.
Version: 2.0.0
Author: Algoritmika Ltd
Author URI: https://profiles.wordpress.org/algoritmika/
Requires at least: 5.0
Text Domain: recent-orders-widget-for-woocommerce
Domain Path: /langs
WC tested up to: 9.8
Requires Plugins: woocommerce
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

if ( 'recent-orders-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.4.0
	 * @since   1.1.0
	 */
	$plugin = 'recent-orders-for-woocommerce-pro/recent-orders-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		defined( 'ALG_WC_RECENT_ORDERS_FILE_FREE' ) || define( 'ALG_WC_RECENT_ORDERS_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_RECENT_ORDERS_VERSION' ) || define( 'ALG_WC_RECENT_ORDERS_VERSION', '2.0.0' );

defined( 'ALG_WC_RECENT_ORDERS_FILE' ) || define( 'ALG_WC_RECENT_ORDERS_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-recent-orders.php';

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
