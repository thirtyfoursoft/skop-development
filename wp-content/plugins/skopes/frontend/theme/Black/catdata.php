<?php
	include '../../../../../../wp-load.php';
	if (!class_exists('userinfo')) {
		include('core/class_userinfo.php');
	}
	

	$objUser = new clsSkopes();
	$obj = new userinfo();

	if(isset($_REQUEST["catid"]))
	{
		if($_REQUEST["catid"]!="")
			$catdata=$objUser->GetCategoryDataById($_REQUEST["catid"]);
			
		if($_REQUEST["type"]=="DefaultCategory")
		{
?>
			<form>
				<h5 class="boxheading" id="cat_heading"><?php echo stripslashes($catdata->name);?></h5>
				<div class="scrollbox">
					<div class="scrollbar-section" id="catdesc_scroll">
						<p><?php echo nl2br(stripslashes($catdata->text));?></p>
					</div>
				</div>
				<!-- Start: Confirm Section -->
				<div class="confirm-section clearfix">
					<div class="hgroup clearfix">
						<h2>Include</h2>
						<h5>this Functional Area?</h5>
					</div>
					<div class="confirm-button clearfix">
					<?php
						$loggedin_id = get_current_user_id();
						$totalCompanyQues1 = $obj->cquestioncount();
						//$userCompanyQues1 = $obj->usercquestioncount($loggedin_id);
						$userCompanyQues1 = $obj->usercquestioncount_custom($loggedin_id);
						$totalProjectQues1 = $obj->projectpagecount();
						$userProjectQues1 = $obj->projectpageusercount($loggedin_id);
						$userProjectMore1 = $obj->projectmoreCount($loggedin_id);
						$userFaDisplay1 = $objUser->checkFunaStatus($loggedin_id);
						$userFaCount1 = $objUser->checkFunaStatuscount($loggedin_id);

						$result11 = $obj->getuserstage($totalCompanyQues1,$userCompanyQues1,$totalProjectQues1,$userProjectQues1,$userFaCount1);
						$stage1 = ($result11==3 ? 'two': 'one') ;

						// **************************************************************
						// Add file to check user paid for this feature or not 
						include("include/block-stage-one-pdf.php");
						$stage2DocsPdf = getRiskSectionStatus('stage2DocsPdf');
						$planMessage = 'This feature is a premium feature - please visit your <a href="'.home_url().'?act=myaccount">account</a> to add it to your package.';
			
						if($stage1 == 'two'){
					?>
						<?php if ($stage2DocsPdf == 0) { ?>
						    <a class="button-yes static" href="javascript:void(1)" id="" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');">Yes</a>
						<?php } else { ?>
						    <a class="button-yes static" href="javascript:void(1)" id="include_yes">Yes</a> 
						<?php } ?>
									

					<?php }else{ ?>
						<a class="button-yes static" href="javascript:void(1)" id="include_yes1">Yes</a>
					<?php } ?>
						
						<a class="button-no static" href="javascript:void(1)"id="include_no">No</a>
					</div>  
				</div>
				<!-- End: Confirm Section -->
			</form> 
<?php
		}
		else
		{

			$catname_len=$objUser->getDefaultData('Category_Name_Length');
			if(unserialize($catname_len->text)>0)
				$catname_len='maxlength="'.unserialize($catname_len->text).'"';
			else
				$catname_len="";

			$catdesc_len=$objUser->getDefaultData('Category_Text_Length');
			if(unserialize($catdesc_len->text)>0)
				$catdesc_len='maxlength="'.unserialize($catdesc_len->text).'"';
			else
				$catdesc_len="";
            $url = plugins_url();  
			
			if(isset($_REQUEST["catid"]) and $_REQUEST["catid"] != '')
			{
			     $catIDUse = $_REQUEST["catid"];
			}
			else
			{
			     $catIDUse = 0;
			}
		?>
			<form>
			    <div id="errorMsgID"></div>		
				
				<input type="hidden" name="catIDUse" id="catIDUseID" value="<?php echo $catIDUse; ?>" />
				
			    <input type="hidden" name="actionURL" id="actionURL" value="<?php echo $url; ?>" /> 
				<div class="label-section">
					<label id="catname_label" <?php if($catdata->name!="") { ?>style="visibility:hidden;"<?php } ?>>Enter new category name<em></em></label>
					<label id="catdesc_label" <?php if($catdata->text) { ?>style="visibility:hidden;"<?php } ?>>Add a detailed description<em></em></label>
				</div>
				<!--<h5 class="boxheading">Lorem ipsum dolor</h5>-->
				<div class="cc-boxheading">
					<input type="text" placeholder="&lt;Custom Category Name&gt;" value="<?php echo $catdata->name;?>" class="cc-heading" id="custom_catname" name="custom_catname" <?php echo $catname_len;?> />
				</div>
				<a href="javascript:void(1)" class="btn-save" id="save_custom_cat">Save</a>
				<a href="javascript:void(1)" class="btn-delete" id="delete_custom_cat">Delete</a>
					<div class="textarea_div">
						<textarea placeholder="&lt;Enter a description for your custom category&gt;" class="cc-textarea"  id="custom_catdesc" name="custom_catdesc"  <?php echo $catdesc_len;?>><?php echo $catdata->text;?></textarea>
					</div>
					<!-- End: Scrollbar Section -->

					<!-- Start: Confirm Section -->
					<div class="confirm-section clearfix">
						<div class="hgroup clearfix">
							<h2>Include</h2>
							<h5>this Category?</h5>
						</div>
						<div class="confirm-button clearfix">
							<a class="button-yes static" href="javascript:void(1)" id="include_yes">Yes</a>
							<a class="button-no static" href="javascript:void(1)"id="include_no">No</a>
						</div>
					</div>
					<!-- End: Confirm Section -->
			</form>
		<?php
		}
	}
?>
