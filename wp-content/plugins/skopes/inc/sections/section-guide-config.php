<?php
/*
*	Section for HTML of guide page config page
*	Written by Royal Tyagi at 03-02-2015.
*	
*/
?>
<?php
	if ($_POST['skope-guide-page-config'] == 'Y') {
		check_admin_referer( "guide-page-config" );
		for( $i=1; $i <= 4; $i++ ) {
			$key = '_guide_page_section_'.$i;
			$val = $_POST[$key];
			update_option( trim($key) ,serialize($val) );
		}

		$url_parameters = 'updated=true';
		wp_redirect(admin_url('admin.php?page=guide-page-config&'.$url_parameters));
		exit;
	}


	for( $i=1; $i <= 4; $i++ ) {

		$key = '_guide_page_section_'.$i;
		$guide_page_section = get_option(trim($key));

		if (empty($guide_page_section)) {

			$data = get_blank_array();
			$val = serialize($data);
			add_option( trim($key), $val );

		}		
	}

	function get_blank_array() {
		$result = array();
		for( $i=0; $i < 5; $i++ ) {


			$result[$i] = array(
				'title'			 => '',
				'discription'	=>'',
				$i => array(
					'text' 			=> '',
					'link' 			=> '',
					'type' 		=> '',
					'pop-up' 	=> '',
					'short_title' 	=> '',
					'status' 	=> '0',
					'pageStatus'	=> ''
				)
			);
		}

		return $result;
	}

	$guide_page_section = array(
		'1'  => unserialize(get_option('_guide_page_section_1')),
		'2'  => unserialize(get_option('_guide_page_section_2')),
		'3'  => unserialize(get_option('_guide_page_section_3')),
		'4'  => unserialize(get_option('_guide_page_section_4'))
	);

	$types = array(
		'enter_data_in_skopes'	=> 'Enter data in Skopes',
		'take_an_action'		=> 'Take an action',
		'learn_something'	=> 'Learn something'
	);

	$status = array(
		'0'	=> 'Disable',
		'1'		=> 'Enable'
	);

	$args = array(
		'sort_order' => 'ASC',
		'sort_column' => 'post_title',
		'hierarchical' => 1,
		'exclude' => '',
		'include' => '',
		'meta_key' => 'skope_guide_checkbox',
		'meta_value' => 'on',
		'authors' => '',
		'child_of' => 0,
		'parent' => -1,
		'exclude_tree' => '',
		'number' => '',
		'offset' => 0,
		'post_type' => 'page',
		'post_status' => 'publish'
	); 
	$pages = get_pages($args);

	$pagesStatus = array(
		'type#dashboard'		=> 'Dashboard',
		'type#aboutcompany'	=> 'aboutcompany',
		'type#aboutproject'	=> 'aboutproject',
		'type#features'	=> 'features',
		'type#moreinfo'	=> 'moreinfo',
		'type#featuresMore'	=> 'featuresMore',
	);

	foreach ($pages as $page ) {
		$pagesStatus['type#'.$page->ID] = $page->post_title;
	}
?>
<div class="wrap">
	<h2><?php echo 'Guide Page configuration'; ?></h2>
<?php	//echo "<pre>";		print_r($guide_page_section);	echo "</pre>"; ?>
	<?php
		if ( 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Your guide page configuration has been saved.</p></div>';
	?>
	<div id="poststuff">
		<form method='post' enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<?php
				echo '<p>You can add or edit the content of guid page.</p>';
				echo '<table class="widefat">';
				wp_nonce_field( "guide-page-config" );
				$numbermappings = array("one","two","three", "four" , "five");

				for ($col =1; $col<=4; $col++) {

					$j = $col-1;
					echo getTableHead( $numbermappings[$j] ); ?>

					<tr valign="top">
						<td>Title</td>
						<td><input type="text" name="_guide_page_section_<?=$col; ?>[title]" value="<?php	echo stripslashes(htmlentities($guide_page_section[$col]['title'])); ?>"></td>
						<td style="text-align: right;">Discription</td>
						<td colspan="4"><textarea name="_guide_page_section_<?=$col; ?>[discription]" value="" style="width:100%;"><?php	echo stripslashes(htmlentities($guide_page_section[$col]['discription'])); ?></textarea></td>
					</tr>

				<?php	 for( $i=0; $i < 5; $i++ ) { ?>
						<tr valign="top">
							<td>Textbox <?php echo $numbermappings[$i]; ?></td>
							<td><input type="text" name="_guide_page_section_<?=$col; ?>[<?=$i; ?>][text]" value="<?php	echo stripslashes(htmlentities($guide_page_section[$col][$i]['text'])); ?>"></td>
							<td><input type="text" name="_guide_page_section_<?=$col; ?>[<?=$i; ?>][link]" value="<?php	echo stripslashes(htmlentities($guide_page_section[$col][$i]['link'])); ?>"></td>
							<td><select name="_guide_page_section_<?=$col; ?>[<?=$i; ?>][type]">
								<?php foreach( $types as $k => $v ) { ?>

									<?php if ( $guide_page_section[$col][$i]['type'] == $k ) { ?>
										<option value="<?=$k; ?>" selected="selected"><?=$v; ?></option>
									<?php } else { ?>
										<option value="<?=$k; ?>"><?=$v; ?></option>
									<?php } ?>

								<?php } ?>
							</select></td>
							<td><input type="text" name="_guide_page_section_<?=$col; ?>[<?=$i; ?>][pop-up]" value="<?php  echo stripslashes(htmlentities($guide_page_section[$col][$i]['pop-up'])); ?>"></td>
							<td><input type="text" name="_guide_page_section_<?=$col; ?>[<?=$i; ?>][short_title]" value="<?php  echo stripslashes(htmlentities($guide_page_section[$col][$i]['short_title'])); ?>"></td>
							<td><select name="_guide_page_section_<?=$col; ?>[<?=$i; ?>][status]">
								<?php foreach( $status as $k => $v ) { ?>

									<?php if ( $guide_page_section[$col][$i]['status'] == $k ) { ?>
										<option value="<?=$k; ?>" selected="selected"><?=$v; ?></option>
									<?php } else { ?>
										<option value="<?=$k; ?>"><?=$v; ?></option>
									<?php } ?>

								<?php } ?>
							</select></td>
							<td><select name="_guide_page_section_<?=$col; ?>[<?=$i; ?>][pageStatus]">
								<?php foreach( $pagesStatus as $k => $v ) { ?>

									<?php if ( $guide_page_section[$col][$i]['pageStatus'] == $k ) { ?>
										<option value="<?=$k; ?>" selected="selected"><?=$v; ?></option>
									<?php } else { ?>
										<option value="<?=$k; ?>"><?=$v; ?></option>
									<?php } ?>

								<?php } ?>
							</select></td>	
						</tr>
					<?php } ?>

				<?php } ?>
				<?php echo '</table>'; ?>
				<p class="submit" style="clear: both;">
					<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Update Settings" />
					<input type="hidden" name="skope-guide-page-config" value="Y" />
				</p>
			</table>
		</form>
	</div>
</div>
<?php
	function getTableHead( $number ) {

		$html =  '<tr valign="top">';
		$html .=	'<th style="padding: 25px;width: 76px;">Step '. $number .':</th>';
		$html .= '<th style="padding: 25px;">Text</th>';
		$html .= '<th style="padding: 25px;">Link</th>';
		$html .= '<th style="padding: 25px;">Type</th>';
		$html .= '<th style="padding: 25px;">Help pop-up text</th>';
		$html .= '<th style="padding: 25px;">Short Title</th>';
		$html .= '<th style="padding: 25px;">Status</th>';
		$html .= '<th style="padding: 25px;">Pages Status</th>';
		$html .= '</tr>';

		return $html;
	}
?>
<style type="text/css">
table.widefat input[type=text] {
	width:100%;
}
table.widefat th {
	font-weight: bold;
}
</style>
