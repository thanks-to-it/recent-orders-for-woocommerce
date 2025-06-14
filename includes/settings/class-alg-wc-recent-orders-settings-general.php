<?php
/**
 * Recent Orders Widget for WooCommerce - General Section Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Recent_Orders_Settings_General' ) ) :

class Alg_WC_Recent_Orders_Settings_General extends Alg_WC_Recent_Orders_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'recent-orders-widget-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_placeholders_text.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function get_placeholders_text( $placeholders ) {
		return sprintf(
			/* Translators: %s: Placeholder list. */
			__( 'Available placeholders: %s.', 'recent-orders-widget-for-woocommerce' ),
			'<code>' . implode( '</code>, <code>', $placeholders ) . '</code>'
		);
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (desc) `alg_wc_recent_orders_use_transients`
	 * @todo    (desc) `order_statuses`?
	 */
	function get_settings() {
		return array(

			array(
				'title'             => __( 'Recent Orders Options', 'recent-orders-widget-for-woocommerce' ),
				'desc'              => (
					sprintf(
						/* Translators: %1$s: Widget link, %2$s: Shortcode name. */
						__( 'Recent orders can be outputted with the %1$s widget, or with the %2$s shortcode.', 'recent-orders-widget-for-woocommerce' ),
						'<a href="' . admin_url( 'widgets.php' ) . '">' . __( 'Recent Orders', 'recent-orders-widget-for-woocommerce' ) . '</a>',
						'<code>[alg_wc_recent_orders]</code>'
					) . ' ' .
					sprintf(
						/* Translators: %s: Attribute list. */
						__( 'You can also optionally override the default option values with %s shortcode attributes.', 'recent-orders-widget-for-woocommerce' ),
						'<code>' . implode( '</code>, <code>', array(
							'limit',
							'template_before',
							'template_row',
							'template_after',
							'template_guest',
							'order_date_format',
							'order_statuses',
						) ) . '</code>'
					)
				),
				'type'              => 'title',
				'id'                => 'alg_wc_recent_orders_plugin_options',
			),
			array(
				'title'             => __( 'Limit', 'recent-orders-widget-for-woocommerce' ),
				'desc_tip'          => __( 'Number of recent orders to display.', 'recent-orders-widget-for-woocommerce' ),
				'id'                => 'alg_wc_recent_orders_limit',
				'default'           => 5,
				'type'              => 'number',
				'custom_attributes' => array( 'min' => -1 ),
			),
			array(
				'title'             => __( 'Before', 'recent-orders-widget-for-woocommerce' ),
				'desc_tip'          => (
					__( 'Outputted before the recent orders results.', 'recent-orders-widget-for-woocommerce' ) . ' ' .
					__( 'You can use shortcodes here.', 'recent-orders-widget-for-woocommerce' )
				),
				'desc'              => $this->get_placeholders_text( array(
					'%my_account_url%',
					'%orders_url%',
					'%user_display_name%',
				) ),
				'id'                => 'alg_wc_recent_orders_template_before',
				'default'           => '<p>' . sprintf(
					/* Translators: %s: Link. */
					__( 'Hello %s', 'recent-orders-widget-for-woocommerce' ),
					'<a href="%my_account_url%">%user_display_name%</a>'
				) . '</p>' . PHP_EOL . '<table>',
				'type'              => 'textarea',
				'css'               => 'width:100%;height:75px;',
			),
			array(
				'title'             => __( 'Each order', 'recent-orders-widget-for-woocommerce' ),
				'desc_tip'          => (
					__( 'Outputted for each result, i.e., for each recent order.', 'recent-orders-widget-for-woocommerce' ) . ' ' .
					__( 'You can use shortcodes here.', 'recent-orders-widget-for-woocommerce' )
				),
				'desc'              => $this->get_placeholders_text( array(
					'%order_number%',
					'%order_url%',
					'%order_date%',
					'%order_total%',
					'%order_again_button%',
					'%order_status%',
					'%order_item_count%',
				) ),
				'id'                => 'alg_wc_recent_orders_template_row',
				'default'           => '<tr><td><a href="%order_url%">#%order_number%</a></td><td>%order_date%</td><td>%order_total%</td><td>%order_again_button%</td></tr>',
				'type'              => 'textarea',
				'css'               => 'width:100%;height:75px;',
			),
			array(
				'title'             => __( 'Each order product', 'recent-orders-widget-for-woocommerce' ),
				'desc_tip'          => (
					__( 'Outputted for each order item, i.e., for each order product.', 'recent-orders-widget-for-woocommerce' ) . ' ' .
					__( 'Duplicated products are skipped.', 'recent-orders-widget-for-woocommerce' ) . ' ' .
					__( 'You can use shortcodes here.', 'recent-orders-widget-for-woocommerce' )
				),
				'desc'              => $this->get_placeholders_text( array(
					'%product_id%',
					'%product_url%',
					'%product_title%',
					'%product_add_to_cart_url%',
					'%product_price%',
					'%product_image%',
				) ),
				'id'                => 'alg_wc_recent_orders_template_order_item_row',
				'default'           => '',
				'type'              => 'textarea',
				'css'               => 'width:100%;height:75px;',
			),
			array(
				'title'             => __( 'After', 'recent-orders-widget-for-woocommerce' ),
				'desc_tip'          => (
					__( 'Outputted after the recent orders results.', 'recent-orders-widget-for-woocommerce' ) . ' ' .
					__( 'You can use shortcodes here.', 'recent-orders-widget-for-woocommerce' )
				),
				'desc'              => $this->get_placeholders_text( array(
					'%my_account_url%',
					'%orders_url%',
					'%user_display_name%',
				) ),
				'id'                => 'alg_wc_recent_orders_template_after',
				'default'           => '</table>' . PHP_EOL .
					'<p><a class="button" href="%orders_url%">' .
						__( 'View more', 'recent-orders-widget-for-woocommerce' ) .
					'</a></p>',
				'type'              => 'textarea',
				'css'               => 'width:100%;height:75px;',
			),
			array(
				'title'             => __( 'Guest', 'recent-orders-widget-for-woocommerce' ),
				'desc_tip'          =>
					__( 'Outputted for non-logged users.', 'recent-orders-widget-for-woocommerce' ) . ' ' .
					__( 'You can use shortcodes here.', 'recent-orders-widget-for-woocommerce' ),
				'desc'              => $this->get_placeholders_text( array( '%login_url%' ) ),
				'id'                => 'alg_wc_recent_orders_template_guest',
				'default'           => '<p>' .
					sprintf(
						/* Translators: %1$s: Hyperlink tag start, %2$s: Hyperlink tag end. */
						__( 'Please %1$slog in%2$s to view your recent orders.', 'recent-orders-widget-for-woocommerce' ),
						'<a href="%login_url%">',
						'</a>'
					) .
				'</p>',
				'type'              => 'textarea',
				'css'               => 'width:100%;height:75px;',
			),
			array(
				'title'             => __( 'Order date format', 'recent-orders-widget-for-woocommerce' ),
				'desc'              => (
					sprintf(
						/* Translators: %s: Placeholder name. */
						__( 'For the %s placeholder.', 'recent-orders-widget-for-woocommerce' ),
						'<code>%order_date%</code>'
					) . ' ' .
					sprintf(
						/* Translators: %s: Page link. */
						__( 'Check PHP %s function page for the available date formats.', 'recent-orders-widget-for-woocommerce' ),
						'<code><a target="_blank" href="https://www.php.net/manual/en/function.date.php">date()</a></code>'
					)
				),
				'id'                => 'alg_wc_recent_orders_order_date_format',
				'default'           => get_option( 'date_format' ),
				'type'              => 'text',
			),
			array(
				'title'             => __( 'Order statuses', 'recent-orders-widget-for-woocommerce' ),
				'desc_tip'          => __( 'Leave empty to display all order statuses.', 'recent-orders-widget-for-woocommerce' ),
				'id'                => 'alg_wc_recent_orders_order_statuses',
				'default'           => array(),
				'type'              => 'multiselect',
				'class'             => 'chosen_select',
				'options'           => wc_get_order_statuses(),
			),
			array(
				'type'              => 'sectionend',
				'id'                => 'alg_wc_recent_orders_plugin_options',
			),

			array(
				'title'             => __( 'Advanced Options', 'recent-orders-widget-for-woocommerce' ),
				'type'              => 'title',
				'id'                => 'alg_wc_recent_orders_advanced_options',
			),
			array(
				'title'             => __( 'Transients', 'recent-orders-widget-for-woocommerce' ),
				'desc'              => __( 'Enable', 'recent-orders-widget-for-woocommerce' ),
				'id'                => 'alg_wc_recent_orders_use_transients',
				'default'           => 'no',
				'type'              => 'checkbox',
			),
			array(
				'desc'              => __( 'Time until expiration in seconds', 'recent-orders-widget-for-woocommerce' ),
				'id'                => 'alg_wc_recent_orders_transients_expiration',
				'default'           => 3600,
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 1 ),
			),
			array(
				'type'              => 'sectionend',
				'id'                => 'alg_wc_recent_orders_advanced_options',
			),

		);
	}

}

endif;

return new Alg_WC_Recent_Orders_Settings_General();
