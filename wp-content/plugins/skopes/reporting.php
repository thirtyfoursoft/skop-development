<?php
require_once ABSPATH.WPINC.'/pluggable.php';
require_once ABSPATH.WPINC.'/registration.php';

/*
*   Name: Twisha Date:25-7-2013
*   Changes for sorting in all 4 report function (use datatable)
*/

function specdoc_plugin_custom_category()
{
	echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$("#table_custom_cat").dataTable({
				"bPaginate": false,
				"bFilter": false,
				"bInfo": false
			});
		});
		</script>
	';

	echo '<form name="frmcustomcat" id="frmcustomcat" method="post">';
	echo '<h3>List of custom categories active in the system</h3>';
	echo '<table width="100%" id="table_custom_cat"><thead><tr><th>USER</th><th>NAME</th><th>TEXT</th><th>COMMENT</th></tr></thead>';
	$data = getCustomCategoryReportData();
	foreach ($data as $category) {
		echo
			'<tr>'.
			'<td width="20%">'.$category['user'].'</td>'.
			'<td width="20%">'.$category['name'].'</td>'.
			'<td width="30%">'.$category['text'].'</td>'.
			'<td width="30%">'.$category['comment'].'</td>'.
			'</tr>';
	}
	echo '</table>';
	echo '</form>';
	$user_id = get_current_user_id();
	echo '<br/><a href="../wp-content/plugins/specdoc.me/getexcel.php?user_id='.$user_id.'&user_key='
			.md5('hej'.$user_id).'&reportid=customcat">Get report for Excel</a>';
}
function getCustomCategoryReportData()
{
	global $wpdb;

	/*$myrows = $wpdb->get_results('select * from wp_usermeta join wp_users on ID=user_id where meta_key="lc_tool_data"');
	$data = array();

	foreach ($myrows as $myrow) {
		$values = unserialize($myrow->meta_value);
		$user = $myrow->user_login;
		foreach ($values as $category) {
			if (!$category[REPORT_DEFAULT_CATS] && $category[CATEGORY_LINEITEMS] != null) {
				$active = 0;
				foreach($category[CATEGORY_LINEITEMS] as $lineitem)
					if($lineitem[LINEITEM_IS_SELECTED])
						$active = 1;
				if ($active) {
					$data[] = array(
						'user'    => $user,
						'name'    => $category[CATEGORY_NAME],
						'text'    => $category[CATEGORY_TEXT],
						'comment' => $category[CATEGORY_COMMENT]
					);
				}
			}
		}
	}*/

	$myrows= $wpdb->get_results("select * from wp_specdoc_categories c, wp_users u where u.ID=c.foruser and c.enabled=1");
	foreach ($myrows as $myrow) {
		 $data[]=array(
			'user'    => $myrow->user_login,
			'name'    => $myrow->name,
			'text'    => $myrow->text,
			'comment' => $myrow->comment
		 );
	}
	return $data;
}

function specdoc_plugin_standard_lineitem_byaverage()
{
	echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$("#table_lineitem_avg").dataTable({
				"bPaginate": false,
				"bFilter": false,
				"bInfo": false
			});
		});
		</script>
	';
	echo '<form name="frmcustomcat" id="frmcustomcat" method="post">';
	echo '<h3>List of line items by rating in the system</h3>';
	echo '<table width="100%" id="table_lineitem_avg"><thead><tr><th>NAME</th><th>NUMBER OF SELECTIONS</th><th>AVERAGE RATING</th></tr></thead>';
	$data = getStandardLineItemReportData(averagecmp);
	foreach ($data as $datarow) {
		echo
			'<tr>'.
			'<td width="60%">'.$datarow[0].'</td>'.
			'<td width="20%">'.$datarow[1].'</td>'.
			'<td width="20%">'.$datarow[2].'</td>'.
			'</tr>';
	}
	echo '</table>';
	echo '</form>';
	$user_id = get_current_user_id();
	echo '<br/><a href="../wp-content/plugins/specdoc.me/getexcel.php?user_id='.$user_id.'&user_key='
			.md5('hej'.$user_id).'&reportid=standardlia">Get report for Excel</a>';
}
function specdoc_plugin_standard_lineitem_byselections()
{
	echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$("#table_lineitem_sel").dataTable({
				"bPaginate": false,
				"bFilter": false,
				"bInfo": false
			});
		});
		</script>
	';
	echo '<h3>List of line items by popularity in the system</h3>';
	echo '<table width="100%" id="table_lineitem_sel"><thead><tr><th>NAME</th><th>NUMBER OF SELECTIONS</th><th>AVERAGE RATING</th></tr></thead>';
	$data = getStandardLineItemReportData('numselectioncmp');
	foreach ($data as $datarow) {
		echo
			'<tr>'.
			'<td width="60%">'.$datarow[0].'</td>'.
			'<td width="20%">'.$datarow[1].'</td>'.
			'<td width="20%">'.$datarow[2].'</td>'.
			'</tr>';
	}
	echo '</table>';
	echo '</form>';
	$user_id = get_current_user_id();
	echo '<br/><a href="../wp-content/plugins/specdoc.me/getexcel.php?user_id='.$user_id.'&user_key='
			.md5('hej'.$user_id).'&reportid=standardlis">Get report for Excel</a>';
}
function numselectioncmp($a, $b) {
	if ($a[1] == $b[1]) {
		return 0;
	}
	return ($a[1] > $b[1]) ? -1 : 1;
}
function averagecmp($a, $b) {
	if ($a[2] == $b[2]) {
		return 0;
	}
	return ($a[2] > $b[2]) ? -1 : 1;
}
function getStandardLineItemReportData($comparison)
{
	global $wpdb;
	$myrows = $wpdb->get_results('select * from wp_usermeta join wp_users on ID=user_id where meta_key="lc_tool_data"');
	$tmpdata = array();

	foreach ($myrows as $myrow) {
		$values = unserialize($myrow->meta_value);
		$user = $myrow->user_login;
		foreach ($values as $category) {
			if ($category[CATEGORY_ID] != undefined && $category[CATEGORY_LINEITEMS] != null) {
				foreach ($category[CATEGORY_LINEITEMS] as $lineitem) {
					if($lineitem[LINEITEM_IS_SELECTED] && $lineitem[LINEITEM_ID] != undefined)
					{
						if ($tmpdata[$lineitem[LINEITEM_ID]] == undefined)
							$tmpdata[$lineitem[LINEITEM_ID]] = array(0,0);
						$tmpdata[$lineitem[LINEITEM_ID]][0]++;
						$tmpdata[$lineitem[LINEITEM_ID]][1] += $lineitem[LINEITEM_COLOURSLIDER];
					}
				}
			}
		}
	}

	$data = array();
	foreach($tmpdata as $mykey => $myval)
	{
		$myline = $wpdb->get_row('select * from wp_specdoc_line_items where line_item_id='.$mykey);
		if($myline == null)
			continue;
		$data[] = array($myline->name, $myval[0], $myval[1]/$myval[0]);
	}

	uasort($data, $comparison);
	return $data;
}
function specdoc_plugin_custom_lineitem()
{
	echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$("#table_lineitem_report").dataTable({
				"bPaginate": false,
				"bFilter": false,
				"bInfo": false
			});
		});
		</script>
	';

	echo '<form name="frmcustomline" id="frmcustomline" method="post">';
	echo '<h3>List of custom line items active in the system</h3>';
	echo '<table width="100%" id="table_lineitem_report"><thead><tr><th>USER</th><th>CATEGORY</th><th>NAME</th><th>TEXT</th><th>COMMENT</th></tr></thead>';
	$data = getCustomLineitemReportData();
	foreach ($data as $lineitem) {
		echo
			'<tr>'.
			'<td width="20%">'.$lineitem['user'].'</td>'.
			'<td width="20%">'.$lineitem['category'].'</td>'.
			'<td width="20%">'.$lineitem['name'].'</td>'.
			'<td width="20%">'.$lineitem['text'].'</td>'.
			'<td width="20%">'.$lineitem['comment'].'</td>'.
			'</tr>';
	}
	echo '</table>';
	echo '</form>';
	$user_id = get_current_user_id();
	echo '<br/><a href="../wp-content/plugins/specdoc.me/getexcel.php?user_id='.$user_id.'&user_key='
			.md5('hej'.$user_id).'&reportid=customli">Get report for Excel</a>';
}
function getCustomLineitemReportData()
{
	global $wpdb;

	/*$myrows = $wpdb->get_results('select * from wp_usermeta join wp_users on ID=user_id where meta_key="lc_tool_data"');
	$data = array();

	foreach ($myrows as $myrow) {
		$values = unserialize($myrow->meta_value);
		$user = $myrow->user_login;
		foreach ($values as $category) {
			if($category[CATEGORY_LINEITEMS] == null)
				continue;
			foreach($category[CATEGORY_LINEITEMS] as $lineitem) {
				if($lineitem[LINEITEM_IS_SELECTED]) {
					$data[] = array(
						'user'    => $user,
						'category'=> $category[CATEGORY_NAME],
						'name'    => $lineitem[LINEITEM_NAME],
						'text'    => $lineitem[LINEITEM_TEXT],
						'comment' => $lineitem[LINEITEM_COMMENT]
					);
				}
			}
		}
	}*/

	$myrows= $wpdb->get_results("select u.user_login,l.*,c.name as catname,l.name as linename from wp_specdoc_categories c, wp_users u, wp_specdoc_line_items l where c.category_id=l.category_id and u.ID=c.foruser and  u.ID=l.foruser and c.enabled=1 and l.enabled=1");
	foreach ($myrows as $myrow) {
		 $data[]=array(
			'user'    => $myrow->user_login,
			'category'=> $myrow->catname,
			'name'    =>  $myrow->linename,
			'text'    =>  $myrow->text,
			'comment' =>  $myrow->comment
		 );
	}

	return $data;
}
