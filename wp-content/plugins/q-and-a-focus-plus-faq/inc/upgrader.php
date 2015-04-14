<?php 

$qafp_options = get_option( 'qafp_options' );
$qafp_admin = get_option( 'qafp_admin_options' );

if ( !$qafp_options ) { // Create the defaults for a new installation

	$qaplus_options = get_option( 'qaplus_options' ); // Get old Q & A settings
	
	if ( $qaplus_options && $qaplus_options['version'] >= '1.0.5' ) {
		$old_options = get_option( 'qaplus_options' );
		// convert old true/false strings to boolean
		foreach( $old_options as $key => $val ) {
			if ( $val == 'true' ) $qafp_options[$key] = true;
			else if ( $val == 'false' ) $qafp_options[$key] = false;
			else $qafp_options[$key] = $val;
		}
		$qafp_options['catorder'] = 'term_order';
		$qafp_options['homelink'] = 'both';
		$qafp_options['ratings'] = true;
		$qafp_options['restrict_ratings'] = true;
		$qafp_options['rate_wait'] = null;
		$qafp_options['ricons'] = 'light';
		$qafp_options['plusminus'] = false;
		$qafp_options['focus'] = true;
		$qafp_options['tax_off'] = false;
		$qafp_options['hide_tags'] = false;
		$qafp_options['hr'] = true;
		$qafp_options['categoryhead'] = 'h3';
		$qafp_options['faqmargin'] = 'none';
		$qafp_options['answermargin'] = 'none';
		$qafp_options['titlecss'] = 'font-size:110%;font-weight:bold;margin-bottom:.5em';
		$qafp_options['version'] = QAFP_VERSION;
	} else {
		$qafp_options['faq_slug'] = 'faqs';
		$qafp_options['limit'] = '-1';
		$qafp_options['columns'] = '2';
		$qafp_options['postnumber'] = true;	
		$qafp_options['excerpts'] = false;
		$qafp_options['search'] = 'home';
		$qafp_options['searchpos'] = 'top';
		$qafp_options['submissions'] = false;
		$qafp_options['catdesc'] = false;
		$qafp_options['catlink'] = false;
		$qafp_options['catorder'] = 'term_order';
		$qafp_options['expandall'] = 'none';
		$qafp_options['ratings'] = true;
		$qafp_options['restrict_ratings'] = true;
		$qafp_options['rate_wait'] = null;
		$qafp_options['ricons'] = 'light';
		$qafp_options['open'] = 'none';
		$qafp_options['sort'] = 'menu_order';
		$qafp_options['homelink'] = 'both';
		$qafp_options['permalinks'] = false;
		$qafp_options['collapsible'] = true;
		$qafp_options['plusminus'] = false;
		$qafp_options['accordion'] = true;	
		$qafp_options['animation'] = 'fade';
		$qafp_options['version'] = QAFP_VERSION;
		$qafp_options['focus'] = true;
		$qafp_options['tax_off'] = false;
		$qafp_options['hide_tags'] = false;
		$qafp_options['hr'] = true;
		$qafp_options['categoryhead'] = 'h3';
		$qafp_options['faqmargin'] = 'none';
		$qafp_options['answermargin'] = 'none';
		$qafp_options['titlecss'] = 'font-size:110%;font-weight:bold;margin-bottom:.5em';
		$qafp_admin['dismiss_slug'] = false;
	}
	update_option( 'qafp_options', $qafp_options );
	update_option( 'qafp_admin_options', $qafp_admin );

	/* Now add the zero votes for all pre-existing posts */

	$args = array(
		'post_type'     => 'qa_faqs',
		'post_status'   => 'publish',
		'posts_per_page' => -1
	);
		
	$qafp_faqs = new WP_Query( $args );

	while( $qafp_faqs->have_posts() ): $qafp_faqs->the_post();
		global $post;
		add_post_meta($post->ID, 'votes_count', '0', true);
	endwhile;

} else {  /* Installation already exists, install updates */

	if ( $qafp_options['version'] < '1.3.9' ) {
		$qafp_options['catorder'] = 'term_order';
		$qafp_options['homelink'] = 'both';
	}
	
	$qafp_options['version'] = QAFP_VERSION;
	update_option( 'qafp_options', $qafp_options );

}

?>