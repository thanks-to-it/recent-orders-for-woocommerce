<?php
/**
 * Recent Orders Widget for WooCommerce - Core Class
 *
 * @version 1.3.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Recent_Orders_Core' ) ) :

class Alg_WC_Recent_Orders_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @todo    [maybe] (feature) block
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_recent_orders_plugin_enabled', 'yes' ) ) {
			add_shortcode( 'alg_wc_recent_orders', array( $this, 'recent_orders' ) );
			add_action( 'widgets_init', array( $this, 'register_widget' ) );
		}
		// Core loaded
		do_action( 'alg_wc_recent_orders_core_loaded' );
	}

	/**
	 * register Alg_WC_Recent_Orders_Widget widget.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function register_widget() {
		require_once( 'class-alg-wc-recent-orders-widget.php' );
		register_widget( 'Alg_WC_Recent_Orders_Widget' );
	}

	/**
	 * get_options.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_options() {
		return array(
			'limit'             => get_option( 'alg_wc_recent_orders_limit', 5 ),
			'template_before'   => get_option( 'alg_wc_recent_orders_template_before',
				'<p>' . sprintf( __( 'Hello %s', 'recent-orders-widget-for-woocommerce' ), '<a href="%my_account_url%">%user_display_name%</a>' ) . '</p>' . PHP_EOL . '<table>' ),
			'template_row'      => get_option( 'alg_wc_recent_orders_template_row',
				'<tr><td><a href="%order_url%">#%order_number%</a></td><td>%order_date%</td><td>%order_total%</td><td>%order_again_button%</td></tr>' ),
			'template_after'    => get_option( 'alg_wc_recent_orders_template_after',
				'</table>' . PHP_EOL . '<p><a class="button" href="%orders_url%">' . __( 'View more', 'recent-orders-widget-for-woocommerce' ) . '</a></p>' ),
			'template_guest'    => get_option( 'alg_wc_recent_orders_template_guest',
				'<p>' . sprintf( __( 'Please %slog in%s to view your recent orders.', 'recent-orders-widget-for-woocommerce' ), '<a href="%login_url%">', '</a>' ) . '</p>' ),
			'order_date_format' => get_option( 'alg_wc_recent_orders_order_date_format', get_option( 'date_format' ) ),
		);
	}

	/**
	 * get_order_again_button.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    [later] (dev) more statuses, i.e. `woocommerce_valid_order_statuses_for_order_again` (now `completed` only)
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
	 * @todo    [next] (feature) check `WC_Order` functions for more placeholders
	 * @todo    [maybe] (feature) `%order_add_to_cart%`: multiple "add to cart", i.e. similar to `%order_again_button%`
	 * @todo    [maybe] (feature) `%order_product_count%`
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
	 * @todo    [next] (feature) check `WC_User` for more placeholders
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
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @todo    [next] (dev) transients: to `$atts`
	 */
	function recent_orders( $atts, $content = '' ) {
		$atts = shortcode_atts( $this->get_options(), $atts, 'alg_wc_recent_orders' );
		return $this->get_recent_orders( $atts );
	}

	/**
	 * get_recent_orders.
	 *
	 * @version 1.3.0
	 * @since   1.2.0
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/wc_get_orders-and-WC_Order_Query
	 *
	 * @todo    [next] (dev) transients: to `$args`
	 * @todo    [next] (dev) transients: delete on new order (just for the order's user)
	 */
	function get_recent_orders( $args = false ) {
		if ( ! $args ) {
			$args = $this->get_options();
		}
		if ( $user_id = get_current_user_id() ) {
			$use_transients = ( 'yes' === get_option( 'alg_wc_recent_orders_use_transients', 'no' ) );
			if ( $use_transients ) {
				if ( false !== ( $transient = get_transient( 'alg_wc_recent_orders_user_' . $user_id . '_' . md5( serialize( $args ) ) ) ) ) {
					return $transient;
				}
			}
			do_action( 'alg_wc_recent_orders_before' );
			$output = '';
			$rows   = '';
			$orders = wc_get_orders(
				apply_filters( 'alg_wc_recent_orders_query_args', array(
					'limit'    => $args['limit'],
					'customer' => $user_id,
					'type'     => 'shop_order',
					'orderby'  => 'ID',
					'order'    => 'DESC',
				), $args )
			);
			foreach ( $orders as $order ) {
				$placeholders  = $this->get_order_placeholders( $order, $args );
				$rows         .= str_replace( array_keys( $placeholders ), $placeholders, $args['template_row'] );
				$rows          = apply_filters( 'alg_wc_recent_orders_row', $rows, $order, $args );
			}
			if ( ! empty( $rows ) ) {
				$placeholders = $this->get_general_placeholders();
				$before       = str_replace( array_keys( $placeholders ), $placeholders, $args['template_before'] );
				$after        = str_replace( array_keys( $placeholders ), $placeholders, $args['template_after'] );
				$output       = $this->do_shortcode( $before . $rows . $after );
			}
			if ( $use_transients ) {
				$transient_expiration = get_option( 'alg_wc_recent_orders_transients_expiration', 3600 );
				set_transient( 'alg_wc_recent_orders_user_' . $user_id, $output, $transient_expiration );
			}
			return apply_filters( 'alg_wc_recent_orders_output', $output );
		} else {
			$placeholders = $this->get_guest_placeholders();
			return $this->do_shortcode( str_replace( array_keys( $placeholders ), $placeholders, $args['template_guest'] ) );
		}
	}

}

endif;

return new Alg_WC_Recent_Orders_Core();
