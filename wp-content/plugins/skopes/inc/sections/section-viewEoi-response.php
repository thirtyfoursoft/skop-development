<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap">';
    echo '<h1>&nbsp;&nbsp;&nbsp;EOI Response View</h1>';
    echo '<br />';

    /*     get the eoi id from eoi response view page    */
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if (isset($_GET) && $_GET != '') {
        $get_eoi_id = test_input($_GET["eoi_id"]);
    }

	if ( $get_eoi_id == '' ) {
		wp_redirect(admin_url('admin.php?page=specdoc-eoi-response'));
		exit;
	}    

    /*     get the data from database wp_eoiresponse    */
    global $wpdb;
    $eoi_response_results = $wpdb->get_results(
        "SELECT eoi_id, org_name, about_your_org, add_notes_cred
        FROM wp_eoiresponse
        WHERE eoi_id = $get_eoi_id"
    );

    /*     check if this eoi id exists      */
    if ($eoi_response_results[0]->org_name == NULL)
    {
        echo '<table style="float:center">';
        echo '<tr>';
        echo
        '<td style="margin-left:75px;width:1350px"><h3>THIS EOI ID IS NOT EXISTING!</h3></td>';
        echo '</table>';
    }

    /*     display EOI ID, ORGANISATION NAME, REVIEWER NAME, ABOUT YOUR ORGANISATION     */
    else {
        foreach ( $eoi_response_results as $eoi_response_result){
            echo '<table style="float:center">';
            echo '<tr>';
            echo
            '<td style="background-color:#ffeeee; margin-left:15px;width:300px"><h3>EOI ID:</h3></td>';
            echo '<td style="background-color:#ffeeee; margin-left:15px;width:1050px"><p>'.$eoi_response_result->eoi_id.'</p></td>';
            echo '<tr>';
            echo
            '<td style="background-color:#eeeeff; margin-left:15px;width:300px"><h3>ORGANISATION NAME:</h3></td>';
            echo '<td style="background-color:#eeeeff; margin-left:15px;width:1050px"><p>'.$eoi_response_result->org_name.'</p></td>';

            echo '<tr>';
            echo
            '<td style="background-color:#ffeeee; margin-left:15px;width:300px"><h3>REVIEWER NAME:</h3></td>';
            echo '<td style="background-color:#ffeeee; margin-left:15px;width:1050px"><p>'.$eoi_response_result->reviewer_name.'</p></td>';
            echo '<tr>';
            echo
            '<td style="background-color:#eeeeff; margin-left:15px;width:300px"><h3>ABOUT YOUR ORGANISATION:</h3></td>';
            echo '<td style="background-color:#eeeeff; margin-left:15px;width:1050px"><p>'.$eoi_response_result->about_your_org.'</p></td>';
            echo '</table>';
        }
        echo '<br />';

        /*     get the data from database wp_teammember    */
        global $wpdb;
        $team_mem_results = $wpdb->get_results(
            "SELECT team_mem_name, position, project_role, contact_details, comments
        FROM wp_teammember
        WHERE eoi_id = $get_eoi_id"
        );


        /*     display Team Member Name, Position, Project Role, Contact Details, Comments     */
        echo '<table style="float:center">';
        echo '<tr>';
        echo
            '<td style="width:250px"><h3>Name</h3></td>'.
            '<td style="width:250px"><h3>Position</h3></td>'.
            '<td style="width:250px"><h3>Project Role</h3></td>'.
            '<td style="width:300px"><h3>Contact Details</h3></td>'.
            '<td style="width:300px"><h3>Comments</h3></td>';

//echo "<pre>";		print_r($team_mem_results);		echo "</pre>";
        $colortoggle = 0;
        foreach ( $team_mem_results as $team_mem_result){

            /* Set the colour of the table */
            if($colortoggle == 0)
                echo '<tr style="background-color:#ffeeee;">';
            else
                echo '<tr style="background-color:#eeeeff;">';
            $colortoggle = !$colortoggle;

            /* show the table */
            echo '<td><p>'.$team_mem_result->team_mem_name.'</p></td>';
            echo '<td><p>'.$team_mem_result->position.'</p></td>';
            echo '<td><p>'.$team_mem_result->project_role.'</p></td>';
            echo '<td><p>'.$team_mem_result->contact_details.'</p></td>';
            echo '<td><p>'.$team_mem_result->comments.'</p></td>';
        }
        echo '</table>';
        echo '<br />';

        /*     display ADDITIONAL NOTES & CREDENTIALS     */
        echo '<table style="float:center">';
        echo '<tr>';
        echo
        '<td style="background-color:#eeeeff; margin-left:15px;width:350px"><h3>ADDITIONAL NOTES & CREDENTIALS:</h3></td>';
        echo '<td style="background-color:#eeeeff; margin-left:15px;width:1050px"><p>'.$eoi_response_result->add_notes_cred.'</p></td>';
        echo '</table>';
        echo '<br />';

        /*     get the data from database wp_functionalareas    */
        global $wpdb;
        $func_areas_results = $wpdb->get_results(
            "SELECT *
        FROM wp_functionalareas
        WHERE eoi_id = $get_eoi_id"
        );

        /*     display Out of the Box, May Need a Workaround, Cannot Be Done     */
        echo '<table style="float:center">';
        echo '<tr>';
        echo
            '<td style="width:330px"><h3>&nbsp;</h3></td>'.
            '<td style="width:340px"><h3>Out of the Box</h3></td>'.
            '<td style="width:340px"><h3>May Need a Workaround</h3></td>'.
            '<td style="width:340px"><h3>Cannot Be Done</h3></td>';


        $colortoggle = 0;
        $area=1;
							        
        foreach ($func_areas_results as $func_areas_result) {

            /* Set the colour of the table */
            if($colortoggle == 0)
                echo '<tr style="background-color:#ffeeee;">';
            else
                echo '<tr style="background-color:#eeeeff;">';
            $colortoggle = !$colortoggle;

            /* show the table */
            echo '<td><h3>'.$func_areas_result->func_name.'</h3></td>';
			if ( $func_areas_result->complies_status == 'Out of the Box' ) {
	            echo '<td><p>Yes</p></td>';
            } else {
	            echo '<td><p>No</p></td>';
            }

			if ( $func_areas_result->complies_status == 'May Need a Workaround' ) {
	            echo '<td><p>Yes</p></td>';
            } else {
	            echo '<td><p>No</p></td>';
            }

			if ( $func_areas_result->complies_status == 'Cannot Be Done' ) {
	            echo '<td><p>Yes</p></td>';
            } else {
	            echo '<td><p>No</p></td>';
            }

            $area++;
        }
        echo '</table>';
        echo '<br />';
    }
    echo '</div>';
?>
