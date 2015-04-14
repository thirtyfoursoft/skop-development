<?php
/* *****
*
* File made by Royal Tyagi at 27-Jan-15.
* File included in header.php, In which we will check the first time all questions are complete in an area of questions. Features and functions completed if so the add a meta key in 
* user meta table, That key will be used in "Users and payments" section in admin as Completed date.
*
***** */
	$sections = array( 'projectquesinfo', 'teaminialinfo', 'milestonestatus', 'selection_creteria_info', 'membermoreinfo', 'stakeholderinfo', 'benefitinfo' );

	$hits = array();
	foreach ( $sections as $section ) {
		$status = checkStatusOfSections( $loggedin_id, $section );
		$hits[$section] = $status;
	}

	$count = 1;
	if (in_array("1", $hits)) {
		$count++;
	} else if (in_array("0", $hits)) {
		$count++;
	}

	$dateOfCompleteAllAns = get_user_meta( $loggedin_id, '_dateOfCompleteAllAns', true );

	if ( (!$dateOfCompleteAllAns) && ($count == 1) ) {
		$dt = new DateTime();
		$now =  $dt->format('Y-m-d H:i:s');			
		add_user_meta( $loggedin_id, '_dateOfCompleteAllAns', $now );

	}
	
	function checkStatusOfSections ( $userId, $sectionName ) {

		global $wpdb;
		$resutls = $wpdb->get_col("SELECT section_status FROM " . $wpdb->prefix . "specdoc_userstatusinfo WHERE userid= '" . $userId . "' AND section_name = '" . $sectionName . "' ");

		return $resutls[0];
	}
?>
