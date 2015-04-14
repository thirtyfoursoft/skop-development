<?php ob_start();
global $wpdb; 
$userid = get_current_user_id();$parentpath = dirname(plugin_dir_path( __FILE__ ));
$result = $objUser->getuserdata($userid);
$url = site_url();
$obj = new clsSkopes();
$objUserClass = new userinfo();
 
$stage = $obj->getStageOfUser($objUserClass, $loggedin_id);
$FAData = $obj->checkFunaStatusCustom($loggedin_id); 

$totalFA          = $FAData[1]['totalFA'];
$totalInscope     = $FAData[2]['totalInscope'];
$totalNotrequired = $FAData[3]['totalNotrequired'];
$totalunknown     = $FAData[0]['unknown'];
$totalComplete    = $FAData[4]['totalComplete'];  

$planMessage = 'This report is a premium feature - please visit your <a href="'.home_url().'?act=myaccount">account</a> to add it to your package.';

$totalCompanyQues = $objUserClass->cquestioncount(); 
$userCompanyQues  = $objUserClass->usercquestioncount_custom($loggedin_id);

$totalProjectQues = $objUserClass->projectpagecount();
$userProjectQues  = $objUserClass->projectpageusercount($loggedin_id);

$userProjectMore  = $objUserClass->projectmoreCount($loggedin_id);

 $AbtYurOrgRes = $wpdb->get_results("SELECT status From ".$wpdb->prefix."save_status_at_stage2 Where userId = ".$loggedin_id." AND section_name = 'ABOUT YOUR ORGANISATION' AND form_name = 'ABOUT YOUR ORGANISATION' ");
 $AbtYurOrgStatus = $AbtYurOrgRes[0]->status;
  
$messageInHeader = '';
$messageInHeaderSecSect = '';
$totalCompPNR = $totalComplete + $totalNotrequired;

$aboutprojectStatus = $objUserClass->getAboutprojectStatus( $loggedin_id );
$getMoreInfoStatus = $objUserClass->getMoreInfoStatus( $loggedin_id );
$getOrganizationStatus = $objUserClass->getStatusOfOrganizationSection( $loggedin_id );


$is_risk_management = get_option("risk_management");
if ($is_risk_management == 1 ) {
	$compareMoreInfoNo = 5;
} else {
	$compareMoreInfoNo = 4;
}

if($totalCompPNR < $totalFA) {
     if($messageInHeaderSecSect == "") {
	      $messageInHeaderSecSect .= '<a href="'.home_url().'?act=features" style="color: #6db8e2;">Features</a>';
	 } else {
	      $messageInHeaderSecSect .= ', <a href="'.home_url().'?act=features" style="color: #6db8e2;">Features</a>';
	 }
}
if($userProjectMore < $compareMoreInfoNo) {
     if($messageInHeaderSecSect == "") {
	      $messageInHeaderSecSect .= '<a href="'.home_url().'?act=moreinfo" style="color: #6db8e2;">details</a>';
	 } else {
	      $messageInHeaderSecSect .= ', <a href="'.home_url().'?act=moreinfo" style="color: #6db8e2;">details</a>';
	 } 
}

if( $getOrganizationStatus != 0 ) {
     if($messageInHeader == "") {
	      $messageInHeader .= '<a href="'.home_url().'?act=aboutcompany" style="color: #6db8e2;">About your Organisation</a>';
	 } else {
	      $messageInHeader .= ', <a href="'.home_url().'?act=aboutcompany" style="color: #6db8e2;">About your Organisation</a>';
	 } 
}

if($totalunknown > 0) {
     if($messageInHeader == "") {
	     $messageInHeader .= '<a href="'.home_url().'?act=features" style="color: #6db8e2;">Functional Areas</a>';
	 } else {
	     $messageInHeader .= ', <a href="'.home_url().'?act=features" style="color: #6db8e2;">Functional Areas</a>';
	 } 
}

if( isset($_REQUEST['file']) and $_REQUEST['file'] != '' ) {
     $finalFile        = base64_decode($_REQUEST['file']); 
	 $downloadFileName = base64_decode($_REQUEST['downloadFileName']);
 ?>
             <iframe height="0" width="0" src="<?php echo $url;?>/fileTest.php?file=<?php echo $_REQUEST['file']; ?>&downloadFileName=<?php echo $_REQUEST['downloadFileName']; ?>"></iframe>	  
	  <?php
}

if( isset($_REQUEST['pdffile']) and $_REQUEST['pdffile'] != '' ) {
     $finalFile = base64_decode($_REQUEST['pdffile']);
	 $downloadFileName = base64_decode($_REQUEST['downloadFileName']);
 ?>
             <iframe height="0" width="0" src="<?php echo $url;?>/fileTestPdf.php?file=<?php echo $_REQUEST['pdffile']; ?>&downloadFileName=<?php echo $_REQUEST['downloadFileName']; ?>"></iframe>
	  <?php
}

if($result == 'company'){
	$redirecturl = home_url().'?act=aboutcompany';
	$title = "Functional Areas, About your Organisation, About your project";
	$id = "";
}else if($result == 'project'){
    $redirecturl = home_url().'?act=aboutproject';
	$title = "About your project";
	$id = "";
}
else if($result == 'functionalarea'){
	$redirecturl = home_url().'?act=features';	
	$title = "Functional Areas";
	$id = "id='fun_area'";
}
?>
<div class="container">
	 
<!-- Start: Content Section -->
	<div class="content-section darkbox clearfix rp">
		<div class="rp">		
		<h3>Project Initiation documents 
		<?php
		        $checkforFirstSectionHeader = 0;
				if ( ($aboutprojectStatus != 0) || ($userProjectQues < $totalProjectQues) ) {
				
				    if($messageInHeader == "") {
					      $messageInHeader .= '<a href="'.home_url().'?act=aboutproject" style="color: #6db8e2;">About your Project</a>'; 
					} else {
					      $messageInHeader .= ', <a href="'.home_url().'?act=aboutproject" style="color: #6db8e2;">About your Project</a>'; 
					}
				}		
		?>
		<?php if($messageInHeader == ''){
		        $checkforFirstSectionHeader = 0;
				if( $aboutprojectStatus !=0 ){
                    $checkforFirstSectionHeader = $checkforFirstSectionHeader + 1;
				}
				if($checkforFirstSectionHeader > 0) {
                      $messageInHeader2 = '<a href="'.home_url().'?act=aboutproject" style="color: #6db8e2;">About your Project</a>';
				  ?>
					 <span class="rd" style="color: #f2535b;">More data required : <?php echo $messageInHeader2; ?></span>
		<?php  } else { 	?>
		            <span style="color: #7ad671"> Reports ready for download </span>
		<?php   }

		     } else {
		?>
		     <span class="rd" style="color: #f2535b;">More data required : <?php echo $messageInHeader; ?></span>
		<?php } ?>
		<?php
		     $encodedMsg = base64_encode($messageInHeader);  ?>
		</h3></div>
		<div class="box_white marbtm30">
			<div class="pi-left">
				<h5>Project Introduction and Rationale</h5>
				<p>
				    This document will help you consistently and thoroughly communicate about your project and attain buy-in (funding) from stakeholders within your organisation.<br>
                    More information: <a href="<?php echo site_url(); ?>/help/" target="_blank" style="color: black; text-decoration: underline;">Project Rationale Guide.</a>
				</p>
			</div>

			<div class="pi-middle">
				<h5>Content</h5>
				<ul>
					<li>Executive summary</li>
					<li>Project objectives and benefits</li>
					<li>Return on investment</li>
					<li>Roles and Responsibilities</li>
					<li class="last">Process, Timelines & milestones</li>
				</ul>
			</div>
			
			<div class="pi-right">

			<!-- **********  Check status of doc of one stage ****************  -->
			<?php
				include("include/block-stage-one-pdf.php");
				$stage1Pdf = getRiskSectionStatus('stage1pdf');
				$stage1word = getRiskSectionStatus('stage1word');

				$stage2DocsPdf = getRiskSectionStatus('stage2DocsPdf');
				$stage2DocsWord = getRiskSectionStatus('stage2DocsWord');

				if( ($stage == "two") && ( $aboutprojectStatus == 0 ) && ($getOrganizationStatus == 0) ) {
				
					$docxfile = 'Project_pitch_result'.$userid.'_html.docx';
					$filename = $parentpath.'/docx/'.$docxfile;

					if ($stage1Pdf == 0) { ?>
					<a class="createpdf" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
					<?php } else { ?>
						<a class="createpdf" href="<?php echo home_url()."?act=project_pinchpdf"; ?>">Create PDF</a>
					<?php } ?>

					<?php	if ($stage1word == 0) { ?>
						<a class="createdoc" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
					<?php } else { ?>
						 <a class="createdoc" href="<?php echo home_url()."?act=project_pinch"; ?>"">Create doc</a>
					<?php } ?>					

					<?php	} else { ?>

					   <a class = "reportdisabled" href="javascript: void(0);" disabled = "disabled" onclick="showMessageinPopup('<?php echo $encodedMsg; ?>');">Create PDF</a> 
					   <a class="reportdisabled doc" href="javascript: void(0);" disabled="disabled" onclick="showMessageinPopup('<?php echo $encodedMsg; ?>');">Create doc</a>

					<?php } ?>
			</div>
			</div>
		
			<div class="box_white marbtm30">
			<div class="pi-left">
				<h5>Expression of Interest</h5>
				<p>
				    Use this document to make first contact with, and establish the interest of selected vendors. It provides a summary of you needs and helps you (and the vendors) assess their fit to them. <br>
                    More information:
					<a href="<?php echo site_url(); ?>/help/eoi-guide/" target="_blank" style="color: black; text-decoration: underline;">EOI Guide</a>.
				</p>
			</div>
			
			<div class="pi-middle">
				<h5>Content</h5>
				<ul>
				<li>Introduction</li>
				<li>Organisational background</li>
				<li>Process, Timelines & milestones</li>
				<li>Scope: Functional areas</li>
				</ul>
			</div>
			
			<div class="pi-right">
			    <?php  if( ($stage == "two") && ( $aboutprojectStatus == 0 ) && ($getOrganizationStatus == 0) ) { ?>
			    
						<?php  if ($stage1Pdf == 0) { ?>
							<a class="createpdf" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
							<?php } else { ?>
								  <a class="createpdf" href="<?php echo home_url()."?act=eoipdf"; ?>">Create PDF</a>
							<?php } ?>

						<?php	if ($stage1word == 0) { ?>
							<a class="createdoc" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
							<?php } else { ?>
					              <a class="createdoc" href="<?php echo home_url()."?act=eoi"; ?>">Create doc</a>
							<?php } ?>
							
			    <?php } else { ?>

                    <a class="reportdisabled" href="javascript:void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg; ?>');">Create PDF</a>
			        <a class="reportdisabled doc" href="javascript:void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg; ?>');">Create doc</a>	
				        
			    <?php } ?>

			</div>
			
			</div>
			
			<div class="box_white marbtm30">
			<div class="pi-left">
				<h5>Expression of Interest (EOI) response</h5>
				<p>
				    Provide this document to vendors to complete as their means of expressing their interest in your project. This will ensure consistency and let vendors know you are organised. <br>
                    More information: <a href="<?php echo site_url(); ?>/help/eoi-response/" target="_blank" style="color: black; text-decoration: underline;">EOI Response Guide.</a>
				</p>
			</div>
			
			<div class="pi-middle">
				<h5>Content</h5>
				<ul>
					<li>About your organisation</li>
					<li>Vendor team and contacts</li>
					<li>Vendor credentials</li>
					<li>Authorisation</li>
				</ul>
			</div>
			
			<div class="pi-right">
			    <?php 
				$is_EOI_vendor = 	get_option("_eoi_vendor");
			    if( ($stage == "two") && ( $aboutprojectStatus == 0 ) && ($getOrganizationStatus == 0) ) { ?>

					<?php  if ($stage1Pdf == 0) { ?>
						<a class="createpdf" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
						<?php } else { ?>
							  <a class="createpdf" href="<?php echo home_url()."?act=eoi_responsepdf"; ?>">Create PDF</a>
					<?php } ?>

					<?php	if ($stage1word == 0) { ?>
						<a class="createdoc" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
						<?php } else { ?>
				              <a class="createdoc" href="<?php echo home_url()."?act=eoi_response"; ?>">Create doc</a>
					<?php } ?>

					<?php if ($is_EOI_vendor == 1 ): ?>
				    	<a class="createEmail" href="?act=eoi_email">Send Email</a>
				    <?php endif; ?>
				    
			    <?php } else { ?>

                    <a class="reportdisabled" href="javascript:void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg; ?>');">Create PDF</a>
			        <a class="reportdisabled doc" href="javascript:void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg; ?>');">Create doc</a>

			        <?php if ($is_EOI_vendor == 1 ): ?>
				   		<a class="reportdisabled" href="javascript:void(0);">Send Email</a>
				    <?php endif; ?>
			    <?php } ?>


  
			</div>
		</div>

		</div>
		
		<div class="content-section darkbox clearfix rp">
	
		<div class="rp"><h3>Vendor Engagement and Project Management 
		<?php
		    $checkforSecSectionHeader = 0;
			if( $getMoreInfoStatus != 0 ){
				$checkforSecSectionHeader = $checkforSecSectionHeader + 1;
			}

			if($checkforSecSectionHeader > 0) {
			    if($messageInHeaderSecSect == "") {
				    $messageInHeaderSecSect .= '<a href="'.home_url().'?act=moreinfo" style="color: #6db8e2;">details</a>';
				} else {
				    $messageInHeaderSecSect .= ', <a href="'.home_url().'?act=moreinfo" style="color: #6db8e2;">details</a>';
				}
			}
			if($stage == "one" && $getOrganizationStatus !=0 ) {
				if($messageInHeaderSecSect == "") {
					$messageInHeaderSecSect .= '<a href="'.home_url().'?act=aboutcompany" style="color: #6db8e2;">About your Organisation</a>';
				} else {
					$messageInHeaderSecSect .= ', <a href="'.home_url().'?act=aboutcompany" style="color: #6db8e2;">About your Organisation</a>';
				}
			}
		?>
		    <?php if($messageInHeaderSecSect == '') {
			        if($stage == "one" || $getOrganizationStatus !=0) {
					      $msg2Disp = '<a href="'.home_url().'?act=aboutcompany" style="color: #6db8e2;">About your Organisation</a>';
			           ?>
					      <span style="color: #f2535b;">More data required : <?php echo $msg2Disp; ?></span>   
					 <?php } else {
							$checkforSecSectionHeader = 0;
							if( $getMoreInfoStatus != 0 ){
								$checkforSecSectionHeader = $checkforSecSectionHeader + 1;  
							}

							if($checkforSecSectionHeader > 0) {
								  $messageInHeader2 = '<a href="'.home_url().'?act=moreinfo" style="color: #6db8e2;">details</a>';
							  ?>
								 <span class="rd" style="color: #f2535b;">More data required : <?php echo $messageInHeader2; ?></span>
						<?php	} else { ?>
							  <span style="color: #7ad671">Reports ready for download</span>
					<?php
							}
					   }		
               } else { ?>  
		              <span style="color: #f2535b;">More data required : <?php echo $messageInHeaderSecSect; ?></span>
		 <?php } ?>	
        <?php  $encodedMsg2 = base64_encode($messageInHeaderSecSect); 	?>
		</h3></div>
		<div class="box_white marbtm30">
			<div class="pi-left">
				<h5>PROJECT CHARTER & SCOPE OF WORK</h5>
				<p>
				    Presents a full business case and thorough project reference for all stakeholders, also a project management and checklist document. This is your primary reference until you produce detailed and scheduled project plans. <br>
                    More information: <a href="<?php echo site_url(); ?>/help/charter-scope/" target="_blank" style="color: black; text-decoration: underline;">Charter & Scope.</a>
				</p>
			</div>

			<div class="pi-middle">
				<h5>Content</h5>
				<ul>
				<li>Executive summary</li>
				<li>Project objectives and benefits</li>
				<li>Return on investment</li>
				<li>Roles and Responsibilities</li>
				<li>Process, Timelines & milestones</li>
				<li class="last">Scope: Features and functions</li>
				</ul>
			</div>

			<div class="pi-right">
				<?php
					$total = $totalComplete + $totalNotrequired;
					if ( ($getMoreInfoStatus == 0) && ( $stage == 'two' ) && ($total >= $totalFA) && ($userProjectMore >= $compareMoreInfoNo) && ($getOrganizationStatus == 0) ) { ?>
						<?php  if ($stage2DocsPdf == 0) { ?>
							 <a class="createpdf" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
							<?php } else { ?>
							   <a class="createpdf" href="<?php echo home_url()."?act=project_detailpdf"; ?>">Create PDF</a>
							<?php } ?>

						<?php	if ($stage2DocsWord == 0) { ?>
							 <a class="createdoc" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
							<?php } else { ?>
							   <a class="createdoc" href="<?php echo home_url()."?act=project_detail"; ?>">Create doc</a>
							<?php } ?>

				<?php	} else { ?>
			               <a class="reportdisabled" href="javascript: void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg2; ?>');">Create PDF</a>
		                   <a class="reportdisabled doc" href="javascript: void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg2; ?>');">Create doc</a>  	
				<?php	} ?>

			</div>

			</div>

			<div class="box_white marbtm30">
			<div class="pi-left">
				<h5>Request for Quotation (RFQ)</h5>
				<p>
				    This is a non-confidential version of the project charter. It comprehensively describes your project and requirements for system vendors to respond to.  <br>
                    More information: <a href="<?php echo site_url(); ?>/help/rfq-guide/" target="_blank" style="color: black; text-decoration: underline;">RFQ guide.</a>
				</p>
			</div>

			<div class="pi-middle">
				<h5>Content</h5>
				<ul>
				<li>Executive summary</li>
				<li>Organisational background</li>
				<li>Project objectives and benefits</li>
				<li>Client team roles and Responsibilities</li>
				<li>Process, Timelines & milestones</li>
				<li>Evaluation criteria</li>
				<li>Scope: Features and functions</li>			
				</ul>
			</div>
			
			<div class="pi-right">
				<?php	if ( ($getMoreInfoStatus == 0) && ( $stage == 'two' ) && ($total >= $totalFA) && ($userProjectMore >= $compareMoreInfoNo) && ($getOrganizationStatus == 0) ) { ?>

					<?php  if ($stage2DocsPdf == 0) { ?>
						 <a class="createpdf" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
						<?php } else { ?>
						   <a class="createpdf" href="<?php echo home_url()."?act=rfq_pdf"; ?>">Create PDF</a>
						<?php } ?>

					<?php	if ($stage2DocsWord == 0) { ?>
						 <a class="createdoc" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
						<?php } else { ?>
						   <a class="createdoc" href="<?php echo home_url()."?act=rfq"; ?>">Create doc</a>
						<?php } ?>

				<?php	} else { ?>
			               <a class="reportdisabled" href="javascript: void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg2; ?>');">Create PDF</a>
		                   <a class="reportdisabled doc" href="javascript: void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg2; ?>');">Create doc</a>  	
				<?php	} ?>
			</div>
			
			</div>
			
			<div class="box_white marbtm30">
			<div class="pi-left">
				<h5>Request for Quotation (RFQ) response</h5>
				<p>
				    Insist that vendors use this format for responding to your RFQ. Not doing so will prevent you from comparing ‘apples with apples’ which is essential if you are to make the best decisions. <br>
                    More information: <a href="<?php echo site_url(); ?>/help/rfq-response/" target="_blank" style="color: black; text-decoration: underline;">RFQ Response Guide.</a>
				</p>
			</div>

			<div class="pi-middle">
				<h5>Content</h5>
				<ul>
					<li>About your organisation</li>
					<li>Vendor team and contacts</li>
					<li>Vendor credentials</li>
					<li>References</li>
					<li>Scope: Features and functions compliance</li>
					<li>Your process & milestones</li>
					<li>Pricing</li>
				
				</ul>
			</div>
			
			<div class="pi-right">
				<?php	if ( ($getMoreInfoStatus == 0) && ( $stage == 'two' ) && ($total >= $totalFA) && ($userProjectMore >= $compareMoreInfoNo) && ($getOrganizationStatus == 0) ) { ?>

					<?php  if ($stage2DocsPdf == 0) { ?>
					 	<a class="createpdf" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
					<?php } else { ?>
						<a class="createpdf" href="<?php echo home_url()."?act=rfq_responsepdf"; ?>">Create PDF</a>
					<?php } ?>

					<?php	if ($stage2DocsWord == 0) { ?>
						<a class="createdoc" href="javascript: void(0);" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Purchase</a>
					<?php } else { ?>
						<a class="createdoc" href="<?php echo home_url()."?act=rfq_response"; ?>">Create doc</a>
					<?php } ?>

				<?php	} else { ?>
			               <a class="reportdisabled" href="javascript: void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg2; ?>');">Create PDF</a>
		                   <a class="reportdisabled doc" href="javascript: void(0);" onclick="showMessageinPopup('<?php echo $encodedMsg2; ?>');">Create doc</a>  	
				<?php	} ?>
			</div>

			</div>
		</div>
</div>
