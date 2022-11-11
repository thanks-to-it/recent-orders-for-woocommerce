<?php
/**
 * Recent Orders Widget for WooCommerce - Main Class
 *
 * @version 1.2.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Recent_Orders' ) ) :

final class Alg_WC_Recent_Orders {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WC_RECENT_ORDERS_VERSION;

	/**
	 * @var   Alg_WC_Recent_Orders The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Recent_Orders Instance
	 *
	 * Ensures only one instance of Alg_WC_Recent_Orders is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WC_Recent_Orders - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Recent_Orders Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Pro
		if ( 'recent-orders-for-woocommerce-pro.php' === basename( ALG_WC_RECENT_ORDERS_FILE ) ) {
			require_once( 'pro/class-alg-wc-recent-orders-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function localize() {
		load_plugin_textdomain( 'recent-orders-widget-for-woocommerce', false, dirname( plugin_basename( ALG_WC_RECENT_ORDERS_FILE ) ) . '/langs/' );
	}

	/**
	 * includes.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once( 'class-alg-wc-recent-orders-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WC_RECENT_ORDERS_FILE ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		// Version update
		if ( get_option( 'alg_wc_recent_orders_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * action_links.
	 *
	 * @version 1.2.1
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_recent_orders' ) . '">' . esc_html__( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'recent-orders-for-woocommerce.php' === basename( ALG_WC_RECENT_ORDERS_FILE ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/recent-orders-for-woocommerce/">' . 'Go Pro' . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * add_woocommerce_settings_tab.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'settings/class-alg-wc-recent-orders-settings.php' );
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function version_updated() {
		update_option( 'alg_wc_recent_orders_version', $this->version );
	}

	/**
	 * plugin_url.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WC_RECENT_ORDERS_FILE ) );
	}

	/**
	 * plugin_path.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WC_RECENT_ORDERS_FILE ) );
	}

}

endif;
