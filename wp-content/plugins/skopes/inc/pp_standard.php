<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PayPal Standard Payment Gateway
 *
 * Provides a PayPal Standard Payment Gateway.
 *
 * @class 		Skope_Paypal
 * @version		2.0.0
 * @package		skopes/inc
 * @author 		Royal Tyagi
 */

class Skope_Paypal {

	public function __construct() {
		$this->id                 = 'paypal';
		$this->code 			= 'pp_standard';
		$this->has_fields         = false;
		$this->order_button_text  = __( 'Proceed to PayPal', 'woocommerce' );
		$this->liveurl            = 'https://www.paypal.com/cgi-bin/webscr';
		$this->testurl            = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		$this->method_title       = __( 'PayPal', 'woocommerce' );
		//$this->method_description = __( 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.' );
		//$this->notify_url         = WC()->api_request_url( 'WC_Gateway_Paypal' );
		$this->supports           = array(
			'products',
			'refunds'
		);

		$this->init_admin_form_values();
	}


	public function init_admin_form_values() {

		$settings = get_option( $this->code );

		if( $settings =='' ) {
			$standard = array (
				    'pp_standard_status' => '0',
				    'pp_standard_title' => '',
				    'pp_standard_email' => '', 
				    'pp_standard_sandbox' => '0',
				    'pp_standard_debug' => '0',
				    'pp_standard_transaction' => '0'
				);
			$data = serialize($standard);
			add_option( $this->code, $data );
		}

		$NewSettings = get_option( $this->code );

		$this->setting = unserialize($NewSettings);
		$this->currentStatus = $this->setting['pp_standard_status'];

	}

	public function get_paypal_args( $order, $orderID )  {

		session_start();
		if ($this->setting['pp_standard_transaction'] == 1) {
			$tar =  'sale';
		} else {
			$tar = 'authorization';
		}

		$paypal_args = array(
				'cmd'           => '_cart',
				'business'      => $this->setting['pp_standard_email'],
				'no_note'       => 1,
				'currency_code' => 'USD',
				'charset'       => 'utf-8',
				'rm'            => is_ssl() ? 2 : 1,
				'upload'        => 1,
				'return'        =>  get_site_url()."/?act=checkout&action=success",
				'cancel_return' => get_site_url()."/?act=checkout",
				'page_style'    => '',
				'paymentaction' => $tar,
				'bn'            => 'skope_Cart',

				// Order key + ID
				'invoice'       => 'skope-'.$_SESSION['order_id'],
				'custom'        => $_SESSION['order_id'],

				// IPN
				'notify_url'    => get_site_url()."/?act=checkout&action=notify",
		);

		$paypal_args['no_shipping'] = 1;

		$i= 1;
		foreach ( $order as $product ) {
			$paypal_args['item_name_'.$i] = $product['name'];
			$paypal_args['quantity_'.$i] = $product['quantity'];
			$paypal_args['amount_'.$i] = number_format((float)$product['price'], 2, '.', '');

			$i++;
		}
		
		return $paypal_args;

	}

	public function process_payment( $orderInfo, $orderID, $totals, $data ) {

		$paypal_args = $this->get_paypal_args( $orderInfo, $orderID );
		$paypal_args = http_build_query( $paypal_args, '', '&' );

		if ( '1' == $this->setting['pp_standard_sandbox'] ) {
			$paypal_adr = $this->testurl . '?test_ipn=1&';
		} else {
			$paypal_adr = $this->liveurl . '?';
		}

		return array(
			'result' 	=> 'success',
			'redirect'	=> $paypal_adr . $paypal_args
		);		
	}

	public function validateData( $data ) {

		$json = array();
		
		return $json;

	}
	
	public function loadForm() {
		$html = '';

		return $html;
	}
	
}
?>
