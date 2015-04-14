<?php 
global $qafp_options;
$qafp_options = get_option( 'qafp_options' ); 

//our custom post functions
require ( QAFP_PATH . 'inc/custom-post.php' );

// If the version numbers don't match, run the upgrade script
if ( $qafp_options['version'] < QAFP_VERSION ) { 
	require ( QAFP_PATH . 'inc/upgrader.php' );
}

//shortcodes
require ( QAFP_PATH . 'inc/shortcodes.php' );

//Reorder script
require_once(dirname(__FILE__).'/reorder.php');

//ratings
if ( $qafp_options['ratings'] === true ) {
	require ( QAFP_PATH . 'inc/ratings.php' );
}

// widgets
require ( QAFP_PATH . 'inc/widgets.php' );

// action links
function qafp_action_links( $links, $file ) {
	static $this_plugin; 

	if ( !$this_plugin ) {
		$this_plugin = QAFP_LOCATION;
	}

	// check to make sure we are on the correct plugin
	if ( $file == $this_plugin ) {
		// the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
		$settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=qafp">Settings</a>';
		// add the link to the list
		array_unshift( $links, $settings_link );
	}
	return $links;
}

add_filter( 'plugin_action_links', 'qafp_action_links', 10, 2 );

/* Our rewrite functions */

function qafp_rewrites() {
	global $qafp_options;
	if ( ! $qafp_options['faq_slug'] ) { $qafp_options['faq_slug'] = 'faqs'; } else { $qafp_options['faq_slug'] = strtolower( $qafp_options['faq_slug'] ); }
	
	add_rewrite_rule( $qafp_options['faq_slug'] . '/search/?([^/]*)','index.php?s=$matches[1]&post_type=qa_faqs','top');
	  
  	add_rewrite_rule( $qafp_options['faq_slug'] . '/page/?([^/]*)','index.php?pagename=' . $qafp_options['faq_slug'] . '&paged=$matches[1]','top');
   
	add_rewrite_rule( $qafp_options['faq_slug'] . '/category/?([^/]*)/page/?([^/]*)','index.php?pagename=' . $qafp_options['faq_slug'] . '&category_name=$matches[1]&paged=$matches[2]','top');
	
	add_rewrite_rule( $qafp_options['faq_slug'] . '/category/?([^/]*)','index.php?pagename=' . $qafp_options['faq_slug'] . '&category_name=$matches[1]','top');

}
		
add_action('init', 'qafp_rewrites');

add_action( 'wp_head', 'qafp_head' );

if ( ! function_exists( 'qafp_head' ) ) {
	function qafp_head() {
		global $qafp_options;
		if ( wp_style_is( 'q-a-focus-plus', $list = 'enqueued' ) ==true ) echo '<!-- Q & A Focus Plus -->
		<noscript><link rel="stylesheet" type="text/css" href="' .  plugins_url( "css/q-a-focus-plus-noscript.min.css?ver=" . $qafp_options['version'], dirname(__FILE__) ) . '" /></noscript><!-- Q & A Focus Plus-->
		';
	} // end qafp_head 
}

function qafp_template_redirect() {
    global $wp;
	global $wp_query;
	global $post;
	
	if ( is_single() && 'qa_faqs' == get_post_type($post) ) {
    
		if ( file_exists( TEMPLATEPATH . '/single-qafp_faqs.php') ) {	
			$page_template = TEMPLATEPATH . '/single-qafp_faqs.php';
		} elseif ( file_exists( TEMPLATEPATH . '/page.php') ) {	
			$page_template = TEMPLATEPATH . '/page.php';
		} elseif ( file_exists( TEMPLATEPATH . '/single.php' )) {
			$page_template = TEMPLATEPATH . '/single.php';
		} else {
			$page_template = TEMPLATEPATH . '/index.php';
		}
	}
	        
    if ( isset( $page_template ) ) {
		if ( have_posts() ) {
			include( $page_template );
			exit;
		} else {
			$wp_query->is_404 = true;
		}
	}
}

if ( 'QA_REDIRECTS' != FALSE ) {
//	add_action("template_redirect", 'qafp_template_redirect');
}

function addqafpPage(){
	global $qafp_options;
	$qafp_admin = get_option( 'qafp_admin_options' );
	$new_page =  str_replace('-', ' ', $qafp_options['faq_slug']);
	$new_page = ucwords( $new_page );
	// Create post object
	$qafp_post = array(
	  'post_title' => $new_page,
	  'post_content' => '[qafp]',
	  'post_status' => 'publish',
	  'post_type' => 'page',
	  'post_author' => 1
	);
	
	// Insert the post into the database
	wp_insert_post( $qafp_post );
	
	$page_exists = get_page_by_path( $qafp_options['faq_slug'] );
	if ( $page_exists ) {
		echo '<p>' . _e('Page was successfully created!', 'qa-focus-plus') . '</p>';
	} else {
		echo '<p>' . _e('Page could not be created. Please create it and add the shortcode manually.', 'qa-focus-plus') . '</p>';
	}
	die();
}

add_action('wp_ajax_addqafpPage', 'addqafpPage');
add_action('wp_ajax_nopriv_addqafpPage', 'addqafpPage'); // not really needed

function dismissqaffpCreate(){
	$qafp_admin = get_option( 'qafp_admin_options' );
	
	// Insert the post into the database
	$qafp_admin['dismiss_slug'] = true;
	update_option( 'qafp_admin_options', $qafp_admin );
	
	die();
}

add_action('wp_ajax_dismissqaffpCreate', 'dismissqaffpCreate');
add_action('wp_ajax_nopriv_dismissqaffpCreate', 'dismissqaffpCreate');

// Check Old Post Types
/*function check_old_post_type() {
	global $post;
	$args = array( 'post_type' => 'qafp_faqs' );
	$theposts = get_posts( $args );
	if ( !empty($theposts) ) return true ? true : false;
}
add_action('admin_init', 'check_old_post_type');

// Update Old Post Types
function fix_old_post_type() {
	global $post, $wpdb;
	$args = array( 'post_type' => 'qafp_faqs' );
	$theposts = get_posts( $args );
	if ( !empty($theposts) ) {
		foreach ( $theposts as $post ) {			
			$data_update = array('post_type' => "qa_faqs");
			$data_where = array('id' => "$post->ID", 'post_type' => "qafp_faqs");
			$wpdb->update($wpdb->posts, $data_update, $data_where);
		}
	}
}
add_action('admin_init', 'fix_old_post_type');*/

// GET LAST MODIFIED DATE
function qafp_last_faq_mod_date() {
	global $wpdb;
	$update = $wpdb->get_results( "SELECT MAX(post_modified) as post_modified FROM $wpdb->posts
	WHERE post_type = 'qa_faqs' AND post_status = 'publish' LIMIT 1" );
	foreach($update as $post) {
		//return date('F j, Y g:i A', strtotime($post->post_modified));
		return date_i18n( get_option( 'date_format' ), strtotime( $post->post_modified ) );
	}
}
add_action("qafp_mod_date", 'qafp_last_faq_mod_date');

// TAXONOMY SUPPORT
if ( !function_exists( 'ezts_filter_post_tags' ) && !$qafp_options['tax_off'] ) {
	function ezts_filter_post_tags( $query ) {
		if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
			$post_types = get_post_types();
			$query->set( 'post_type', $post_types );
			return $query;
		
		}
	}
	add_filter( 'pre_get_posts', 'ezts_filter_post_tags' );	
}

/* Add category links to single FAQ entries */

function qafp_add_categories() {

	global $qafp_options, $post;
	$id = $post->ID;
	
	$i = 1;
	$qa_tax_output = '';
	
	$terms = get_the_terms( $id, 'faq_category' );
	$count = count( $terms );
	
	if ( $terms ) {
			
		foreach( $terms as $term ) {
			$qa_tax_output .= '<a href=" ' . home_url() . '/' . $qafp_options['faq_slug'] . '/category/' . $term->slug . '/">';
			$qa_tax_output .= $term->name;
			if ( $i != $count ) {
				$qa_tax_output .= '</a>, ';
			} else {
				$qa_tax_output .= '</a>';
			}
			unset($term);
			$i++;
		}
	}
	
	return $qa_tax_output;
}

function qafp_add_categories_to_single ($content) {
	global $post, $qafp_options;

	if ( is_single() && in_the_loop() && 'qa_faqs' == get_post_type($post) ) {
		
		$page_title = get_the_title( get_page_by_path($qafp_options['faq_slug']) );	
		$qa_post_options = '';
		if ( $qafp_options['ratings'] === true ) $qa_post_options = '<p class="qafp-faq-meta qafp-post-like">' . getPostLikeLink($post->ID) . '</p>
		';
		if ( $qafp_options['hr'] === true ) $qa_post_options .= '<hr />
		';
		$posttags = '';
		if ( has_term( '', 'post_tag', $post->ID ) && !$qafp_options['hide_tags'] ) $posttags = get_the_term_list( $post->ID, 'post_tag', $before = __( 'Tags: ', 'qa-focus-plus' ), $sep = ', ', $after = '' );
		$home_link = '<a href="' . home_url() . '/' . $qafp_options['faq_slug'] . '" title="' . $page_title . '">&larr; ' . $page_title . '</a>
		';
		
		$qafp_cats = '';
		$faq_cats = qafp_add_categories();
		$hasCats = !empty( $faq_cats );
		if ( $hasCats ) {
			$catCount = count( $faq_cats );
			if ( $catCount > 1 ) $qafp_cats = __( 'Categories: ', 'qa-focus-plus' ) . $faq_cats;
			else $qafp_cats = __( 'Category: ', 'qa-focus-plus' ) . $faq_cats;
		 //$qafp_cats = sprintf( _n( 'Category: ', 'Categories: ', $faq_cats, 'qa-focus-plus' ), $catCount ) . ' - ' . get_post_reply_link( '', $post->ID );
		//if ( $hasCats ) $qafp_cats = __( 'Posted in: ', 'qa-focus-plus' ) . $faq_cats;
		}
		if ( $hasCats ) {
			$content = $content . $qa_post_options . '<p class="qafp-faq-meta">
			' . $qafp_cats . '<br />
			' . $posttags . 
			'</p>
			' . $home_link;
		} else {
			$content = $content . $qa_post_options . '<p class="qafp-faq-meta">
			' . $posttags . 
			'</p>
			' . $home_link;
		}

	}
	return $content;
}

add_filter( 'the_content', 'qafp_add_categories_to_single' );
