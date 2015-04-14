<?php
/* *****************
*
* File made by Royal Tyagi on 23-March-15
* Added tab's on this page so we can mangae multiple forms from single page in admin
*
*******************/

global $pagenow;

if ( $pagenow == 'admin.php' && $_GET['page'] == 'specdoc-mproject-fields' ) {

	skope_more_projects_page();
}


function skope_admin_tabs( $current = 'setting' ) {
    $tabs = array( 'selection-criteria' => 'Selection Criteria', 'milestone' => 'Milestone' );
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=specdoc-mproject-fields&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}

function skope_more_projects_page() {
global $pagenow;
?>
<div class="wrap">
	<h2><?php echo 'Skope Settings'; ?></h2>

	<?php
		if ( 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Your settings has been saved.</p></div>';
		if ( isset ( $_GET['tab'] ) ) skope_admin_tabs($_GET['tab']); else skope_admin_tabs('selection-criteria');
	?>

	<div id="poststuff">
			<?php

			if ( $pagenow == 'admin.php' && $_GET['page'] == 'specdoc-mproject-fields' ) {

				if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
				else $tab = 'selection-criteria';

				switch ( $tab ) {
					case 'selection-criteria' :
						include('sections/section-selection-criteria-tab.php');
					break;

					case 'milestone' :
						include('sections/section-milestone-tab.php');
					break;
				}
			}
			?>

	</div>
</div>

<?php
}
?>
