<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class skope_eoi_response extends WP_List_Table {

		private $order;
        private $orderby;
        private $posts_per_page;

 		public function __construct() {
            parent :: __construct(array(
                'singular' => "EOI response",
                'plural' => "EOI response",
                'ajax' => false
            ));

            $this->set_order();
            $this->set_orderby();
            $this->prepare_items();
        }

		private function get_sql_results() {
            global $wpdb;

            $this->posts_per_page = $this->get_items_per_page( 'users_network_per_page' );

			$sql_results = array();
			
            $vendorSearch = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';
           
			$sql = "SELECT * FROM " . $wpdb->prefix . "eoiresponse WHERE is_used = 1";

			if ($vendorSearch ) {
				$sql .= " AND (org_name LIKE '%" . $vendorSearch . "%' OR your_name LIKE '%" . $vendorSearch . "%') ";
			}
			
			$sql .= " ORDER BY $this->orderby $this->order";
			$sql_results = $wpdb->get_results($sql);

			foreach ( $sql_results as $sql_result ) { 
				$editLink = admin_url('admin.php?action=editEOI&page=specdoc-eoi-response&eoi_id='.$sql_result->eoi_id);
				$editTitle = 'Edit';

				$deleteLink = admin_url('admin.php?action=deleteEOI&page=specdoc-eoi-response&eoi_id='.$sql_result->eoi_id);
				$deleteTitle = 'Delete';

				$eoiViewLink = admin_url('admin.php?action=eoiView&page=specdoc-eoi-response&eoi_id='.$sql_result->eoi_id);
				$eoiViewTitle = 'View the EOI Response';
				
				$sql_result->eoiView = "<a href='{$eoiViewLink}'>{$eoiViewTitle}</a>";
				$sql_result->edit = "<a href='{$editLink}'>{$editTitle}</a>";
				$sql_result->delete = "<a href='{$deleteLink}' onclick='return ConfirmDelete();'>{$deleteTitle}</a>";
			}

            return $sql_results;
        }

		public function set_order() {
            $order = 'DESC';
            if (isset($_GET['order']) AND $_GET['order'])
                    $order = $_GET['order'];
            $this->order = esc_sql($order);
        }

 	 	public function set_orderby() {
            $orderby = 'org_name';
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
            _e('There are no EOI response yet.');
        }

        /**
         * @see WP_List_Table::get_views()
         */
        public function get_views() {
            return array();
        }

        public function get_columns() {
            $columns = array(
                'eoi_id' => __('EOI ID'),
                'org_name' => __('Organization Name'),
                'your_name' => __('Vendor Name'),
                'your_system_name' => __('System Name'),
                'eoiView' => __('EOI View'),
                'edit' => __('Edit'),
                'delete' => __('Delete')
            );
            return $columns;
        }

        public function get_sortable_columns() {

            $sortable = array();
            $sortable = array(
                'org_name' => array('org_name', true),
                'your_name' => array('your_name', true)
            );            
            return $sortable;
        }
        
		public function prepare_items() {
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

	echo '<div class="wrap">';
		$ftList = new skope_eoi_response();
		echo  '<h2>EOI Response</h2>';
		?>
		<form method="get">
			<input type="hidden" name="page" value="specdoc-eoi-response" />
			<?php $ftList->search_box('search vendor', 'eoi_id'); ?>
		</form>		
		<?php
		$ftList->display();

	echo '</div>';	

?>
<script type="text/javascript">
function ConfirmDelete() {

	var x = confirm("Do you really wants to delete this EOI Response");

	if (x) {
		return true;
	} else {
		return false;
	}

}
</script>
