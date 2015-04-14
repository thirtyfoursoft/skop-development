<?php
class QAFP_Recent_Faqs extends WP_Widget {
	// Register widget with WordPress.
	function __construct() {
		parent::__construct(
			'qafp_recent_faqs', // Base ID
			__('Recent FAQs', 'qa-focus-plus'), // Name
			array( 'description' => __( 'Display the recents FAQs from Q and A Focus Plus.', 'qa-focus-plus' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		if ( ! $instance[ 'hideonarchivepages' ] || ( $instance[ 'hideonarchivepages' ] && ! is_category() ) ) // KEEP UNTIL ARCHIVE BEHAVIOR IS CORRECTED
			$this->show_recent_faq_widget($args, $instance);
	
	}

	public function show_recent_faq_widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', $instance['title'] );
	
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		if ( ! $instance['numberposts'] ) $instance['numberposts'] = 5;
		
		// get latest faqs
		$pq = NULL;
		$pq = new WP_Query(array( 'post_type' => 'qa_faqs', 'orderby' => 'post_date', 'showposts' => $instance['numberposts'] ));
		if( $pq->have_posts() ) { ?>
			<ul>
			<?php while($pq->have_posts()) { $pq->the_post(); ?>
				<li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
			<?php } ?>
			</ul>
			<?php wp_reset_query();
		} ?>
		
		<?php echo $args['after_widget'];
			
	}

	/**
	 * Back-end widget form.
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ]; else $title = '';
		if ( isset( $instance[ 'numberposts' ] ) ) $numberposts = $instance[ 'numberposts' ]; else $numberposts = 5;
		$hideonarchivepages = ( isset( $instance[ 'hideonarchivepages' ] ) ? true : false );
		//if ( isset( $instance[ 'showfaqlink' ] ) ) $showfaqlink = $instance['showfaqlink']; else $showfaqlink = true;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'qa-focus-plus' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        <p>
        <label for="<?php echo $this->get_field_id( 'numberposts' ); ?>"><?php _e( 'Number of FAQs to display:', 'qa-focus-plus' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'numberposts' ); ?>" name="<?php echo $this->get_field_name( 'numberposts' ); ?>" type="text" value="<?php echo esc_attr( $numberposts ); ?>" size="3" />
        </p>
        <p>
		<input id="<?php echo $this->get_field_id( 'hideonarchivepages' ); ?>" name="<?php echo $this->get_field_name( 'hideonarchivepages' ); ?>" type="checkbox" value="true" <?php checked( $hideonarchivepages ) ?> />
        <label for="<?php echo $this->get_field_id( 'hideonarchivepages' ); ?>"><?php _e( 'Hide on category archive pages.', 'qa-focus-plus' ); ?></label>
        </p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : 'Recent FAQs';
		$instance['numberposts'] = ( ! empty( $new_instance['numberposts'] ) ) ? strip_tags( $new_instance['numberposts'] ) : 5;
		$instance[ 'hideonarchivepages' ] = $new_instance['hideonarchivepages'];
		return $instance;
	}

} // class QAFP_Recent_Faqs

// register QAFP_Recent_Faqs widget
function register_QAFP_Recent_Faqs() {
	register_widget( 'QAFP_Recent_Faqs' );
}
add_action( 'widgets_init', 'register_QAFP_Recent_Faqs' );

?>