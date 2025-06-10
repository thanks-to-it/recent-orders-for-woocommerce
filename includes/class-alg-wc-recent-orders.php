<?php
/**
 * Recent Orders Widget for WooCommerce - Main Class
 *
 * @version 2.0.0
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
	 * core.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	public $core;

	/**
	 * @var   Alg_WC_Recent_Orders The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Recent_Orders Instance.
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
	 * @version 2.0.0
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

		// Declare compatibility with custom order tables for WooCommerce
		add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

		// Pro
		if ( 'recent-orders-for-woocommerce-pro.php' === basename( ALG_WC_RECENT_ORDERS_FILE ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'pro/class-alg-wc-recent-orders-pro.php';
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
		load_plugin_textdomain(
			'recent-orders-widget-for-woocommerce',
			false,
			dirname( plugin_basename( ALG_WC_RECENT_ORDERS_FILE ) ) . '/langs/'
		);
	}

	/**
	 * wc_declare_compatibility.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 *
	 * @see     https://developer.woocommerce.com/docs/features/high-performance-order-storage/recipe-book/
	 */
	function wc_declare_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			$files = ( defined( 'ALG_WC_RECENT_ORDERS_FILE_FREE' ) ?
				array( ALG_WC_RECENT_ORDERS_FILE, ALG_WC_RECENT_ORDERS_FILE_FREE ) :
				array( ALG_WC_RECENT_ORDERS_FILE )
			);
			foreach ( $files as $file ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $file, true );
			}
		}
	}

	/**
	 * includes.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-recent-orders-core.php';
	}

	/**
	 * admin.
	 *
	 * @version 2.0.0
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
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();

		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_recent_orders' ) . '">' .
			esc_html__( 'Settings', 'recent-orders-widget-for-woocommerce' ) .
		'</a>';

		return array_merge( $custom_links, $links );
	}

	/**
	 * add_woocommerce_settings_tab.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once plugin_dir_path( __FILE__ ) . 'settings/class-alg-wc-recent-orders-settings.php';
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
