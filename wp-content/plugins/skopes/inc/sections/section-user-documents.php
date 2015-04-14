<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class skope_users_document extends WP_List_Table {

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

			$sql_results = array();
			$stage = get_user_meta( $userID, 'skope_user_stage', true );

			$allFiles = array(

				'Project_Rationale.docx'	=> 'Project_pitch_template',
				'Expression_of_Interest.docx'	=> 'EOI_template',
				'EOI_Response.docx'		=> 'EOI_Response_template',
				'Charter_Scope.docx'		=> 'Project_details_template',
				'Request_for_Quotation.docx'	=> 'EOI_template',
				'RFQ_Response.docx'		=> 'RFQ_Response_template'

			);
			
			$choosenTheme = get_option("selected_theme");
			if( ! class_exists( 'clsSkopes' ) ) {
				require_once(RC_TC_BASE_DIR."/frontend/theme/".$choosenTheme."/core/class_skopes.php");
			}

			if( ! class_exists( 'userinfo' ) ) {
				require_once(RC_TC_BASE_DIR."/frontend/theme/".$choosenTheme."/core/class_userinfo.php");
			}

			$skope = new clsSkopes();
			$userinfo = new userinfo();
			
			$is_risk_management = get_option("risk_management");
			if ($is_risk_management == 1 ) { 
				$compareMoreInfoNo = 5;
			} else {
				$compareMoreInfoNo = 4;
			}

			$count = 1;
			foreach ( $allFiles as $k => $v ) {
			
            	$sql_results = $wpdb->get_row("SELECT docName AS reportName, DATE_FORMAT(lastDownload,'%M %d, %Y') AS lastDownloadDate, DATE_FORMAT(firstDownload,'%M %d, %Y') AS firstDownloadDate FROM " . $wpdb->prefix . "specdoc_downloadedDoc_records WHERE user_id = '" . $userID . "' AND docName = '" . $k . "' ");

				if (empty($sql_results)) {
					$sql_results = new stdClass();
					$sql_results->reportName = $k;
					$sql_results->lastDownloadDate = 'Not yet';
					$sql_results->firstDownloadDate = 'Not yet';
				}

				$startedData = get_user_meta( $userID, '_answerStartedData', 'true' );

				if ( $startedData ) {
					$format = apply_filters( 'wpll_date_format', get_option( 'date_format' ) );
					$value = date( $format, strtotime($startedData));
				} else {
					$value = 'Never';
				}
				
				$sql_results->startedDate = $value;

				$completedDate = get_user_meta( $userID, '_dateOfCompleteAllAns', 'true' );

				if ( $completedDate ) {
					$format = apply_filters( 'wpll_date_format', get_option( 'date_format' ) );
					$valueTwo = date( $format, strtotime($completedDate));
				} else {
					$valueTwo = 'Never';
				}

				$sql_results->completedDate = $valueTwo;

				$lastEditDate = get_user_meta( $userID, '_lastEditDate', 'true' );

				if ( $lastEditDate ) {
					$format = apply_filters( 'wpll_date_format', get_option( 'date_format' ) );
					$valueThree = date( $format, strtotime($lastEditDate));
				} else {
					$valueThree = 'Never';
				}

				$sql_results->lastEditDate = $valueThree;
				$sql_results->linktoReport = '<a href="#">Incomplete</a>';
				$sql_results->downloadPDF = '<a href="javascript: void(0);">Incomplete</a>';

				if ( $count <= 3 ) {

					$FirstThreeStatus = $userinfo->getAboutprojectStatus( $userID );
					$oganizationStatus = $userinfo->getStatusOfOrganizationSection( $userID );

					if ( ($FirstThreeStatus == 0) && ( $stage == 'two' ) && ($oganizationStatus == 0) ) {

						$downloadedURL = admin_url('admin.php?action=downloadDoc&page=users-and-payments&user_id='.$userID.'&fileName='.$k.'&tempName='.$v.'&type=doc');
						$sql_results->linktoReport = "<a href='{$downloadedURL}'>Complete</a>";

						$downloadPDF = admin_url('admin.php?action=downloadDoc&page=users-and-payments&user_id='.$userID.'&fileName='.$k.'&tempName='.$v.'&type=pdf');
						$sql_results->downloadPDF = "<a href='{$downloadPDF}'>Download PDF</a>";

					}

				} else {

					$lastThreeStatus = $userinfo->getMoreInfoStatus( $userID );
					$oganizationStatus = $userinfo->getStatusOfOrganizationSection( $userID );
					
					$featuresItems = $skope->checkFunaStatusCustom( $userID );

					$totalFA          = $featuresItems[1]['totalFA'];
					$totalInscope     = $featuresItems[2]['totalInscope'];
					$totalNotrequired = $featuresItems[3]['totalNotrequired'];
					$totalunknown     = $featuresItems[0]['unknown'];
					$totalComplete    = $featuresItems[4]['totalComplete'];

					$total = $totalComplete + $totalNotrequired;

					if( ! class_exists( 'userinfo' ) ) {
						require_once( ABSPATH . 'wp-content/plugins/skopes/frontend/core/class_userinfo.php' );
					}

					$userSkope = new userinfo();
					$userProjectMore  = $userSkope->projectmoreCount( $userID );
					

					if ( ($lastThreeStatus == 0) && ( $stage == 'two' ) && ($total >= $totalFA) && ($userProjectMore >= $compareMoreInfoNo) && ($oganizationStatus ==0 ) ) {

						$downloadedURL = admin_url('admin.php?action=downloadDoc&page=users-and-payments&user_id='.$userID.'&fileName='.$k.'&tempName='.$v.'&type=doc');
						$sql_results->linktoReport = "<a href='{$downloadedURL}'>Complete</a>";

						$downloadPDF = admin_url('admin.php?action=downloadDoc&page=users-and-payments&user_id='.$userID.'&fileName='.$k.'&tempName='.$v.'&type=pdf');
						$sql_results->downloadPDF = "<a href='{$downloadPDF}'>Download PDF</a>";					

					}

				}

				$count++;
				$final_results[] = $sql_results;
			}

            return $final_results;
        }

        public function getTitle( $id )  {

        	$user_info = get_userdata( $id );
        	$this->userName = $user_info->user_login . ' - documents history ';
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
                'reportName' => __('Report name'),
                'startedDate' => __('Date first edited'),
                'completedDate' => __('Date first completed'),
                'lastEditDate' => __('Last edit date'),
                'firstDownloadDate' => __('First Download date'),
                'lastDownloadDate' => __('Last Download date'),
                'linktoReport' => __('Download doc'),
                'downloadPDF' => __('Download PDF'),
            );
            return $columns;
        }

        public function get_sortable_columns() {

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
			$ftList = new skope_users_document($_GET['user_id']);
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
