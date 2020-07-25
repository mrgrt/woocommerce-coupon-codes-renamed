<?php
/**
 * Plugin Name: WooCommerce Coupon Codes Renamed
 * Plugin URI: https://github.com/mrgrt/woocommerce-coupon-codes-renamed
 * Description: Rename coupon codes in woocommerce
 * Version: 1.1
 * Tested up to: 4.2.2
 * Author: Grahame Thomson
 * Author URI: https://github.com/mrgrt
 */

add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_cart', 10, 3 );
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_cart', 10, 3 );
add_filter('woocommerce_coupon_error', 'rename_coupon_label', 10, 3);
add_filter('woocommerce_coupon_message', 'rename_coupon_label', 10, 3);
add_filter('woocommerce_cart_totals_coupon_label', 'rename_coupon_label',10, 1);
add_filter( 'woocommerce_checkout_coupon_message', 'woocommerce_rename_coupon_message_on_checkout' );

add_filter( 'woocommerce_get_sections_advanced', 'coupon_code_add_section' );
add_filter( 'woocommerce_get_settings_advanced', 'coupon_code_all_settings', 10, 2 );


function woocommerce_rename_coupon_field_on_cart( $translated_text, $text, $text_domain ) {

	$code_text  = get_option( 'coupon_code_text' );

	// bail if not modifying frontend woocommerce text
	if ( is_admin() || 'woocommerce' !== $text_domain) {
		return $translated_text;
	}
	if ( 'Coupon:' === $text && $code_text) {
		$translated_text =  $code_text . ':';
	}

	if ('Coupon has been removed.' === $text && $code_text){
		$translated_text = $code_text . ' has been removed.';
	}

	if ( 'Apply coupon' === $text && $code_text) {
		$translated_text = 'Apply Code';
	}

	if ( 'Coupon code' === $text && $code_text) {
		$translated_text = $code_text;
	
	} 

	return $translated_text;
}


// rename the "Have a Coupon?" message on the checkout page
function woocommerce_rename_coupon_message_on_checkout($message) {


	$code_text  = get_option( 'coupon_code_text' );
	$vowels = array('a','e','i','o','u');

	if($code_text){

		if(in_array($code_text{0}, $vowels)){
			$question = 'Have an ';
		} else{
			$question = 'Have a ';
		}

		$question .= $code_text . '?';

		$message  =  $question . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>';


	}


	return  $message;
}


function rename_coupon_label($err, $err_code=null, $something=null){

	$code_text  = get_option( 'coupon_code_text' );

	if($code_text){
		$err = str_ireplace("Coupon",$code_text,$err);
	}

	return $err;
}



function coupon_code_add_section( $sections ) {
	
	$sections['coupon-codes'] = __( 'Coupon Codes', 'text-domain' );
	return $sections;
	
}



function coupon_code_all_settings( $settings, $current_section ) {

	if ( $current_section == 'coupon-codes' ) {
		$settings_coupon_codes = array();
		// Add Title to the Settings
		$settings_coupon_codes[] = array( 'name' => __( 'Coupon Code Settings', 'text-domain' ), 'type' => 'title', 'desc' => __( 'The following options are used to configure the labels, buttons and messages for coupon codes.', 'text-domain' ), 'id' => 'coupon_code_text' );
	
		// Add second text field option
		$settings_coupon_codes[] = array(
			'name'     => __( 'Coupon Code Text', 'text-domain' ),
			'desc_tip' => __( "This will replace all occurences of 'coupon code' throughout the site.", 'text-domain' ),
			'id'       => 'coupon_code_text',
			'type'     => 'text',
			'desc'     => __( "Replace replace all occurences of 'coupon code' throughout the site", 'text-domain' ),
		);
		
		$settings_coupon_codes[] = array( 'type' => 'sectionend', 'id' => 'coupon_code_text' );
		return $settings_coupon_codes;
	
	/**
	 * If not, return the standard settings
	 **/
	} else {
		return $settings;
	}
}
