<?php

/* Define the custom box */
add_action( 'add_meta_boxes', 'skope_add_custom_box', 10 );

/* Do something with the data entered */
add_action( 'save_post', 'skope_save__sidebar_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function skope_add_custom_box() {
  add_meta_box( 'skope_side_bar', 'Side Bar Content', 'skope_editor_meta_box', 'page', 'normal', 'core' );
}

/* Prints the box content */
function skope_editor_meta_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'skope_noncename' );

  $field_value = get_post_meta( $post->ID, '_skope_side_bar', false );
  wp_editor( $field_value[0], '_skope_side_bar' );
}

/* When the post is saved, saves our custom data */
function skope_save__sidebar_postdata( $post_id ) {

  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( ( isset ( $_POST['skope_noncename'] ) ) && ( ! wp_verify_nonce( $_POST['skope_noncename'], plugin_basename( __FILE__ ) ) ) )
      return;

  // Check permissions
  if ( ( isset ( $_POST['post_type'] ) ) && ( 'page' == $_POST['post_type'] )  ) {
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
      return;
    }    
  }
  else {
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }
  }

  // OK, we're authenticated: we need to find and save the data
  if ( isset ( $_POST['_skope_side_bar'] ) ) {
    update_post_meta( $post_id, '_skope_side_bar', $_POST['_skope_side_bar'] );
  }
}


/*  ***************  Add checkbox on page as meta box to make this page as guide page *******************  */
add_action( 'add_meta_boxes', 'skope_add_guide_checkbbox', 10 );

function skope_add_guide_checkbbox() {
  add_meta_box( 'skope_guide_checkbox', 'Add it on Guide page dropdown?', 'skope_guide_checkbox', 'page', 'side', 'default' );
}

function skope_guide_checkbox() {
    global $post;
    $custom = get_post_custom($post->ID);
    if (array_key_exists('skope_guide_checkbox', $custom)) {
    	$sl_meta_box_sidebar = $custom["skope_guide_checkbox"][0]; 
    } else {
		$sl_meta_box_sidebar = '';
    }
?>

<input type="checkbox" name="skope_guide_checkbox" <?php if( $sl_meta_box_sidebar == true ) { ?>checked="checked"<?php } ?> />  Check this box to show this page on guide page.
<?php
}

add_action('save_post', 'save_guide_checkbbox');

function save_guide_checkbbox($post_ID = 0) {
    $post_ID = (int) $post_ID;
    $post_type = get_post_type( $post_ID );
    $post_status = get_post_status( $post_ID );

	  // Check permissions
	  if ( ( isset ( $_POST['post_type'] ) ) && ( 'page' == $_POST['post_type'] )  ) {
		if ( ! current_user_can( 'edit_page', $post_ID ) ) {
		  return;
		}    
	  }
	  else {
		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
		  return;
		}
	  }

    if ($post_type) {
    	update_post_meta($post_ID, "skope_guide_checkbox", $_POST["skope_guide_checkbox"]);
    }
   return $post_ID;
} ?>
