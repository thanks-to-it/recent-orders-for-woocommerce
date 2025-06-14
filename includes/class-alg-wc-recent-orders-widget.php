<?php
/**
 * Recent Orders Widget for WooCommerce - Widget Class
 *
 * @version 2.0.0
 * @since   1.2.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Recent_Orders_Widget' ) ) :

class Alg_WC_Recent_Orders_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'alg_wc_recent_orders_widget',
			'description' => __( 'Display customer\'s recent orders list.', 'recent-orders-widget-for-woocommerce' ),
		);
		parent::__construct(
			$widget_ops['classname'],
			__( 'Recent Orders', 'recent-orders-widget-for-woocommerce' ),
			$widget_ops
		);
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 *
	 * @param   array $args
	 * @param   array $instance
	 */
	function widget( $args, $instance ) {
		echo wp_kses_post(
			$args['before_widget'] .
				(
					! empty( $instance['title'] ) ?
					(
						$args['before_title'] .
							apply_filters( 'widget_title', $instance['title'] ) .
						$args['after_title']
					) :
					''
				) .
				do_shortcode( '[alg_wc_recent_orders]' ) .
			$args['after_widget']
		);
	}

	/**
	 * get_widget_option_fields.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (dev) add options
	 */
	function get_widget_option_fields() {
		return array(
			'title' => array(
				'title'   => __( 'Title', 'recent-orders-widget-for-woocommerce' ),
				'default' => '',
			),
		);
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 *
	 * @param   array $instance The widget options
	 */
	function form( $instance ) {
		$html = '';
		foreach ( $this->get_widget_option_fields() as $id => $widget_option_field ) {
			$value = (
				! empty( $instance[ $id ] ) ?
				$instance[ $id ] :
				$widget_option_field['default']
			);
			$label = (
				isset( $widget_option_field['title'] ) ?
				sprintf(
					'<label for="%s">%s</label>',
					esc_attr( $this->get_field_id( $id ) ),
					esc_html( $widget_option_field['title'] )
				) :
				''
			);
			if ( ! isset( $widget_option_field['type'] ) ) {
				$widget_option_field['type'] = 'text';
			}
			if ( ! isset( $widget_option_field['class'] ) ) {
				$widget_option_field['class'] = 'widefat';
			}
			$desc        = (
				isset( $widget_option_field['desc'] ) ?
				'<br><em>' . wp_kses_post( $widget_option_field['desc'] ) . '</em>' :
				''
			);
			$custom_atts = (
				isset( $widget_option_field['custom_atts'] ) ?
				' ' . $widget_option_field['custom_atts'] :
				''
			);
			switch ( $widget_option_field['type'] ) {
				case 'select':
					$options = '';
					foreach ( $widget_option_field['options'] as $option_id => $option_title ) {
						$options .= sprintf(
							'<option value="%s"%s>%s</option>',
							$option_id,
							selected( $option_id, $value, false ),
							esc_html( $option_title )
						);
					}
					$field = sprintf(
						'<select class="' . $widget_option_field['class'] . '" id="%s" name="%s"%s>%s</select>',
						esc_attr( $this->get_field_id( $id ) ),
						esc_attr( $this->get_field_name( $id ) ),
						$custom_atts,
						$options
					);
					break;
				default: // e.g., 'text'
					$field = sprintf(
						'<input class="%s" id="%s" name="%s" type="%s" value="%s"%s>',
						$widget_option_field['class'],
						esc_attr( $this->get_field_id( $id ) ),
						esc_attr( $this->get_field_name( $id ) ),
						$widget_option_field['type'],
						esc_attr( $value ),
						$custom_atts
					);
			}
			$html .= '<p>' . $label . $field . $desc . '</p>';
		}
		$html .= '<p>' . sprintf(
			/* Translators: %s: Settings page link. */
			esc_html__( 'Set options in %s.', 'recent-orders-widget-for-woocommerce' ),
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_recent_orders' ) . '" target="_blank">' .
				esc_html__( 'WooCommerce > Settings > Recent Orders', 'recent-orders-widget-for-woocommerce' ) .
			'</a>'
		) . '</p>';
		echo wp_kses(
			$html,
			array_merge(
				wp_kses_allowed_html( 'post' ),
				array(
					'input' => array(
						'class' => true,
						'id'    => true,
						'name'  => true,
						'type'  => true,
						'value' => true,
					)
				)
			)
		);
	}

	/**
	 * Processing widget options on save.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @param   array $new_instance the new options
	 * @param   array $old_instance the previous options
	 */
	function update( $new_instance, $old_instance ) {
		foreach ( $this->get_widget_option_fields() as $id => $widget_option_field ) {
			if ( empty( $new_instance[ $id ] ) ) {
				$new_instance[ $id ] = $widget_option_field['default'];
			}
		}
		return $new_instance;
	}

}

endif;
