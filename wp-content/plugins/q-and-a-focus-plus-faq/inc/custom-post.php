<?php 
add_action( 'init', 'create_qafp_post_types', 0 );
function create_qafp_post_types() {
	 
	 global $qafp_options;
	 
	 $labels = array(
		'name' => _x( 'FAQ Categories', 'qa-focus-plus' ),
		'singular_name' => _x( 'FAQ Category', 'qa-focus-plus'),
		'search_items' =>  __( 'Search FAQ Categories', 'qa-focus-plus'),
		'all_items' => __( 'All FAQ Categories', 'qa-focus-plus' ),
		'parent_item' => __( 'Parent FAQ Category', 'qa-focus-plus' ),
		'parent_item_colon' => __( 'Parent FAQ Category:', 'qa-focus-plus'),
		'edit_item' => __( 'Edit FAQ Category', 'qa-focus-plus'), 
		'update_item' => __( 'Update FAQ Category', 'qa-focus-plus'),
		'add_new_item' => __( 'Add New FAQ Category', 'qa-focus-plus'),
		'new_item_name' => __( 'New FAQ Category Name', 'qa-focus-plus')
  	);

	register_post_type( 'qa_faqs',
		array(
			'labels' => array(
				'name' => __( 'FAQs', 'qa-focus-plus' ),
				'singular_name' => __( 'FAQ', 'qa-focus-plus' ),
				'edit_item'	=>	__( 'Edit FAQ', 'qa-focus-plus'),
				'add_new_item'	=>	__( 'Add FAQ', 'qa-focus-plus')
			),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => $qafp_options['faq_slug'], 'with_front' => false ),
			'taxonomies' => array( 'FAQs', 'post_tag'),
			'supports' => array('title','editor','comments','author'),
			'has_archive' => false
		)
	); 	
  
  	register_taxonomy('faq_category',array('qa_faqs'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'faq-category' ),
	));
  
}	

add_action('restrict_manage_posts','restrict_listings_by_categories');
function restrict_listings_by_categories() {
    global $typenow;
    global $wp_query;
    if ($typenow=='qa_faqs') {
        
		$tax_slug = 'faq_category';
        
		// retrieve the taxonomy object
		$tax_obj = get_taxonomy($tax_slug);
		$tax_name = $tax_obj->labels->name;
		// retrieve array of term objects per taxonomy
		$terms = get_terms($tax_slug);

		// output html for taxonomy dropdown filter
		echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
		echo "<option value=''>Show All $tax_name</option>";
		foreach ($terms as $term) {
			// output each select option line, check against the last $_GET to show the current option selected
			echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
		}
		echo "</select>";
    }
}

/* Add custom columns to the CPT manage screen */

function set_qafp_faqs_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title', 'qa-focus-plus'),
        'faq_category' => __('Category', 'qa-focus-plus'),
        'date' => __('Date', 'qa-focus-plus')
    );
}
add_filter('manage_qafp_faqs_posts_columns', 'set_qafp_faqs_columns');

add_action('manage_posts_custom_column', 'qafp_show_columns');
function qafp_show_columns($name) {
    global $post;
    switch ($name) {
        case 'faq_category':
            $faq_cats = get_the_terms(0, "faq_category");
			$cats_html = array();
			if(is_array($faq_cats)){
				foreach ($faq_cats as $term)
						array_push($cats_html, '<a href="edit.php?post_type=qa_faqs&faq_category='.$term->slug.'">' . $term->name . '</a>');

				echo implode($cats_html, ", ");
			}
			break;
		default :
			break;	
	}
}