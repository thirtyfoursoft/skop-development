<?php
	include("../../../../wp-load.php");
  if(isset($_REQUEST["type"]))
  {
      //echo $_REQUEST["type"];
	  if($_REQUEST["type"]=="register_success")
	  {
		  ?>
		  <h5>Thanks for registering</h5>
		  <?php
		  if(get_option("thankyou_msg"))
		  {
			echo  '<div class="content"><p>'.nl2br(get_option("thankyou_msg")).'</p></div>';
		  }
		  else
		  {
		?>
			<div class="content">
				<p>An email has sent to the address you have been provided as part of the account set up process. Please click on the link in the email to proceed.</p> 
				<p>Thank you for using Skopes and good luck with your project.</p>
				
			</div>
		<?php
		  }
	  }
	  else if($_REQUEST["type"]=="fail")
	  {
		?>
			<h5>Invalid email address or password!</h5>
			<?php
				  if(get_option("login_fail_msg"))
				  {
					echo  '<div class="content"><p>'.nl2br(get_option("login_fail_msg")).'</p></div>';
				  }
				  else
				  {
			  ?>
			<div class="content">
				<p>
				Please check your email address or password.
				</p>
				<p>
				Entered email address or password is not recognize by the system.
				</p>
			</div>
		<?php
		  }
	  }
	  else if($_REQUEST["type"]=="invalidemail")
	  {
		?>
			<h5>Invalid email address!</h5>
			<?php
				  if(get_option("invalid_email_msg"))
				  {
					echo  '<div class="content"><p>'.nl2br(get_option("invalid_email_msg")).'</p></div>';
				  }
				  else
				  {
			?>
					<div class="content">
						<p>
						Please check your email address.
						</p>
						<p>
						Entered email address does not exist in our system.
						</p>
					</div>
		<?php
				  }
	  }
	  else if($_REQUEST["type"]=="warningPopupOnClose")
	  { ?>
	        <div class="content">  <p>
			      <span>
				     Are you sure you wish to exit without saving your changes?
                  </span>
				  <a id="save_progress_popup" href="#" class="btn btn-blue"><span class="btn-inner"><strong>Save</strong> Progress</span></a> &nbsp;
                  <a id="complete_category_popup" href="#" class="btn btn-green"><span class="btn-inner"><strong>Functional Area</strong> Complete</span></a> 
			</p>
			</div>    
	 <?php }
	  else if($_REQUEST["type"]=="reportGenerationError")
	  {
		?>
		          <div class="content">  <p>
			              <h5 style="color: #6db8e2;">
						     <span style="color: #6db8e2; font-weight: bold;">
						      <?php
							      $str_repl = str_replace(" ", "+", $_REQUEST["msgText"]);
							      echo "More data required : ".base64_decode($str_repl);
							  ?>
							 </span>  
						  </h5>  
					</p>
					</div>	  
		<?php 
	  }
	  else if($_REQUEST["type"]=="notavailable")
	  {
		?>
		          <div class="content">  <p>
			              <h5>Not available in beta</h5>  
					</p>
					</div>	  
		<?php 
	  }
	  else if($_REQUEST["type"]=="catname_error")
	  {
		?>
			<h5>Category Name Required</h5>
			<?php
				  if(get_option("cat_name_msg"))
				  {
					echo  '<div class="content"><p>'.nl2br(get_option("cat_name_msg")).'</p></div>';
				  }
				  else
				  {
			?>
					 <div class="content">
						<p>
						You can not leave category name as blank. Please enter category name.
						</p>
					</div> 
		<?php
				  }
	  }
	  else if($_REQUEST["type"]=="catdesc_error")
	  {
		?>
			<h5>Category Description Required</h5>
			<?php
				  if(get_option("cat_desc_msg"))
				  {
					echo  '<div class="content"><p>'.nl2br(get_option("cat_desc_msg")).'</p></div>';
				  }
				  else
				  {
			?>
					<div class="content">
						<p>
						You can not leave description as blank. Please enter category description.
						</p>
					</div>
		<?php
				  }
	  }
	  else if($_REQUEST["type"]=="linename_error")
	  {
		?>
			<h5>Line Item Name Required</h5>
			<?php
				  if(get_option("lineitem_name_msg")) 
				  {
					echo  '<div class="content"><p>'.nl2br(get_option("lineitem_name_msg")).'</p></div>';
				  }
				  else
				  {
			?>
					<div class="content">
						<p>
						You can not leave line item name as blank. Please enter line item name.
						</p>
					</div>
		<?php
				  }
	  }
	  
	  else if($_REQUEST["type"]=="cat_add")
	  {
	  ?>
		<h5>Successfully Added Function</h5>
		 <div class="content">
			<p>
	           You have added successfully the new function.		
  			</p>
		</div> 	
	  <?php 
	  }
	  
	  else if($_REQUEST["type"]=="cat_delete")
	  {
	       $url = plugins_url();
	  ?>
		<h5>DELETE THIS FUNCTIONAL AREA?</h5>
		<?php
				  if(get_option("cat_del_msg"))
				  {
					echo  '<div class="content"><p>'.nl2br(get_option("cat_del_msg")).'</p></div>';
					echo '<a href="#" class="yes-delete" catid="'.$_REQUEST["catid"].'">
							<span class="inner"><strong>YES</strong> delete!</span>
						</a>';
				  }
				  else
				  {
			?>
					<div class="content">
						<p>
						Deleting this custom functional area will remove it and all data associated with it.
						</p>
						<p>
						Are you sure you wish to delete this custom functional area?
						</p>
					<form name="deleteForm" id="deleteFormID" action="<?php echo $url; ?>/skopes/frontend/delete_custom_cat.php" method="post">
                      <input type="hidden" name="catid" id="catidInForm" value="<?php echo $_REQUEST["catid"];?>" />  
					  <input type="submit" name="delete" class="inner innerspan" value="YES delete!" />
					</form>	
						<!-- <a href="#" class="yes-delete" catid="<?php //echo $_REQUEST["catid"];?>">
							<span class="inner"><strong>YES</strong> delete!</span>
						</a> --> 
					</div>
	  <?php
				  }
	  }
	  else if($_REQUEST["type"]=="linename_delete")
	  {
	  ?>
		<h5>DELETE THIS FEATURE?</h5>
		<?php
				  if(get_option("lineitem_del_msg") && $_REQUEST["lineid"]!='')
				  { 
					echo  '<div class="content"><p>'.nl2br(get_option("lineitem_del_msg")).'</p></div>';
					echo '<a href="#" class="yes-delete" lineid="'.$_REQUEST["lineid"].'" catid="'.$_REQUEST["catid"].'">
							<span class="inner"><strong>YES</strong> delete!</span>
						</a>';
				  }
				  else 
				  { 
				  echo  '<p>
						Deleting this feature will remove it and all data associated with it.
						</p>';
			?>
					
	  <?php
				  }
	  }
	  else if($_REQUEST["type"]=="saveprogress")
	  {
		?>
		<h5>Progress saved!</h5>
		<div class="content">
			<p>
				Your data has been saved successfully in the Skop.es
			</p>

		</div>
		<?php
	  }
	  else if($_REQUEST["type"]=="incompleteall")
	  {
		?>
		<h5>No category has been completed yet!</h5>
		<div class="content">
			<p>
				You've not yet added any category information, watch the HELP video to find out how to start!
			</p>

		</div>
		<?php
	  }
	  else if($_REQUEST["type"]=="incomplete")
	  {
		?>
		<h5>Complete all categories</h5>
		<div class="content">
			<p>
				You need to complete all categories before finishing. Please watch the HELP video to find out how
			</p>
		</div>
		<?php
	  }
	  else if($_REQUEST["type"]=="feedback")
	  {
		?>
		<h5>Thank You!</h5>
		<div class="content">
			<p>
				Admin will reiview your feedback. Thank you for submitting your feedback.
			</p>
		</div>
		<?php
	  }	   else if($_REQUEST["type"]=="linenoselect")	  {		?>		
	  <h5>Select a line item</h5>		
	  <div class="content">			
		<p>
			You have to select a line item to complete a functional area.			
		</p>		
	  </div>		
	  <?php	  }
	  
	  else if($_REQUEST["type"]=="nocompletecat")
	  {
		?>
		<h5>No category has been completed yet!</h5>
		<div class="content">
			<p>
				You've not yet completed any functional area.
			</p>

		</div>
		<?php
	  }
	  else if($_REQUEST["type"]=="catinscope")
	  {?>
		<h5>Category has been added in scope</h5>
		<div class="content">
			<p>
				Selected category has been added in scope.
			</p>
		</div>
	  <?php
	  }
	  else if($_REQUEST["type"]=="setstatus")
	  {?>
	    <h5></h5>
		<div class="content">
			<p>
				Click "No" on the categories that you don't want to include on <a href="http://beta.skop.es/?act=features" class="mainpage">functions</a>.
			</p><p></p>
		</div>
	  
	  <?php
	  }else if ($_REQUEST["type"]=="sherror"){ ?>
	     <h5></h5>
			<div class="content">
			<p>
				Please complete your stakeholder list before completing the feature line items
			</p>
			</div>
	  <?php }
	  
	  else if ($_REQUEST["type"]=="del_logo"){ ?>
		<div class="content"><p> Are you sure you want to delete the logo?</p></div>
		<a href="#" class="yes-deletelogo" >
			<span class="inner"><strong>YES</strong> delete!</span>
		</a> 
		 
		
	 <?php }
	 elseif ($_REQUEST["type"]=="noaccress"){?>
	    <div class="content">
			<p>
				This can no longer be accessed, the information is 'locked in' to prevent changes being made to 'published' and agreed details.
			</p>
		</div>
	 <?php
	 } 	else if($_REQUEST["type"]=="reportPlanError") {
		?>
		          <div class="content">  <p>
			              <h6 style="color: #6db8e2;">
						     <span style="color: #6db8e2;font-size: 14px;">
						      <?php
							      $str_repl = str_replace(" ", "+", $_REQUEST["msgText"]);
							      echo "".base64_decode($str_repl);
							  ?>
							 </span>  
						  </h6>  
					</p>
					</div>	  
		<?php 
	  }
  }
  
?>
