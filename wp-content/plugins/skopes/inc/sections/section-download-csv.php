<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	if ($_GET['user_id'] != '' ) {

		$userID = $_GET['user_id'];
	} else {

		wp_redirect(admin_url('admin.php?page=users-and-payments'));
		exit;
	}
	
	$choosenTheme = get_option("selected_theme");
	if( ! class_exists( 'clsSkopes' ) ) {
		require_once(RC_TC_BASE_DIR."/frontend/theme/".$choosenTheme."/core/class_skopes.php");
	}
	$skope = new clsSkopes();

	function GetLineItemFromToolDataNew( $userID, $catID ) {

			$tooldata = get_user_meta($userID,'tool_data',true);
			for($i=0;$i<sizeof($tooldata);$i++) {
				if($catID==$tooldata[$i][CATEGORY_ID]) {
					return $tooldata[$i][CATEGORY_LINEITEMS];
				}
			}
	}
	
	global $wpdb;

	$categories = get_user_meta($userID,'tool_data',true);

	if ($categories == '') {
		$forAdmin =1;
		$cat = $skope->SaveDefaultToolData($userID, $forAdmin);
		$categories = get_user_meta($userID,'tool_data',true);
	}

	$catStatus = array (
		'0' => 'Incomplete',
		'1' => 'Complete',
		'2' => 'Notrequired',
		'3' => 'Inscope'
	);

	$lineItemStatus = array(
		'0' => 'No',
		'1' => 'Yes'
	);
 
	$importance = array(
		'0' => 'Nice to have',
		'1' => 'Important ',		
		'2' => 'Mandatory'
	);
	
	$data = array();
	if ($categories != '') {

		foreach ($categories as $cat ) {

			global $wpdb;

			$catName = $wpdb->get_var("SELECT name FROM " . $wpdb->prefix . "specdoc_categories WHERE category_id = '" . $cat[0] . "' ");
			$lineitemdata = GetLineItemFromToolDataNew( $userID, $cat[0] );
	
			if (empty($lineitemdata)) {
				continue;
			}

			foreach ($lineitemdata as $linedata) {

				$ben_data = array();
				$dataTwo = array();
				
				if($linedata[LINEITEM_BENEFIT_FOR]!="") {
					$ben_data = explode(",",$linedata[LINEITEM_BENEFIT_FOR]);
				}
			
				if (!empty($ben_data)) {
					foreach ( $ben_data as $k => $v ) {
						$j = $k+1;

						if ($v == 'Other') {
							$dataTwo['Stakeholder other'] = $linedata[LINEITEM_BENEFIT_OTHER];
						} else {
							$dataTwo['Stakeholder'.$j] = $v;
						}
					}
				}
				
				$lineItemName = $wpdb->get_var("SELECT name FROM " . $wpdb->prefix . "specdoc_line_items WHERE line_item_id = '" . $linedata[0] . "' ");			
				$dataOne = array(
					'catName'	=> $catName,
					'catStatus' => $catStatus[$cat[3]],
					'lineName' => $lineItemName,
					'lineItemStatus' => $lineItemStatus[$linedata[9]],
					'notes' =>  $linedata[3],
					'lineID' => $linedata[0],
					'importance' => $importance[$linedata[2]],
					'year' => $linedata[5],
					'cost'   => $linedata[7],
					'time'   => $linedata[8],
					'LINEITEM_EOI' => $linedata['LINEITEM_EOI']
				);

				$data[] = $dataOne + $dataTwo;
			}
		}
		
	} else {
			echo "No data listed yet or account dont verified";
	}

	if (!empty($data)) {

			$user_info = get_user_by('id', $userID);

			$csvDir = $_SERVER['DOCUMENT_ROOT'].'/csvDownload';
			if (!file_exists($csvDir)) {
				mkdir($csvDir, 0777, true);
			}

			$userName = trim($user_info->first_name) . '_' . trim($user_info->last_name);

			if ($userName == '') {

				$lol = 'Downloadedfile.csv';
				$fileName = $csvDir.'/Downloadedfile.csv';

			} else {

				$lol = trim($user_info->first_name) . '_' . trim($user_info->last_name).'file.csv';
				$fileName = $csvDir.'/'.trim($user_info->first_name) . '_' . trim($user_info->last_name).'file.csv';
			}

			$fp = fopen($fileName, 'w');

			$header = array (
					'catName'	=> 'Category Name',
					'catStatus' => 'Category Status',
					'lineName' => 'Feature Name',
					'lineItemStatus' => 'Feature Status',
					'notes' =>  'Notes',
					'lineID' => 'Feature ID',
					'importance' => 'Importance',
					'year' => 'Times per Year',
					'cost'   => 'Cost per time',
					'time'   => 'Hours per time',
					'LINEITEM_EOI' => 'Feature EOI',
					'stakeholder' => 'Stakeholder'					
			);
		
			$header = $header;
			$count = 1;

			foreach ($data as $fields) {

				if ($count == 1) {
					fputcsv($fp, $header);
				}
				fputcsv($fp, $fields);

				$count++;
			}

			fclose($fp);

			$url = site_url().'/csvDownload/'.$lol;

			wp_redirect($url);

/*
			if (!unlink($fileName)) {
			  echo ("Error deleting $fileName");
			} else {
			  echo ("Deleted $fileName");
			}
*/
	}
?>
