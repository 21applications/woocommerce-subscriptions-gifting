<?php
/**
 * WooCommerce Compatibility functions
 *
 * Functions to take advantage of APIs added to new versions of WooCommerce while maintaining backward compatibility.
 *
 * @author   Prospress
 * @category Core
 * @package  WooCommerce Subscriptions Gifting/Functions
 * @version  1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Check if the installed version of WooCommerce is older than a specified version.
 *
 * @param string $version
 * @return bool
 * @since 1.0.1
 */
function wcsg_is_woocommerce_pre( $version ) {

	if ( ! defined( 'WC_VERSION' ) || version_compare( WC_VERSION, $version, '<' ) ) {
		$woocommerce_is_pre_version = true;
	} else {
		$woocommerce_is_pre_version = false;
	}

	return $woocommerce_is_pre_version;
}

/**
 * Get an object's property value in a version compatible way.
 *
 * @param object $object WC_Order|WC_Subscription|WC_Product etc
 * @param string property name
 * @return mixed
 * @since 1.0.1
 */
function wcsg_get_objects_property( $object, $property ) {
	$value = '';

	switch ( $property ) {
		case 'order':
			if ( is_callable( array( $object, 'get_parent' ) ) ) {
				$value = $object->get_parent();
			} else {
				$value = $object->order;
			}
			break;
		default:
			$function = 'get_' . $property;

			if ( is_callable( array( $object, $function ) ) ) {
				$value = $object->$function();
			} else {
				$value = $object->$property;
			}
			break;
	}

	return $value;

}

/**
 * Get an object's ID in a version compatible way.
 *
 * @param object $object WC_Order|WC_Subscription|WC_Product etc
 * @return int
 * @since 1.0.1
 */
function wcsg_get_objects_id( $object ) {

	if ( method_exists( $object, 'get_id' ) ) {
		$id = $object->get_id();
	} else {
		$id = $object->id;
	}

	return $id;
}
