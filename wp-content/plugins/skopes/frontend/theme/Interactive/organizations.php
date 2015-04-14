<?php 	include '../../../../../../wp-load.php'; 

session_start();
include('core/class_userinfo.php');
$objUser = new userinfo();
$data = $_POST;

global $wpdb;
$objUser2 = new clsSkopes(); 
$loggedin_id = get_current_user_id();

$totalCompanyQues = $objUser->cquestioncount();
$userCompanyQues = $objUser->usercquestioncount_custom($loggedin_id);
$totalProjectQues = $objUser->projectpagecount();
$userProjectQues = $objUser->projectpageusercount($loggedin_id);
$userProjectMore = $objUser->projectmoreCount($loggedin_id);
$userFaDisplay = $objUser2->checkFunaStatus($loggedin_id);
$userFaCount = $objUser2->checkFunaStatuscount($loggedin_id);

$result1 = $objUser->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
$stage = ($result1==3 ? 'two': 'one');

if (isset($_POST['formName']) && $_POST['formName'] != '' ) {
	$action = $_POST['formName'];

	switch ($action) {

  		case "basicInfo":
  			global $wpdb;

			$objUser2->saveSectionStageAfterStage2($loggedin_id, 'ABOUT YOUR ORGANISATION', 'orgBasicInfo', 'InProgress');
  			$restult =  $objUser2->SaveUserCompany($data,$loggedin_id);

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgBasicInfo'");

			if (empty($query)) {

				$updateqry = $wpdb->query("INSERT INTO ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1', userid='$loggedin_id', section_name = 'orgBasicInfo' ");
			
			} else {

				$updateqry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'orgBasicInfo' ";
				$wpdb->query($updateqry);
			}

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgBasicInfo'");
			$orgBasicInfo = $query[0];
			
			echo $orgBasicInfo;

        break;

        case "contactInfo":

  			global $wpdb;

			$objUser2->saveSectionStageAfterStage2($loggedin_id, 'ABOUT YOUR ORGANISATION', 'orgContactInfo', 'InProgress');
  			$restult =  $objUser2->SaveUserCompany($data,$loggedin_id);

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgContactInfo'");

			if (empty($query)) {

				$updateqry = $wpdb->query("INSERT INTO ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1', userid='$loggedin_id', section_name = 'orgContactInfo' ");
			
			} else {

				$updateqry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'orgContactInfo' ";
				$wpdb->query($updateqry);
			}

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgContactInfo'");
			$orgContactInfo = $query[0];
			
			echo $orgContactInfo;
		
        break;

        case 'orgMissionInfo':

			$objUser2->saveSectionStageAfterStage2($loggedin_id, 'ABOUT YOUR ORGANISATION', 'orgMissionInfo', 'InProgress');
  			$restult =  $objUser2->SaveUserCompany($data,$loggedin_id);

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgMissionInfo'");

			if (empty($query)) {

				$updateqry = $wpdb->query("INSERT INTO ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1', userid='$loggedin_id', section_name = 'orgMissionInfo' ");
			
			} else {

				$updateqry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'orgMissionInfo' ";
				$wpdb->query($updateqry);
			}

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgMissionInfo'");
			$orgMissionInfo = $query[0];
			
			echo $orgMissionInfo;			
			
        break;

	}
}

if (isset($_POST['formNameNew']) && $_POST['formNameNew'] != '' ) {
	$action = $_POST['formNameNew'];

	switch ($action) {
        case 'uploadLogo':

				$upload_dir = wp_upload_dir();			
				$upload_dirpath = $upload_dir['basedir'] .'/companylogos/'.$loggedin_id;
				if (!is_dir($upload_dirpath)) { 
					mkdir($upload_dirpath, 0777, true);
				}
				foreach($_FILES as $file) {
				 if(!empty($file['name'])){
								// upload company logo
								$uploadfiles = $file;

								$filename = $filetmp = $uploadfiles['name'];
								$filetmp = $uploadfiles['tmp_name'];		   
								$filetype = wp_check_filetype( basename( $filename ), null );			
								$filetitle = preg_replace('/\.[^.]+$/', '', basename( $filename ) );
								$filename = $filetitle . '.' . $filetype['ext'];
					
								$filedest = $upload_dirpath . '/' . $filename;
			
								 move_uploaded_file($filetmp, $filedest); // fiel uploading done
								 $objUser2->createThumbnail($filedest,$loggedin_id);

								 $_POST["com_logo"] = $filename;
								 unset($_POST["logoval"]);
								} else {
								 $_POST["com_logo"] = $_POST["logoval"];
								}
								update_user_meta($loggedin_id,'com_logo',$filename);
					}
        break;
      }
}

?>
