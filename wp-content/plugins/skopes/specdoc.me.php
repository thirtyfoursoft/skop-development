<?php
/*
Plugin Name: Skop.es
Description: A private plugin for Skop.es
Version: 1.0
Plugin URI: http://prismitsolutions.com
Author: Prism IT Solutions, Surat, Gujarat (INDIA)
Author URI: http://prismitsolutions.com
License: Developed for Skop.es
*/
ob_start();
require_once('reporting.php');
require_once('inc/meta-box.php');

//require_once('userprofile.php');
add_action('admin_menu', 'my_plugin_menu');

define('CATEGORY_ID', 0);
define('CATEGORY_IS_CUSTOM', 1);
define('CATEGORY_NOTE', 2);
define('CATEGORY_STATUS', 3);
define('CATEGORY_START_TIME', 4);
define('CATEGORY_FINISH_TIME', 5);
define('CATEGORY_LINEITEMS', 6);

define('LINEITEM_ID', 0);
define('LINEITEM_IS_CUSTOM', 1);
define('LINEITEM_IMPORTANCE', 2);
define('LINEITEM_NOTE', 3);
define('LINEITEM_IS_AUTOMATED', 4);
define('LINEITEM_YEAR', 5);
define('LINEITEM_INVESTMENT_REQ', 6);
define('LINEITEM_COST', 7);
define('LINEITEM_TIME', 8);
define('LINEITEM_STATUS', 9);
define('LINEITEM_START_TIME', 10);
define('LINEITEM_FINISH_TIME', 11);
define('LINEITEM_IS_BENEFIT', 12);
define('LINEITEM_BENEFIT_FOR', 13);
define('LINEITEM_BENEFIT_OTHER', 14);

// Function to add the setting link on the plugin page.
function skope_plugin_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=specdoc-settings">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'skope_plugin_settings_link' );

/* ******** Start Logo upload **************  */
function my_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', WP_PLUGIN_URL.'/skopes/js/my-script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}
 
function my_admin_styles() {
	wp_enqueue_style('thickbox');
}
 
if (isset($_GET['page']) && $_GET['page'] == 'specdoc-settings') {
	add_action('admin_print_scripts', 'my_admin_scripts');
	add_action('admin_print_styles', 'my_admin_styles');
}


function my_plugin_menu() {
	add_menu_page(
		'Skop.es', 'Skop.es', 'manage_options', 'specdoc-main',
		'specdoc_plugin_main'
	);

	add_submenu_page(
		'specdoc-main', 'Specdoc.me Options', 'Features and Functions',
		'manage_options', 'specdoc-category-admin',
		'specdoc_plugin_category_options'
	);

	add_submenu_page(
		'specdoc-main', 'Specdoc.me Defaults', 'Defaults Administration',
		'manage_options', 'specdoc-defaults-admin',
		'specdoc_plugin_category_defaults'
	);

	add_submenu_page(
		'specdoc-main', 'Specdoc.me Defaults', 'Settings and fees',
		'manage_options', 'specdoc-settings',
		'skopes_settings'
	);

	add_submenu_page(
		'specdoc-main', 'Specdoc.me Defaults', 'Users and payments',
		'manage_options', 'users-and-payments',
		'skope_users_and_payments'
	);

	add_submenu_page(
		'specdoc-main', 'Specdoc.me Defaults', 'Help Text',
		'manage_options', 'help-text',
		'skope_help_text'
	);

	add_submenu_page(
		'specdoc-main', 'Specdoc.me Defaults', 'Guide page config',
		'manage_options', 'guide-page-config',
		'skope_guide_page_config'
	);

	add_submenu_page(
		'specdoc-main', 'Specdoc.me Reports', 'Reports',
		'manage_options', 'specdoc-reports',
		'specdoc_plugin_reports_main'
	);

	$is_EOI_vendor = 	get_option("_eoi_vendor");
	
	if ($is_EOI_vendor == 1 ):
		add_submenu_page(
		    'specdoc-main', 'Specdoc.me EOI', 'EOI Response',
		    'manage_options', 'specdoc-eoi-response',
		    'specdoc_plugin_eoi_response'
		);	
    endif;
	
	add_submenu_page( 'specdoc-main','Organisation Questions', 'Organisation Questions', 'manage_options', 'specdoc-org-fields', 'specdoc_plugin_org_questions');
	add_submenu_page( 'specdoc-main','Project Questions ', 'Project Questions', 'manage_options', 'specdoc-project-fields', 'specdoc_plugin_project_questions');
	add_submenu_page( 'specdoc-main','Advanced questions', 'Advanced questions', 'manage_options', 'specdoc-mproject-fields', 'specdoc_more_project_questions');

	add_submenu_page(
		'specdoc-reports', 'Specdoc.me Custom Category Reports', 'Custom Category Report',
		'manage_options', 'specdoc-custom-category-reports',
		'specdoc_plugin_custom_category'
	);

	add_submenu_page(
		'specdoc-reports', 'Specdoc.me Custom Lineitem Reports', 'Custom Lineitem Report',
		'manage_options', 'specdoc-custom-lineitem-reports',
		'specdoc_plugin_custom_lineitem'
	);

	add_submenu_page(
		'specdoc-reports', 'Specdoc.me Standard Lineitem Reports', 'Standard Lineitem Report',
		'manage_options', 'specdoc-standard-lineitem-reports-byaverage',
		'specdoc_plugin_standard_lineitem_byaverage'
	);

	add_submenu_page(
		'specdoc-reports', 'Specdoc.me Standard Lineitem Reports', 'Standard Lineitem Report',
		'manage_options', 'specdoc-standard-lineitem-reports-byselections',
		'specdoc_plugin_standard_lineitem_byselections'
	);

	// The next one won't show up on purpose

	add_submenu_page(
		'specdoc-main-dummy', 'Specdoc.me LineItems', 'Specdoc LineItems',
		'manage_options', 'specdoc-lineitems-admin',
		'specdoc_plugin_category_lineitems'
	);

}

/*
*	Name: Twisha Date:25-7-2013
*   Start
*/
function scripts_method() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('custom-script',plugins_url().'/skopes/js/jquery.dataTables.js',array( 'jquery' ));
	wp_enqueue_style('custom-style',plugins_url().'/skopes/css/datatable.css');
	
	/* Adding Plugin custm CSS file */
	wp_enqueue_style('skopes-accordion-plugin-style', plugins_url().'/skopes/css/newstyle.css');
	wp_enqueue_style('skopes-new-accordion-plugin-style', 'http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
	
	/* Adding plugin javascript Main file */
	wp_enqueue_script('skopes-accordion-plugin-main', plugins_url().'/skopes/js/skopes-accordion-main.js', array('jquery'));

	/* Adding plugin javascript active file */
	wp_enqueue_script('skopes-accordion-plugin-script-active', plugins_url().'/skopes/js/skopes-accordion-active.js', array('jquery'), '1.0', true);
	wp_enqueue_script('skopes-accordion-plugin1-script-active', 'http://code.jquery.com/jquery-1.9.1.js', array('jquery'), '1.0', true);
	wp_enqueue_script('skopes-accordion-plugin2-script-active', 'http://code.jquery.com/ui/1.10.4/jquery-ui.js', array('jquery'), '1.0', true);
	
}

add_action( 'admin_enqueue_scripts', 'scripts_method' );
/*
*   Name: Twisha Date:25-7-2013
*   End
*/



function specdoc_plugin_main() {
	echo '<p>This plugin is &copy; 2011 Skop.es</p>';
	echo '<p>Check specific submenus for configurable options.</p>';
}

function specdoc_plugin_reports_main() {

	echo "Please choose a specific report.";
	echo '<p>';
	echo '<a href="admin.php?page=specdoc-custom-category-reports">Quick custom category list</a><br/>';
	echo '<a href="admin.php?page=specdoc-custom-lineitem-reports">Quick custom line item list</a><br/>';
	echo '<a href="admin.php?page=specdoc-standard-lineitem-reports-byaverage">Standard line items by average rating</a><br/>';
	echo '<a href="admin.php?page=specdoc-standard-lineitem-reports-byselections">Standard line items by number of selections</a><br/>';
	$user_id = get_current_user_id();
	echo '<br><a href="'.plugins_url().'/specdoc.me/getexcel.php?user_id='.$user_id.'&user_key='.md5('hej'.$user_id).'&reportid=all">Export All Report</a><br>';
	echo '<br/>More reports coming soon.';
}

function specdoc_plugin_category_options() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	if($_POST['id'] && isset($_POST['Save']))
		updateCategory($_POST['id'], $_POST['name'], $_POST['text'], $_POST['comment']);

	if($_POST['id'] && isset($_POST['Delete']))
		deleteCategory($_POST['id']);

	if(isset($_POST['Add_New']))
		addCategory($_POST['name'], $_POST['text'], $_POST['comment']);

	$defaults = getDefaults();

	$ca_namelength    = (int)$defaults["Category_Name_Length"];
	if($ca_namelength == 0)
		$ca_namelength = 50;

	$ca_textlength    = (int)$defaults["Category_Text_Length"];
	if($ca_textlength == 0)
		$ca_textlength = 200;

	$ca_commentlength    = (int)$defaults["Category_Comment_Length"];
	if($ca_commentlength == 0)
		$ca_commentlength = 200;

	echo '
	<SCRIPT LANGUAGE="JavaScript">
		function CountLeft(field, count, max) {
			count.innerText = (max - field.value.length) + "/"+max;
		}
	</script>
	';

	echo '<div class="wrap">';
	echo '<h1>Features and Functions</h1>'; 
	echo '<table>';
	echo '<tr>';
	echo '<td>&nbsp</td><td><h3>NAME</h3></td><td><h3>TEXT</h3></td><td><h3>COMMENT</h3></td><td>&nbsp</td><td>&nbsp</td></tr>';
	$categories = getCategories();
	$colortoggle = 0;
	foreach ($categories as $category) {

		$ca = $category->category_id;

		if($colortoggle == 0)
			echo '<tr style="background-color:#ffeeee;">';
		else
			echo '<tr style="background-color:#eeeeff;">';

		$colortoggle = !$colortoggle;

		echo '<form name="form'.$category->category_id.'" method="post" action=""><td>';
		echo '&nbsp;';
		echo '<input type="hidden" name="id" value="'.$category->category_id.'"/></td>';

		echo '<td valign="top"><label id="lbl'.$ca.'">'.($ca_namelength-strlen($category->name)).'/'.$ca_namelength.'</label><br/>'.
				'<input type="text" size="40" name="name" value="'.stripslashes(htmlentities($category->name)).'"'.
				' onKeyDown="CountLeft(this.form.name, getElementById(\'lbl'.$ca.'\'), '.$ca_namelength.');" '.
				' onKeyUp="CountLeft(this.form.name, getElementById(\'lbl'.$ca.'\'), '.$ca_namelength.');" /></td>';

		echo '<td><label id="lblt'.$ca.'">'.($ca_textlength-strlen($category->text)).'/'.$ca_textlength.'</label><br/>'.
			'<textarea cols="40" rows="5" name="text"'.
				' onKeyDown="CountLeft(this.form.text, getElementById(\'lblt'.$ca.'\'), '.$ca_textlength.');" '.
				' onKeyUp="CountLeft(this.form.text, getElementById(\'lblt'.$ca.'\'), '.$ca_textlength.');">'.stripslashes(htmlentities($category->text)).'</textarea></td>';

		echo '<td><label id="lblc'.$ca.'">'.($ca_commentlength-strlen($category->comment)).'/'.$ca_commentlength.'</label><br/>'.
			'<textarea cols="40" rows="5" name="comment"'.
				' onKeyDown="CountLeft(this.form.comment, getElementById(\'lblc'.$ca.'\'), '.$ca_commentlength.');" '.
				' onKeyUp="CountLeft(this.form.comment, getElementById(\'lblc'.$ca.'\'), '.$ca_commentlength.');">'.stripslashes(htmlentities($category->comment)).'</textarea></td>';

		echo '<td><input type="submit" name="Save" value="Save"/>';
		echo '<input type="submit" name="Delete" value="Delete"/></td>';
		echo '</form><td>';
		echo '<form method="post" action="admin.php?page=specdoc-lineitems-admin"><input type="submit" '.
			 'name="Line items" value="Line items"/><input type="hidden" name="category_id" value="'.
			 $category->category_id.'"/></form></td></tr>';
	}

	if($colortoggle == 0)
		echo '<tr style="background-color:#ddddff;">';
	else
		echo '<tr style="background-color:#eeeeff;">';

	$ca = "z01";

	echo '<form name="form" method="post" action=""><td>NEW</td>';
	echo '<td valign="top"><label id="lbl'.$ca.'">'.($ca_namelength).'/'.$ca_namelength.'</label><br/>'.
			'<input type="text" size="40" name="name" value=""'.
			' onKeyDown="CountLeft(this.form.name, getElementById(\'lbl'.$ca.'\'), '.$ca_namelength.');" '.
			' onKeyUp="CountLeft(this.form.name, getElementById(\'lbl'.$ca.'\'), '.$ca_namelength.');" /></td>';

	echo '<td><label id="lblt'.$ca.'">'.($ca_textlength).'/'.$ca_textlength.'</label><br/>'.
		'<textarea cols="40" rows="5" name="text"'.
			' onKeyDown="CountLeft(this.form.text, getElementById(\'lblt'.$ca.'\'), '.$ca_textlength.');" '.
			' onKeyUp="CountLeft(this.form.text, getElementById(\'lblt'.$ca.'\'), '.$ca_textlength.');"></textarea></td>';

	echo '<td><label id="lblc'.$ca.'">'.($ca_commentlength).'/'.$ca_commentlength.'</label><br/>'.
		'<textarea cols="40" rows="5" name="comment"'.
			' onKeyDown="CountLeft(this.form.comment, getElementById(\'lblc'.$ca.'\'), '.$ca_commentlength.');" '.
			' onKeyUp="CountLeft(this.form.comment, getElementById(\'lblc'.$ca.'\'), '.$ca_commentlength.');"></textarea></td>';

		echo '<td><input type="submit" name="Add New" value="Add New"/></td><td>&nbsp;</td>';
	echo '</form></tr>';


	echo '</table></div>';
}

register_activation_hook(__FILE__,'specdoc_createtables');

global $specdoc_db_version;
$specdoc_db_version = "1.0";

function specdoc_createtables() {
	global $wpdb;
	global $specdoc_db_version;

	$table_name = $wpdb->prefix."specdoc_categories";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	{
		$sql = "CREATE TABLE " . $table_name . " (
			category_id mediumint(9) NOT NULL AUTO_INCREMENT,
			foruser bigint(20) unsigned,
			enabled tinyint not null,
			name text NOT NULL,
			text text NOT NULL,
			comment text not null,
			UNIQUE KEY category_id (category_id),
			FOREIGN KEY (foruser) references ".$wpdb->prefix."users(ID)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

	}

	$table_name = $wpdb->prefix."specdoc_line_items";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	{
		$sql = "CREATE TABLE " . $table_name . " (
			line_item_id mediumint(9) NOT NULL AUTO_INCREMENT,
			category_id mediumint(9) NOT NULL,
			foruser bigint(20) unsigned,
			enabled tinyint not null,
			name text NOT NULL,
			text text NOT NULL,
			comment text not null,
			UNIQUE KEY line_item_id (line_item_id),
			FOREIGN KEY (category_id) references ".$wpdb->prefix."specdoc_categories(category_id),
			FOREIGN KEY (foruser) references ".$wpdb->prefix."users(ID)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

	}

	$table_name = $wpdb->prefix."specdoc_defaults";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	{
		$sql = "CREATE TABLE " . $table_name . " (
			name varchar(64) NOT NULL,
			text text NOT NULL,
			comment text not null,
			UNIQUE KEY name (name)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

	}

	$table_name = $wpdb->prefix."specdoc_riskManagement";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `riskName` varchar(64) NOT NULL,
		  `LikelihoodOfOccurrence` varchar(64) NOT NULL,
		  `riskType` varchar(44) NOT NULL,
		  `riskDescription` text NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	$table_name = $wpdb->prefix."specdoc_downloadedDoc_records";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `docName` varchar(114) NOT NULL,		  
		   `firstDownload` datetime NOT NULL,
		   `lastDownload` datetime NOT NULL,
		  `count` int(11) NOT NULL,		   
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	add_option("specdoc_db_version", $specdoc_db_version);

}

function getCategories() {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_categories";

	$sql = "SELECT * FROM ".$table_name." WHERE enabled=1 AND foruser IS NULL ORDER BY category_id";
	return $wpdb->get_results($sql, OBJECT_K);
}

function getLineItems($category_id) {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_line_items";

	$sql = "SELECT * FROM ".$table_name." WHERE category_id=".$category_id." AND enabled=1 AND foruser IS NULL ORDER BY line_item_id";
	return $wpdb->get_results($sql, OBJECT_K);
}

function getDefaults() {
	global $wpdb;
	$return_value = array();

	$table_name = $wpdb->prefix."specdoc_defaults";

	$sql = "SELECT * FROM ".$table_name." ORDER BY name";
	$results = $wpdb->get_results($sql, ARRAY_A);
	foreach ($results as $result)
	{
		$return_value[$result['name']] = unserialize(stripslashes($result['text']));
	}
	return $return_value;
}

function updateCategory($id, $name, $text, $comment) {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_categories";

	$sql = 'UPDATE '.$table_name.' SET name="'.mysql_escape_string($name).'", text="'.mysql_escape_string($text).
		'",comment="'.mysql_escape_string($comment).'" WHERE category_id='.$id;
	$wpdb->query($sql);
}

function updateLineItem($id, $name, $text, $comment) {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_line_items";

	$sql = 'UPDATE '.$table_name.' SET name="'.mysql_escape_string($name).'", text="'.mysql_escape_string($text).
		'",comment="'.mysql_escape_string($comment).'" WHERE line_item_id='.$id;
	$wpdb->query($sql);
}

function updateDefault($name, $text, $comment) {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_defaults";

	$sql = 'INSERT INTO '.$table_name.' SET name="'.mysql_escape_string($name).'", text="'.mysql_escape_string(serialize($text)).
		'",comment="'.mysql_escape_string($comment).'" ON DUPLICATE KEY UPDATE text="'.mysql_escape_string(serialize($text)).
		'",comment="'.mysql_escape_string($comment).'"';
	$wpdb->query($sql);
}

function deleteCategory($id) {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_categories";

	$sql = 'UPDATE '.$table_name.' SET enabled=0 WHERE category_id='.$id;
	$wpdb->query($sql);
}

function deleteLineItem($id) {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_line_items";

	$sql = 'UPDATE '.$table_name.' SET enabled=0 WHERE line_item_id='.$id;
	$wpdb->query($sql);
}

function addCategory($name, $text, $comment) {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_categories";

	$sql = 'INSERT INTO '.$table_name.' (name,text,comment,enabled) VALUES ("'.mysql_escape_string($name).'", "'
		.mysql_escape_string($text).'","'.mysql_escape_string($comment).'",1)';
	$wpdb->query($sql);
	$cat_id = $wpdb->insert_id;
	addLineItem($cat_id, "Default Line Item", "Default Text", "Default Comment");
}

function addLineItem($category_id, $name, $text, $comment) {
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_line_items";

	$sql = 'INSERT INTO '.$table_name.' (category_id,name,text,comment,enabled) VALUES ('.$category_id.', "'.mysql_escape_string($name).'", "'
		.mysql_escape_string($text).'","'.mysql_escape_string($comment).'",1)';
	$wpdb->query($sql);
}

function createAddDeleteBox($name, $values) {
	echo '<h3>'.$name.'</h3>';
	echo '<form name="form'.$name.'" method="post" action=""><input type="text" name="add_'.$name.'_text"/>';
	echo '<input type="submit" name="add_'.$name.'_submit" value="Add '.$name.'"/>';
	$i = 0;
	echo '<table>';
	foreach ($values as $value) {
		echo '<tr><td>'.$value.'</td><td><input type="submit" name="delete_'
		.$name.'_'.$i.'" value="Delete"/></td></tr>';
		$i++;
	}
	echo '</table></form><br/>';
}

function createTextBox($name, $value) {
	$nameWithSpaces = str_replace('_', ' ', $name);
	$escapedValue = htmlspecialchars($value);
	echo '<h3>'.$nameWithSpaces.'</h3>';
	echo '<form name="form'.$name.'" method="post" action="">'
		.'<textarea cols="40" rows="5" name="set_'.$name.'_text">'.$escapedValue
		.'</textarea><input type="submit" name="set_'.$name
		.'_submit" value="Set '.$nameWithSpaces.'"/></form>';
}

function createTextField($name, $value) {
	$nameWithSpaces = str_replace('_', ' ', $name);
	$escapedValue = htmlspecialchars($value);
	echo '<h3>'.$nameWithSpaces.'</h3>';
	echo '<form name="form'.$name.'" method="post" action="">'
		.'<input type="text" name="set_'.$name.'_text" value="'.$escapedValue.'" />'
		.'<input type="submit" name="set_'
		.$name.'_submit" value="Set '
		.$nameWithSpaces.'"/></form>';
}

function createCheckbox($name, $value)
{
	$nameWithSpaces = str_replace('_', ' ', $name);
	$escapedValue = htmlspecialchars($value);

	echo '<form name="form'.$name.'" method="post" action="">';
	if($value==1)
		$chk="checked='checked'";
	else
		$chk="";
	echo '<input type="checkbox" name="set_'.$name.'_check" value="'.$escapedValue.'"  '.$chk.' />&nbsp;<b>'.$nameWithSpaces.'</b>'
		.'&nbsp;&nbsp;<input type="submit" name="set_'.$name.'_submit" value="Save"/></form>';
}

function getDefaultNames()
{
	$defaultnames = array(
		/*"Objective","Position/Role","Organisation", "Turnover", "Territory", "Base_Type", "Number_of_members","Database", "Happiness_with_Database"*/
		"Objective","Position/Role","Organisation", "Turnover", "Number_of_members","Database", "Happiness_with_Database"
	);
	return $defaultnames;
}

function getDefaultOnOff()
{
	$defaultonoffs = array(
		"Turn_On_Off_Primary_objective",
		"Turn_On_Off_Project_sponsor_name",
		"Turn_On_Off_Position",
		"Turn_On_Off_Other",
		"Turn_On_Off_Country",
		"Turn_On_Off_State",
		"Turn_On_Off_City",
		"Turn_On_Off_National_or_state_based_organisation",
		"Turn_On_Off_Number_of_locations",
		"Turn_On_Off_Approx_turnover_of_your_organisation",
		"Turn_On_Off_Number_of_full_time_equivalent_staff",
		/*"Turn_On_Off_Hourly_Rate",*/
		"Turn_On_Off_Number_of_team_members_in_project_delivery",
		"Turn_On_Off_Expected_project_budget",
		"Turn_On_Off_Expected_project_timeline",
		"Turn_On_Off_Number_of_team_members_to_be_trained",
		"Turn_On_Off_Launch_date_and_event_/_motivation_for_launch",
		"Turn_On_Off_Number_of_members_in_your_database",
		"Turn_On_Off_Number_of_non-members_in_your_database",
		"Turn_On_Off_Database_software_you_use",
		"Turn_On_Off_How_happy_are_you_with_your_current_database",
		"Turn_On_Off_Explanation"
	);
	return $defaultonoffs;
}

/** get company questions from database **/
function getDefaultCQFieldNames()
{

    global $wpdb;

	$table_name = $wpdb->prefix."specdoc_field_items";

	$sql = "SELECT * FROM ".$table_name." WHERE type='cq' ORDER BY Id";
	$result = $wpdb->get_results($sql, ARRAY_A);

	foreach($result as $res) {
		$defaultcqs[] = $res['fieldname'];		
	}
	
	return $defaultcqs;
}


/** get id from field name **/
function getFieldNamesId($fieldname)
{

    global $wpdb;
	
	$table_name = $wpdb->prefix."specdoc_field_items";

	$sql = "SELECT Id FROM ".$table_name." WHERE fieldname='$fieldname'";
	$result = $wpdb->get_row($sql, ARRAY_A);
	$id = $result['Id'];
	
	return $id;
}
/** get project questions from database **/
function getDefaultPQFieldNames()
{

    global $wpdb;

	$table_name = $wpdb->prefix."specdoc_field_items";

	$psql = "SELECT * FROM ".$table_name." WHERE type='pq'";
	$presult = $wpdb->get_results($psql, ARRAY_A);

	foreach($presult as $pres){		
		$defaultpqs[] = $pres['fieldname'];		
	}
	
	return $defaultpqs;
}

/** get shortcodes for admin Project section**/
function getProjectShortcodes($i)
{

    switch ($i) {
		case 1:
			$text = 'Document short code $authors$';
			break;
		case 2:
			$text = 'Document short code $keyobjectives$';
			break;
		case 3:
			$text = 'Document short code $projectbudget$';
			break;
			
	 }
	return $text;
}

/** dispaly Mandatory  message section**/
function mandatoryMessage()
{
   $message = "<span class='mandatory'>Mandatory field used in Skopes</span>" ;
   return $message;
}

/** get shortcodes for admin Company section**/
function getCompanyShortcodes($i)
{

    switch ($i) {
		case 1:
			$text = 'Document short code $companylogo$';
			break;
		case 2:
			$text = 'Document short code $streetaddress$';
			break;
		case 3:
			$text = 'Document short code $city_state_zip$';
			break;
		case 4:
			$text = 'Document short code $city_state_zip$';
			break;
		case 5:
			$text = 'Document short code $city_state_zip$';
			break;
		case 6:
			$text = 'Document short code $org_background$';
			break;
		case 7:
			$text = 'Document short code $org_goal_state$';
			break;
		case 8:
			$text = '';
			break;
			
	 }
	return $text;
}


function getDefaultTextFieldNames()
{
	return array(
		"Administrator_Email",
		"Category_Name_Length",
		"Category_Text_Length",
		"Category_Comment_Length",
		"Line_Item_Name_Length",
		"Line_Item_Text_Length",
		"Line_Item_Comment_Length",
		"Impotrtance_Low_Title",
		"Impotrtance_Low_Value",

		"Impotrtance_Medium_Title",
		"Impotrtance_Medium_Value",
		"Impotrtance_High_Title",
		"Impotrtance_High_Value",
	);
}

function getDefaultTextBoxNames()

{
	$defaulttextboxnames = array(
		"Explanation_Display_Text",
		"ROI_Question_1",
		"ROI_Question_2",
		"Help_Bodytext",
		"INSTRUCTIONS_BODY",
		"INSTRUCTIONS_VIDEO_URL",
		"INSTRUCTIONS_LEGEND_TITLE",
		"INSTRUCTIONS_LEGEND_BLUE",
		"INSTRUCTIONS_LEGEND_GREEN",
		"INSTRUCTIONS_LEGEND_GREY",
		"Project_Dashboard_and_Progress",
		"How_does_project_compare?",
		"Dashboard_Notice_1",
		"Dashboard_Notice_2",
		"Dashboard_Warning"
	);

	return $defaulttextboxnames;
}

function getDefaultReportTextBoxNames()

{
	$defaultreporttextboxnames = array(
		"General_Introduction",
		"General_ROI",
		"General_Warnings_&_Caveats",
		"Functional_Area_Introduction",
		"Feature_List_Introduction"
	);

	return $defaultreporttextboxnames;
}

function specdoc_plugin_category_defaults()
{

	$defaultnames = getDefaultNames();
	$defaulttextboxnames = getDefaultTextBoxNames();
	$defaultreporttextboxnames = getDefaultReportTextBoxNames();
	$defaulttextfieldnames = getDefaultTextFieldNames();
	$defaultonoffs = getDefaultOnOff();

	$defaults = getDefaults();

	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	foreach ($defaulttextfieldnames as $defaulttextfieldname) {
		if (isset($_POST['set_'.$defaulttextfieldname.'_submit'])) {
			$value = $_POST['set_'.$defaulttextfieldname.'_text'];
			echo $value;
			$defaults[$defaulttextfieldname] = $value;
			updateDefault($defaulttextfieldname, $value, '');
		}
	}

	foreach ($defaultonoffs as $defaultonoff) {

		if (isset($_POST['set_'.$defaultonoff.'_submit'])) {
			if(isset($_POST['set_'.$defaultonoff.'_check']))
				$value = 1;
			else
				$value = 0;

			$defaults[$defaultonoff] = $value;
			updateDefault($defaultonoff, $value, '');
		}
	}


	foreach ($defaultnames as $defaultname) {
		foreach ($_POST as $postval => $dummy) {

			$lookfor = 'delete_'.$defaultname.'_';

			if (strpos($postval, $lookfor) === 0) {
				$valuetodelete = intval(str_replace($lookfor, '', $postval));
				array_splice($defaults[$defaultname], $valuetodelete, 1);
				updateDefault($defaultname, $defaults[$defaultname], '');
			}
		}

		if (isset($_POST['add_'.$defaultname.'_submit'])) {
			if($defaults[$defaultname] == null)
				$defaults[$defaultname] = array();
			array_push($defaults[$defaultname], $_POST['add_'.$defaultname.'_text']);
			updateDefault($defaultname, $defaults[$defaultname], '');
		}
	}

	foreach ($defaulttextboxnames as $defaulttextboxname) {
		if (isset($_POST['set_'.$defaulttextboxname.'_submit'])) {
			$value = $_POST['set_'.$defaulttextboxname.'_text'];
			//echo $value;
			$defaults[$defaulttextboxname] = $value;
			updateDefault($defaulttextboxname, $value, '');
		}
	}

	foreach ($defaultreporttextboxnames as $defaultreporttextboxname) {
		if (isset($_POST['set_'.$defaultreporttextboxname.'_submit'])) {
			$value = $_POST['set_'.$defaultreporttextboxname.'_text'];
			//echo $value;
			$defaults[$defaultreporttextboxname] = $value;
			updateDefault($defaultreporttextboxname, $value, '');
		}
	}

	echo '<h1>Defaults Administration</h1>';
	echo '<h3 style="background:#ccc;padding:3px"><u>Tool Interface</u></h3>';
	foreach ($defaulttextfieldnames as $defaulttextfieldname) {

		if($colortoggle == 0)
			echo '<div style="background-color:#ffeeee;">';
		else
			echo '<div style="background-color:#eeeeff;">';

		$colortoggle = !$colortoggle;

		createTextField(
			$defaulttextfieldname,
			isset($defaults[$defaulttextfieldname])?
			$defaults[$defaulttextfieldname]:''
		);

		echo '</div>';
	}
	echo '<h3 style="background:#ccc;padding:3px"><u>Profile Fields</u></h3>';

	foreach ($defaultonoffs as $defaultonoff) {
		if($colortoggle == 0)
			echo '<div style="background-color:#ffeeee;">';
		else
			echo '<div style="background-color:#eeeeff;">';

		$colortoggle = !$colortoggle;

		createCheckbox($defaultonoff, isset($defaults[$defaultonoff])?$defaults[$defaultonoff]:'0');

		echo '</div>';
	}
	echo "<br> <b>Note:</b> Check to turn On and Uncheck to turn Off fields in front end";

	foreach ($defaultnames as $defaultname) {
		if($colortoggle == 0)
			echo '<div style="background-color:#ffeeee;">';
		else
			echo '<div style="background-color:#eeeeff;">';

		$colortoggle = !$colortoggle;

		createAddDeleteBox($defaultname, isset($defaults[$defaultname])?$defaults[$defaultname]:array());

		echo '</div>';
	}
	echo '<h3 style="background:#ccc;padding:3px"><u>Instructions</u></h3>';
	foreach ($defaulttextboxnames as $defaulttextboxname) {
		if($colortoggle == 0)
			echo '<div style="background-color:#ffeeee;">';
		else
			echo '<div style="background-color:#eeeeff;">';

		$colortoggle = !$colortoggle;

		createTextBox($defaulttextboxname, isset($defaults[$defaulttextboxname])?$defaults[$defaulttextboxname]:'');

		echo '</div>';
	}

	echo '<h3 style="background:#ccc;padding:3px"><u>Report</u></h3>';
	foreach ($defaultreporttextboxnames as $defaultreporttextboxname) {
		if($colortoggle == 0)
			echo '<div style="background-color:#ffeeee;">';
		else
			echo '<div style="background-color:#eeeeff;">';

		$colortoggle = !$colortoggle;

		createTextBox($defaultreporttextboxname, isset($defaults[$defaultreporttextboxname])?$defaults[$defaultreporttextboxname]:'');

		echo '</div>';
	}
}

function specdoc_plugin_category_lineitems()
{
	$defaults = getDefaults();

	$li_namelength    = (int)$defaults["Line_Item_Name_Length"];
	if($li_namelength == 0)
		$li_namelength = 50;

	$li_textlength    = (int)$defaults["Line_Item_Text_Length"];
	if($li_textlength == 0)
		$li_textlength = 200;

	$li_commentlength    = (int)$defaults["Line_Item_Comment_Length"];
	if($li_commentlength == 0)
		$li_commentlength = 200;

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	$category_id = $_POST['category_id'];
	if(!$category_id) {
		echo 'Please change line items via the <a href="' . admin_url('admin.php?page=specdoc-category-admin') . '">category admin page</a>.';
		return;
	}

	
	if($_POST['id'] && isset($_POST['Save']))
		updateLineItem($_POST['id'], $_POST['name'], $_POST['text'], $_POST['comment']);

	if($_POST['id'] && isset($_POST['Delete']))
		deleteLineItem($_POST['id']);

	if(isset($_POST['Add_New']))
		addLineItem($category_id, $_POST['name'], $_POST['text'], $_POST['comment']);

	echo '
	<SCRIPT LANGUAGE="JavaScript">
		function CountLeft(field, count, max) {
			count.innerText = (max - field.value.length) + "/"+max;
		}
	</script>
	';
	echo '<div class="wrap">';
	echo '<h1>Line Item Administration</h1>';
	echo '<table style="border-width:2px;border-style:solid;">';
	echo '<tr style="background-color:lightgreen;">';
	echo '<td>&nbsp</td><td>NAME</td><td>Notes and Details text</td><td>COMMENT</td><td>&nbsp;</td></tr>';

	$lineitems = getLineItems($category_id);

	foreach($lineitems as $lineitem)
	{
		if($colortoggle == 0)
			echo '<tr style="background-color:#ddddff;">';
		else
			echo '<tr style="background-color:#eeeeff;">';

		$colortoggle = !$colortoggle;
		$li = $lineitem->line_item_id;

		echo '<form name="form'.$lineitem->line_item_id.'" method="post" action=""><td>&nbsp;';
		echo '<input type="hidden" name="id" value="'.$lineitem->line_item_id.'"/></td>';

		echo '<td valign="top"><label id="lbl'.$li.'">'.($li_namelength-strlen($lineitem->name)).'/'.$li_namelength.'</label><br/>'.
				'<input type="text" size="40" name="name" value="'.$lineitem->name.'"'.
				' onKeyDown="CountLeft(this.form.name, getElementById(\'lbl'.$li.'\'), '.$li_namelength.');" '.
				' onKeyUp="CountLeft(this.form.name, getElementById(\'lbl'.$li.'\'), '.$li_namelength.');" /></td>';

		echo '<td><label id="lblt'.$li.'">'.($li_textlength-strlen($lineitem->text)).'/'.$li_textlength.'</label><br/>'.
			'<textarea cols="40" rows="5" name="text"'.
				' onKeyDown="CountLeft(this.form.text, getElementById(\'lblt'.$li.'\'), '.$li_textlength.');" '.
				' onKeyUp="CountLeft(this.form.text, getElementById(\'lblt'.$li.'\'), '.$li_textlength.');">'.$lineitem->text.'</textarea></td>';

		echo '<td><label id="lblc'.$li.'">'.($li_commentlength-strlen($lineitem->comment)).'/'.$li_commentlength.'</label><br/>'.
			'<textarea cols="40" rows="5" name="comment"'.
				' onKeyDown="CountLeft(this.form.comment, getElementById(\'lblc'.$li.'\'), '.$li_commentlength.');" '.
				' onKeyUp="CountLeft(this.form.comment, getElementById(\'lblc'.$li.'\'), '.$li_commentlength.');">'.$lineitem->comment.'</textarea></td>';

		echo '<td><input type="submit" name="Save" value="Save"/>';
		echo '<input type="submit" name="Delete" value="Delete"/></td>';
		echo '<input type="hidden" name="category_id" value="'.$category_id.'"/></form></tr>';
	}

	if($colortoggle == 0)
		echo '<tr style="background-color:#ddddff;">';
	else
		echo '<tr style="background-color:#eeeeff;">';

	$li="z01";

	echo '<form name="form" method="post" action=""><td>NEW</td><td valign="top">';
	echo '<input type="hidden" name="category_id" value="'.$category_id.'"/>';

	echo '<label id="lbl'.$li.'">'.($li_namelength).'/'.$li_namelength.'</label><br/>'.
			'<input type="text" size="40" name="name" value=""'.
			' onKeyDown="CountLeft(this.form.name, getElementById(\'lbl'.$li.'\'), '.$li_namelength.');" '.
			' onKeyUp="CountLeft(this.form.name, getElementById(\'lbl'.$li.'\'), '.$li_namelength.');" /></td>';

	echo '<td><label id="lblt'.$li.'">'.($li_textlength).'/'.$li_textlength.'</label><br/>'.
		'<textarea cols="40" rows="5" name="text"'.
			' onKeyDown="CountLeft(this.form.text, getElementById(\'lblt'.$li.'\'), '.$li_textlength.');" '.
			' onKeyUp="CountLeft(this.form.text, getElementById(\'lblt'.$li.'\'), '.$li_textlength.');"></textarea></td>';

	echo '<td><label id="lblc'.$li.'">'.($li_commentlength).'/'.$li_commentlength.'</label><br/>'.
		'<textarea cols="40" rows="5" name="comment"'.
			' onKeyDown="CountLeft(this.form.comment, getElementById(\'lblc'.$li.'\'), '.$li_commentlength.');" '.
			' onKeyUp="CountLeft(this.form.comment, getElementById(\'lblc'.$li.'\'), '.$li_commentlength.');"></textarea></td>';

	echo '<td><input type="submit" name="Add New" value="Add New"/></td>';
	echo '</form></tr>';


	echo '</table></div>';
}
/** update form fields **/
function updateform_fields($id,$ques,$helptext,$status,$type, $multiSelectOption = null){
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_field_items";

	$sql = 'UPDATE '.$table_name.' SET question="'.addslashes($ques).'", help_text="'.addslashes($helptext).
		'",status="'.addslashes($status).'" ,type="'.addslashes($type).'", multiSelectOption = "' . $multiSelectOption . '" WHERE id='.$id;
		
	$wpdb->query($sql);  
}

/** update fields end **/

/** Select the form fields **/
function getformfield($id)
{
	global $wpdb;

	$table_name = $wpdb->prefix."specdoc_field_items";

	$sql = "SELECT * FROM ".$table_name." WHERE id= $id";
	return $wpdb->get_results($sql, OBJECT_K);
}

/** Select ends **/

/** New function added by Shallini Date:5-3-2014 **/

function specdoc_plugin_form_fields() {
 
    /** update company fields**/
	$CQFN1 = getDefaultCQFieldNames();	
	foreach ($CQFN1 as $cf) {
		if (isset($_POST[$cf.'_savecq'])) {
			$id = $_POST[$cf.'_id'];
			$question = $_POST[$cf.'_question'];
			$helpText = $_POST[$cf.'_helptext'];
			$onOff = $_POST[$cf.'_onoffswitch'];
			$status = ($onOff =='on' ? 1 : 0); 
			$type = 'cq';		

			updateform_fields($id,$question,$helpText,$status,$type);
			//$defaults[$defaulttextfieldname] = $value;
			//updateDefault($defaulttextfieldname, $value, '');
		}
	}
	 /** update project fields**/
	$PQFN1 = getDefaultPQFieldNames();

		foreach ($PQFN1 as $cf1) {
		if (isset($_POST[$cf1.'_savepq'])) {		 
			$id1 = $_POST[$cf1.'_id'];
			$question1 = $_POST[$cf1.'_question'];
			$helpText1 = $_POST[$cf1.'_helptext'];
			$onOff1 = $_POST[$cf1.'_onoffswitch'];
			$status1 = ($onOff1 =='on' ? 1 : 0); 
			$type1 = 'pq';			 
			updateform_fields($id1,$question1,$helpText1,$status1,$type1);
			
			}
		}

	?>
	 <script>
		jQuery(function() {
			 jQuery( "#accordion" ).accordion({
				collapsible: true,
				autoHeight: true,
				active: false
			});
		});
		
		
	</script>
	
	<?php
    
	$text = '<h2>Form Fields</h2>';
	$text .= '<div id="accordion">';
		$text .= '<h3>About the Company questions</h3>';
		$text .= '<div>';
			$text .= '<div class="container">';
				$text .= '<div class="rows-container">';
					$text .= '<div class="heading1 row-col">Questions</div>';
					$text .= '<div class="heading2 row-col">Help Text</div>';
					$text .= '<div class="heading3 row-col">Status</div>';
				$text .= '</div>';
				$CQFN = getDefaultCQFieldNames();
	
				foreach($CQFN as $qc_names) 
				{   
					$id = getFieldNamesId($qc_names); // get id from name
					$getRecord = getformfield($id);
					 //admin.php?page=specdoc-fields
					$text .= '<div class="rows-container">';
				    $text .= '<form name="'.$qc_names.'_question" id="'.$qc_names.'_company_question" action="" method="post">'; 
					$text .= '<div class="row-col-1 row-col">';
						$text .= '<input type="text" name="'.$qc_names.'_question" value="'.$getRecord[$id]->question.'" style="width:350px">';
					$text .= '</div>';
					$text .= '<div class="row-col-2 row-col">';
						$text .= '<textarea name="'.$qc_names.'_helptext" cols="30" rows="5">'.$getRecord[$id]->help_text.' </textarea>';
					$text .= '</div>';
					$text .= '<div class="row-col-3 row-col">';
							if($getRecord[$id]->status == 1){
								$checked = "checked=checked";
							}
							else{ 
								$checked = "";
							}
					$text .= '<div class="onoffswitch">';
					$text .= '<input type="checkbox" name="'.$qc_names.'_onoffswitch" class="onoffswitch-checkbox" id="'.$qc_names.'_onoffswitch" '.$checked.'>
								<label class="onoffswitch-label" for="'.$qc_names.'_onoffswitch">
								<div class="onoffswitch-inner"></div>
								<div class="onoffswitch-switch"></div>
								</label>';
					$text .= '</div>';//onoffswitch end
					$text .= '</div>';//row-col-3 end
					$text .= '<div class="row-col-4 row-col">';
						$formName = $qc_names."_company_question";
						$text .= '<input type="submit" name="'.$qc_names.'_savecq" value="save">';
						$text .= '<input type="hidden" name="'.$qc_names.'_id" value="'.$getRecord[$id]->Id.'">';
						$text .= '<input type="hidden" name="field" value="'.$getRecord[$id]->fieldname.'">';
					$text .= '</div>';
				$text .= '</form>'; 
				$text .= '</div>';
				}
			$text .= '</div>';
		$text .= '</div>';
		
	$text .= '<h3>About the Project questions</h3>';
	
		
		$text .= '<div>';
			$text .= '<div class="container">';
				$text .= '<div class="rows-container">';
					$text .= '<div class="heading1 row-col">Questions</div>';
					$text .= '<div class="heading2 row-col">Help Text</div>';
					$text .= '<div class="heading3 row-col">Status</div>';
				$text .= '</div>';
				$PQFN = getDefaultPQFieldNames();
	
				foreach($PQFN as $pq_names) 
				{   
					 $id = getFieldNamesId($pq_names); // get id from name
					 $getPQRecord = getformfield($id);
					 //admin.php?page=specdoc-fields
					$text .= '<div class="rows-container">';
				    $text .= '<form name="'.$pq_names.'_question" id="'.$pq_names.'_company_question" action="admin.php?page=specdoc-fields" method="post">';
					$text .= '<div class="row-col-1 row-col">';
						$text .= '<input type="text" name="'.$pq_names.'_question" value="'.$getPQRecord[$id]->question.'" style="width:350px">';
					$text .= '</div>';
					$text .= '<div class="row-col-2 row-col">';
						$text .= '<textarea name="'.$pq_names.'_helptext" cols="30" rows="5">'.$getPQRecord[$id]->help_text.' </textarea>';
					$text .= '</div>';


					$text .= '<div class="row-col-3 row-col">';
							if($getPQRecord[$id]->status == 1){
								$checked = "checked=checked";
							}
							else{ 
								$checked = "";
							}
					$text .= '<div class="onoffswitch">';
					$text .= '<input type="checkbox" name="'.$pq_names.'_onoffswitch" class="onoffswitch-checkbox" id="'.$pq_names.'_onoffswitch" '.$checked.'>
								<label class="onoffswitch-label" for="'.$pq_names.'_onoffswitch">
								<div class="onoffswitch-inner"></div>
								<div class="onoffswitch-switch"></div>
								</label>';
					$text .= '</div>';//onoffswitch end
					$text .= '</div>';//row-col-3 end
					$text .= '<div class="row-col-4 row-col">';
						$formName = $pq_names."_project_question";
						$text .= '<input type="submit" name="'.$pq_names.'_savepq" value="save">';
						$text .= '<input type="hidden" name="'.$pq_names.'_id" value="'.$getPQRecord[$id]->Id.'">';
						$text .= '<input type="hidden" name="field" value="'.$getPQRecord[$id]->fieldname.'">';
					$text .= '</div>';
				$text .= '</form>'; 
				$text .= '</div>';
				}
			$text .= '</div>';//container end
		$text .= '</div>';//main end

	$text .= '</div>';//accordian end
			
	
	echo $text;
}

/** New function Ends **/

/** New function created By Shallini 29-April-2014**/

/** add company questions **/
function specdoc_plugin_org_questions() {


	// Add a new column in table as we need to add multichoice question in this section, So we can't add a new column in table dirctally so we will check the column name is exists or not in table (as we added this question after completion of this section)

	global $wpdb;
	$col_name = 'multiSelectOption';
	$table_name = $wpdb->prefix."specdoc_field_items";

	$col = $wpdb->query("SELECT ".$col_name." FROM ".$table_name);

	if (!$col){
		$wpdb->query("ALTER TABLE ".$table_name." ADD ".$col_name." TEXT NULL");
		$wpdb->query("INSERT INTO ".$table_name." SET fieldname ='company_question11', question = '', help_text = '', status ='0', type ='cq', multiSelectOption ='' ");
	}

	/* *************
	 25/04/2015, Today clinte saying to add another question in organization section so I am going to write down code to crate new entries in db as there are no way to add new question from admin. Don't make entrires in db directally because it will causing issue on Updation of live instance so good this is manage all these things Programatically.
		
		Core written by Royal Tyagi on 25/14/2015
	*****************/
		$sql_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "specdoc_field_items WHERE type= 'cq'");
		if ($sql_count == 11) {
		
			$insert = $wpdb->query("INSERT INTO " . $table_name . " (`fieldname`, `question`, `help_text`, `status`, `type`) VALUES
			('company_question12', '', '', '0', 'cq'),
			('company_question13', '', '', '0', 'cq'),
			('company_question14', '', '', '0', 'cq'),
			('company_question15', '', '', '0', 'cq'),
			('company_question16', '', '', '0', 'cq'),
			('company_question17', '', '', '0', 'cq')");
			
		}

  /** update company fields**/
	$CQFN1 = getDefaultCQFieldNames();

//		echo "<pre>";		print_r($_POST);		echo "</pre>";		echo "<br/>";	
	foreach ($CQFN1 as $cf) {
			$id = $_POST[$cf.'_id'];
			$question = $_POST[$cf.'_question'];
			$helpText = $_POST[$cf.'_helptext'];
			$onOff = $_POST[$cf.'_onoffswitch'];
			$status = ($onOff =='on' ? 1 : 0);
			$multiSelectOption = (isset($_POST[$cf.'_multiSelectOption']) ? $_POST[$cf.'_multiSelectOption'] : '' );
			$type = 'cq';

			if ($multiSelectOption != '') {
				updateform_fields($id,$question,$helpText,$status,$type, $multiSelectOption);
			} else {
				updateform_fields($id,$question,$helpText,$status,$type);
			}
	}

	$cq = '<h3>About the Company questions</h3>';
	$cq .= '<div>';
	$k = 1;
			$cq .= '<div class="container">';
				$cq .= '<div class="rows-container">';
					$cq .= '<div class="heading1 row-col">Questions</div>';
					$cq .= '<div class="heading2 row-col">Help Text</div>';
					$cq .= '<div class="heading3 row-col">Status</div>';
				$cq .= '</div>';
				
				$CQFN = getDefaultCQFieldNames();
			
				 $cq .= '<form name="company_question" id="company_question" action="admin.php?page=specdoc-org-fields"  method="post">'; 
				foreach($CQFN as $qc_names) {
					$id = getFieldNamesId($qc_names); // get id from name
					$getRecord = getformfield($id);

					 if ( $k ==11 ) {
						$cq .= '<h2>Multi choice question</h2>';
					 }
					$cq .= '<div class="rows-container">';
				   
					$cq .= '<div class="row-col-1 row-col">';
						$cq .= '<input type="text" name="'.$qc_names.'_question" value="'.stripslashes($getRecord[$id]->question).'" style="width:350px">';
					$cq .= "<span class='shortcodes'>";
					//$cq .= getCompanyShortcodes($k);
					$cq .= 'Document short code $org_'.$k.'$';
					$cq .= "</span>";
					if($k == 9){
					$cq .= mandatoryMessage();
					}
					$cq .= '</div>';
					$cq .= '<div class="row-col-2 row-col">';
						$cq .= '<textarea name="'.$qc_names.'_helptext" cols="30" rows="5">'.stripslashes(trim($getRecord[$id]->help_text)).' </textarea>';
					$cq .= '</div>';

					if($k == 11){
						$cq .= '<div class="row-col-5 row-col" style="width: 24%;">';
							$cq .= '<textarea name="'.$qc_names.'_multiSelectOption" cols="30" rows="5">'.stripslashes(trim($getRecord[$id]->multiSelectOption)).' </textarea>';
							$cq .= '<br/><p style="font-size: 11px;">You can put the label of checkobox in this box seprating by | eg. One | Two | three</p>';
						$cq .= '</div>';
					}
					
					$cq .= '<div class="row-col-3 row-col">';
						if($getRecord[$id]->status == 1) {
							$checked = "checked=checked";
						} else {
							$checked = "";
						}
					$cq .= '<div class="onoffswitch">';
					$cq .= '<input type="checkbox" name="'.$qc_names.'_onoffswitch" class="onoffswitch-checkbox" id="'.$qc_names.'_onoffswitch" '.$checked.'>
								<label class="onoffswitch-label" for="'.$qc_names.'_onoffswitch">
								<div class="onoffswitch-inner"></div>
								<div class="onoffswitch-switch"></div>
								</label>';
					$cq .= '</div>';//onoffswitch end
					$cq .= '</div>';//row-col-3 end
					$cq .= '<div class="">';
						$cq .= '<input type="hidden" name="'.$qc_names.'_id" value="'.$getRecord[$id]->Id.'">';
						$cq .= '<input type="hidden" name="field" value="'.$getRecord[$id]->fieldname.'">';
					$cq .= '</div>';

				$cq .= '</div>';
				$k++;
				}
				
		$formName = $qc_names."_company_question";
		$cq .= '<input type="submit" name="'.$qc_names.'_savecq" value="save">';
		$cq .= '</form>'; 
		$cq .= '</div>';
		$cq .= '</div>';
  
  echo $cq;
}


function specdoc_plugin_project_questions(){

	 /** update project fields**/
		$PQFN1 = getDefaultPQFieldNames();
		
		foreach ($PQFN1 as $cf1) {
			 
			$id1 = $_POST[$cf1.'_id'];
			$question1 = $_POST[$cf1.'_question'];
			$helpText1 = $_POST[$cf1.'_helptext'];
			$onOff1 = $_POST[$cf1.'_onoffswitch'];
			$status1 = ($onOff1 =='on' ? 1 : 0); 
			$type1 = 'pq';			 
			updateform_fields($id1,$question1,$helpText1,$status1,$type1);			
			
		}
		
	$proq = '<h3>About the Project questions</h3>';
	
	$proq .= '<div>';
			$proq .= '<div class="container">';
				$proq .= '<div class="rows-container">';
					$proq .= '<div class="heading1 row-col">Questions</div>';
					$proq .= '<div class="heading2 row-col">Help Text</div>';
					$proq .= '<div class="heading3 row-col">Status</div>';
				$proq .= '</div>';
				$PQFN = getDefaultPQFieldNames();
				
				$proq .= '<form name="project_questions" id="project_questions" action="admin.php?page=specdoc-project-fields" method="post">';
				$i = 1;
				foreach($PQFN as $pq_names) 
				{
					 $id = getFieldNamesId($pq_names); // get id from name
					 $getPQRecord = getformfield($id);
					 //admin.php?page=specdoc-fields
					 $proq .= '<div class="rows-container">';
				
					 $proq .= '<div class="row-col-1 row-col">';
						$proq .= '<input type="text" name="'.$pq_names.'_question" value="'. stripslashes(trim($getPQRecord[$id]->question)) .'" style="width:350px"><br>';
					//$proq .= "<span class='shortcodes'>".getProjectShortcodes($i)."</span>"; 
					$proq .= '<span class="shortcodes">Document short code  $prog_'.$i.'$</span>'; 
					if($i == 1 || $i == 4){
					 $proq .= mandatoryMessage();  
					}
					$proq .= '</div>';
					$proq .= '<div class="row-col-2 row-col">';
						$proq .= '<textarea name="'.$pq_names.'_helptext" cols="30" rows="5">'. stripslashes(trim($getPQRecord[$id]->help_text)) .' </textarea>';
					$proq .= '</div>';
					$proq .= '<div class="row-col-3 row-col">';   
							if($getPQRecord[$id]->status == 1){
								$checked = "checked=checked";
							}
							else{ 
								$checked = "";
							}
					$proq .= '<div class="onoffswitch">';
					$proq .= '<input type="checkbox" name="'.$pq_names.'_onoffswitch" class="onoffswitch-checkbox" id="'.$pq_names.'_onoffswitch" '.$checked.'>
								<label class="onoffswitch-label" for="'.$pq_names.'_onoffswitch">
								<div class="onoffswitch-inner"></div>
								<div class="onoffswitch-switch"></div>
								</label>';
					$proq .= '</div>';//onoffswitch end
					$proq .= '</div>';//row-col-3 end
					$proq .= '<div class="row-col-4 row-col">';
						/* $formName = $pq_names."_project_question";
						$proq .= '<input type="submit" name="'.$pq_names.'_savepq" value="save">'; */
						$proq .= '<input type="hidden" name="'.$pq_names.'_id" value="'.$getPQRecord[$id]->Id.'">';
						$proq .= '<input type="hidden" name="field" value="'.$getPQRecord[$id]->fieldname.'">';
					$proq .= '</div>';
				
				$proq .= '</div>';
				 $i++;
				}
			$formName = $pq_names."_project_question";
			
			$proq .= '<input type="submit" name="'.$pq_names.'_savepq" value="save" class="saveitem">'; 
			$proq .= '</form>';
			$proq .= '</div>';//container end
    echo $proq;
}

function specdoc_more_project_questions(){

	include(RC_TC_BASE_DIR."/inc/skope_more_projects.php");
}
/** New function Ends **/

/*
*	Name: Twisha Date:14-8-2013
*   Skop.es setting in admin menu for some basic settings 
*
*/
function skopes_settings() {
	include(RC_TC_BASE_DIR."/inc/skope_setting.php");
}


/*
*	Name: Royal Tyagi Date:14-1-2015
*   Skop.es setting in admin menu for some basic settings 
*
*/
function skope_users_and_payments() {

	include(RC_TC_BASE_DIR."/inc/skope_user_payments_tab.php");

	/*
	if ( isset ( $_GET['action'] ) ) $action = $_GET['action'];
	else $action = ''; 

	switch ( $action ){
		case 'payments' :
			include(RC_TC_BASE_DIR."/inc/sections/section-user-payments.php");
		break;

		case 'documents' :
			include(RC_TC_BASE_DIR."/inc/sections/section-user-documents.php");
		break;
		
		case 'delete' :
			include(RC_TC_BASE_DIR."/inc/sections/section-user-delete.php");
		break;

		case 'downloadDoc' :
			include(RC_TC_BASE_DIR."/inc/sections/section-download-doc.php");
		break;

		case 'downloadCSV' :
			include(RC_TC_BASE_DIR."/inc/sections/section-download-csv.php");
		break;		

		default:
			include(RC_TC_BASE_DIR."/inc/sections/section-user-details.php");
		break;

	}
*/
}

/*

*	Name: Royal Tyagi Date:16-1-2015
*   Help text navigation under the skope 
*

*/
function skope_help_text() {
	include(RC_TC_BASE_DIR."/inc/sections/section-help-content.php");
}


/*

*	Fucntion created by Royal Tyagi at Date:03-02-2015
*   For Guide page configuration under the skope navigation 
*
*/
function skope_guide_page_config() {
	include(RC_TC_BASE_DIR."/inc/sections/section-guide-config.php");
}


/*
*	Fucntion created by Royal Tyagi at Date:19-03-2015
*   For show the all EOI response in admin
*
*/

function specdoc_plugin_eoi_response() {

	if ( isset ( $_GET['action'] ) ) $action = $_GET['action'];
	else $action = ''; 

	switch ( $action ){
		case 'editEOI' :
			include(RC_TC_BASE_DIR."/inc/sections/section-editEoi-response.php");
		break;

		case 'deleteEOI' :
			include(RC_TC_BASE_DIR."/inc/sections/section-deleteEoi-response.php");
		break;

		case 'eoiView' :
			include(RC_TC_BASE_DIR."/inc/sections/section-viewEoi-response.php");
		break;
		
		default:
			include(RC_TC_BASE_DIR."/inc/sections/section-eoi-response.php");
		break;
	}

}

/*
*   Name: Twisha Patel Date: 14-8-2013
*   display template on front
*/
if( !defined( 'RC_TC_BASE_FILE' ) ) define( 'RC_TC_BASE_FILE', __FILE__ );
if( !defined( 'RC_TC_BASE_DIR' ) ) define( 'RC_TC_BASE_DIR', dirname( RC_TC_BASE_FILE ) );
if( !defined( 'RC_TC_PLUGIN_URL' ) ) define( 'RC_TC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

add_filter( 'template_include', 'prism_template_chooser');
function prism_template_chooser($template) {
	global $post;

	$post_id = get_the_ID();
	if ( is_front_page() )
	{
		if(is_user_logged_in())
		{
			if(isset($_GET["action"]))
			{
				if($_GET["action"]=="report")
					return prism_template_hierarchy('report');
				else
					return prism_template_hierarchy('page');
			}
			else
				return prism_template_hierarchy('page');
		}
		else
			return prism_template_hierarchy('page');
	}
	else if(is_page())
	{
		return prism_template_hierarchy('content');
	} elseif (is_single()) {

			return prism_template_hierarchy('content');
	}
	else
	{
		/*echo "else";*/
		return $template;
	}
}

function prism_template_hierarchy( $template ) {
	// Get the template slug
	$choosenTheme = get_option("selected_theme");
	$template_slug = rtrim($template, '.php');
	$template = $template_slug . '.php';
	// Check if a custom template exists in the theme folder, if not, load the plugin template file
	if ( $theme_file = locate_template(array('plugin_template/'.$template)) )
		$file = $theme_file;
	else
		if ($choosenTheme != '') {
			$file = RC_TC_BASE_DIR . '/frontend/theme/' . $choosenTheme .'/'. $template;
		} else {
			//$file = RC_TC_BASE_DIR . '/frontend/' . $template;
			$file = RC_TC_BASE_DIR . '/frontend/theme/Black/'. $template;
		}
	
	return apply_filters( 'prism_load_template_'.$template, $file);
}


/* check user is activated or not when user login to the system */

add_filter('wp_authenticate_user', 'myplugin_auth_login',10,2);


function myplugin_auth_login ($user, $password) {
	global $wpdb;

	$userid=$user->ID;
	if(!is_super_admin($userid))
	{
		$res_meta=$wpdb->get_row("select * from ".$wpdb->prefix."usermeta where meta_key='active_status' and meta_value='1' and user_id='".$userid."'");
		if($res_meta>0)
			return $user;
		else
			return false;
	}
	else 
		return $user;
	
}

add_action('init', 'myplugin_logout');
function myplugin_logout()
{
	if(isset($_REQUEST["act"]))
	{
		if($_REQUEST["act"]=="logout")
		{
			$_SESSION["tool_data"]="";
			unset($_SESSION["tool_data"]);

			$_SESSION["delete_cat"]="";
			unset($_SESSION["delete_cat"]);

			$_SESSION["delete_lineitem"]="";
			unset($_SESSION["delete_lineitem"]);

			wp_logout();
			$redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : home_url();
			wp_safe_redirect( $redirect_to );
			exit();
		}
	}
}

//echo RC_TC_BASE_DIR;
$choosenTheme = get_option("selected_theme");
include(RC_TC_BASE_DIR."/frontend/theme/".$choosenTheme."/core/class_skopes.php");

/* create widget for footer links */
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name'=> 'Footer Links',
		'id' => 'footer_links',
		'before_widget' => '<div class="span3">',
		'after_widget' => '</div>',
		'before_title' => '<h5>',
		'after_title' => '</h5>',
	));
}
/* create widget for header links */
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name'=> 'Header Links',
		'id' => 'header_links',
		'class' => 'header-menu',
		'before_widget' => '<div class="header-menu">',
		'after_widget' => '</div>',
		'before_title' => '<h5>',
		'after_title' => '</h5>',
	));
}

ob_flush();

/* ********************* 
* Code Added by Royal Tyagi to add the button on static pages 
* 
***********************/

function skoope_add_to_content( $content ) {

	global $post;

	$post = get_post( $post_id );
	$slug = $post->post_name;


	$user_id = get_current_user_id();
	$class = get_user_meta( $user_id, $slug, true );

	if ($class == '') {
		$className = 'txt';
	} else {
		$className = 'green-complete';
	}

    $custom = get_post_custom($post_id);
    if (array_key_exists('skope_guide_checkbox', $custom)) {
    	$sl_meta_box_sidebar = $custom["skope_guide_checkbox"][0]; 
    } else {
		$sl_meta_box_sidebar = '';
    }	

    if( is_page() && 'qa_faqs' != get_post_type() && $sl_meta_box_sidebar != '' ) {

        $content .= '<div class="submit-buttons ' . $className . '" style="text-align:center; margin-top: 15%;" id="proid">';
		$content .= '<span class="small btn-blue1" style="margin-right: 35px;">';
		$content .= '<a id="backToguide" class="" href="'. home_url().'?act=guide">Back to guide</a>';
		$content .= '</span>';
		$content .= '<span class="small btn-blue1">';
		$content .=  '<a id="backToguideComplete" uid="' . $user_id . '" slug="' . $slug . '" class="" href="#">Complete</a>';
		$content .= '</span>';
		$content .= '</div>';
		$content .= '<div class="cdiv">';
		$content .= '<div id="shimag"></div>';
		$content .= '</div>';

    }

    if( is_page() && 'qa_faqs' === get_post_type() ) { 
		$content = '';
	}

    return $content;
}
add_filter('the_content', 'skoope_add_to_content');


/* ********************* 
* Code Added by Royal Tyagi to delete the Skope data from database when admin delete any user from admin
* 

***********************/

function skope_deleteDataWithUser( $user_id ) {

		global $wpdb;

		$tables = array (
			'userID' 	=>  'save_status_at_stage2',
			'userID' 	=>  'guideActionInSkop',
			'user_id' 	=>  'specdoc_guide_user_items',
			'user_id' 	=>  'specdoc_riskManagement',
			'userid' 	=>  'specdoc_userlistinfo',
			'user_id' 	=>  'specdoc_usermilestones',
			'userid' 	=>  'specdoc_userstatusinfo',
			'userid' 	=>  'specdoc_userteaminfo',
			'user_id'  => 'eoiresponse'
		);

		foreach ( $tables as $k => $table ) {

			$query = $wpdb->query("DELETE FROM " . $wpdb->prefix.$table. " WHERE " . $k . " = '" . $user_id . "' ");

		}


		$sql =  $wpdb->query("DELETE t1, t2 FROM " . $wpdb->prefix . "specdoc_order t1 INNER JOIN " . $wpdb->prefix . "specdoc_order_product t2 ON (t1.order_id = t2.order_id) WHERE t1.customer_id = '" . $user_id . "'");
		
}


add_action( 'delete_user', 'skope_deleteDataWithUser' );


function skope_update_last_login( $user_login ) {
		$user = get_user_by( 'login', $user_login );
		update_user_meta( $user->ID, 'skope_last_login', time() );
}
add_action('wp_login', 'skope_update_last_login');

/* *************************************
* Add new Role of user in WordPress named Vendor for EOI functionality
* 
*/
$result = add_role(
    'vendor',
    __( 'Vendor' ),
    array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => false,
        'delete_posts' => false, // Use false to explicitly deny
    )
);


/* *************************************
* Add hook to block the wp-admin access for all users excepting admin
* 
*/

function skope_restrict_admin() {
    if ( ! current_user_can( 'manage_options' )  && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' ) {
        wp_redirect( home_url() );
    }
}
add_action( 'admin_init', 'skope_restrict_admin', 1 );


/**
 * Programmatically logs a user in
 * 
 * @param string $username
 * @return bool True if the login was successful; false if it wasn't
 */
    function programmatic_login( $username ) {
        if ( is_user_logged_in() ) {
            wp_logout();
        }

    add_filter( 'authenticate', 'allow_programmatic_login', 10, 3 );    // hook in earlier than other callbacks to short-circuit them
    $user = wp_signon( array( 'user_login' => $username ) );
    remove_filter( 'authenticate', 'allow_programmatic_login', 10, 3 );

    if ( is_a( $user, 'WP_User' ) ) {
        wp_set_current_user( $user->ID, $user->user_login );

        if ( is_user_logged_in() ) {
            return true;
        }
    }

    return false;
 }

 /**
  * An 'authenticate' filter callback that authenticates the user using only     the username.
  *
  * To avoid potential security vulnerabilities, this should only be used in     the context of a programmatic login,
  * and unhooked immediately after it fires.
  * 
  * @param WP_User $user
  * @param string $username
  * @param string $password
  * @return bool|WP_User a WP_User object if the username matched an existing user, or false if it didn't
  */
 function allow_programmatic_login( $user, $username, $password ) {
    return get_user_by( 'login', $username );
 }
?>
