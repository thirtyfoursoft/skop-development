<?php
	/*
	*  Written by Roayl Tyagi on 05-Jan-15
	*
	*   Checkout page on which user will check the added items with total & can choose payment methods
	*
	*/
?>
<?php
	session_start();
	unset($_SESSION['cart']);
	unset($_SESSION['order_id']);
?>
<div class="content-section clearfix">
	<div class="container">
		<div class="subpage-content">
			<div class="box">
				<h2 class="boxheading">Your Order Has Been Completed!</h2>
				<div id="poststuff">
					<div class="" style="padding:35px 25px">
						<h2>Order Status:  <?php echo $_GET['os']; ?></h2>
						<p>Your order has been successfully processed!
						You can view your order history by going to the my account page and by clicking on history.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
