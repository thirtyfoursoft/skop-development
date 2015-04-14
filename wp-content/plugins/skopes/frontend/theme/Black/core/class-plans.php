<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * For load plans on account page
 *
 * 
 * @class 		Skope_plans
 * @version		2.0.0
 * @package		skopes/frontend/core
 * @author 		Royal Tyagi
 */

class SkopePlans {

	private $data = array();
	
	public function __construct() {

		$this->userId = get_current_user_id();
		$this->getPlans();

	}

	public function getPlans( $key = null ) {

		$allPlans = $this->skope_getAllProducts();

		foreach ( $allPlans as $allPlan ) {

			if ( $key != "" ) {
			
				if ( $key != $allPlan['product_key'] ) {
					continue;
				}
			}

			if ($allPlan['status'] == 0 ) {
				$status = 'Free';
			} else {
				$status = $this->getStatus( $allPlan['id'] );
			}

			$text = $this->formatPrice( $allPlan['price'], '$' );

			$data[] = array (
				'id'		=> $allPlan['id'],
				'name'		=> $allPlan['name'],
				'product_key'		=> $allPlan['product_key'],
				'price'		=> $allPlan['price'],
				'text'		=> $text,
				'status'		=> $status
			);
		}

		$this->foo = $data;
		$this->plans = $data;
	}

	public function formatPrice ($number, $symbol = null ) {
		$string = '';
		$string .= $symbol;

		$string .= number_format($number, 2);

		return $string;
	}

	public function getStatus( $pId ) {

		global $wpdb;

		$query = "SELECT * FROM ". $wpdb->prefix ."specdoc_order t1 INNER JOIN ". $wpdb->prefix ."specdoc_order_product t2 ON (t1.order_id = t2.order_id) WHERE t1.customer_id = '" . $this->userId . "' AND t2.product_id = '" . $pId . "' AND t1.order_status = 'Completed'";

		$result = $wpdb->get_row($query,ARRAY_A);

		if ( !empty($result) ) {
			$status = 'arrow';
		} else {
			$status = 'Locked';
		}

		return $status;

	}

	public function skope_getAllProducts( $pId = 0 ) {
		global $wpdb;

		$query = "SELECT * FROM ". $wpdb->prefix ."specdoc_product";

		if ( $pId > 0 ) {
			$query .= " WHERE id= '" . $pId . "'";
		}

		$query .= " ORDER BY sort_order ASC";

		if ( $pId > 0 ) {
			$result = $wpdb->get_row($query,ARRAY_A);
		} else {
			$result = $wpdb->get_results($query,ARRAY_A);
		}

		return $result;

	}

	public function addToCart( $id, $qty =1 )  {

		$key = $id;

		if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
			$_SESSION['cart'] = array();
		}

		if ((int)$key && ((int)$key > 0)) {
			if (!isset($_SESSION['cart'][$key])) {
				$_SESSION['cart'][$key] = (int)$qty;
			} else {
				$_SESSION['cart'][$key] += (int)$qty;
			}
		}

	}

	public function getCartProducts() {

		session_start();
		$cart = $_SESSION['cart'];

		if ( !empty($cart) ) {

			foreach ($cart as $key => $quantity) {
				$product_info = $this->skope_getAllProducts($key);

				$this->data[$key] = array(
					'id' => $product_info['id'],
					'name' => $product_info['name'],
					'price' => $product_info['price'],
					'text' => $this->formatPrice( $product_info['price'], '$' )
					
				);
			}
			
		} else {
			$this->data = array();
		}

		return $this->data;
	}

	public function getCartTotal() {

		$total = 0;

		foreach ($this->getCartProducts() as $product) {
			$total += $product['price'];
		}

		$total = $this->formatPrice( $total );
		return $total;
	}


	public function get_available_payment_gateways() {
	
		$load_gateways = array(
			'pp_standard'  => 'Skope_Paypal',
			'pp_pro'  => 'Skope_Paypal_Pro'
		);

		$payments_methods = array();

		foreach ( $load_gateways as $file => $gateway ) {

			include(RC_TC_BASE_DIR."/inc/".$file.".php");
			$load_gateway = new $gateway();

			if ( isset( $load_gateway->currentStatus ) && ($load_gateway->currentStatus == 1) ) {
				$payments_methods[ $load_gateway->code ] = $load_gateway;
			}

		}

		return $payments_methods;

	}

	public function addOrder( $data ) {

		global $wpdb;

		$query = "INSERT INTO ". $wpdb->prefix ."specdoc_order SET customer_id = '" . $this->userId . "', total = '" . $data['total'] . "', payment_method = '" . $data['paymentMethod'] . "', order_status = 'skope_pending', currency_code = 'USD', ip ='', date_added = NOW(), transection_id = ''";

		$wpdb->query( $query );
		$order_id = $wpdb->insert_id;

		//$order_id = 1;

		if ($data['products']) {
			foreach ( $data['products'] as $product ) {

				$query = "INSERT INTO ". $wpdb->prefix ."specdoc_order_product SET order_id = '" . $order_id . "', product_id = '" . $product['id'] . "', name = '" . $product['name'] . "', quantity = '1', price = '" . $product['price'] . "', total = '" . $product['price'] . "'";

				$wpdb->query( $query );
			}
		}

		session_start();
		$_SESSION['order_id'] = $order_id;
		
		return $order_id;

	}

	public function getOrder( $orderId ) {

		global $wpdb;

		$query = "SELECT * FROM ". $wpdb->prefix ."specdoc_order t1 INNER JOIN ". $wpdb->prefix ."specdoc_order_product t2 ON (t1.order_id = t2.order_id) WHERE t1.order_id = '" . $orderId . "'";

		$result = $wpdb->get_results($query,ARRAY_A);

		return $result;

	}


	public function confirmOrder( $orderID, $status, $transectionId ) {

		global $wpdb;

		$query = "UPDATE ". $wpdb->prefix ."specdoc_order SET order_status = '" . $status . "', transection_id = '" . $transectionId . "' WHERE order_id = '" . $orderID . "'";
		$wpdb->query( $query );
		
	}

}
?>
