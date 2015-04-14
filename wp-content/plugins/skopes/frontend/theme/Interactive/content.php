<?php
	/*
	*   Name: Twisha Patel  Date: 14th Aug 2013
	*
	*   Login and register page funtionality
	*
	*/

	$objUser = new clsSkopes();
	if ( !is_user_logged_in() ) { 
		$page = get_site_url();
		wp_redirect($page."?act=login");
		exit;
	} else {
		$loggedin_id = get_current_user_id();
		$user = new WP_User( $loggedin_id );
		$role = $user->roles[0];

		if ( $role == 'vendor' ) {
			$page = get_site_url();
			wp_redirect($page."?act=eoi_res");
			exit;
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Skop.es - Digital Project Scoping Tool</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<meta name="description" content="" />
	<meta name="author" content="" />

	<!-- stylesheet -->
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/bootstrap.css" rel="stylesheet" media="screen" />
	<?php 
		$choosenTheme = get_option("selected_theme");
	if( $choosenTheme == 1 ) { ?>
		<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/style.css" rel="stylesheet" media="screen" />
	<?php } else { ?>
		<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/whiteLabel/style.css" rel="stylesheet" media="screen" />
	<?php } ?>
		<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/morestyle.css" rel="stylesheet" media="screen" />
<!--<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/style.css" rel="stylesheet" media="screen" />-->
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/bootstrap-responsive.css" rel="stylesheet" media="screen" />
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/font-awesome.css" rel="stylesheet" media="screen" />
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/jquery.mCustomScrollbar.css" rel="stylesheet" media="screen" />
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery-1.8.1.js"></script>
	<!--[if lt IE 9]>
		<script src="js/html5.js" type="text/javascript"></script>
		  <script type="text/javascript" src="js/PIE.js"></script>
		<link href="css/font-awesome-ie7.css" rel="stylesheet" media="screen" />
	<![endif]-->

	<!--[if IE 7]>
		<link href="css/font-awesome-ie7.css" rel="stylesheet" media="screen" />
	<![endif]-->


	<!-- favicon and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/apple-touch-icon-144.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/apple-touch-icon-72.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/apple-touch-icon.png" />
	<link rel="shortcut icon" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/favicon.png" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/favicon.ico" />

</head>
<body>
<?php 
$pages_array = array(31,33,35,37,41,44,46,48,50,52,54,56,58,60);
$cur_id = get_the_ID();
$pid = in_array($cur_id,$pages_array) ? $cur_id :'';

if(!is_page($pid)){  ?>
	<input type="hidden" id="path" value="<?php echo RC_TC_PLUGIN_URL ?>frontend"/>
		 <!-- Start: Popup  -->
		<div class="overlay"></div>
		<div class="popup-section registration" id="popup">
			<div class="popupbox">
				<a class="closebtn" href="#" id="close_popup"></a>
				<!-- Start: Box -->
				<div class="box">
				</div>
				<!-- End: Box -->
			</div>
		</div>
		<!-- Emd: Popup -->
<?php } ?>
<div class="overlay" style='display:none;'></div>
<div class="popup_overlay"></div>
	<div class="popup-section registration" id="popup">
		<div class="popupbox">
			<a class="closebtn" href="#" id="close_popup"></a>
			<!-- Start: Box -->
			<div class="box" id="big_popup"></div>
			<div class="box" id="small_popup"></div>
			<!-- End: Box -->
		</div>
</div>
		<section id="wrapper" class="subpage">

		<div class="loader"><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/loader.gif" alt="" /></div>

		<?php include("header.php");	?>

		<section class="middle-section">
			<div class="container">
				<div class="content-section clearfix">
							<!--<h4 class="subpage-title"><?php the_title(); ?></h4>-->
						<?php if (have_posts()) : ?>
							<?php $sideBar = get_post_meta( $post->ID, '_skope_side_bar', false ); 	?>
							<?php while (have_posts()) : the_post(); ?>

								<?php if (!empty($sideBar) && ($sideBar[0] !='') ) { ?>
									<div class="subpage-content left-side" id="post-<?php the_ID(); ?>">
								<?php } else { ?>
									<div class="subpage-content" id="post-<?php the_ID(); ?>">
								<?php } ?>

									<?php the_content(__('(more...)')); ?>
								</div>

								<?php if (!empty($sideBar) && ($sideBar[0] !='')) { ?>
									<div class="right-sidebar" id="post-<?php the_ID(); ?>">
										<div class="right_content"><?php		echo $sideBar[0];	 ?></div>
									</div>
								<?php } ?>
							<?php endwhile; ?>
						<?php else : ?>
							<div class="subpage-content">

								<p class="warning"><?php _e('Sorry, but you are looking for something that isn&#39;t here.'); ?></p>
							</div>
						<?php endif; ?>
				</div>
			</div>
		</section>
		<!-- End: Middle Section -->

		<?php include("footer.php");?>

	</section>

	<!-- Javascript -->
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/html5.js"></script>

	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/bootstrap.js"></script>
	<!--script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery.tinyscrollbar.min.js"></script-->
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery.placeholder.js"></script>
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery.mCustomScrollbar.min.js"></script>
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/custom.js"></script>

</body>
</html>
