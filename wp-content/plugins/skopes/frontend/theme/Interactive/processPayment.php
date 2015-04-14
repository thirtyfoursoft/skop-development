<?php
	include '../../../../../../wp-load.php';
	global $wpdb;
	if (!class_exists('skopePlans')) {
		include('core/class-plans.php');
	}
	$skopePlans = new skopePlans();

	if( $_POST['skope_placeOrder'] == 'Y' ) {
		check_admin_referer( "skope-pay" );

		$data = $_POST;

		$products = $skopePlans->getCartProducts();
		$totals = $skopePlans->getCartTotal();

		$orderData = array (
			'products' => $products,
			'total'	=> $totals,
			'paymentMethod'  => $data['payment_method'],
		);

		include(RC_TC_BASE_DIR."/inc/".$data['payment_method'].".php");

		$load_gateways = array(
			'pp_standard'  => 'Skope_Paypal',
			'pp_pro'  => 'Skope_Paypal_Pro'
		);

		$gateway = new $load_gateways[$data['payment_method']];

		$error = $gateway->validateData( $data );

		if (empty($error) ) {
			$orderID = $skopePlans->addOrder( $orderData );
			$orderInfo = $skopePlans->getOrder( $orderID );
			
			$response = $gateway->process_payment( $orderInfo, $orderID, $totals, $data );

			echo json_encode($response);

		} else {
			echo json_encode($error);
		}

	}

	if (isset($_GET['action']) && $_GET['action'] == 'loadForm' ) {

		$load_gateways = array(
			'pp_standard'  => 'Skope_Paypal',
			'pp_pro'  => 'Skope_Paypal_Pro'
		);

		$paymentMethod = $_GET['method'];
		include(RC_TC_BASE_DIR."/inc/".$paymentMethod.".php");
		$gateway = new $load_gateways[$paymentMethod];

		$html = $gateway->loadForm();

		echo $html;
	}
?>
