<?php
	/*
	*  Written by Roayl Tyagi on 06-jan-15
	*
	*   This fill will be check the use is valid user ( Users has paid for that or not )  to downlad the pdf files for stage one.
	*
	*/
?>
<?php

	include(RC_TC_BASE_DIR."/frontend/core/class-plans.php");
	$skopePlans = new skopePlans();
	$skopePlans->getPlans( $product_key );
	$status = $skopePlans->plans;

	if( !empty ($status) ) {

		if ( $status[0]['status'] == 'Locked' ) {

			$url =  home_url()."?act=myaccount";
			wp_redirect( $url );
			exit;

		}
	}

	function getRiskSectionStatus( $keyword ) {

		include(RC_TC_BASE_DIR."/frontend/core/class-plans.php");
		$skopePlans = new skopePlans();
		$skopePlans->getPlans( $keyword );
		$status = $skopePlans->plans;

		if( !empty ($status) ) {

			if ( $status[0]['status'] != 'Locked' ) {

				$results = 1;
			} else {
				$results = 0;
			}
		}

		return $results;

	}
?>
