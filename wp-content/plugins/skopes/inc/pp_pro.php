<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PayPal Pro Payment Gateway
 *
 * Provides a PayPal Pro Payment Gateway.
 *
 * @class 		Skope_Paypal_Pro
 * @version		2.0.0
 * @package		skopes/inc
 * @author 		Royal Tyagi
 */

class Skope_Paypal_Pro {

	public function __construct() {
		$this->id                 = 'paypal ';
		$this->code 			= 'pp_pro';
		$this->has_fields         = false;
		$this->order_button_text  = __( 'Make Payment' );
		$this->method_title       = __( 'Credit or Debit Card (Processed securely by PayPal)', 'woocommerce' );
		//$this->method_description = __( 'Put your credit card details below to make the payment.' );

		$this->init_admin_form_values();
	}

	public function init_admin_form_values() {

		$settings = get_option( $this->code );

		if( $settings =='' ) {
			$standard = array (
				    'pp_pro_status' => '0',
				    'pp_pro_title' => 'PayPal Pro',
				    'pp_pro_api_user' => '', 
				    'pp_pro_api_pass' => '0',
				    'pp_pro_api_signature' => '0',
				    'pp_pro_sandbox' => '0',
				    'pp_pro_transaction' => '0'
				);
			$data = serialize($standard);
			add_option( $this->code, $data );
		}

		$NewSettings = get_option( $this->code );

		$this->setting = unserialize($NewSettings);
		$this->currentStatus = $this->setting['pp_pro_status'];

	}

	public function loadForm() {

		$cards = array();
		$cards[] = array(
			'text'  => 'Visa', 
			'value' => 'VISA'
		);

		$cards[] = array(
			'text'  => 'MasterCard', 
			'value' => 'MASTERCARD'
		);

		$cards[] = array(
			'text'  => 'Discover Card', 
			'value' => 'DISCOVER'
		);

		$cards[] = array(
			'text'  => 'American Express',
			'value' => 'AMEX'
		);

		/*$cards[] = array(
			'text'  => 'Maestro', 
			'value' => 'SWITCH'
		);

		$cards[] = array(
			'text'  => 'Solo', 
			'value' => 'SOLO'
		);
		*/
		$months = array();

		for ($i = 1; $i <= 12; $i++) {
			$months[] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$year_valid = array();

		for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {	
			$year_valid[] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)), 
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$year_expire = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$year_expire[] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}

		ob_start();
		?>
	 <h2>Credit Card Details</h2>
	  <table class="form">
		<tr>
		  <td>Card Type:</td>
		  <td>
			<select name="cc_type">
			  <?php foreach ($cards as $card) { ?>
			    <option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
			  <?php } ?>
			</select>
		  </td>
		</tr>
		<tr>
		  <td><?php echo 'Card Number:'; ?></td>
		  <td><input type="text" name="cc_number" value="" /></td>
		</tr>
		<tr>
		  <td><?php echo 'Card Valid From Date:'; ?></td>
		  <td>
		    <select name="cc_start_date_month">
		      <?php foreach ($months as $month) { ?>
		        <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
		      <?php } ?>
		    </select>
		    /
		    <select name="cc_start_date_year">
		      <?php foreach ($year_valid as $year) { ?>
		        <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
		      <?php } ?>
		    </select>
		    <?php  echo '(if available)';  //echo $text_start_date; ?>
		  </td>
		</tr>
		<tr>
		  <td><?php echo 'Card Expiry Date:'; ?></td>
		  <td>
		    <select name="cc_expire_date_month">
		      <?php foreach ($months as $month) { ?>
		        <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
		      <?php } ?>
		    </select>
		    /
		    <select name="cc_expire_date_year">
		      <?php foreach ($year_expire as $year) { ?>
		        <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
		      <?php } ?>
		    </select>
		  </td>
		</tr>
		<tr>
		  <td><?php echo 'Card Security Code (CVV2):'; ?></td>
		  <td><input type="text" name="cc_cvv2" value="" size="3" /></td>
		</tr>
		<!--<tr>
		  <td><?php echo $entry_cc_issue; ?></td>
		  <td><input type="text" name="cc_issue" value="" size="1" />
		    <?php echo $text_issue; ?></td>
		</tr>-->
	  </table>
	  
	  <?php
		$html = ob_get_clean();
		
		return $html;
	}

	public function validateData( $data ) {

		$json = array();
		
		if( $data['cc_number'] == '' || $data['cc_cvv2'] == '' ) {
			$json['error'] = 'Please enter credit card and CVV number';
		}

		return $json;

	}

	public function process_payment( $orderInfo, $orderID, $totals, $data ) {

		$payment_type = 'Sale';
		
		$request  = 'METHOD=DoDirectPayment';
		$request .= '&VERSION=51.0';
		$request .= '&USER=' . urlencode($this->setting['pp_pro_api_user']);
		$request .= '&PWD=' . urlencode($this->setting['pp_pro_api_pass']);
		$request .= '&SIGNATURE=' . urlencode($this->setting['pp_pro_api_signature']);
		$request .= '&CUSTREF=' . (int)$orderID;
		$request .= '&PAYMENTACTION=' . $payment_type;
		$request .= '&AMT=' . $totals;
		$request .= '&CREDITCARDTYPE=' . $data['cc_type'];
		$request .= '&ACCT=' . urlencode(str_replace(' ', '', $data['cc_number']));
		$request .= '&CARDSTART=' . urlencode($data['cc_start_date_month'] . $data['cc_start_date_year']);
		$request .= '&EXPDATE=' . urlencode($data['cc_expire_date_month'] . $data['cc_expire_date_year']);
		$request .= '&CVV2=' . urlencode($data['cc_cvv2']);
		$request .= '&CURRENCYCODE=USD';		

		if ( '1' == $this->setting['pp_pro_sandbox'] ) {
			$curl = curl_init('https://api-3t.sandbox.paypal.com/nvp');
		} else {
			$curl = curl_init('https://api-3t.paypal.com/nvp');			
		}

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

		$response = curl_exec($curl);

		curl_close($curl);

		if (!$response) {
			$this->log->write('DoDirectPayment failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
		}

		$response_info = array();

		parse_str($response, $response_info);

		$json = array();

		if (($response_info['ACK'] == 'Success') || ($response_info['ACK'] == 'SuccessWithWarning')) {

			$message = '';

			if (isset($response_info['AVSCODE'])) {
				$message .= 'AVSCODE: ' . $response_info['AVSCODE'] . "\n";
			}

			if (isset($response_info['CVV2MATCH'])) {
				$message .= 'CVV2MATCH: ' . $response_info['CVV2MATCH'] . "\n";
			}

			if (isset($response_info['TRANSACTIONID'])) {
				$message .= 'TRANSACTIONID: ' . $response_info['TRANSACTIONID'] . "\n";
			}

			$status = 'Completed';
			include(RC_TC_BASE_DIR."/frontend/core/class-plans.php");
			$skopePlans = new skopePlans();
			$skopePlans->confirmOrder( $orderID, $status, $message );

			$url =  home_url()."?act=success&os=".$status;

			$json['redirect'] = $url;

		} else {
			$json['error'] = $response_info['L_ERRORCODE0'] . '  ' . $response_info['L_LONGMESSAGE0'];
		}

		return $json;
	}

}
