<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class skope_users_payments extends WP_List_Table {

		private $order;
        private $orderby;
        private $posts_per_page;
        public $userName;

 		public function __construct( $userID ) {
            parent :: __construct(array(
                'singular' => "Users details",
                'plural' => "Users details",
                'ajax' => false
            ));

			$this->getTitle( $userID );
            $this->set_order();
            $this->set_orderby();
            $this->prepare_items( $userID );
        }

		private function get_sql_results( $userID ) {
            global $wpdb;

            $this->posts_per_page = $this->get_items_per_page( 'users_network_per_page' );

			$sql = "SELECT t2.name AS paymentPoint, DATE_FORMAT(t1.date_added,'%M %d, %Y') AS purchaseDate, CONCAT('$', ROUND(t2.price, 2)) AS amount FROM " . $wpdb->prefix . "specdoc_order t1 INNER JOIN " . $wpdb->prefix . "specdoc_order_product t2 ON ( t1.order_id = t2.order_id ) WHERE t1.order_status = 'Completed' AND t1.customer_id = '" . $userID . "'";

			$sql_results = $wpdb->get_results($sql);

			foreach ( $sql_results as $sql_result ) {
				$startedData = get_user_meta( $userID, '_answerStartedData', 'true' );

				if ( $startedData ) {
					$format = apply_filters( 'wpll_date_format', get_option( 'date_format' ) );
					$value = date( $format, strtotime($startedData));
				} else {
					$value = 'Never';
				}
				
				$sql_result->startedDate = $value;


				$completedDate = get_user_meta( $userID, '_dateOfCompleteAllAns', 'true' );

				if ( $completedDate ) {
					$format = apply_filters( 'wpll_date_format', get_option( 'date_format' ) );
					$valueTwo = date( $format, strtotime($completedDate));
				} else {
					$valueTwo = 'Never';
				}

				$sql_result->completedDate = $valueTwo;
			}

            return $sql_results;
        }

        public function getTitle( $id )  {

        	$user_info = get_userdata( $id );
        	$this->userName = $user_info->user_login . ' - Payments ';
        }

		public function set_order() {
            $order = 'DESC';
            if (isset($_GET['order']) AND $_GET['order'])
                    $order = $_GET['order'];
            $this->order = esc_sql($order);
        }

 	 	public function set_orderby() {
            $orderby = 't2.user_nicename';
            if (isset($_GET['orderby']) AND $_GET['orderby'])
                    $orderby = $_GET['orderby'];
            $this->orderby = esc_sql($orderby);
        }

        public function ajax_user_can() {
            return current_user_can('edit_posts');
        }

        /**
         * @see WP_List_Table::no_items()
         */
        public function no_items() {
            _e('This user doesnt made any payment yet.');
        }

        /**
         * @see WP_List_Table::get_views()
         */
        public function get_views() {
            return array();
        }

        public function get_columns() {
            $columns = array(
                'paymentPoint' => __('Payment point'),
                'purchaseDate' => __('Purchase date'),
                'amount' => __('Amount/Fee '),
                'startedDate' => __('Started date / first edit'),
                'completedDate' => __('Completed date'),

            );
            return $columns;
        }

        public function get_sortable_columns() {
            /*$sortable = array(
                'display_name' => array('t2.display_name', true),
                'user_registered' => array('t2.user_registered', true)
            );
            */
            $sortable = array();
            return $sortable;
        }
        
		public function prepare_items( $userID ) {
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array(
				$columns,
				$hidden,
				$sortable
			);

			// SQL results
			$posts = $this->get_sql_results( $userID );

			empty($posts) AND $posts = array();

			# >>>> Pagination
			$per_page = $this->posts_per_page;
			$current_page = $this->get_pagenum();
			$total_items = count($posts);
			$this->set_pagination_args(array(
				'total_items' => $total_items,
				'per_page' => $per_page,
				'total_pages' => ceil($total_items / $per_page)
			));
			$last_post = $current_page * $per_page;
			$first_post = $last_post - $per_page + 1;
			$last_post > $total_items AND $last_post = $total_items;

			// Setup the range of keys/indizes that contain 
			// the posts on the currently displayed page(d).
			// Flip keys with values as the range outputs the range in the values.
			$range = array_flip(range($first_post - 1, $last_post - 1, 1));

			// Filter out the posts we're not displaying on the current page.
			$posts_array = array_intersect_key($posts, $range);

			$this->items = $posts_array;
		}

        /**
         * A single column
         */
        public function column_default($item, $column_name) {
            return $item->$column_name;
        }

        /**
         * Override of table nav to avoid breaking with bulk actions & according nonce field
         */
        public function display_tablenav($which) {
            ?>
            <div class="tablenav <?php echo esc_attr($which); ?>">
                <?php
                $this->extra_tablenav($which);
                $this->pagination($which);

                ?>
                <br class="clear" />
            </div>
            <?php
        }

        /**
         * Disables the views for 'side' context as there's not enough free space in the UI
         * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
         * 
         * @see WP_List_Table::extra_tablenav()
         */
        public function extra_tablenav($which) {
            global $wp_meta_boxes;
            $views = $this->get_views();
            if (empty($views)) return;

            $this->views();
        }
	}

	if ($_GET['user_id'] != '' ) {

		echo '<div class="wrap">';
			$ftList = new skope_users_payments($_GET['user_id']);
			echo  '<h2>'. __( $ftList->userName ) .'</h2>';
			?>
			<?php
				$ftList->display();

	   		 echo '</div>';

	} else {

		wp_redirect(admin_url('admin.php?page=users-and-payments'));
		exit;
	}
?>
