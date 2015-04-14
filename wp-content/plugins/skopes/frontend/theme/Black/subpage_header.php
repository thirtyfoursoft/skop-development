<?php
	include(RC_TC_BASE_DIR."/frontend/core/class_userinfo.php");
	$obj = new userinfo();
	$loggedin_id = get_current_user_id();
?>
<!-- Start: Header Section -->
<header class="header-section">
	<!-- Start: Header Top -->
	<section class="header-top">
		<div class="container clearfix">
			<a href="<?php echo home_url(); ?>" id="logo">Skopes</a>
			
				<?php
					if ( is_user_logged_in() )
					{
						if(get_user_meta($loggedin_id,'userprofile',true)==1)
						{
							if(isset($_REQUEST["act"]))
							{
								
							}
							else
							{
					?>
							<ul class="top-menu">
								<li>
									<a href="javascript:void(0)" class="helpbtn"> ? </a>
								</li>
								<li> 			
									<a href="javascript:void(0)" class="helpbtn1">  menu </a>
								</li>
							</ul>
					<?php
							}
						}
						else
						{
						?>
							<ul class="top-menu">
								<li> 			
								<a href="javascript:void(0)" class="helpbtn1">  menu </a>
								</li>
							</ul>
						<?php
						}
					?>
				<?php
					}
					
				?>
			
		</div>
	</section>
	<!-- End: Header Top -->
	<!-- Start: Help Section -->
	<?php
		if ( is_user_logged_in() )
		{
			if(get_user_meta($loggedin_id,'userprofile',true)==1 || !is_front_page())
			{

				$help_body=$objUser->getDefaultData('Help_Bodytext');
				$body=unserialize($help_body->text);

				$instruction_body=$objUser->getDefaultData('INSTRUCTIONS_BODY');
				$insbody=unserialize($instruction_body->text);

				$instruction_url=$objUser->getDefaultData('INSTRUCTIONS_VIDEO_URL');
				$insurl=unserialize($instruction_url->text);

	?>
	
	<?php
			
			$totalCompanyQues = $obj->cquestioncount();
			$userCompanyQues = $obj->usercquestioncount($loggedin_id);
			$totalProjectQues = $obj->projectpagecount();
			$userProjectQues = $obj->projectpageusercount($loggedin_id);
			$userProjectMore = $obj->projectmoreCount($loggedin_id);
			$userFaDisplay = $objUser->checkFunaStatus($loggedin_id);
			$userFaCount = $objUser->checkFunaStatuscount($loggedin_id);

			$result1 = $obj->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
			$stage = ($result1==3 ? 'two': 'one') ;
			
			if ($stage == 'two'){
				$com_link = "#";
				$comid = 'id = "com_noaccess" ';
				$pro_link = "#";
				$proid = 'id = "pro_noaccess" ';
				$feature_link = "#";
				$featureid = 'id = "feature_noaccess" ';
			 }else {
				$com_link = home_url()."?act=aboutcompany";
				$comid = '';
				$pro_link = home_url()."?act=aboutproject";
				$proid ='';
				$feature_link = home_url()."?act=features";
				$featureid = '';
			 }
		?>
	<section class="helpsection">
		
		<section style="display:none;"  class="helpbtm1">
						
				<div class="main-menu-top-cont">	

					<div class="container clearfix">

						<ul class="menu main-menu-bottom">
							<li> <a href="<?php echo home_url();?>"> Home </a> </li>
							<li class="<?php if($_REQUEST["act"] == 'getreport'){?> active <?php } ?>"> <a href="<?php echo home_url()."?act=getreport";?>"> Download Documents</a> </li>
							<li class="<?php if($_REQUEST["act"] == 'dashboard'){?> active <?php } ?>"> <a href="<?php echo home_url()."?act=dashboard";?>"> Insights & Dashboard </a> </li>
							<li class="help"> <a href="<?php echo get_permalink(64); ?>"> Help </a> </li>
							<li class="FAQ"> <a href="<?php echo get_permalink(66); ?>"> FAQ </a> </li>
							<li><a id="log_out" href="<?php echo home_url()."?act=logout";?>" class="btn btn-mini btn-purple"> Log out </a></li> 
						</ul>						
					</div>
				</div>
				
		
				<div class="container clearfix">
				
					<ul class="menu main-menu-top">
						<li class="<?php if($_REQUEST["act"] == 'myprofile'){?> active <?php } ?>"><a href="<?php echo home_url()."?act=myprofile";?>" id="my_account" class="btn btn-mini btn-redlight ">My Account</a> </li>
						<li class="<?php if($_REQUEST["act"] == 'aboutcompany'){?> active <?php } ?>"> 
						 
						<a href="<?=$com_link;?>" <?=$comid;?>> My organisation </a> </li>
						<li class="<?php if($_REQUEST["act"] == 'aboutproject'){?> active <?php } ?>"> <a href="<?=$pro_link;?>" <?=$proid;?>> Projects Basics </a> </li>
						<li class="<?php if($_REQUEST["act"] == 'moreinfo'){?> active <?php } ?>"> <a href="<?php echo home_url()."?act=moreinfo";?>"> Projects Details </a> </li>
						<li class="<?php if($_REQUEST["act"] == 'features'){?> active <?php } ?>"> <a href="<?=$feature_link;?>" <?=$featureid;?>> Functional Requirements </a> </li>
					
					</ul>
				</div>
		
	</section>
	</section>
	
	<?php
			}
		}
	?>
	<!-- End: Help Section -->
</header>
<!-- End: Header Section -->