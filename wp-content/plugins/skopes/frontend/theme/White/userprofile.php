<?php
	/*
	*   Name: Twisha Patel  Date: 16th Aug 2013
	*
	*   User Profilepafe funtionality
	*
	*/

	/*global $wp_rewrite;
	$wp_rewrite = new WP_Rewrite();
	*/
?>
 
<div class="content-section clearfix">
	<div class="container">
		<div class="subpage-content">
			<div class="box">
				<h2 class="boxheading">My Profile</h2>
				<form class="user-profileform" id="frmprofile" method="post" onsubmit="return fnvalidate_profile()">
					<input type="hidden" name="hiddenaction" id="hiddenaction" value="SaveProfile" />
					<input type="submit" name="hbtnsave" id="hbtnsave" style="position: absolute; left: -9999px; width: 1px; height: 1px;" />
					  <?php if ( !is_user_logged_in() ) : ?>
 
<p class="warning">
<?php _e('You must be logged in to edit your profile.', 'profile'); ?>
</p><!-- .warning -->
<?php else : ?>

<?php if ( $_GET['updated'] == 'true' ) : ?> <div id="message" class="updated"><p>Your profile has been updated.</p></div> <?php endif; ?>
<form method="post" id="adduser" action="<?php the_permalink(); ?>">

<div class="upf-raw">
	<div class="upf-cell column1">
		<label>First Name:</label>
	</div>
	<div class="upf-cell column2">
		<input class="text-input" name="first_name" type="text" id="first_name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
	</div>
</div>

<div class="upf-raw">
	<div class="upf-cell column1">
		<label>Last Name:</label>
	</div>
	<div class="upf-cell column2">
		<input class="text-input" name="last_name" type="text" id="last_name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
	</div>
</div>

<div class="upf-raw">
	<div class="upf-cell column1">
		<label>E-mail *:</label>
	</div> 
	<div class="upf-cell column2">
		<input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
		<span class="error" id="email_error"></span> 
	</div>
</div>

<div class="upf-raw">
	<div class="upf-cell column1">
		<label>Website:</label>
	</div>
	<div class="upf-cell column2">
		<input class="text-input" name="url" type="text" id="url" value="<?php the_author_meta( 'user_url', $current_user->ID ); ?>" placeholder="http://www.example.com"/>
		<span class="error" id="url_error"></span>
	</div>
</div>
 
<?php
//action hook for plugin and extra fields
//do_action('edit_user_profile',$current_user);
?>

</form><!-- #adduser -->
<?php endif; 
  $getval = $obj->GetUserProfileInfo($loggedin_id) == 4 ? 'btn-green1':'btn-blue1';
  $div_val = ($getval == 'btn-green1' ? '':'txt');
  
  ?>
			<div class="upf-buttonsection  <?php echo $div_val;?>" id="btnsection">
				<?php
					if(get_user_meta($loggedin_id,"userprofile",true)=="1") {
				?>
						<a class="<?php echo $getval;?> btn1 mrgn" href="#" id="save_profile"><span class="btn-inner1 fnt d1"><strong>OK!</strong> Save Updates</span></a>
				<?php } else {  ?>
						<a class="staticbtn" href="#" id="save_profile"><span class="innerbtn"><strong>OK!</strong> Start Scoping</span></a>
				<?php  }   ?>
			</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/userprofile.js"></script>
