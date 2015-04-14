<?php
/* *****************
*
* File made by Royal Tyagi on 23-Dec-14
* Added tab's on this page so we can mangae multiple forms from single page in admin
*
*******************/

global $pagenow;

if ( $pagenow == 'admin.php' && $_GET['page'] == 'specdoc-settings' ) {

	if ( $_POST["skope-settings-submit"] == 'Y' ) {
		check_admin_referer( "skope-settings-page" );
		skope_save_skope_basic_settings( $_POST );
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('admin.php?page=specdoc-settings&'.$url_parameters));
		exit;
	}

	if ( $_POST["skope-plan-submit"] == 'Y') {
		check_admin_referer( "skope-settings-page" );
		skope_updaate_skope_plan_settings( $_POST['plan_setting'] );
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('admin.php?page=specdoc-settings&'.$url_parameters));
		exit;
	}

	if ($_POST['skope-pp-standard-submit'] == 'Y') {
		check_admin_referer( "skope-settings-page" );
		skope_updaate_skope_ppStandard_settings( $_POST['standard'] );
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('admin.php?page=specdoc-settings&'.$url_parameters));
		exit;
	}

	if ($_POST['skope-pp-pro-submit'] == 'Y') {
		check_admin_referer( "skope-settings-page" );
		skope_updaate_pp_pro_settings( $_POST['pro'] );
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('admin.php?page=specdoc-settings&'.$url_parameters));
		exit;
	}

	skope_settings_page();
}

/* ******************
* 
* Function for Save data in db for first tab (setting)
*
******************* */

function skope_save_skope_basic_settings( $data ) {

		if($data["hidden_action"]=="SaveMessages") {
			update_option("my_logo_img",$data["my_logo_img"]);
			update_option("selected_theme",$data["selected_theme"]);
			update_option("risk_management",$data["risk_management"]);
			update_option("skop_slogan",$data["skop_slogan"]);
			update_option("thankyou_msg",$data["thankyou_msg"]);
			update_option("login_fail_msg",$data["login_fail_msg"]);
			update_option("invalid_email_msg",$data["invalid_email_msg"]);
			update_option("cat_name_msg",$data["cat_name_msg"]);
			update_option("cat_desc_msg",$data["cat_desc_msg"]);
			update_option("lineiten_name_msg",$data["lineiten_name_msg"]);
			update_option("lineitem_del_msg",$data["lineitem_del_msg"]);
			update_option("cat_del_msg",$data["cat_del_msg"]);
			update_option("confirm_email_sub",$data["confirm_email_sub"]);
			update_option("confirm_email_content",$data["confirm_email_content"]);
			update_option("_eoi_vendor",$data["eoi_vendor"]);

		} else {
			$error = "Please select frontend page.";
		}
}

/* ******************
* 
* Function for Save data in db for Second tab ( Payment )
*
******************* */

function skope_updaate_skope_plan_settings( $alldata ) {

	global $wpdb;
	 
	foreach ( $alldata as $data ) {

		if ( isset( $data['status'] ) && $data['status'] == 'on' ) {
			$status = 1;
		} else {
			$status = 0;
		}

		$query = "UPDATE ". $wpdb->prefix ."specdoc_product SET price = '" . $data['price'] . "', sort_order = '" . $data['sort_order'] . "', status = '" . $status . "', date_modified = NOW() WHERE id = '" . $data['id'] . "' ";

		$result = $wpdb->query($query);
	}

}

/* ******************
* 
* Function for Save data in db for third tab ( PayPal standard )

*
******************* */

function skope_updaate_skope_ppStandard_settings( $data ) {

		$standard = serialize($data);
		update_option( 'pp_standard', $standard );
}


/* ******************
* 
* Function for Save data in db for fourth tab ( PayPal Pro )

*
******************* */

function skope_updaate_pp_pro_settings( $data ) {

		$standard = serialize($data);
		update_option( 'pp_pro', $standard );
}

/* ******************
* 
* Function for check & create products table in db with dummy contenet 
*
******************* */

function skope_checkAndCreateProductTables() {

	global $wpdb;
	global $specdoc_db_version;

	$table_name = $wpdb->prefix."specdoc_product";

	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

		$sql = "CREATE TABLE " . $table_name . " (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
		  `product_key` varchar(255) DEFAULT NULL,
		  `sort_order` int(3) NOT NULL DEFAULT '0',
		  `status` tinyint(1) NOT NULL DEFAULT '0',
		  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		/* ******
		-- Dumping data for table `specdoc_product`
		****** */

		$insert = "INSERT INTO " . $table_name . " (`id`, `name`, `price`, `product_key`, `sort_order`, `status`, `date_added`, `date_modified`) VALUES
		(1, 'Access Tool/Setup Account', '0.00', 'signup_account', '1', '', '20014-12-23 16:06:50', '20014-12-23 01:05:39'),
		(2, 'Access stage one documents (PDF)', '0.00', 'stage1pdf', '2', '', '20014-12-23 16:06:50', '20014-12-23 01:05:39'),
		(3, 'Access stage one documents (Word)', '0.00', 'stage1word', '3', '', '20014-12-23 16:06:50', '20014-12-23 01:05:39'),
		(4, 'Access Risk section', '0.00', 'risk_section', '4', '', '20014-12-23 16:06:50', '20014-12-23 01:05:39'),
		(5, 'Access stage two feature Line items poup and stage tow documents (PDF)', '0.00', 'stage2DocsPdf', '5', '', '20014-12-23 16:06:50', '20014-12-23 01:05:39'),
		(6, 'Access stage two documents (Word)', '0.00', 'stage2DocsWord', '6', '', '20014-12-23 16:06:50', '20014-12-23 01:05:39'),
		(7, 'Access Project close functions', '0.00', 'project_close', '7', '', '20014-12-23 16:06:50', '20014-12-23 01:05:39');";

		dbDelta($insert);
	}

	$table_name = $wpdb->prefix."specdoc_order";

	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

		$sql= "CREATE TABLE " . $table_name . " (
		  `order_id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL DEFAULT '0',
		  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
		  `payment_method` varchar(44) NOT NULL,
		  `order_status` varchar(66) NOT NULL,
		  `currency_code` varchar(3) NOT NULL,
		  `ip` varchar(40) NOT NULL,
		  `date_added` datetime NOT NULL,
		  `transection_id` TEXT NOT NULL,
		  PRIMARY KEY (`order_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

	}

	$table_name = $wpdb->prefix."specdoc_order_product";

	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

		$sql = "CREATE TABLE " . $table_name . " (
		  `order_product_id` int(11) NOT NULL AUTO_INCREMENT,
		  `order_id` int(11) NOT NULL,
		  `product_id` int(11) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `quantity` int(4) NOT NULL,
		  `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
		  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
		  PRIMARY KEY (`order_product_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

	}

}


/* ******************
* 
* Function for get all plans or products from db
*
******************* */

function skope_getAllProducts() {
	global $wpdb;
	 
	$query = "SELECT * FROM ". $wpdb->prefix ."specdoc_product ORDER BY sort_order ASC ";

	$result = $wpdb->get_results($query,ARRAY_A);
	return $result;

}


/* ******************
* 
* Function for manage tab's
*
******************* */

function skope_admin_tabs( $current = 'setting' ) {
    $tabs = array( 'setting' => 'Setting', 'plans' => 'Plans', 'paypal' => 'PayPal', 'paypal_pro' => 'PayPal Pro', 'doc_template' => 'Doc templates' );
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=specdoc-settings&tab=$tab'>$name</a>";
        
    }
    echo '</h2>';
}


/* ******************
* 
* Function for load the HTML of the page according to tab's
*
******************* */

function skope_settings_page() {
	global $pagenow;
?>
<div class="wrap">
	<h2><?php echo 'Skope Settings'; ?></h2>
	
	<?php
		if ( 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Your settings have been saved.</p></div>';
		if ( isset ( $_GET['tab'] ) ) skope_admin_tabs($_GET['tab']); else skope_admin_tabs('setting');
	?>

	<div id="poststuff">
			<?php
	
			if ( $pagenow == 'admin.php' && $_GET['page'] == 'specdoc-settings' ) {
			
				if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; 
				else $tab = 'setting'; 
				
				if ( isset ( $_REQUEST['upload'] ) ) $uploadSt = $_REQUEST['upload']; 
				else $uploadSt = 0; 
				
				if($uploadSt == 1)
				{
				     if( isset($_FILES['upload_template']['error']) and $_FILES['upload_template']['error'] == 0 )
					 {
					         $ext = pathinfo($_FILES['upload_template']['name'], PATHINFO_EXTENSION); 
							 if($ext == 'docx')
							 {
							      $correctFileName = $_REQUEST['filename'].'.docx';
								    if($correctFileName == $_FILES['upload_template']['name'])
									{
									      $fileTmpPath = $_FILES['upload_template']['tmp_name'];
                                          $fileURL =  $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/skopes/frontend/wordtemplate/files/templates/'.$correctFileName;	
                                          move_uploaded_file($fileTmpPath, $fileURL); 
										  echo "<span style='color: blue; font-weight: bold;'>You have successfully replaced the document file.</span>"; 
									}
									else
									{
									      echo "<span style='color: red; font-weight: bold;'>You have uploaded the document file with wrong name.</span>"; 
									} 
							 }
							 else
							 {
							     echo "<span style='color: red; font-weight: bold;'>You have uploaded the wrong extension document file.</span>";
							 }
					 } 
				}
				
				switch ( $tab ) {
					case 'setting' :
						include('sections/section-setting-tab.php');
					break;

					case 'plans' :
						include('sections/section-plans.php');
					break;
					
					case 'paypal' :
						include('sections/section-paypal.php');
					break;

					case 'paypal_pro':
						include('sections/section-paypal-pro.php');
					break;

					case 'doc_template':
						include('sections/section-doc-template.php');
					break;
				}
			}
			?>

	</div>
</div>

<?php
}
