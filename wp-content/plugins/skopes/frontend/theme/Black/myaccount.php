<?php
	/*
	*  Written by Roayl Tyagi on 25-Dec-14
	*
	*   My Account page on which user will change the password & purchase the plans.
	*
	*/
?>
<?php
/* <?php wp_set_password( $password, $user_id ) ?>
*/
	session_start();

	include("core/class-plans.php");
	$skopePlans = new skopePlans();

	if( $_POST['addTocart'] == 'Y' ) {

		if ( isset($_POST['price']) ) {

			$_SESSION['cart'] = array();
			
			foreach( $_POST['price'] as $pid ) {
				$skopePlans->addToCart( $pid );
			}

			$url =  home_url()."?act=checkout";
			wp_redirect( $url ); exit;			

		} else {
			$errorCart = 'Please select atleast one plan.';
		}
	}

?>
<div class="content-section clearfix">
	<div class="container">
		<div class="subpage-content">
			<div class="box">
				<h2 class="boxheading">Access levels and payment</h2>
				<div id="poststuff">
					<form method="post" action="" class="" id="skopePlans" enctype="multipart/form-data">
						<table class="form-table">
							<?php if ( $errorCart ) echo '<tr><td colspan="4"><span class="error">' . $errorCart . '</span></td></tr>';	?>
							<tr>
								<th scope="row" style="width:300px;"><label>Access Levels</label></th>
								<th valign="row"><label>Status</label></th>
								<th valign="row"><label>Fee to Access</label></th>
								<th valign="row"><label>Purchase</label></th>
							</tr>
							<?php foreach( $skopePlans->plans as $plans ) { ?>
								<tr>
									<td><?php echo $plans['name']; ?></td>
									<?php if ($plans['status'] == 'Free' ) { ?>

										<td><?php echo $plans['status']; ?></td>
										<td></td>
										<td></td>

									<?php } elseif ($plans['status'] == 'Locked') { ?>

										<td><?php echo $plans['status']; ?></td>
										<td><?php echo $plans['text']; ?></td>
										<!--<td><input type="hidden" name="pId[]" value="<?php echo $plans['id']; ?>"></td>-->
										<td><input type="checkbox" name="price[]" value="<?php echo $plans['id']; ?>" data-type="<?php echo $plans['price']; ?>"></td>

									<?php } else { ?>
										<?php $src = plugins_url( 'images/in-stock.png', __FILE__ ); ?>
										<td><img src="<?php echo $src; ?>" width="23"></td>
										<td></td>
										<td></td>

									<?php } ?>
								</tr>
							<?php } ?>
							<tr>
								<td></td>
								<td style="font-weight:bold;">Total: </td>
								<td class="total_price" style="font-weight:bold;">$0.00</td>
								<td>
									<div class="upf-cell column2">
										<input type="submit" name="buy"  class="button-primary btn-blue1" id='buy' value="Buy" style="width: 120px; background: #85c8ee;"/>
										<input type="hidden" name="addTocart" value="Y" />
									</div>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/my-account.js"></script>
