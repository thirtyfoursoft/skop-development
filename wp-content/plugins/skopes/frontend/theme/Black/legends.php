<?php
	include("../../../../wp-config.php");

	$objUser = new clsSkopes();
	$userid=get_current_user_id();

	$legend_title=$objUser->getDefaultData('INSTRUCTIONS_LEGEND_TITLE');
	$legend_title_text=unserialize($legend_title->text);
?>
	<h2 class="boxheading"><?php echo $legend_title_text; ?></h2>
	<div class="scrollbox">
		<div class="scrollbar-section">
			<?php
				$legend_blue=$objUser->getDefaultData('INSTRUCTIONS_LEGEND_BLUE');
				$legend_blue_text=unserialize($legend_blue->text);
			?>
				<div class="legend_blue marbtm30"><?php echo $legend_blue_text; ?></div>
			<?php
				$legend_green=$objUser->getDefaultData('INSTRUCTIONS_LEGEND_GREEN');
				$legend_green_text=unserialize($legend_green->text);
			?>
				<div class="legend_green marbtm30"><?php echo $legend_green_text; ?></div>
			<?php
				$legend_grey=$objUser->getDefaultData('INSTRUCTIONS_LEGEND_GREY');
				$legend_grey_text=unserialize($legend_grey->text);
			?>
			<div class="legend_grey marbtm30"><?php echo $legend_grey_text; ?></div>
		</div>
	</div>