<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class skope_users_details extends WP_List_Table {

		private $order;
        private $orderby;
        private $posts_per_page;

 		public function __construct() {
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
            
            $usersearch = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

			$sql = "SELECT ID, user_nicename, display_name, DATE_FORMAT(user_registered,'%M %d, %Y') AS user_registered FROM " . $wpdb->prefix . "users WHERE ID != '0'";

			if ($usersearch ) {

				$sql .= " AND display_name LIKE '%" . $usersearch . "%'";
			}

			$sql .= " ORDER BY $this->orderby $this->order";
			$sql_results = $wpdb->get_results($sql);

			foreach ( $sql_results as $k => $sql_result ) {

				$user = new WP_User( $sql_result->ID );
				$role = $user->roles[0];

				if ($role == 'vendor') {
					unset($sql_results[$k]);
					continue;
				}

				$activeStatus = get_user_meta( $sql_result->ID, 'active_status', true);
				if ($activeStatus == 0 && $role != 'administrator' ) {
					unset($sql_results[$k]);
					continue;
				}

				$total = $wpdb->get_results("SELECT CONCAT( '$', ROUND(SUM(total), 2)) AS total_fee FROM " . $wpdb->prefix . "specdoc_order WHERE customer_id= '" . $sql_result->ID . "' AND order_status = 'Completed' GROUP BY customer_id");

				if( empty($total) ) {
					$total_fees = '$0.00';
				} else {
					$total_fees = 	$total[0]->total_fee;
				}

				$sql_result->total_fee = $total_fees;

				$sql_result->lastLogin = $this->get_user_last_login( $sql_result->ID );

				$paymentLink = admin_url('admin.php?action=payments&page=users-and-payments&user_id='.$sql_result->ID);
				$paymentTitle = 'View payment details';

				$documentsLink = admin_url('admin.php?action=documents&page=users-and-payments&user_id='.$sql_result->ID);
				$documentsTitle = 'View document list';

				$deleteLink = admin_url('admin.php?action=delete&page=users-and-payments&user_id='.$sql_result->ID);
				
				$sql_result->payments = "<a href='{$paymentLink}'>{$paymentTitle}</a>";
				$sql_result->documents = "<a href='{$documentsLink}'>{$documentsTitle}</a>";

				$user_info = get_userdata($sql_result->ID);

				if ($user_info->roles[0] != 'administrator') {
					$sql_result->delete = "<a href='{$deleteLink}' onclick='return ConfirmDelete();'>Delete User</a>";
				} else {
					$sql_result->delete = "";
				}


				$downloadTitle = 'Download CSV';
				$downloadLink = admin_url('admin.php?action=downloadCSV&page=users-and-payments&user_id='.$sql_result->ID);
				$sql_result->download = "<a href='{$downloadLink}'>{$downloadTitle}</a>";

				$user_info = get_user_by('id', $sql_result->ID);
				$userName = $user_info->user_login;

				$dataLogin = array(
					'id' => $sql_result->ID,
					'username' => $userName
				);
				
				$key = base64_encode(serialize($dataLogin));
			
				$browseTitle = 'Browse';
				$browseLink = get_site_url().'?doLogin='.$key;
				$sql_result->browse = "<a target='_blank' href='{$browseLink}'>{$browseTitle}</a>";				
				
			}

            return $sql_results;
        }

		public function get_user_last_login( $user_id ) {
		
			$value      = __( 'Never', 'wp-last-login' );
			$last_login = (int) get_user_meta( $user_id, 'skope_last_login', true );

			if ( $last_login ) {
				$format = apply_filters( 'wpll_date_format', get_option( 'date_format' ) );
				$value  = date_i18n( $format, $last_login );
			}

			return $value;
		}
		
		public function set_order() {
            $order = 'asc';
            if (isset($_GET['order']) AND $_GET['order'])
                    $order = $_GET['order'];
            $this->order = esc_sql($order);
        }

 	 	public function set_orderby() {
            $orderby = 'user_nicename';
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
            _e('No user made any payment yet on the website.');
        }

        /**
         * @see WP_List_Table::get_views()
         */
        public function get_views() {
            return array();
        }

        public function get_columns() {
            $columns = array(
                'display_name' => __('Username'),
                'user_registered' => __('Date joined'),
                'lastLogin' => __('Last login'),
                'total_fee' => __('Total fee'),
                'payments' => __('Payments'),
                'documents' => __('Documents'),
                'delete' => __('Delete user'),
                'download' => __('Download CSV'),
                'browse' => __('Browse via front end')
            );
            return $columns;
        }

        public function get_sortable_columns() {
            $sortable = array(
                'display_name' => array('display_name', true),
                'user_registered' => array('user_registered', true)
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
			# <<<< Pagination
			// Prepare the data
		/*            $permalink = __('Edit:');
			foreach ($posts_array as $key => $post) {
				$link = get_edit_post_link($post->ID);
				$no_title = __('No title set');
				$title = !$post->post_title ? "<em>{$no_title}</em>" : $post->post_title;
				$posts[$key]->post_title = "<a title='{$permalink} {$title}' href='{$link}'>{$title}</a>";
			}
		*/
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

    echo '<div class="wrap">
		<div id="icon-users" class="icon32"></div>
		<h2>'. __('Users and payments') .'</h2>';
		if ( 'del' == esc_attr( $_GET['update'] ) ) echo '<div id="message" class="updated" ><p>User deleted.</p></div>';
		$ftList = new skope_users_details();
		?>
		<form method="get">
			<input type="hidden" name="page" value="users-and-payments" />
			<?php $ftList->search_box('search user', 'search_id'); ?>
		</form>
		<?php
			$ftList->display();
  		
   		 echo '</div>';
?>
<script type="text/javascript">
function ConfirmDelete() {

	var x = confirm("This will delete all user and skopes related data! Are you sure you wish to delete all this users data?");

	if (x) {
		return true;
	} else {
		return false;
	}

}
</script>
