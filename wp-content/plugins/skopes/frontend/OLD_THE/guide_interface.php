<?php
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.  
	$loggedin_id = get_current_user_id();
	global $wpdb;
	$objSkop = new clsSkopes();
	$FAData = $objSkop->checkFunaStatusCustom($loggedin_id);

	$totalFA          = $FAData[1]['totalFA'];
	$totalInscope     = $FAData[2]['totalInscope'];
	$totalNotrequired = $FAData[3]['totalNotrequired'];
	$totalunknown     = $FAData[0]['unknown'];
	$totalComplete    = $FAData[4]['totalComplete']; 

	$UserInfoObj = new userinfo();
?>

<div class="container">
	<div class="guide-box">
		<div class="guide-column">
			<?php
				/* *********************   Check status for First column to change the class of li  ************************* */
					//$progress_guideClasss = ($progress_guide == 1 ? 'active':'');

					$post = get_post( 35 );
					$slug = $post->post_name;
					$progress_guide = get_user_meta( $loggedin_id, $slug, true );
					$progress_guideClasss = ($progress_guide == 1 ? 'active':'');					

				   //$tstatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id, 2);
					$post = get_post( 37 );
					$slug = $post->post_name;
					$tstatusVal = get_user_meta( $loggedin_id, $slug, true );
					$tstatusClass = ($tstatusVal == 1 ? 'active':'');

					$pfstatusVal = $UserInfoObj->selectGuideStatus($loggedin_id, 19);
					$pfstatusClass = ($pfstatusVal == 1 ? 'active':'');

				   //$chstatusVal = $UserInfoObj->selectGuideStatus($loggedin_id, 3);
					$post = get_post( 41 );
					$slug = $post->post_name;
					$chstatusVal =  get_user_meta( $loggedin_id, $slug, true );
				   $chstatusClass = ($chstatusVal == 1 ? 'active':'');

				   //$pastatusVal = $UserInfoObj->selectGuideStatus($loggedin_id, 4);
					$post = get_post( 44 );
					$slug = $post->post_name;
					$pastatusVal = get_user_meta( $loggedin_id, $slug, true );
				   $paStatusClass = ($pastatusVal == 1 ? 'active':'');

					$totalActive = $progress_guide + $tstatusVal + $pfstatusVal + $chstatusVal + $pastatusVal;
					$totalProcess =  ($totalActive * 20);
					$marginTop = 69- ($totalActive * 13.8);

					$backgroundPostion =  (($totalActive * 7.2) - 36);

				/* *********************  End Check status for First column to change the class of li  ************************* */
			?>
				<div class="presnetej">
					<div class="progress-presnet" style="top:<?php echo $marginTop; ?>px !important;"><?php echo $totalProcess; ?>% complete</div>
					<div class="presnetej-cont">
						<div class="select-number" style="background-position:100% <?php echo $backgroundPostion; ?>%">
								<!--<img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/newHome/3-img.png" alt="" />-->
								<div class="one-number"></div>
						</div>
					</div>
				</div>
				<a class="arrow-right" href="#">arrow</a>
				<div class="column_topContent">
					<h5>GET UP TO SPEED, READ THE HELPFUL GUIDES</h5>
					<p>To run a succesful project it is critical that you know what you are going to do when, how and with whom. The Skopes platform will help you define this.</p>
				</div>

				<h4>Progress tracker</h4>
				<ul>
				<?php $columnOne = unserialize(get_option('_guide_page_section_1'));  ?>
					<li class="<?php echo $progress_guideClasss; ?>"><a class="tooltips" href="<?php echo stripslashes($columnOne[0]['link']); ?>"><?php echo stripslashes($columnOne[0]['text']); ?><span><?php echo stripslashes($columnOne[0]['pop-up']); ?></span></a></li>
					<li class="<?php echo $tstatusClass;?>"><a class="tooltips" href="<?php echo stripslashes($columnOne[1]['link']); ?>"><?php echo stripslashes($columnOne[1]['text']); ?><span><?php echo stripslashes($columnOne[1]['pop-up']); ?></span></a></li>
					<li class="<?php echo $pfstatusClass;?>"><a class="tooltips" href="<?php echo stripslashes($columnOne[2]['link']); ?>"><?php echo stripslashes($columnOne[2]['text']); ?><span><?php echo stripslashes($columnOne[2]['pop-up']); ?></span></a></li>
					<li class="<?php echo $chstatusClass;?>"><a class="tooltips" href="<?php echo stripslashes($columnOne[3]['link']); ?>"><?php echo stripslashes($columnOne[3]['text']); ?><span><?php echo stripslashes($columnOne[3]['pop-up']); ?></span></a></li>
					<li class="<?php echo $paStatusClass;?>"><a class="tooltips" href="<?php echo stripslashes($columnOne[4]['link']); ?>"><?php echo stripslashes($columnOne[4]['text']); ?><span><?php echo stripslashes($columnOne[4]['pop-up']); ?></span></a></li>
				</ul>
		</div>

		<div class="guide-column">
		<?php 
			$totalofInScAndNotreq = $totalInscope + $totalNotrequired;
			$totalofInScAndComAndNotreq = $totalInscope + $totalNotrequired + $totalComplete;
			$totalofComAndNotreq = $totalNotrequired + $totalComplete;

            if($totalofInScAndNotreq == $totalFA) {
			    $yesFADone = 1;
			} else if($totalofInScAndComAndNotreq == $totalFA) {
			    $yesFADone = 1;
			} else {
			    $yesFADone = 0;
			}
			
			if($totalunknown  == 0 and $totalInscope == 0 and $totalofComAndNotreq == $totalFA) {
			    $yesFADone2 = 1; 
			} else {
			    $yesFADone2 = 0;
			}

			if( $yesFADone2 == 1 ) {
				$query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = 'Input details on features and priorities within each functional area' ";
				 $result3 = $wpdb->get_results($query3);
				 $itemIdV = $result3[0]->itemId;

				 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV;
				 $result5 = $wpdb->get_results($query5); 
				 $item_status = 1; 

				 if( is_array($result5) and empty($result5) ) {
					 $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", $itemIdV, $item_status)";
				 } else {
					 $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV;
				 }

				 $wpdb->query($query4);

			} else {

				 $query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = 'Input details on features and priorities within each functional area' ";
				 $result3 = $wpdb->get_results($query3);
				 $itemIdV = $result3[0]->itemId;

				 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV;
				 $result5 = $wpdb->get_results($query5); 
				 $item_status = 0; 

				 if( is_array($result5) and empty($result5) ) {
					 $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", $itemIdV, $item_status)";
				 } else {
					 $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV;
				 }

				 $wpdb->query($query4);
			}


			if($yesFADone == 1) {
				 $query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = 'Select high-level functional areas that are required' ";
				 $result3 = $wpdb->get_results($query3);
				 $itemIdV = $result3[0]->itemId;

				 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV;
				 $result5 = $wpdb->get_results($query5); 
				 $item_status = 1; 

				 if( is_array($result5) and empty($result5) ) { 
					 $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", $itemIdV, $item_status)";
				 } else {  
					 $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV;


				 }

				 $wpdb->query($query4);
			} else {
				 $query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = 'Select high-level functional areas that are required' ";
				 $result3 = $wpdb->get_results($query3);
				 $itemIdV = $result3[0]->itemId;

				 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV;
				 $result5 = $wpdb->get_results($query5); 
				 $item_status = 0; 

				 if( is_array($result5) and empty($result5) ) {
					 $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", $itemIdV, $item_status)";
				 } else {  
					 $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV;
				 }

				 $wpdb->query($query4);
			}

			$grp3Total = $UserInfoObj->groupItemsCount($loggedin_id,3);
			$grp3Val = $UserInfoObj->selectGroupStatus($loggedin_id,3);
			$grp3SumVal = ($grp3Val == ''? 0 : $grp3Val);
			$grp3Class = ($grp3SumVal < 3? 'guide-blue btn-blue1': 'guide-green btn-green1'); 

			$query = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = 'ABOUT YOUR ORGANISATION' AND title = 'Provide background about your organisation' AND step = 'step2' ";
			$result = $wpdb->get_results($query);
			$statusStep2 = $result[0]->status;

			/* *********************   Check status for second column to change the class of li  ************************* */
		   $bgostatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id, 20 );
		   $bgoStatusClass = ( $bgostatusVal == 1 ? 'active':'');

		 /*  $pbgstatusVal = $UserInfoObj->selectGuideStatus($loggedin_id,5);
		    $pbgStatusClass = ($pbgstatusVal == 1 ? 'active':'');
		*/

			$pbgstatusVal_dup = $UserInfoObj->getAboutprojectStatusFromuserstatusinfo( $loggedin_id );
			$pbgStatusClass = ($pbgstatusVal_dup == 0 ? 'active':'');

			$pbgstatusVal = ($pbgstatusVal_dup == 0 ? '1':'0');
			
		   $hlgstatusVal = $UserInfoObj->selectGuideStatus($loggedin_id,6);
		   $hlgStatusClass = ($hlgstatusVal == 1 ? 'active':'');

		   //$frgstatusVal = $UserInfoObj->selectGuideStatus($loggedin_id,7);
			$post = get_post( 46 );
			$slug = $post->post_name;
			$frgstatusVal = get_user_meta( $loggedin_id, $slug, true );
		   $frStatusClass = ($frgstatusVal == 1 ? 'active':'');

		   //$pdtatusVal = $UserInfoObj->selectGuideStatus($loggedin_id,8);
			$post = get_post( 48 );
			$slug = $post->post_name;
			$pdtatusVal = get_user_meta( $loggedin_id, $slug, true );
		   $pdstatusClass = ($pdtatusVal == 1 ? 'active':'');

			// This calulation works for progress bar
			$totalActive = $bgostatusVal + $pbgstatusVal + $hlgstatusVal + $frgstatusVal + $pdtatusVal;
			$totalProcess =  ($totalActive * 20);
			$marginTop = 69- ($totalActive * 13.8);

			$backgroundPostion =  (($totalActive * 7.2) - 36);

			// Second column row Report generate button check

			if ($bgostatusVal == 1 && $pbgstatusVal ==1 && $hlgstatusVal ==1 ) {
				$secondColumnReport = "";
			} else {
				$secondColumnReport = "disable";
			}


			/* *********************   End status for second column to change the class of li  ************************* */
		?>
			<div class="presnetej two-presnetej">

				<div class="progress-presnet" style="top:<?php echo $marginTop; ?>px !important;"><?php echo $totalProcess; ?>% complete</div>
				<div class="presnetej-cont">
					<div class="select-number" style="background-position:100% <?php echo $backgroundPostion; ?>%">
							<!--<img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/newHome/2-img.png" alt="" />-->
							<div class="two-number"></div>
					</div>
				</div>
			</div>

				<a class="arrow-right" href="#">arrow</a>
				<div class="column_topContent">
					<h5>GET ORGANISED & OBTAIN PROJECT APPROVAL</h5>
					<p>Begin team building. Create enough documentation to capture needs and get buy-in from all stakeholders. Investigate software platforms and suppliers.</p>
				</div>
				<h4>Progress tracker</h4>
				<?php $columnTwo = unserialize(get_option('_guide_page_section_2'));  ?>
				<ul>
					<li class="<?php echo $bgoStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnTwo[0]['link']); ?>"><?php echo stripslashes($columnTwo[0]['text']); ?><span><?php echo stripslashes($columnTwo[0]['pop-up']); ?></span></a></li>
					<li class="<?php echo $pbgStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnTwo[1]['link']); ?>"><?php echo stripslashes($columnTwo[1]['text']); ?><span><?php echo stripslashes($columnTwo[1]['pop-up']); ?></span></a></li>
				<li class="<?php echo $hlgStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnTwo[2]['link']); ?>"><?php echo stripslashes($columnTwo[2]['text']); ?><span><?php echo stripslashes($columnTwo[2]['pop-up']); ?></span></a></li>
					<li class="<?php echo $frStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnTwo[3]['link']); ?>"><?php echo stripslashes($columnTwo[3]['text']); ?><span><?php echo stripslashes($columnTwo[3]['pop-up']); ?></span></a></li>
					<li class="<?php echo $pdstatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnTwo[4]['link']); ?>"><?php echo stripslashes($columnTwo[4]['text']); ?><span><?php echo stripslashes($columnTwo[4]['pop-up']); ?></span></a></li>
				</ul>
				<?php if ($secondColumnReport != "") { ?>
					<a class="report-btn disable" href="javascript:void(0);">SEE Report </a>
				<?php } else { ?>
					<a class="report-btn" href="<?php echo home_url()."?act=getreport"; ?>"><span class="repot-left"></span>SEE Report <span class="repot-right"></span></a>
				<?php } ?>
		</div>

	<?php
			/* *********************   Check status for third column to change the class of li  ************************* */
		   $pbiStatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id, 9 );
		   $pbiStatusClass = ($pbiStatusVal == 1 ? 'active':'');

		   $prorStatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id, 10 );
		   $prorStatusClass = ($prorStatusVal == 1 ? 'active':'');

			$getCatId = $objSkop->getOneFAInscope($loggedin_id);
			$homeUrl = home_url()."?act=features";
			$catName = stripslashes($objSkop->GetCategoryName($getCatId)) ;
			$encrypt = $objSkop->k_encrypt($getCatId);

			 if( $getCatId > 0) {
				$eachFunctionalAreaURL = $homeUrl."&edit=$encrypt";
			} else {
				$eachFunctionalAreaURL = $homeUrl;
			}

			//$colStatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id,11 );
			$post = get_post( 50 );
			$slug = $post->post_name;
			$colStatusVal = get_user_meta( $loggedin_id, $slug, true );
			$colStatusClass = ($colStatusVal == 1 ? 'active':'');

		   //$smStatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id,12 );
			$post = get_post( 52 );
			$slug = $post->post_name;
			$smStatusVal = get_user_meta( $loggedin_id, $slug, true );
		   $smStatusClass = ($smStatusVal == 1 ? 'active':'');

		  // $svStatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id,13 );
			$post = get_post( 54 );
			$slug = $post->post_name;
			$svStatusVal = get_user_meta( $loggedin_id, $slug, true );
		   $svStatusClass = ($svStatusVal == 1 ? 'active':'');


			// This calulation works for progress bar
			$totalActive = $pbiStatusVal + $prorStatusVal + $colStatusVal + $smStatusVal + $svStatusVal;
			$totalProcess =  ($totalActive * 20);
			$marginTop = 69- ($totalActive * 13.8);

			$backgroundPostion =  (($totalActive * 7.2) - 36);

			// third column row Report generate button check

			if ($pbiStatusVal == 1 && $prorStatusVal ==1 ) {
				$thirdColumnReport = "";
			} else {
				$thirdColumnReport = "disable";
			}

			/* *********************  End Check status for third column to change the class of li  ************************* */
	?>
		<div class="guide-column">
			<div class="presnetej">
					<div class="progress-presnet" style="top:<?php echo $marginTop; ?>px !important;"><?php echo $totalProcess; ?>% complete</div>
					<div class="presnetej-cont">
						<div class="select-number" style="background-position:100% <?php echo $backgroundPostion; ?>%">
								<div class="third-number"></div>
						</div>
					</div>

				<!--<img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/newHome/three-img.png" alt="" />-->

			</div>
				<div class="column_topContent">
					<h5>GET FINALISATION ON THE PROJECT DETAILS</h5>
					<p>Fully capture and agree project requirements and delieverable Finalise software selection and engage vendors. Finalise the project team and get started</p>
				</div>
				<h4>Progress tracker</h4>
				<?php $columnThree = unserialize(get_option('_guide_page_section_3'));  ?>
				<ul>
					<li class="<?php echo $pbiStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnThree[0]['link']); ?>"><?php echo stripslashes($columnThree[0]['text']); ?><span><?php echo stripslashes($columnThree[0]['pop-up']); ?></span></a></li>
					<li class="<?php echo $colStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnThree[1]['link']); ?>"><?php echo stripslashes($columnThree[1]['text']); ?><span><?php echo stripslashes($columnThree[1]['pop-up']); ?></span></a></li>
					<li class="<?php echo $prorStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnThree[2]['link']); ?>"><?php echo stripslashes($columnThree[2]['text']); ?><span><?php echo stripslashes($columnThree[2]['pop-up']); ?></span></a></li>
					<li class="<?php echo $smStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnThree[3]['link']); ?>"><?php echo stripslashes($columnThree[3]['text']); ?><span><?php echo stripslashes($columnThree[3]['pop-up']); ?></span></a></li>
					<li class="<?php echo $svStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnThree[4]['link']); ?>"><?php echo stripslashes($columnThree[4]['text']); ?><span><?php echo stripslashes($columnThree[4]['pop-up']); ?></span></a></li>
				</ul>
				<?php if ($thirdColumnReport != "") { ?>
					<a class="report-btn disable" href="javascript:void(0);">SEE Report </a>
				<?php } else { ?>
					<a class="report-btn" href="<?php echo home_url()."?act=getreport"; ?>"><span class="repot-left"></span>SEE Report <span class="repot-right"></span></a>
				<?php } ?>
		</div>

	<?php
			/* *********************   Check status for fourth column to change the class of li  ************************* */

		//$ksStatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id,14 );
		$post = get_post( 31 );
		$slug = $post->post_name;
		$ksStatusVal = get_user_meta( $loggedin_id, $slug, true );
		$ksStatusClass = ($ksStatusVal == 1 ? 'active':'');

		//$eiStatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id,15 );
		$post = get_post( 33 );
		$slug = $post->post_name;
		$eiStatusVal = get_user_meta( $loggedin_id, $slug, true );
		$eiStatusClass = ($eiStatusVal == 1 ? 'active':'');

		//$pmStatusVal = $UserInfoObj->selectGuideStatus( $loggedin_id,16 );
		$post = get_post( 56 );
		$slug = $post->post_name;
		$pmStatusVal = get_user_meta( $loggedin_id, $slug, true );
		$pmStatusClass = ($pmStatusVal == 1 ? 'active':'');

		//$ksdStatusVal = $UserInfoObj->selectGuideStatus($loggedin_id,17);
		$post = get_post( 58 );
		$slug = $post->post_name;
		$ksdStatusVal = get_user_meta( $loggedin_id, $slug, true );
		$kslStatusClass = ($ksdStatusVal == 1 ? 'active':'');

		//$cldStatusVal = $UserInfoObj->selectGuideStatus($loggedin_id,18);
		$post = get_post( 60 );
		$slug = $post->post_name;
		$cldStatusVal = get_user_meta( $loggedin_id, $slug, true );
		$cldStatusClass = ($cldStatusVal == 1 ? 'active':'');

		// This calulation works for progress bar
		$totalActive = $ksStatusVal + $eiStatusVal + $pmStatusVal + $ksdStatusVal + $cldStatusVal;
		$totalProcess =  ($totalActive * 20);
		$marginTop = 69- ($totalActive * 13.8);

		$backgroundPostion =  (($totalActive * 7.2) - 36);

		// Fouth column row Report generate button check

		if ($secondColumnReport == "" && $thirdColumnReport == "" ) {
			$fourthColumnReport = "";
		} else {
			$fourthColumnReport = "disable";
		}

			/* *********************   End Check status for fourth column to change the class of li  ************************* */
	?>
		<div class="guide-column guide-four">
			<div class="presnetej">
				<div class="progress-presnet" style="top:<?php echo $marginTop; ?>px !important;"><?php echo $totalProcess; ?>% complete</div>
				<div class="presnetej-cont">
					<div class="select-number" style="background-position:100% <?php echo $backgroundPostion; ?>%">
							<div class="fourth-number"></div>
					</div>
				</div>
				<!--<img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/newHome/four-img.png" alt="" />-->
			</div>
				<a class="rotatingwatch-btn">Rotating Watch</a>
				<div class="column_topContent">
					<h5>GET THE PROJECT RUNNING THROUGH TO COMPLETION</h5>
					<p>Commence the project. Stay on top off deliverables, actions, review and feedback to keep the project moving. Make preparations for the project release.</p>
				</div>
				<h4>Progress tracker</h4>
				<?php $columnFour = unserialize(get_option('_guide_page_section_4'));  ?>
				<ul>
					<li class="<?php echo $ksStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnFour[0]['link']); ?>"><?php echo stripslashes($columnFour[0]['text']); ?><span><?php echo stripslashes($columnFour[0]['pop-up']); ?></span></a></li>
					<li class="<?php echo $eiStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnFour[1]['link']); ?>"><?php echo stripslashes($columnFour[1]['text']); ?><span><?php echo stripslashes($columnFour[1]['pop-up']); ?></span></a></li>
					<li class="<?php echo $pmStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnFour[2]['link']); ?>"><?php echo stripslashes($columnFour[2]['text']); ?><span><?php echo stripslashes($columnFour[2]['pop-up']); ?></span></a></li>
					<li class="<?php echo $kslStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnFour[3]['link']); ?>"><?php echo stripslashes($columnFour[3]['text']); ?><span><?php echo stripslashes($columnFour[3]['pop-up']); ?></span></a></li>

					<li class="<?php echo $cldStatusClass; ?>"><a class="tooltips" href="<?php echo stripslashes($columnFour[4]['link']); ?>" ><?php echo stripslashes($columnFour[4]['text']); ?><span><?php echo stripslashes($columnFour[4]['pop-up']); ?></span></a></li>
				</ul>
				<?php if ($fourthColumnReport != "") { ?>
					<a class="report-btn disable" href="javascript:void(0);">SEE Report </a>
				<?php } else { ?>
						<a class="report-btn" href="<?php echo home_url()."?act=getreport"; ?>"><span class="repot-left"></span>SEE Report <span class="repot-right"></span></a>
				<?php } ?>
		</div>
	</div>
</div>
<!---End Guide Box--->
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/guide.js"></script>
