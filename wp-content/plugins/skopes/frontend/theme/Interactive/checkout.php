<?php
	/*
	*  Written by Roayl Tyagi on 29-Dec-14
	*
	*   Checkout page on which user will check the added items with total & can choose payment methods
	*
	*/
?>
<?php
	session_start();
//	include(RC_TC_BASE_DIR."/frontend/core/class-plans.php");
	if (!class_exists('skopePlans')) {
		include('core/class-plans.php');
	}	
	$skopePlans = new skopePlans();
	$cart = $_SESSION['cart'];
	
	if ( empty($cart) ) {
		$url =  home_url()."?act=myaccount";
		wp_redirect( $url );
		exit;
	}

	$products = $skopePlans->getCartProducts();
	$totals = $skopePlans->getCartTotal();


	if( isset($_GET['action']) && $_GET['action'] == 'success' ) {

		session_start();
		$order_id = $_SESSION['order_id'];

		if ($_GET['tx'] != '' && $_GET['act'] == 'checkout' ) {

			if ($order_id == $_GET['cm']) {

				// Pass three arguments order_id, status & transectionID
				$skopePlans->confirmOrder( $order_id, $_GET['st'], $_GET['tx'] );
			}
		}

		$url =  home_url()."?act=success&os=".$_GET['st'];
		wp_redirect( $url );
		exit;		
	}

?>
<div class="content-section clearfix">
	<div class="container">
		<div class="subpage-content">
			<div class="box">
				<h2 class="boxheading">Checkout</h2>
				<h2 style="margin: 2% 0 -2% 4%;">Your Order</h2>
				<div id="poststuff">
					<form method="post" action="" class="" id="chckout" enctype="multipart/form-data">
						<table class="form-table">
							<tr>
								<th scope="row" style="width:300px;"><label>Access Levels</label></th>
								<th valign="row" style="text-align: center;"><label>Unit Price</label></th>
								<th valign="row" style="text-align: center;"><label>Total</label></th>
							</tr>
							<?php foreach ($products as $product ) { ?>
								<tr>
									<td><?php  echo $product['name']; ?></td>
										<td style="text-align: center;"><?php  echo $product['text']; ?></td>
										<td style="text-align: center;"><?php  echo $product['text']; ?></td>
								</tr>
							<?php } ?>
							<tr>
								<td></td>
								<td style="font-weight:bold; text-align: center;">Total: </td>
								<input type="hidden" name="total" value="<?php echo $totals; ?>" />
								<td class="total_price" style="font-weight:bold; text-align: center;">$<?php echo $totals; ?></td>
							</tr>
						</table>
						<div id="payment">
							<h3>Payment Methods</h3>
							<ul class="payment_methods methods">
								<?php
									if ( $available_gateways = $skopePlans->get_available_payment_gateways() ) {

										foreach ( $available_gateways as $gateway ) {
											?>
											<li class="payment_method_<?php echo $gateway->code; ?>">
												<input id="payment_method_<?php echo $gateway->code; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->code ); ?>" <?php //checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
												<label for="payment_method_<?php echo $gateway->code; ?>"><?php echo $gateway->method_title; ?></label>
												<?php
													if ( $gateway->method_description ) {
														echo '<div class="payment_box payment_method_' . $gateway->code . '" style="display:none;"><p>';
														echo $gateway->method_description;
														echo '</p><span class="last"></span></div>';
													}
												?>
											</li>
											<?php
										}
									} else {

										echo '<p>' . __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) . '</p>';

									}
								?>
							</ul>
							<div id="payment_forms"></div>
						<div class="form-row place-order">
							<?php if ( $available_gateways ) { ?>
								<?php wp_nonce_field( 'skope-pay' ); ?>
								<input type="hidden" name="skope_placeOrder" value="Y" />
								<input type="submit" class="button alt" name="skope_checkout_place_order" id="place_order" value="Place Order" data-value="Place order">
							<?php } ?>
						</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/my-account.js"></script>
<!--<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/my-account.js"></script>-->
