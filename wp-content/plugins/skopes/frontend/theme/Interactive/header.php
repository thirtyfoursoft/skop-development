<!--  ********************* Differernt stylesheet for help popus that will come on the landing page  ************************-->
<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/helpPopup.css" rel="stylesheet" media="screen" />
<?php 
	if (!class_exists('userinfo')) {
		include('core/class_userinfo.php');
	}
	$obj = new userinfo();
	$loggedin_id = get_current_user_id();

	if (is_user_logged_in()) {
		include('include/update-completed-date.php');
	}
?>
<script language="javascript" type="text/javascript">
function intOnlyFunction(i)
{
	if(i.value.length>0)
	{
	     i.value = i.value.replace(/[^\d]+/g, '');
	}
}
</script>
<?php
	/*  Check user read the progress guid or not .. it will show only one time in whole life of user  */
	$progress_guide = get_user_meta( $loggedin_id, 'progress_guide', true );

	$cuurentURL = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];	
	$cuurentURL = rtrim($cuurentURL,"/");
	$blogURL = get_site_url();
	$blogURL = str_replace('http://', '', $blogURL);

	if ($cuurentURL == $blogURL) {
		$url = home_url()."?act=guide";
		wp_redirect($url);
		exit();
	}
			
	if (($progress_guide == '') || ( isset($_REQUEST["show"]) && ($_REQUEST["show"] == 'help') ) ) {
		if (is_user_logged_in()) {
			add_user_meta( $loggedin_id, 'progress_guide', '1' );
			include_once('helpPopup.php');
		}
	}

	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$url = "https://";
	} else {
		$url = "http://";
	}

	$url .= $_SERVER['SERVER_NAME'];
	if($_SERVER['SERVER_PORT'] != 80) {
		$url .= ":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$url .= $_SERVER["REQUEST_URI"];
	}
	$url .= '&show=help';

?>
<div id="main-content">
  <div class="attribution"><div class="container">Powered by <strong>Skop.es</strong></div></div>
<!---Header--->
	<header id="header">

	<!---Sub Header--->
	<div class="sub-header">
		<div class="container">
			<div class="header-logo"><a href="<?php echo home_url()."?act=guide"; ?>"><img src="<?php echo get_option('my_logo_img'); ?>" alt=""/></a></div>
			<div class="site-tagline"><?php echo get_option('skop_slogan')?></div>
			<?php
				if ( is_user_logged_in() ) {
					if(get_user_meta($loggedin_id,'userprofile',true)==1) {
				?>
					<ul class="top-menu">
						<li><a href="javascript:void(0)" class="helpbtn top_menu" status="help"> ? </a></li>
						<li><a href="javascript:void(0)" class="helpbtn1 top_menu" status="menu">  menu </a></li>
					</ul>
				<?php   } else {  ?>
					<ul class="top-menu">
						<li><a href="javascript:void(0)" class="helpbtn1">menu</a></li>
					</ul>						 
					<?php
					}
				}
			?>
			</div>
	</div>
	<!---End Sub Header--->
	<?php
			$com_link = home_url()."?act=aboutcompany";
			$comid = '';
			$pro_link = home_url()."?act=aboutproject";
			$proid ='';
				
			 $feature_link = home_url()."?act=features"; 

			$text = '';
			if(is_front_page()) {
			
			 $act = $_GET['act'];
			 switch($act) {
			 
				case 'features':

					$text = "This screen facilitates the collection of your functional requirements, i.e. which functionality is ‘in-scope’ for your project. Initially it asks only for high level (Functional Area) selection and input, later it asks for more detailed input on each feature line item. We have provided further <a href='".home_url()."/help/features'>instructions and guidance here</a>";
				break;

				case 'getreport':

					$text = "This interface allows you to create and download the documentation you will need for the project initiation process. For each document a brief explanation and a table of content are provided. <a href='".home_url()."/help/documents'>Further information here</a>.<br> Note: the documents only become available after sufficient information is input to Skopes";
				break;

				case 'guide':

					$text = "This page displays the process that Skopes guides you through. 1, 2 and 3 are pre-project stages and 4 follows the project completion. Each stage is described below and actions are also prompted and tracked from this interface. You can learn more about the <a href='".home_url()."/help/guide'>stages Skopes facilitates here</a>";
				break;

				case 'aboutcompany':

					$text = "Background information is important because it paints a bigger picture for suppliers and other projects participants about your organisation and its history. This helps people make suggestions and contribution beyond mechanicallyresponding to questions. Further guidanceby clicking on the “?” next to each question";
				break;

				case 'myprofile':

					$text = "Background information is important because it paints a bigger picture for suppliers and other projects participants about your organisation and its history. This helps people make suggestions and contribution beyond mechanicallyresponding to questions. Further guidanceby clicking on the “?” next to each question";
				break;

				case 'aboutproject':

					$text = "This interface gathers basic information about your project. This information is important in the project set up process, and will be published in the resulting documentation. Further guidance by clicking on the “?” next to each question";
				break;

				case 'moreinfo':
					$text = "This interface gathers further detail about your project. This information is important in the project set up and management processes. It will be published in the resulting documentation. Further by clicking on the “?” next to each question";
				break;

				case 'dashboard':
				case 'moreinfo':
					$text = "This interface displays two key sets of information, the first is your progress though using the Skopes tool towards setting up your project (progress bar and feature list). The second is anonymous peer intelligence and insights. <a href='".home_url()."/help/dashboard'>Further information here</a>";
				break;		  

			 }

			if ($cuurentURL == $blogURL) {
					$text = "This page displays the process that Skopes guides you through. 1, 2 and 3 are pre-project stages and 4 follows the project completion. Each stage is described below and actions are also prompted and tracked from this interface. You can learn more about the <a href='".home_url()."/help/guide'>stages Skopes facilitates here</a>";
			}
			
		}

		if ($text != '' ) {

			if( (isset($_REQUEST["act"]) && ($_REQUEST["act"] == 'aboutproject' || $_REQUEST["act"] == 'moreinfo' || $_REQUEST["act"] == 'features' || $_REQUEST["act"] == 'getreport' || $_REQUEST["act"] == 'guide' )) || ($cuurentURL == $blogURL) ) {

				$helpText = '<div class="sub-expend"><div class="container"><div class="left-colum"><span class="btn-help">Help</span><p>' . $text . '</p></div><div class="right-colum"><a class="view-btn" href=' . $url . '><span></span>Pop-up help for this page</a><br/><a class="view-btn" href="'.home_url().'/help"><span></span>Go to help page</a><br/><a class="view-btn" href="' . get_permalink(66) . '"><span>FAQ</span></a></div></div></div>';

			} else {
				$helpText = '<div class="sub-expend"><div class="container"><div class="left-colum"><span class="btn-help">Help</span><p>' . $text . '</p></div><div class="right-colum"><a class="view-btn" href="'.home_url().'/help"><span></span>Go to help page</a><br/><a class="view-btn" href="' . get_permalink(66) . '"><span>FAQ</span></a></div></div></div>';
			}

		} else {
			$helpText = '';
		}
	?>

	<?php
		if ( is_user_logged_in() ) {
			if(get_user_meta($loggedin_id,'userprofile',true)==1 || !is_front_page()) { ?>

				<div class="bottom-header" style="display:none;">
					<div class="container">
						<ul class="top-nav">
							<li class="firstHome"><a href="<?php echo home_url().'?act=guide'; ?>"><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/newHome/Homeicon.png" alt=""  style="width: 20px;">
							<li><a href="#">Input Screens</a>
							<div class="expend">
								<div class="container">
									<ul class="drop-menu">
										<li class="<?php if($_REQUEST["act"] == 'myprofile'){?> active <?php } ?>"><a href="<?php echo home_url()."?act=myprofile";?>" id="my_account" class="">My Profile</a> </li>
										<li class="<?php if($_REQUEST["act"] == 'aboutcompany'){?> active <?php } ?>"> 
										 <? //=$comid;?>
										<a href="<?=$com_link;?>" > My Organisation </a> </li>
										<li class="<?php if($_REQUEST["act"] == 'aboutproject'){?> active <?php } ?>"> <a href="<?=$pro_link;?>" <?=$proid;?>> Projects Basics </a> </li>
										<li class="<?php if($_REQUEST["act"] == 'moreinfo'){?> active <?php } ?>"> <a href="<?php echo home_url()."?act=moreinfo";?>">Advanced PM</a> </li>
										<li class="<?php if($_REQUEST["act"] == 'features'){?> active <?php } ?>"> <a href="<?=$feature_link;?>" <?=$featureid;?>> Functional Requirements </a> </li>
									</ul>
								</div>
							</div>
							</li>
							<li><a href="#">Info Screens</a>
							<div class="expend">
								<div class="container">
									<ul class="drop-menu">
										<li class="<?php if($_REQUEST["act"] == 'getreport'){?> active <?php } ?>"> <a href="<?php echo home_url()."?act=getreport";?>"> Download Documents</a></li>
										<li class="<?php if($_REQUEST["act"] == 'dashboard'){?> active <?php } ?>"> <a href="<?php echo home_url()."?act=dashboard";?>"> Insights & Dashboard </a> </li>
									</ul>
								</div>
							</div>
							</li>
							</li>
							<li class="last-child"><a href="#">Help</a>
								<?php echo $helpText;	?>
							</li>
							<li class=""><a href="#" id="my_account" class="">My Account</a>
								<div class="expend">
									<div class="container">
										<ul class="drop-menu">
											<li class="<?php if($_REQUEST['act'] == 'change-password'){?> active <?php } ?>"><a href="<?php echo home_url().'?act=change-password';?>">Change password</a> </li>									
											<li class="<?php if($_REQUEST['act'] == 'myaccount'){?> active <?php } ?>"><a href="<?php echo home_url()."?act=myaccount";?>" id="my_account" class="">Access levels and payment</a></li>
											<li><a id="log_out" href="<?php echo home_url()."?act=logout";?>" class=""> Log Out </a></li>										
										</ul>
									</div>
								</div>
							 </li>
							 <?php
						 	$is_EOI_vendor = 	get_option("_eoi_vendor");
							if ($is_EOI_vendor == 1 ):  ?>
								 <li class="<?php if($_REQUEST['act'] == 'eoi_result'){?> active <?php } ?>"><a href="<?php echo home_url().'?act=eoi_result';?>" id="eoi_result" class="">EOI Result</a></li>
						<?php endif; ?>
						</ul>
					</div>
				</div>
	<?php } 
			} 
	?>


	</header>
<!---End Header--->

<?php
if(isset($_REQUEST["edit"]) && ($_REQUEST["edit"]!="")) { ?>

	<script type="text/javascript">
		jQuery(window).load(function (){
			jQuery('.overlay').css('display:block');
		});
	</script>

<?php } else { ?>

	<script type="text/javascript">
		jQuery(window).load(function () {
			jQuery('.overlay').hide();
		});
	</script>

<?php } ?>
