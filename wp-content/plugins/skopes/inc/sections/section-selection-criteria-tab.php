<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	$choosenTheme = get_option("selected_theme");
	if( ! class_exists( 'clsSkopes' ) ) {
		require_once(RC_TC_BASE_DIR."/frontend/theme/".$choosenTheme."/core/class_skopes.php");
	}
	$skope = new clsSkopes();

	alter_table();
	
/* ******************
* 
*	Code for check iif db has old structure of table then alter it
*
********************/

	function alter_table() {

		global $wpdb;
		$set_count = $wpdb->get_var("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $wpdb->prefix . "specdoc_selection_criteria' AND COLUMN_NAME = 'percentage' ");

		if ($set_count == 0) {

			$sql = $wpdb->query("ALTER TABLE " . $wpdb->prefix . "specdoc_selection_criteria 
				ADD COLUMN percentage varchar(255) NOT NULL,
				ADD COLUMN helpText varchar(512) DEFAULT NULL;");
		}
	}

	
	class skope_selection_criteria extends WP_List_Table {

		private $order;
        private $orderby;
        private $posts_per_page;

 		public function __construct() {
            parent :: __construct(array(
                'singular' => "Selection criteria",
                'plural' => "Selection criteria",
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
			$scSearch = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

			$sql = "SELECT * FROM " . $wpdb->prefix . "specdoc_selection_criteria WHERE scid != ''";

			if ($scSearch ) {
				$sql .= " AND (scid LIKE '%" . $scSearch . "%' OR sc_name LIKE '%" . $scSearch . "%') ";
			}			
			$sql .= " ORDER BY $this->orderby $this->order";
			
			$sql_results = $wpdb->get_results($sql);

			foreach ( $sql_results as $sql_result ) { 
				$editLink = admin_url('admin.php?page=specdoc-mproject-fields&tab=selection-criteria&id='.$sql_result->scid);
				$editTitle = 'Edit';

				$sql_result->edit = "<a href='{$editLink}'>{$editTitle}</a>";
			}			
            return $sql_results;
        }

		public function set_order() {
            $order = 'ASC';
            if (isset($_GET['order']) AND $_GET['order'])
                    $order = $_GET['order'];
            $this->order = esc_sql($order);
        }

 	 	public function set_orderby() {
            $orderby = 'scid';
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
            _e('There are no Search criteria yet.');
        }

        /**
         * @see WP_List_Table::get_views()
         */
        public function get_views() {
            return array();
        }

        public function get_columns() {
            $columns = array(
                'scid' => __('ID'),
                'sc_name' => __('Selection criteria Name'),
                'percentage'	=> __('Score/Percentage'),
                'helpText'		=> __('Help text'),
                'edit' => __('Edit')
            );
            return $columns;
        }

        public function get_sortable_columns() {

            $sortable = array();
            $sortable = array(
                'scid' => array('scid', true),
                'sc_name' => array('sc_name', true)
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


	if (isset($_POST) && $_POST['specdoc-mproject-fields-submit'] == 'Y') {
		global $wpdb;
		check_admin_referer( "specdoc-mproject-fields" );

		$saveData = $_POST['data'];

		if ($saveData['scid'] != '' ) {

			$skope->updateSelectionCriteria($saveData);
			$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
			wp_redirect(admin_url('admin.php?page=specdoc-mproject-fields&'.$url_parameters));
			exit;
			
		} else {

			$skope->insertSelectionCriteria($saveData);
			$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
			wp_redirect(admin_url('admin.php?page=specdoc-mproject-fields&'.$url_parameters));
			exit;
		}

	}

	echo '<div class="wrap">';
		$ftList = new skope_selection_criteria();
		?>

	<?php 
		if (isset($_GET['id'])) $id = $_GET['id'];
		else $id = '';
	?>

		<form method='post' action="" enctype="multipart/form-data">
		<?php
			global $wpdb;

			echo '<h3>Add/Edit Selection Critera</h3>';
			echo '<table class="form-table">';
			wp_nonce_field( "specdoc-mproject-fields" );

			if ($id != '') {

				$data = $skope->getAllSelectionCriteriaData($id);
				$data['scid'] = $id;

			} else {

				$data = array(
					'sc_name' => '',
					'percentage' => '',
					'helpText'		=> '',
					'scid'		=> ''
				);
		 }
		?>
			<tr>
				<th scope="row"><label for="data[sc_name]">Selection Critera Name</label></th>
					<td valign="top"><input type="text" name='data[sc_name]' value="<?php echo $data['sc_name'] ?>" style="width: 25em;"/>
				</td>
				<input type="hidden" name='data[scid]' value="<?php echo $data['scid'] ?>" style="width: 25em;"/>
			</tr>
			<tr>
				<th scope="row"><label for="data[percentage]">Percentage</label></th>
					<td valign="top"><input type="text" name='data[percentage]' value="<?php echo $data['percentage'] ?>" style="width: 25em;"/>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="data[helpText]">Help text</label></th>
					<td valign="top"><input type="text" name='data[helpText]' value="<?php echo $data['helpText'] ?>" style="width: 25em;"/>
				</td>
			</tr>			
		<?php	echo '</table>';   ?>
			<p class="submit" style="clear: both;">
				<?php if ($id != '') { ?>
					<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Update" />
				<?php } else { ?>
					<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Add New" />
				<?php } ?>
				<input type="hidden" name="specdoc-mproject-fields-submit" value="Y" />
			</p>
		</form>

		<form method="get">
			<input type="hidden" name="page" value="specdoc-mproject-fields" />
			<?php $ftList->search_box('Search criteria', 'scid'); ?>
		</form>
		<?php
		$ftList->display();

	echo '</div>';

?>
