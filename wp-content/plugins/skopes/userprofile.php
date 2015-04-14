<?php
require_once ABSPATH.WPINC.'/pluggable.php';require_once ABSPATH.WPINC.'/registration.php';
add_filter('user_contactmethods', 'user_contactmethod_filter');
function user_contactmethod_filter($contactmethods)
{
	return array('company_name' => 'Company',
				 'project_name' => 'Project name',
				 'lc_objective' => 'Objective',
                 'lc_sponsor' => 'Sponsor',
                 'lc_location' => 'Location',
                 'lc_base' => 'Base',
                 'lc_locations' => 'Locations',
                 'lc_turnover' => 'Turnover',
                 'lc_staff' => 'Staff',
                 'lc_delivery' => 'Delivery',
                 'lc_trainees' => 'Trainees',
                 'lc_members' => 'Members',
                 'lc_nonmembers' => 'Non members',
                 'lc_dbsoftware' => 'DB Software',
                 'lc_happy' => 'Happiness');
}

function get_user_data()
{
	$userId = $_REQUEST['user_id'];
	if($userId == null)
		return null;
	return get_user_meta($userId, 'lc_tool_data');
}

function get_active_lineitems($category)
{
	$active = array();
	// Check if any line item is active
	foreach($category[CATEGORY_LINEITEMS] as $lineitem)
		if($lineitem[LINEITEM_IS_SELECTED])
			$active[] = $lineitem;
	return $active;
}

function add_specdoc_data()
{
	echo '<h3>Specdoc data</h3>';
	echo '<table class="form-table"';
	echo '<tr><th><label for="datatable">Answers</label></th>';
	echo '<td><table name="datatable" id="datatable">';
	$result = get_user_data();
	if ($result == null) 	{
		echo '<h3>No data found</h3></table></td></tr></table>';
		return;
	}
	$result = $result[0];
	// DATA FOUND, NOW OUTPUT THE (SELECTED) RESULTS
	foreach ($result as $row) {
		$activeLineitems = get_active_lineitems($row);
		if (count($activeLineitems)) {
			echo '<tr style="background-color:lightblue;">';			echo '<td>'.$row[CATEGORY_NAME].'</td>';			echo '<td>'.$row[CATEGORY_TEXT].'</td>';			echo '<td>'.$row[CATEGORY_COMMENT].'</td>';			echo '</tr>';
			foreach ($activeLineitems as $activeLineitem) {
				echo '<tr>';				echo '<td>'.$activeLineitem[LINEITEM_NAME].'</td>';				echo '<td>'.$activeLineitem[LINEITEM_TEXT].'</td>';				echo '<td>'.$activeLineitem[LINEITEM_COMMENT].'</td><td>';				$slider = $activeLineitem[LINEITEM_COLOURSLIDER];
				echo $slider==0 ? 'LOW' : $slider==1 ? 'MEDIUM' : 'HIGH';				echo '</td><td>';				echo $activeLineitem[REPORT_DEFAULT_LINES] ? '&nbsp;' : 'CUSTOM';				echo '</td></tr>';			}		}	}
	echo '</table>';
	if(get_user_meta($_REQUEST['user_id'], 'lc_report') != null)
		echo '<br/><a href="../wp-content/plugins/specdoc.me/getreport.php?user_id='.$_REQUEST['user_id'].'&user_key='
			.md5('hej'.$_REQUEST['user_id']).'">Get latest PDF report</a>';	if(get_user_meta($_REQUEST['user_id'], 'lc_report_word') != null)		echo '<br/><a href="../wp-content/plugins/specdoc.me/getword.php?user_id='.$_REQUEST['user_id'].'&user_key='			.md5('hej'.$_REQUEST['user_id']).'">Get latest Word report</a>';				echo '</table></td></tr></table>';
}

add_action('edit_user_profile', 'add_specdoc_data');

function specdoc_plugin_download_report()
{
	echo "OLLE ".$_REQUEST['user_id']."!";
}
