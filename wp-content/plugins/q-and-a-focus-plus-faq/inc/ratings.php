<?php
global $qafp_options;
$timebeforerevote = $qafp_options['rate_wait']; // minutes 525600 = 1 year

function qa_add_custom_fields($post_ID) {
	global $wpdb;
	if(!wp_is_post_revision($post_ID)) {
		add_post_meta($post_ID, 'votes_count', '0', true);
	}
}
add_action('publish_qafp_faqs', 'qa_add_custom_fields');
add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');
	
function ratings() {
	wp_enqueue_script('q-a-focus-plus-ratings', QAFP_URL . '/js/ratings.min.js', array('jquery'), QAFP_VERSION, 1 );
	wp_localize_script('q-a-focus-plus-ratings', 'ajax_var', array(
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('qafp-rate-nonce')
	));	
}
add_action( 'wp_enqueue_scripts', 'ratings' );

function post_like() {

	$nonce = $_POST['nonce'];
 
    if ( ! wp_verify_nonce( $nonce, 'qafp-rate-nonce' ) ) die ( 'Gotcha!');
		
	if( isset($_POST['post_like']) ) {
		
		global $current_user;

		get_currentuserinfo();
		if ( is_user_logged_in() ) $id = $current_user->ID; else $id = 'A' . ip2long($_SERVER['REMOTE_ADDR']);

		$post_id = $_POST['post_id'];

		$meta_ID = get_post_meta($post_id, "voted_ID");
		$voted_ID = $meta_ID[0];
		if( !is_array($voted_ID) ) $voted_ID = array();
		
		$meta_count = get_post_meta($post_id, "votes_count", true);

		if ( !hasAlreadyVoted($post_id) ) {
			$voted_ID[$id] = time();

			update_post_meta($post_id, "voted_ID", $voted_ID);
			update_post_meta($post_id, "votes_count", ++$meta_count);
			
			echo $meta_count;
		} else {
			echo "already";
		}

	}
	exit;
}

function hasAlreadyVoted($post_id) {
	
	global $current_user, $timebeforerevote, $qafp_options;
	
	/*$meta_ID = get_post_meta($post_id, "voted_ID");
	$voted_ID = $meta_ID[0];
	if( !is_array($voted_ID) ) $voted_ID = array();*/
	$voted_ID = array();
	$meta_ID = get_post_meta($post_id, "voted_ID");
	if ( isset($meta_ID[0]) ) $voted_ID = $meta_ID[0];

	get_currentuserinfo();
	if ( is_user_logged_in() ) $id = $current_user->ID; else $id = 'A' . ip2long($_SERVER['REMOTE_ADDR']);
	
	if ( in_array($id, array_keys($voted_ID)) ) {
		if ( $id[0] != 'A' || $qafp_options['rate_wait'] == null ) return true;
		$time = $voted_ID[$id];
		$now = time();
		if ( round(($now - $time) / 60) > $timebeforerevote ) return false;
		return true;
	}

	return false;
}

function getPostLikeLink($post_id) {

	global $qafp_options;

	$vote_count = get_post_meta($post_id, "votes_count", true);
	if ( !$vote_count ) $vote_count = 0;
	$icons = $qafp_options['ricons'];
	$output = '';

	if ( $qafp_options['restrict_ratings'] == false || ( $qafp_options['restrict_ratings'] === true && is_user_logged_in() ) ) {
		if(hasAlreadyVoted($post_id)) {
			$vote_count = $vote_count - 1;
			if ($vote_count == 1) $persons = __('person', 'qa-focus-plus'); else $persons = __('people', 'qa-focus-plus');
				$output .= '<span title="' . __('You already found this helpful!', 'qa-focus-plus') . '" class="qtip qafp-like-' . $icons .' qafp-alreadyvoted-' . $icons .'"></span>&nbsp;';
				$output .= '<span class="qafp-count">';
				$output .= sprintf( __('You and %1$s other %2$s found this helpful.', 'qa-focus-plus'), $vote_count, $persons);
				$output .= '</span>';
		} else {
			if ($vote_count == 1) $persons = __('person', 'qa-focus-plus'); else $persons = __('people', 'qa-focus-plus');
			$output .= '<a href="javascript:void(\'0\');" data-post_id="'.$post_id.'" class="qafp-star"><span class="qafp-rating-helper">';
			$output .= __('Please click here if this helped you.', 'qa-focus-plus');
			$output .= '</span><br />';
			$output .= '<span title="' . __('This is helpful!', 'qa-focus-plus') . '" class="qtip qafp-like-' . $icons .'">';
			$output .= '</span></a>
			<span class="qafp-count">';
			$output .= sprintf( __('%1$s %2$s found this helpful.', 'qa-focus-plus'), $vote_count, $persons);
			$output .= '</span>';
		}
	} else if ( $qafp_options['restrict_ratings'] === true && !is_user_logged_in() ) {
		if ($vote_count == 1) $persons = __('person', 'qa-focus-plus'); else $persons = __('people', 'qa-focus-plus');
		$output .= '<a href="' . wp_login_url( get_permalink() ) . '" target="_top"><span class="qafp-rating-helper">';
		$output .= __('Please log in to rate this.', 'qa-focus-plus');
		$output .= '</span><br />';
		$output .= '<span title="' . __('Please log in to rate this.', 'qa-focus-plus') . '" class="qtip qafp-like-' . $icons .'">';
		$output .= '</span></a>
		<span class="qafp-count">';
		$output .= sprintf( __('%1$s %2$s found this helpful.', 'qa-focus-plus'), $vote_count, $persons);
		$output .= '</span>';
	}
	
	return $output;
}
?>