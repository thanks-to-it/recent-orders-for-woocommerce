<?php
/**
 * Recent Orders Widget for WooCommerce - Core Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Recent_Orders_Core' ) ) :

class Alg_WC_Recent_Orders_Core {

	/**
	 * products.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	public $products;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) block
	 * @todo    (v2.0.0) code refactoring: remove hooks: `alg_wc_recent_orders_query_args`, `shortcode_atts_alg_wc_recent_orders`, `alg_wc_recent_orders_row`
	 */
	function __construct() {

		// Shortcode
		add_shortcode( 'alg_wc_recent_orders', array( $this, 'recent_orders' ) );

		// Hooks
		add_filter( 'alg_wc_recent_orders_query_args', array( $this, 'query_args' ), 10, 2 );
		add_filter( 'shortcode_atts_alg_wc_recent_orders', array( $this, 'shortcode_atts' ), 10, 3 );
		add_filter( 'alg_wc_recent_orders_row', array( $this, 'add_order_products' ), 10, 3 );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// Core loaded
		do_action( 'alg_wc_recent_orders_core_loaded' );

	}

	/**
	 * add_order_products.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function add_order_products( $rows, $order, $args ) {
		if ( ! empty( $args['template_order_item_row'] ) ) {
			foreach ( $order->get_items() as $item ) {
				if (
					is_a( $item, 'WC_Order_Item_Product' ) &&
					( $product = $item->get_product() ) &&
					! in_array( $product->get_id(), $this->products )
				) {
					$this->products[] = $product->get_id();
					$placeholders     = $this->get_order_product_placeholders( $product );
					$rows            .= str_replace(
						array_keys( $placeholders ),
						$placeholders,
						$args['template_order_item_row']
					);
				}
			}
		}
		return $rows;
	}

	/**
	 * get_order_product_placeholders.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function get_order_product_placeholders( $product ) {
		return array(
			'%product_id%'              => $product->get_id(),
			'%product_url%'             => $product->get_permalink(),
			'%product_title%'           => $product->get_title(),
			'%product_add_to_cart_url%' => $product->add_to_cart_url(),
			'%product_price%'           => $product->get_price_html(),
			'%product_image%'           => $product->get_image(),
		);
	}

	/**
	 * shortcode_atts.
	 *
	 * @version 1.4.0
	 * @since   1.2.0
	 *
	 * @todo    (dev) add `alg_wc_recent_orders_option` filter (then e.g., we can use function instead of the shortcode in the widget)
	 */
	function shortcode_atts( $out, $default_atts, $atts ) {
		$out['order_statuses']          = ( $atts['order_statuses']          ?? get_option( 'alg_wc_recent_orders_order_statuses', array() ) );
		$out['template_order_item_row'] = ( $atts['template_order_item_row'] ?? get_option( 'alg_wc_recent_orders_template_order_item_row', '' ) );
		return $out;
	}

	/**
	 * query_args.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) check `wc_get_orders()` function for more options, e.g., `type` (`shop_order_refund`), or `payment_method`, etc.
	 */
	function query_args( $query_args, $args ) {
		if ( ! empty( $args['order_statuses'] ) ) {
			$query_args['status'] = (
				is_array( $args['order_statuses'] ) ?
				$args['order_statuses'] :
				array_map( 'trim', explode( ',', $args['order_statuses'] ) )
			);
		}
		return $query_args;
	}

	/**
	 * Register Alg_WC_Recent_Orders_Widget widget.
	 *
	 * @version 1.4.0
	 * @since   1.2.0
	 */
	function register_widget() {
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-recent-orders-widget.php';
		register_widget( 'Alg_WC_Recent_Orders_Widget' );
	}

	/**
	 * get_options.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function get_options() {
		return array(
			'limit'             => get_option( 'alg_wc_recent_orders_limit', 5 ),
			'template_before'   => get_option( 'alg_wc_recent_orders_template_before',
				'<p>' .
					sprintf(
						/* Translators: %s: Link. */
						__( 'Hello %s', 'recent-orders-widget-for-woocommerce' ),
						'<a href="%my_account_url%">%user_display_name%</a>'
					) .
				'</p>' . PHP_EOL .
				'<table>'
			),
			'template_row'      => get_option( 'alg_wc_recent_orders_template_row',
				'<tr><td><a href="%order_url%">#%order_number%</a></td><td>%order_date%</td><td>%order_total%</td><td>%order_again_button%</td></tr>'
			),
			'template_after'    => get_option( 'alg_wc_recent_orders_template_after',
				'</table>' . PHP_EOL .
				'<p><a class="button" href="%orders_url%">' .
					__( 'View more', 'recent-orders-widget-for-woocommerce' ) .
				'</a></p>'
			),
			'template_guest'    => get_option( 'alg_wc_recent_orders_template_guest',
				'<p>' .
					sprintf(
						/* Translators: %1$s: Hyperlink tag start, %2$s: Hyperlink tag end. */
						__( 'Please %1$slog in%2$s to view your recent orders.', 'recent-orders-widget-for-woocommerce' ),
						'<a href="%login_url%">',
						'</a>'
					) .
				'</p>'
			),
			'order_date_format' => get_option( 'alg_wc_recent_orders_order_date_format',
				get_option( 'date_format' )
			),
		);
	}

	/**
	 * get_order_again_button.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) more statuses, i.e., `woocommerce_valid_order_statuses_for_order_again` (now `completed` only)
	 */
	function get_order_again_button( $order ) {
		ob_start();
		woocommerce_order_again_button( $order );
		return ob_get_clean();
	}

	/**
	 * get_order_placeholders.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) check `WC_Order` functions for more placeholders
	 * @todo    (feature) `%order_add_to_cart%`: multiple "add to cart", i.e., similar to `%order_again_button%`
	 * @todo    (feature) `%order_product_count%`
	 */
	function get_order_placeholders( $order, $args ) {
		return array(
			'%order_number%'       => $order->get_order_number(),
			'%order_url%'          => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . 'view-order/' . $order->get_order_number() . '/',
			'%order_date%'         => ( ( $date = $order->get_date_created() ) ? $date->date_i18n( $args['order_date_format'] ) : '' ),
			'%order_total%'        => wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ),
			'%order_again_button%' => $this->get_order_again_button( $order ),
			'%order_status%'       => wc_get_order_status_name( $order->get_status() ),
			'%order_item_count%'   => $order->get_item_count(),
		);
	}

	/**
	 * get_general_placeholders.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) check `WC_User` for more placeholders
	 */
	function get_general_placeholders() {
		$user = wp_get_current_user();
		return array(
			'%user_display_name%' => $user->display_name,
			'%my_account_url%'    => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ),
			'%orders_url%'        => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . 'orders/',
		);
	}

	/**
	 * get_guest_placeholders.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_guest_placeholders() {
		return array(
			'%login_url%' => wp_login_url( get_permalink() ),
		);
	}

	/**
	 * do_shortcode.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function do_shortcode( $content ) {
		remove_shortcode( 'alg_wc_recent_orders' );
		$res = do_shortcode( $content );
		add_shortcode( 'alg_wc_recent_orders', array( $this, 'recent_orders' ) );
		return $res;
	}

	/**
	 * recent_orders.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) transients: to `$atts`
	 */
	function recent_orders( $atts, $content = '' ) {
		$atts = shortcode_atts( $this->get_options(), $atts, 'alg_wc_recent_orders' );
		return wp_kses_post( $this->get_recent_orders( $atts ) );
	}

	/**
	 * get_recent_orders.
	 *
	 * @version 1.3.0
	 * @since   1.2.0
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/wc_get_orders-and-WC_Order_Query
	 *
	 * @todo    (dev) transients: to `$args`
	 * @todo    (dev) transients: delete on new order (just for the order's user)
	 */
	function get_recent_orders( $args = false ) {

		if ( ! $args ) {
			$args = $this->get_options();
		}

		if ( $user_id = get_current_user_id() ) {

			$use_transients = ( 'yes' === get_option( 'alg_wc_recent_orders_use_transients', 'no' ) );
			if ( $use_transients ) {
				$transient_name = 'alg_wc_recent_orders_user_' . $user_id . '_' . md5( serialize( $args ) );
				if ( false !== ( $transient = get_transient( $transient_name ) ) ) {
					return $transient;
				}
			}

			do_action( 'alg_wc_recent_orders_before' );

			$this->products = array();
			$output         = '';
			$rows           = '';
			$orders         = wc_get_orders(
				apply_filters(
					'alg_wc_recent_orders_query_args',
					array(
						'limit'    => $args['limit'],
						'customer' => $user_id,
						'type'     => 'shop_order',
						'orderby'  => 'ID',
						'order'    => 'DESC',
					),
					$args
				)
			);

			foreach ( $orders as $order ) {
				$placeholders  = $this->get_order_placeholders( $order, $args );
				$rows         .= str_replace(
					array_keys( $placeholders ),
					$placeholders,
					$args['template_row']
				);
				$rows          = apply_filters( 'alg_wc_recent_orders_row', $rows, $order, $args );
			}

			if ( ! empty( $rows ) ) {
				$placeholders = $this->get_general_placeholders();
				$before       = str_replace(
					array_keys( $placeholders ),
					$placeholders,
					$args['template_before']
				);
				$after        = str_replace(
					array_keys( $placeholders ),
					$placeholders,
					$args['template_after']
				);
				$output       = $this->do_shortcode( $before . $rows . $after );
			}

			if ( $use_transients ) {
				$transient_expiration = get_option( 'alg_wc_recent_orders_transients_expiration', 3600 );
				set_transient( $transient_name, $output, $transient_expiration );
			}

			return apply_filters( 'alg_wc_recent_orders_output', $output );

		} else {

			$placeholders = $this->get_guest_placeholders();

			return $this->do_shortcode(
				str_replace( array_keys( $placeholders ),
				$placeholders,
				$args['template_guest'] )
			);

		}

	}

}

endif;

return new Alg_WC_Recent_Orders_Core();
