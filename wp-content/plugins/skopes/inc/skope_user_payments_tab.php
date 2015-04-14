<?php
/* *****************
*
* File made by Royal Tyagi on 23-March-15
* Added tab's on this page so we can mangae multiple forms from single page in admin
*
*******************/

global $pagenow;

if ( $pagenow == 'admin.php' && $_GET['page'] == 'users-and-payments' ) {

	skope_more_projects_page();
}


function skope_admin_tabs( $current = 'users' ) {
    $tabs = array( 'users' => 'Users', 'vendor' => 'Vendor' );
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=users-and-payments&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}

function skope_more_projects_page() {
global $pagenow;
?>
<div class="wrap">
	<!--<h2><?php echo 'Users and payments'; ?></h2>-->
	<?php
		if ( 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Your settings has been saved.</p></div>';
		if ( isset ( $_GET['tab'] ) ) skope_admin_tabs($_GET['tab']); else skope_admin_tabs('users');
	?>
	<div id="poststuff">
			<?php

			if ( $pagenow == 'admin.php' && $_GET['page'] == 'users-and-payments' ) {

				if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
				else $tab = 'users';

				switch ( $tab ) {
					case 'users' :

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

					break;

					case 'vendor' :

						if ( isset ( $_GET['action'] ) ) $action = $_GET['action'];
						else $action = ''; 

						switch ( $action ){
							case 'payments' :
								include(RC_TC_BASE_DIR."/inc/sections/section-user-payments.php");
							break;
	
							case 'delete' :
								include(RC_TC_BASE_DIR."/inc/sections/section-user-delete.php");
							break;

							default:
								include(RC_TC_BASE_DIR."/inc/sections/section-vendors-list.php");
							break;

						}

					break;
				}
			}
			?>

	</div>
</div>

<?php
}
?>
