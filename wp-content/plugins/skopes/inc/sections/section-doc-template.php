<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class skope_doc_template extends WP_List_Table {

		private $order;
        private $orderby;
        private $posts_per_page;
        public $userName;

 		public function __construct( ) {
            parent :: __construct(array(
                'singular' => "Users details",
                'plural' => "Users details",
                'ajax' => false
            ));

            $this->set_order();
            $this->set_orderby();
            $this->prepare_items();
        }

		private function get_sql_results() {
            global $wpdb;

            $this->posts_per_page = $this->get_items_per_page( 'users_network_per_page' );

			$allFiles = array(

				'Project_Rationale.docx'	=> 'Project_pitch_template',
				'Expression_of_Interest.docx'	=> 'EOI_template',
				'EOI_Response.docx'		=> 'EOI_Response_template',
				'Charter_Scope.docx'		=> 'Project_details_template',
				'Request_for_Quotation.docx'	=> 'RFQ_template',
				'RFQ_Response.docx'		=> 'RFQ_Response_template'

			);

			foreach ( $allFiles as $k => $v ) {

				$fileURL =  site_url() . '/wp-content/plugins/skopes/frontend/wordtemplate/files/templates/'.$v.'.docx';
				$finalURL = 'http://docs.google.com/viewer?url='.urlencode($fileURL);
				$url = admin_url( 'admin.php?page=specdoc-settings&tab=doc_template&upload=1', 'http' ); 
				
				$replaceBttn = '<form name="uploadTemplate" method="post" action="'.$url.'" enctype="multipart/form-data"><input type="hidden" name="filename" value="'.$v.'" /><input type="file" name="upload_template" value="" /><br><input type="submit" name="upload_template_bttn" value="Replace Template" /></form>';
				
				$sql_results = new stdClass();
				$sql_results->docTitle = $v;
				$sql_results->fileName = $k;
				$sql_results->downloadTemplate =  "<a href='{$finalURL}' target='_blank'>Download Template</a>";
				$sql_results->replaceTemplate = $replaceBttn;

				$final_results[] = $sql_results;
			}

            return $final_results;
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
            _e('There are no downloaded doc information for this user yet.');
        }

        /**
         * @see WP_List_Table::get_views()
         */
        public function get_views() {
            return array();
        }

        public function get_columns() {
            $columns = array(
                'docTitle' => __('Doc Title'),
                'fileName' => __('File Name'),
                'downloadTemplate' => __('Download Template'),
                'replaceTemplate' => __('Replace Template'),
            );
            return $columns;
        }

        public function get_sortable_columns() {

            $sortable = array();
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
			$posts = $this->get_sql_results();

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
		$ftList = new skope_doc_template();
//		echo  '<h2>Document templates</h2>';
		$ftList->display();

	echo '</div>';


?>
