<?php
/**
 * Plugin Name: WooCommerce Subscriptions Gifting
 * Plugin URI:
 * Description: Allow customers to buy products and services with recurring payments for other recipients.
 * Author: Prospress Inc.
 * Author URI: http://prospress.com/
 * Version: 1.0-bleeding
 *
 * Copyright 2015 Prospress, Inc.  (email : freedoms@prospress.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package		WooCommerce Subscriptions Gifting
 * @author		James Allan
 * @since		1.0
 */

require_once( 'includes/class-wcsg-product.php' );

require_once( 'includes/class-wcsg-cart.php' );

require_once( 'includes/class-wcsg-checkout.php' );

require_once( 'includes/class-wcsg-recipient-management.php' );

class WCS_Gifting {

	/**
	 * Setup hooks & filters, when the class is initialised.
	 */
	public static function init() {

		add_action( 'wp_enqueue_scripts', __CLASS__ . '::gifting_scripts' );

	}

	/**
	 * Register/queue frontend scripts.
	 */
	public static function gifting_scripts() {
		wp_register_script( 'woocommerce_subscriptions_gifting', plugins_url( '/js/wcs-gifting.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'woocommerce_subscriptions_gifting' );
	}

	/**
	 * Returns gifting ui html elements assigning values and styles specified by whether $email is provided
	 * @param int|id The id attribute used to differeniate items on cart and checkout pages
	 */
	public static function generate_gifting_html( $id, $email ) {
		$email_field_args = array(
			'return'      => true,
			'label'       => 'Recipient\'s Email Address:',
			'placeholder' => 'recipient@example.com',
			'class'       => array( 'woocommerce_subscriptions_gifting_recipient_email' ),
		);

		if ( ! empty( $email ) && ( self::recipient_email_is_current_user( $email ) || ! is_email( $email ) ) ) {
			array_push( $email_field_args['class'], 'woocommerce-invalid' );
		}

		$email_field = woocommerce_form_field( 'recipient_email[' . $id . ']', $email_field_args , $email );

		if ( empty( $email ) ) {
			$email_field = str_replace( '<p', '<p style ="display: none"', $email_field );
		}

		$email_field = str_replace( 'type="text"', 'type="email"', $email_field );

		return '<fieldset>'
			 . '<input type="checkbox" id="gifting_' . esc_attr( $id ) . '_option" class="woocommerce_subscription_gifting_checkbox" value="gift" ' . ( ( empty( $email ) ) ? '' : 'checked' ) . ' >' . esc_html__( 'This is a gift', 'woocommerce_subscriptions_gifting' ) . '<br>'
			 . $email_field
			 . '</fieldset>';
	}

	public static function recipient_email_is_current_user( $recipient_email ){
		$current_user_email = wp_get_current_user()->user_email;
		return $current_user_email == $recipient_email;
	}
}
WCS_Gifting::init();
