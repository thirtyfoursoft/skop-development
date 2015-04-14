<?php 
add_shortcode('qa', 'qafphome_shortcode');
add_shortcode('qafp', 'qafphome_shortcode');
add_shortcode('qafp_show_last_updated', 'qafplastupdated_shortcode');
add_shortcode('qafp_show_home_link', 'qafphomelink_shortcode');

/* Define the shortcode functions */

function qafplastupdated_shortcode() {
	return qafp_last_faq_mod_date();
}

function qafphomelink_shortcode() {
	global $qafp_options;
	$page_title = get_the_title( get_page_by_path($qafp_options['faq_slug']) );
	return '<a href="' . home_url() . '/' . $qafp_options['faq_slug'] . '" title="' . $page_title . '">&larr; ' . $page_title . '</a>';
}

function qafphome_shortcode( $atts ) {
	
	global $qafp_options, $catname;
	$catname = (get_query_var('category_name'));
	
	STATIC $i = 0;

	$qafp_shortcode_output = '';
	
	extract(shortcode_atts(array(
		'id' => '',
		'cat' => '',
		'limit' => $qafp_options['limit'],
		'search' => $qafp_options['search'],
		'searchpos' => $qafp_options['searchpos'],
		'catlink' => $qafp_options['catlink'],
		'catdesc' => $qafp_options['catdesc'],
		'postnumber' => $qafp_options['postnumber'],
		'excerpts' => $qafp_options['excerpts'],
		'homelink' => $qafp_options['homelink'],
		'permalinks' => $qafp_options['permalinks'],
		'accordion'	=> $qafp_options['accordion'],
		'collapsible' => $qafp_options['collapsible'],
		'plusminus'	=> $qafp_options['plusminus'],
		'sort' => $qafp_options['sort'],
		'animation' => $qafp_options['animation'],
		'exclude' => '',
		'orderby' => 'name',
		'catorder' => $qafp_options['catorder'],
		'focus' => $qafp_options['focus'],
		'hr' => $qafp_options['hr'],
	), $atts));
	
	$ratings = $qafp_options['ratings'];
	if ( $ratings != true ) $sort = 'menu_order';
	
	if ( $qafp_options['faqmargin'] != 'none' ) {
		$faqmargin = ' style="margin-left:' . $qafp_options['faqmargin'] . 'em;"';
	} else {
		$faqmargin = '';
	}
	if ( $qafp_options['answermargin'] != 'none' ) {
		$answermargin = ' style="margin-left:' . $qafp_options['answermargin'] . 'em;"';
	} else {
		$answermargin = '';
	}
	
	$hrmargin = ' style="margin-left:-' . $qafp_options['answermargin'] . 'em;"';
	$plmargin = '';
	
	if ( $plusminus === true && $collapsible === true ) $showplusminus = true; else $showplusminus = false;
	//$args = array(
	//	'orderby' => $orderby,
	//	'exclude' => $exclude,
	//	'taxonomy' => 'faq_category',
	//); 
	
	if ( $id ) { // Show a single entry //

		$qafp_shortcode_output .= '<div class="qafp-faqs qafp-single cf';
			
			switch( $animation ) {
				
				case 'none' :
				$qafp_shortcode_output .= ' animation-none';
				break;
				
				case 'slide' :
				$qafp_shortcode_output .= ' animation-slide';
				break;
				
				default :
				$qafp_shortcode_output .= ' animation-fade';
				break;
			}	

		if ( $accordion === true )	$qafp_shortcode_output .= ' accordion';

		if ( $collapsible === true ) $qafp_shortcode_output .= ' collapsible';
		
		if ( $focus === true ) $qafp_shortcode_output .= ' focus';
		 						
		$qafp_shortcode_output .= '">
		';
		
		$args = array(
			'p'	=> $id,
			'post_type'     => 'qa_faqs',
			'post_status'   => 'publish',
			'posts_per_page' => 1,
		);
				
		$qafp_faqs = new WP_Query( $args );
		
		while( $qafp_faqs->have_posts() ): $qafp_faqs->the_post();
			
			global $post;
			$qafp_shortcode_output .= '<div id="qafp-faq' . $i . '" class="qafp-faq"' . $faqmargin . '>
			<div class="qafp-faq-title" style="' . $qafp_options['titlecss'] . '"><a class="qafp-faq-anchor" href="' . get_permalink() . '">';
			if ( $showplusminus === true ) { $qafp_shortcode_output .= '<span class="qafp-fa-caret-right"></span>'; }
			$qafp_shortcode_output .= get_the_title() . '</a></div>
			<div class="qafp-faq-answer"' . $answermargin . '>' . apply_filters( 'the_content', get_the_content() );

			// Start permalinks and ratings code
			if ( $permalinks === true || $ratings === true ) $qafp_shortcode_output .= '
			<p class="qafp-faq-meta qafp-post-like">
			';
			if ( $ratings === true ) {
				$qafp_shortcode_output .= getPostLikeLink($post->ID);
				$plmargin = ' style="margin-left:1em;"';
			}
			if ( $permalinks === true ) $qafp_shortcode_output .= '
			<a class="qafp-permalink" href="' . get_permalink() . '"' . $plmargin . '>' . __( 'Permalink', 'qa-focus-plus') . '</a>';
			if ( $permalinks === true || $ratings === true ) $qafp_shortcode_output .= '
			</p>
			'; // End permalinks and ratings code

			if ( $hr === true ) $qafp_shortcode_output .= '<hr' . $hrmargin . ' />
			';

			$qafp_shortcode_output .= '</div><!-- .qafp-faq-answer --></div><!-- .qafp-faq -->
			'; // .qafp-faq-answer .qafp-faq
			

		$i++;	

		endwhile; // end loop

		$qafp_shortcode_output .= '</div><!-- .qafp-faqs -->
		'; // .qafp-faqs

		wp_reset_postdata();

	} elseif ( $catname || $cat ) { // Show a single category //

		$home_link = null;
		if ( $homelink != 'none' ) {
			$home_link = '<p>
			';
			$home_link .= qafphomelink_shortcode();
			$home_link .= '</p>
			';
		}
		
		if ( $cat ) {
			$catname = $cat;
		}
		
		//if ( $qafp_options['last_update'] ) $qafp_shortcode_output .= qafp_last_faq_mod_date();

		if ( $searchpos == "top" && ( $search == "category" || $search == "both" ) )
			$qafp_shortcode_output .= qafp_search();

		$category = get_term_by( 'slug', $catname, 'faq_category' );

		$qafp_shortcode_output .= '<div class="qafp-faqs qafp-category cf';
			
			switch( $animation ) {
				
				case 'none' :
				$qafp_shortcode_output .= ' animation-none';
				break;
				
				case 'slide' :
				$qafp_shortcode_output .= ' animation-slide';
				break;
				
				default :
				$qafp_shortcode_output .= ' animation-fade';
				break;
			}	

		if ( $accordion === true )	$qafp_shortcode_output .= ' accordion';

		if ( $collapsible === true ) $qafp_shortcode_output .= ' collapsible';
		
		if ( $focus === true ) $qafp_shortcode_output .= ' focus';
		 						
		$qafp_shortcode_output .= '">
		';

		if ( $homelink == 'above' || $homelink == 'both' ) {
			$qafp_shortcode_output .=  $home_link;
		}
		$qafp_shortcode_output .= '<div class="qafp-category">
		<' . $qafp_options['categoryhead'] . ' class="faq-catname">' . $category->name . '</' . $qafp_options['categoryhead'] . '>
		';
		if ( $catdesc === true ) {
			$cat_desc = term_description( $category->term_id, 'faq_category' );
			if ( $cat_desc != '' ) $qafp_shortcode_output .= '<div class="qafp-catdesc">' . $cat_desc . '</div>';
		}

		$args = array(
			'order'         => 'ASC',
			'orderby' 		=> 'menu_order',
			'post_type'     => 'qa_faqs',
			'post_status'   => 'publish',
			'posts_per_page' => -1,
			'faq_category'	=> $category->slug
		);
				
		$qafp_faqs = new WP_Query( $args );
		
		while( $qafp_faqs->have_posts() ): $qafp_faqs->the_post();
			
			global $post;
			$comment_link = '';
			if ( comments_open() ) {
				$comments_number = get_comments_number();
				$comment_link = sprintf( _n( '1 Comment', '%s Comments', $comments_number, 'qa-focus-plus' ), $comments_number ) . ' - ' . get_post_reply_link( '', $post->ID );
			}
			
			$qafp_shortcode_output .= '<div id="qafp-faq' . $i . '" class="qafp-faq"' . $faqmargin . '>
			<div class="qafp-faq-title" style="' . $qafp_options['titlecss'] . '"><a class="qafp-faq-anchor" href="' . get_permalink() . '">';
			if ( $showplusminus === true ) { $qafp_shortcode_output .= '<span class="qafp-fa-caret-right"></span>'; }
			$qafp_shortcode_output .= get_the_title() . '</a></div>
			<div class="qafp-faq-answer"' . $answermargin . '>' . apply_filters( 'the_content', get_the_content() );

			// Start permalinks and ratings code
			if ( $permalinks === true || $ratings === true ) $qafp_shortcode_output .= '
			<p class="qafp-faq-meta qafp-post-like">
			';
			if ( $ratings === true ) {
				$qafp_shortcode_output .= getPostLikeLink($post->ID);
				$plmargin = ' style="margin-left:1em;"';
			}
			if ( $permalinks === true ) $qafp_shortcode_output .= '
			<a class="qafp-permalink" href="' . get_permalink() . '"' . $plmargin . '>' . __( 'Permalink', 'qa-focus-plus') . '</a>';
			if ( $permalinks === true || $ratings === true ) $qafp_shortcode_output .= '
			</p>
			'; // End permalinks and ratings code

			if ( comments_open() ) {
				$qafp_shortcode_output .= '<div class="qafp-comments">' . $comment_link . '</div>
				';
			}
			
			if ( $hr === true ) $qafp_shortcode_output .= '<hr' . $hrmargin . ' />
			';

			$qafp_shortcode_output .= '</div><!-- .qafp-faq-answer -->
			</div><!-- .qafp-faq -->
			'; // .qafp-faq-answer .qafp-faq

		$i++;	

		endwhile; // end loop

		$qafp_shortcode_output .= '</div><!-- .qafp-category -->
		'; // .qafp-category
		if ( $homelink == 'below' || $homelink == 'both' ) {
			$qafp_shortcode_output .= $home_link;
		}
		$qafp_shortcode_output .= '</div><!-- .qafp-faqs -->'; // .qafp-faqs

		wp_reset_postdata();
		if ( $searchpos == "bottom" && ( $search == "category" || $search == "both" ) )
			$qafp_shortcode_output .= qafp_search();

	} else { // Show the Q&A Homepage //
		
		$args = array(
			'orderby'	=> $catorder, //'term_order',
			'order'	=> 'ASC',
			'taxonomy'	=> 'faq_category'
		);	

		$categories = get_categories( $args );	
		
		if ( $categories ) {
			
			$qafp_shortcode_output .= '<div class="qafp-faqs qafp-home cf';
			
			switch( $animation ) {
				
				case 'none' :
				$qafp_shortcode_output .= ' animation-none';
				break;
				
				case 'slide' :
				$qafp_shortcode_output .= ' animation-slide';
				break;
				
				default :
				$qafp_shortcode_output .= ' animation-fade';
				break;
			}	

			if ( $accordion === true )	$qafp_shortcode_output .= ' accordion';

			if ( $collapsible === true ) $qafp_shortcode_output .= ' collapsible';
			
			if ( $focus === true ) $qafp_shortcode_output .= ' focus';
		 						
			$qafp_shortcode_output .= '">
			';

			if ( $qafp_options['last_update'] ) {
				
				if ( $searchpos == "top" && ( $search == "home" || $search == "both" ) ) {
					$qafp_shortcode_output .= '<div class="qafp-modified qafp-modified-search">' . __( 'FAQ last updated', 'qa-focus-plus' ) . ': ' . qafp_last_faq_mod_date() . '</div>
					';
					$qafp_shortcode_output .= qafp_search();
				} else {
					$qafp_shortcode_output .= '<div class="qafp-modified qafp-modified-nosearch">' . __( 'FAQ last updated', 'qa-focus-plus' ) . ': ' . qafp_last_faq_mod_date() . '</div>
					';
				}
			
			} else {

				if ( $searchpos == "top" && ( $search == "home" || $search == "both" ) )
					$qafp_shortcode_output .= qafp_search();

			}
						
			foreach ( $categories as $category ) {

				$catheader = $category->name;	
				if ( $postnumber === true ) {
					$catheader .= ' (' . $category->count . ')';
				} 

				$qafp_shortcode_output .=  '<div class="qafp-category" id="' . $category->slug . '">
				<' . $qafp_options['categoryhead'] . ' class="faq-catname">' . $catheader . '</' . $qafp_options['categoryhead'] . '>
				';
				
				if ( $catdesc === true ) {
					$cat_desc = term_description( $category->term_id, 'faq_category' );
					if ( $cat_desc != '' ) $qafp_shortcode_output .= '<div class="qafp-catdesc">' . $cat_desc . '</div>';
				}
				
				if ( $catlink === true ) {
					$url = home_url() . '/' . $qafp_options['faq_slug'] . '/category/' . $category->category_nicename;
					$qafp_shortcode_output .= '<p>
					<a class="qafp-show-more" href="' . $url . '">' . __( 'View category', 'qa-focus-plus' ) . ' &rarr;</a>
					</p>
					';	
				}
				
				if ( $sort == 'menu_order' ) {
					$args = array(
						'order'         => 'ASC',
						'orderby' 		=> 'menu_order',
						'post_type'     => 'qa_faqs',
						'post_status'   => 'publish',
						'posts_per_page' => $limit,
						'faq_category'	=> $category->slug
					);
				} else {
					$args = array(
						'meta_key'	=> 'votes_count',
						'order'         => 'DESC',
						'orderby' 		=> 'meta_value_num',
						'post_type'     => 'qa_faqs',
						'post_status'   => 'publish',
						'posts_per_page' => $limit,
						'faq_category'	=> $category->slug
					);
				}
						
				$qafp_faqs = new WP_Query( $args );
				
				while( $qafp_faqs->have_posts() ): $qafp_faqs->the_post();
					
					global $post;
					$comment_link = '';
					if ( comments_open() ) {
						$comments_number = get_comments_number();
						if ( $excerpts === true ) {
							$comment_link = sprintf( _n( '1 Comment', '%s Comments', $comments_number, 'qa-focus-plus' ), $comments_number );
						} else {
							$comment_link = sprintf( _n( '1 Comment', '%s Comments', $comments_number, 'qa-focus-plus' ), $comments_number ) . ' - ' . get_post_reply_link( '', $post->ID );
						}
					}
					
					$qafp_shortcode_output .= '<div id="qafp-faq' . $i . '" class="qafp-faq cf"' . $faqmargin . '>
					<div class="qafp-faq-title" style="' . $qafp_options['titlecss'] . '"><a class="qafp-faq-anchor" href="' . get_permalink() . '">';
					if ( $showplusminus === true ) { $qafp_shortcode_output .= '<span class="qafp-fa-caret-right"></span>'; }
					$qafp_shortcode_output .= get_the_title() . '</a></div>
					';
					
					if ( $excerpts === true ) {
						$qafp_shortcode_output .= '<div class="qafp-faq-answer"' . $answermargin . '>' . apply_filters( 'the_content', get_the_excerpt() );
					} else { 
						$qafp_shortcode_output .= '<div class="qafp-faq-answer"' . $answermargin . '>' . apply_filters( 'the_content', get_the_content() );
					}

					// Start permalinks and ratings code
					if ( $permalinks === true || $ratings === true ) $qafp_shortcode_output .= '
					<p class="qafp-faq-meta qafp-post-like">
					';
					if ( $ratings === true ) {
						$qafp_shortcode_output .= getPostLikeLink($post->ID);
						$plmargin = ' style="margin-left:1em;"';
					}
					if ( $permalinks === true ) $qafp_shortcode_output .= '
					<a class="qafp-permalink" href="' . get_permalink() . '"' . $plmargin . '>' . __( 'Permalink', 'qa-focus-plus') . '</a>';
					if ( $permalinks === true || $ratings === true ) $qafp_shortcode_output .= '
					</p>
					'; // End permalinks and ratings code

					if ( comments_open() ) {
						$qafp_shortcode_output .= '<div class="qafp-comments">' . $comment_link . '</div>
						';
					}
					
					if ( $hr === true ) $qafp_shortcode_output .= '<hr' . $hrmargin . ' />
					';

					$qafp_shortcode_output .= '</div><!-- .qafp-faq-answer --></div><!-- .qafp-faq -->
					'; // .qafp-faq-answer .qafp-faq

					$i++;
		
				endwhile; // end loop
				
				/*if ( $catlink === true ) {
					$url =  home_url() . '/' . $qafp_options['faq_slug'] . '/category/' . $category->category_nicename;
					$qafp_shortcode_output .= '<a class="qafp-show-more" href="' . $url . '">' . sprintf ( __( 'View %s category', 'qa-focus-plus' ), $category->name ) . ' &rarr;</a>
					';	
				}*/
				
				$qafp_shortcode_output .= '</div><!-- .qafp-category -->
				'; // .qafp-category

			}
						
			$qafp_shortcode_output .= '</div><!-- .qafp-faqs -->
			'; // .qafp-faqs

			if ( $searchpos == "bottom" && ( $search == "home" || $search == "both" ) )
				$qafp_shortcode_output .= qafp_search();

		} else { // no categories, just home
		
			$qafp_shortcode_output .= '<div class="qafp-faqs qafp-home cf';
			
			switch( $animation ) {
				
				case 'none' :
				$qafp_shortcode_output .= ' animation-none';
				break;
				
				case 'slide' :
				$qafp_shortcode_output .= ' animation-slide';
				break;
				
				default :
				$qafp_shortcode_output .= ' animation-fade';
				break;
			}	

			if ( $accordion === true )	$qafp_shortcode_output .= ' accordion';

			if ( $collapsible === true ) $qafp_shortcode_output .= ' collapsible';
			
			if ( $focus === true ) $qafp_shortcode_output .= ' focus';
		 					
			$qafp_shortcode_output .= '">
			';
			
			if ( $qafp_options['last_update'] ) {
				
				if ( $searchpos == "top" && ( $search == "home" || $search == "both" ) ) {
					$qafp_shortcode_output .= '<div class="qafp-modified qafp-modified-search">' . __( 'FAQ last updated', 'qa-focus-plus' ) . ': ' . qafp_last_faq_mod_date() . '</div>
					';
					$qafp_shortcode_output .= qafp_search();
				} else {
					$qafp_shortcode_output .= '<div class="qafp-modified qafp-modified-nosearch">' . __( 'FAQ last updated', 'qa-focus-plus' ) . ': ' . qafp_last_faq_mod_date() . '</div>
					';
				}
			
			} else {

				if ( $searchpos == "top" && ( $search == "home" || $search == "both" ) )
					$qafp_shortcode_output .= qafp_search();

			}

			$args = array(
				'order'         => 'ASC',
				'orderby' 		=> 'menu_order',
				'post_type'     => 'qa_faqs',
				'post_status'   => 'publish',
				'posts_per_page' => -1,
			);

			$qafp_faqs = new WP_Query( $args );
			
			while( $qafp_faqs->have_posts() ): $qafp_faqs->the_post();
				
				global $post;
				$comment_link = '';
				if ( comments_open() ) {
					$comments_number = get_comments_number();
					if ( $excerpts === true ) {
						$comment_link = sprintf( _n( '1 Comment', '%s Comments', $comments_number, 'qa-focus-plus' ), $comments_number );
					} else {
						$comment_link = sprintf( _n( '1 Comment', '%s Comments', $comments_number, 'qa-focus-plus' ), $comments_number ) . ' - ' . get_post_reply_link( '', $post->ID );
					}
				}
			
				$qafp_shortcode_output .= '<div id="qafp-faq' . $i . '" class="qafp-faq cf"' . $faqmargin . '>
				<div class="qafp-faq-title" style="' . $qafp_options['titlecss'] . '"><a class="qafp-faq-anchor" href="' . get_permalink() . '">';
					if ( $showplusminus === true ) { $qafp_shortcode_output .= '<span class="qafp-fa-caret-right"></span>'; }
					$qafp_shortcode_output .= get_the_title() . '</a></div>
				';
				
				if ( $excerpts === true ) {
					$qafp_shortcode_output .= '<div class="qafp-faq-answer"' . $answermargin . '>' . apply_filters( 'the_content', get_the_excerpt() );
				} else { 
					$qafp_shortcode_output .= '<div class="qafp-faq-answer"' . $answermargin . '>' . apply_filters( 'the_content', get_the_content() );
				}

				// Start permalinks and ratings code
				if ( $permalinks === true || $ratings === true ) $qafp_shortcode_output .= '
				<p class="qafp-faq-meta qafp-post-like">
				';
				if ( $ratings === true ) {
					$qafp_shortcode_output .= getPostLikeLink($post->ID);
					$plmargin = ' style="margin-left:1em;"';
				}
				if ( $permalinks === true ) $qafp_shortcode_output .= '
				<a class="qafp-permalink" href="' . get_permalink() . '"' . $plmargin . '>' . __( 'Permalink', 'qa-focus-plus') . '</a>';
				if ( $permalinks === true || $ratings === true ) $qafp_shortcode_output .= '
				</p>
				'; // End permalinks and ratings code

				if ( comments_open() ) {
					$qafp_shortcode_output .= '<div class="qafp-comments">' . $comment_link . '</div>
					';
				}
				
				if ( $hr === true ) $qafp_shortcode_output .= '<hr' . $hrmargin . ' />
				';

				$qafp_shortcode_output .= '</div><!-- .qafp-faq-answer --></div><!-- .qafp-faq -->
				'; // .qafp-faq-answer .qafp-faq

				$i++;
	
			endwhile; // end loop
				
			$qafp_shortcode_output .= '</div><!-- .qafp-faqs -->
			'; // .qafp-faqs

			if ( $searchpos == "bottom" && ( $search == "home" || $search == "both" ) )
				$qafp_shortcode_output .= qafp_search();

		}
			
	}	

	wp_reset_postdata();

	return $qafp_shortcode_output;	
}

// functions and hooks go here 

function qafp_search() { 
	global $qafp_options;

    $searchform = '<form role="search" method="get" id="qafp_searchform" action="' . home_url() . '">
		<input type="text" value="" placeholder="' . __('Search FAQs', 'qa-focus-plus') . '" name="s" id="qasearch" class="qafp_search" />
		<input type="hidden" name="search_link" id="qafp_search_link" value="' . home_url() . '/' . $qafp_options['faq_slug'] . '/search/"/>
		<input type="submit" id="qafp_searchsubmit" value="' . __('Search', 'qa-focus-plus') . '" />
		</form>';

	return $searchform;
}