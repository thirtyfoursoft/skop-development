<?php

	//////////////////////////////////////////////////////////////////////
	///// THIS FUNCTION REGISTER USER NAD CREATES OUR SPOT USER CLASS
	//////////////////////////////////////////////////////////////////////

	remove_action('wp_ajax__sf_register', '_sf_register_function');
	remove_action('wp_ajax_nopriv__sf_register', '_sf_register_function');

	add_action('wp_ajax__sf_register', '_sf_register_updated_function');
	add_action('wp_ajax_nopriv__sf_register', '_sf_register_updated_function');

	function _sf_register_updated_function() {

		$return = array();
		$return['error'] = false;
		$return['message'] = array();

		//// VERIFIES NONCE
		$nonce = isset($_POST['nonce']) ? trim($_POST['nonce']) : '';
		if(!wp_verify_nonce($nonce, 'sf-register-nonce'))
			die('Busted!');

		//// VERIFIES CREDENTIALS
		$username = isset($_POST['username']) ? trim($_POST['username']) : '';
		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
		$password = isset($_POST['password']) ? trim($_POST['password']) : '';
		$confirmpassword = isset($_POST['confirmpassword']) ? trim($_POST['confirmpassword']) : '';
		$userrole = isset($_POST['userrole']) ? trim($_POST['userrole']) : '';

		//// MAKES SURE USER HAS FILLES USERNME AND EMAIL
		if($userrole == '') { $return['error'] = true; $return['message'] = __('Please choose an account type.', 'btoa'); }
		if($email == '' || !is_email($email)) { $return['error'] = true; $return['message'] = __('Please type in a valid email address.', 'btoa'); }
		if($username == '') { $return['error'] = true; $return['message'] = __('Please choose an username.', 'btoa'); }
		if($password == '' || ($password != $confirmpassword)) { $return['error'] = true; $return['message'] = __('Please type correct password.', 'btoa'); }

		///// IF USER HAS FILLED USER AND PASS
		if($return['error'] == false) {

			$return_registration = _sf_process_user_custom_registration($return, $email, $username,$password,$userrole);
			$return = $return_registration;

		}

		echo json_encode($return);

		exit;

	}

	function _sf_process_user_custom_registration($return, $email, $username,$password,$userrole) {

		//// CHECKS IF USERNAME EXISTS
		$user = new WP_User('', $username);
		if(!$user->exists()) {

			//// CHECK FOR USERNAMES EMAIL
			$user = get_user_by('email', $email);
			if(!$user) {

				//$password = wp_generate_password();

				//// NOW WE CAN FINALLY REGISTER THE USER
				$args = array(

					'user_login' => esc_attr($username),
					'user_email' => $email,
					'user_pass' => $password,
					'role' => 'submitter'
				);

				//// CREATES THE USER
				$user = wp_insert_user($args);

				if(!is_object($user)) {

					//// MAKES SURE HE CAN"T SEE THE ADMIN BAR
					update_user_meta($user, 'show_admin_bar_front', 'false');
					update_user_meta($user, 'rpr_account_type', $userrole);

					$user = new WP_User($user);

					//// MAKES UP THE EMAIL WE SEND THE USER
					$message = sprintf2(__("Welcome to %site_name,

						Here are your credentials ready for use:
						Username: %username
						Password: %password

						Kind regards,
						The %site_name team.", 'btoa'), array(

							'site_name' => get_bloginfo('name'),
							'username' => $username,
							'password' => $password,

					));

					$subject = sprintf2(__('Your %site_name credentials', 'btoa'), array('site_name' => get_bloginfo('name')));

					$headers = "From: ".get_bloginfo('name')." <".get_option('admin_email').">";

					///// SENDS THE EMAIL
					if(!wp_mail($email, $subject, $message, $headers)) {

						$return['error'] = true; $return['message'] = sprintf2(__('Unfortunately an error occurred sending you the email. Your credentials are %username and %password', 'btoa'), array(

							'username' => $username,
							'password' => $password,

						));

					}

					///// SENDS THE EMAIL TO THE ADMINISTRATOR
					$message = sprintf2(__('Dear Admin,

						A new user has signed up to your website.

						Username: %username
						Email Address: %email', 'btoa'), array(

							'username' => $username,
							'email' => $email,

					));

					$return['message2'] = $message;

					$subject = sprintf2(__('New user registration at %site_name.', 'btoa'), array('site_name' => get_bloginfo('name')));

					$headers = "From: ".get_bloginfo('name')." <".get_option('admin_email').">";

					wp_mail(get_option('admin_email'), $subject, $message, $headers);

					//// SENDS SUCCESS MESSAGE
					if($return['error'] == false) {

						$return['message'] = __('All Done! Check your email inbox for your password.', 'btoa');

					}


				} else { $return['error'] = true; $return['message'] = __('There was an error creating your user. Please contact the site administrator. (Error Code 23).', 'btoa'); }

			} else { $return['error'] = true; $return['message'] = __('Email address already in use.', 'btoa'); }

		} else { $return['error'] = true; $return['message'] = __('Username already in use.', 'btoa'); }

		return $return;

	}

	add_action('init', 'btoa_custom_register_scripts');

	function btoa_custom_register_scripts(){
		wp_enqueue_style('jquery-ui-core','http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('jquery-ui-timepicker-addon',get_stylesheet_directory_uri().'/css/jquery-ui-timepicker-addon.css');

		wp_enqueue_script('jquery-ui-tooltip',array('jquery'));

		wp_enqueue_script('timepicker-addons',get_stylesheet_directory_uri().'/js/jquery-ui-timepicker-addon.js',array('jquery','jquery-ui-core','jquery-ui-datepicker','jquery-ui-slider'));
		wp_enqueue_script('jquery-ui-sliderAccess',get_stylesheet_directory_uri().'/js/jquery-ui-sliderAccess.js',array('jquery','jquery-ui-core'));
		wp_enqueue_script('jquery-prism',get_stylesheet_directory_uri().'/js/prism.js');
		wp_deregister_script('btoaUser');
		wp_register_script( 'btoaUser',  get_stylesheet_directory_uri().'/js/sf.users.js');
	}

	function generate_metabox_for_frontend($type = '',$public=''){
		$weekly_hours = array();
		$business_hours = 0;
		$apply_two_set_of_time = "";

		$custom_fields = get_option('wpcf-fields');

	?>

	<div id="_sf_custom_fields" class="_sf_box">

		<div class="head">

			<div class="left"><?php _e('Details', 'btoa'); ?></div>

			<div class="clear"></div>
			<!-- /.clear/ -->

		</div>

		<div class="inside">
			<?php
				$tempArray = json_decode(html_entity_decode(get_post_meta($_GET['id'], '_sf_custom_fields', true)));

				foreach($custom_fields as $fields_detial)
				{
					$value = "";
					if(isset($_GET['id']))
						$value = get_post_meta($_GET['id'], 'wpcf-'.$fields_detial[id],true);

					if($fields_detial["type"] == 'textfield') $fields_detial['type'] = "text";
					echo "<p>
					<lable for='$fields_detial[id]'>$fields_detial[name]</lable>
					<input type='$fields_detial[type]' placeholder='Enter your $fields_detial[slug]' name='$fields_detial[id]' id='$fields_detial[id]' value='".$value."' />
					</p>";
				}
				if(isset($_GET['id']))
				{
					$type =  get_post_meta($_GET['id'], 'list_type',true);
					$weekly_hours = get_post_meta($_GET['id'], 'weekly_hours', true);
					$business_hours = get_post_meta($_GET['id'], 'business_hours', true);
					$apply_two_set_of_time = get_post_meta($_GET['id'], 'apply_two_set_of_time', true);
				}
				if($public=="")
				{
			?>
				<input type="hidden" value="<?php echo $type;?>" name="list_type" id="list_type" />
				<p>
					<label for='business_hors'>Business Hours</label>
					<table>
						<tr>
							<td><input <?php echo ($business_hours ? '' : 'checked="checked"' );?>  type="radio" name="business_hours" id="business_hours_0" value="0" />&nbsp;I prefer not to specify operating hours&nbsp;</td>
							<td><input <?php echo ($business_hours ? 'checked="checked"' : '' );?> type="radio" name="business_hours" id="business_hours_1" value="1" >&nbsp; My operating hours are</td>
						</tr>
					</table>
				</p>
				<div class="clear"></div>
				<div id="business_hours_panel" class="business_hours_panel business_twish_hours">
					<ul>
						<?php
							$days = array('mon','tue','wed','thu','fri','sat','sun');
							foreach($days as $day){
								$dayArray =  explode('|',$weekly_hours[$day]);
								$dayArray[0] = explode('-',$dayArray[0]);
								$dayArray[1] = explode('-',$dayArray[1]);
							?>
							<li>
								<div class="days">
									<?php echo ucwords($day); ?>:
								</div>
								<div class="set_once_hours">
									<input type="text" name="<?php echo $day; ?>_business_hour_0" id="<?php echo $day; ?>_business_hour_0" value="<?php echo (isset($dayArray[0][0]) ? trim($dayArray[0][0]) : '' );?>" />&nbsp;-&nbsp;
									<input type="text" name="<?php echo $day; ?>_business_hour_1" id="<?php echo $day; ?>_business_hour_1" value="<?php echo (isset($dayArray[0][1]) ? trim($dayArray[0][1]) : '' );?>" />&nbsp;&nbsp;
									<div>
										<input type="text" name="<?php echo $day; ?>_business_hour_2" id="<?php echo $day; ?>_business_hour_2" value="<?php echo (isset($dayArray[1][0]) ? trim($dayArray[1][0]) : '' );?>" />&nbsp;-&nbsp;
										<input type="text" name="<?php echo $day; ?>_business_hour_3" id="<?php echo $day; ?>_business_hour_3" value="<?php echo (isset($dayArray[1][1]) ? trim($dayArray[1][1]) : '' );?>" />&nbsp;&nbsp;
									</div>
								</div>
								<div class="hours_action">
									<a href="#" >Clear</a>
									<?php
										if($day == 'mon'){
										?>
										&nbsp;|&nbsp;<a href="#" class="apply_all">Apply to all</a>
										<?php }?>
								</div>
							</li>
							<?php
							}
						?>
					</ul>
					<div class="clear"></div>
					<div>
						<input type="hidden" value="" name="_sf_custom_fields_array" id="_sf_custom_fields_array" />
						<input type="checkbox" <?php echo ($apply_two_set_of_time ? 'checked="checked' : '');?> name="apply_two_set_of_time" id="apply_two_set_of_time" />&nbsp;I'd like to enter 2 sets of hours for each day
					</div>
					<div class="business_hours_hide_panel"></div>
				</div>
			<?php
				}
				else
				{
					/* advance scheduler */
					do_action('_prism_advance_scheduler', $_GET['id'],$type);
				}
			?>
		</div>

	</div>

	<?php
	}

	remove_action('wp_ajax__sf_submit', '_sf_submit_function');
	remove_action('wp_ajax_nopriv__sf_submit', '_sf_submit_function');
	add_action('wp_ajax__sf_submit', '_save_listing_extra');
	add_action('wp_ajax_nopriv__sf_submit', '_save_listing_extra');

	function _save_listing_extra() {

		$return = array();
		$return['error'] = false;
		$return['error_fields'] = array();

		//// VERIFIES NONCE
		$nonce = isset($_POST['nonce']) ? trim($_POST['nonce']) : '';
		if(!wp_verify_nonce($nonce, 'sf-submit-nonce'))
			die('Busted!');

		//// VERIFIES CURRENT USER IS THE AUTHOR OF THIS POST
		$post_id = isset($_POST['post_id']) ? trim($_POST['post_id']) : '';

		if($post = get_post($post_id)) {

			//// CHECK FI THERE"S ANYTHING IN THE CART, IF SO, DO NOT PUBLISH
			$cart_meta = _sf_check_cart_only_meta($post_id);

			$return['cart_meta'] = $cart_meta;

			//// IF NO CART ITEMS
			if(count($cart_meta) > 0) {

				$return['error'] = true;
				$return['message'] = __('There are still items in your cart. Please make the payment or remove items from your cart before submitting.', 'btoa');

			}
			else {

				$current_user = wp_get_current_user();
				if($post->post_author == $current_user->ID) {

					/// FIELDS
					$args = array('ID' => $post_id);
					$fields = array();
					parse_str($_POST['data'], $fields);

					//$return['fields'] = '';
					//foreach($fields as $key => $field) { $return['fields'] .= ' --- '.$key.' -> '.$field; };

					//// FIRSTLY LET US SAVE THE TITLE
					$title = $fields['_sf_title'];
					$args['post_title'] = $title;

					//// CONTENT
					$args['post_content'] = strip_tags($fields['_sf_spot_content'], '<h2><h3><h4><h5><h6><a><p><ul><ol><strong><b><em><i><del><ins><img><li><code><small><big><br>');

					//// DATE
					//$args['post_date'] = date('Y-m-d H:i:s', time());
					//$args['post_date_gmt'] = gmdate('Y-m-d H:i:s', time());

					//// STATUS
					if(ddp('pbl_publish') == 'on' || $post->post_status == 'publish') { $args['post_status'] = 'publish'; }
					else { $args['post_status'] = 'pending'; }

					//// IF IT"S ALREADY PUBLISHED AND WE ARE EDITING, WE NEED TO SENT THE ADMIN AN EMAIL
					if($post->post_status == 'publish') { _sf_post_editing($post); }

					//// WE NEED TO LOOK FOR ADD TO CART METABOXES THAT ARE ON AND SAVE THEM WITH OUR DRAFT
					$cart_meta = _sf_check_cart_meta($post_id);

					//// FINALLY SAVES
					wp_update_post($args);

					//// UPDATES CART META
					if(count($cart_meta) > 0) { _sf_update_cart_meta($post_id, $cart_meta); }

					//// GOES THROUGH OUR IMAGES ARRAY NAD MAKES SURE WE ARE SAVING JUST THE AMOUNT WE HAVE TO
					$images = json_decode(stripslashes(stripslashes($fields['_sf_gallery'])));
					if(is_object($images)) {

						$max_images = _sf_get_maximum_images($post_id); $i = 0;
						$the_images = new stdClass();
						foreach($images as $_image) {

							if($i < $max_images) { $the_images->$i = $_image; }
							$i++;

						}
						update_post_meta($post_id, '_sf_gallery_images', htmlspecialchars(json_encode($the_images)));

					}

					//// SAVES THE SLOGAN
					$slogan = $fields['_sf_slogan'];
					update_post_meta($post_id, 'slogan', $slogan);



					//// CATEGORY ? FIRST MAKES SURE WE CAN SELECT MULTIPLE OR JUST ONE CATEGORY
					if(isset($fields['_sf_category'])) {

						$categories = $fields['_sf_category'];
						if(ddp('pbl_cats') != 'on' && is_array($categories)) {

							//// ONLY SELECT THE FIRST ONE
							$categories = array($categories[0]);

						}
						/// UPDATES CATEGORIES
						wp_set_post_terms($post_id, $categories, 'spot_cats');

					}



					//// TAGS ? MAKES SURE USER CAN ONLY ADD THE LIMIT NUMBER OF TAGS
					if($fields['_sf_tags'] != '') {

						$tags = json_decode(stripslashes(stripslashes($fields['_sf_tags'])));
						$max_tags = _sf_get_maximum_tags($post_id); $i = 0;
						$the_tags = array();
						foreach($tags as $_tag) {

							if($i < $max_tags) { $the_tags[] = $_tag; }
							$i++;

						}

						//// UPDATES TAGS
						wp_set_post_terms($post_id, $the_tags, 'spot_tags', false);

					}


					//// SAVES ADDRESS
					$address = $fields['_sf_address'];
					update_post_meta($post_id, 'address', htmlspecialchars($address));


					//// SAVES LOCATION
					$lat = $fields['_sf_latitude'];
					$lng = $fields['_sf_longitude'];
					update_post_meta($post_id, 'latitude', $lat);
					update_post_meta($post_id, 'longitude', $lng);


					//// SAVES CUSTOM PIN
					if(_sf_check_custom_pin($post_id)) {

						$pin = $fields['_sf_custom_pin'];
						update_post_meta($post_id, 'pin', $pin);

					}


					//// SAVES CONTACT FORM
					if(_sf_check_contact_form($post_id)) {

						//// IF IS TURNED ON
						if($fields['_sf_contact_form'] == 'on') {

							//// UPDATES IT
							update_post_meta($post_id, 'contact_form', 'on');

						} else {

							update_post_meta($post_id, 'contact_form', '');

						}

					} else { update_post_meta($post_id, 'contact_form', ''); }



					//// SAVES FEATURED SELECTION
					if(_sf_check_featured_selection($post_id)) {

						//// IF IS TURNED ON
						if($fields['_sf_featured'] == 'on') {

							//// UPDATES IT
							update_post_meta($post_id, 'featured', 'on');

						} else {

							update_post_meta($post_id, 'featured', '');

						}

					} else { update_post_meta($post_id, 'featured', ''); }


					//// SAVES CUSTOM FIELDS
					if(ddp('pbl_custom_fields') == 'on' && _sf_check_custom_fields($post_id))
					{

						if(isset($fields['_sf_custom_fields'])) {

							$custom_fields_test = json_decode(stripslashes(stripslashes($fields['_sf_custom_fields'])));

							//// IF IS OBJECT
							if(is_object($custom_fields_test)) {

								//// STRIP HTML TAGS
								$custom_fields = htmlspecialchars(strip_tags($fields['_sf_custom_fields']));

								//// SAVES FIELDS
								update_post_meta($post_id, '_sf_custom_fields', $custom_fields);

							}

						}

					}


					///// IF WE HAVE A RATING MAKE SURE WE SET THE INITIAL TO 0
					if(ddp('rating') == 'on') {

						update_post_meta($post_id, 'rating', 0);
						update_post_meta($post_id, 'rating_count', 0);

					}

					////


					//// OUR SEARCH FIELDS
					//// GOES THROUGH EACH FIELD AND CHECKS IF IT IS A CUSTOM FIELD
					foreach($fields as $key => $_search_field) {

						//// IF ITS A CUSTOM SUBMISISON FIELD
						if(strpos($key, '_sf_submission_field_') !== false) {

							//// LETS GET OUR FIELD IF
							$field_id = explode('_', $key);
							$field_id = $field_id[4];

							//// LETS TRY AND GET OUR POST
							if($the_field = get_post($field_id)) {

								/// IF ITS INDEED A CUSTOM FIELD
								if($the_field->post_type == 'submission_field') {

									//// IF ITS A TEXT FIELD LETS SEE IF WE ALLOW HTML SO WE CAN STRIP OUR TAGS
									$value =  $fields['_sf_submission_field_'.$field_id];
									if(get_post_meta($field_id, 'field_type', true) == 'text') {

										//// ITS TEXT CHECK FOR HTML
										if(get_post_meta($field_id, 'allow_html', true) != 'on') {

											strip_tags($value);

										}

									} //// ENDS IF ITS TEXT

									//// FI ITS A FILE WE NEED TO STRIP SLASHES TO STORE IT PROPERLY
									if(get_post_meta($field_id, 'field_type', true) == 'file') {

										$value = htmlspecialchars(stripslashes(stripslashes(strip_tags(htmlspecialchars_decode($value)))));

									}

									///// STORES IT
									update_post_meta($post_id, '_sf_submission_field_'.$field_id, $value);

								}

							}

						}

						//// IF IT CONTAINS THE BASIC OF OUR NAME
						elseif(strpos($key, '_sf_field') !== false) {

							/// FIELD IS
							$field_id = explode('_', $key);
							$field_id = $field_id[3];

							//// IF IT'S A SEARCH FIELD
							if($the_field = get_post($field_id)) {

								//// MAKES SURE ITS NOT PAID, AND IF IT IS, THAT USER HAS PAID FOR IT
								$field_paid = true;
								if(get_post_meta($the_field->ID, 'public_field_price', true) != '' && get_post_meta($the_field->ID, 'public_field_price', true) != '0') {

									$field_paid = false;

									//// IF USER HAS PAID FOR IT WE SET TO TRUE
									if(get_post_meta($post_id, '_sf_'.$the_field->ID, true) == 'on') { $field_paid = true; }
									else { update_post_meta($post_id, '_sf_'.$the_field->ID, ''); update_post_meta($post_id, '_sf_field_'.$the_field->ID, ''); } /// TURN IT OFF AND DONT SAVE IT

								}

								///// IF USER HAS PAID FOR IT - AND IF THE FIELD IS FREE
								if($field_paid) {

									///////////////////////////////////
									//// IF IT'S A CHECKBOX
									///////////////////////////////////
									if(get_post_meta($the_field->ID, 'field_type', true) == 'check') {

										//// IF IS TURNED ON
										if($fields['_sf_field_'.$field_id] == 'on') {

											//// UPDATES IT
											update_post_meta($post_id, '_sf_field_'.$the_field->ID, 'on');

										} else {

											//// UPDATES IT
											update_post_meta($post_id, '_sf_field_'.$the_field->ID, '');

										}

									}


									///////////////////////////////////
									//// IF IT'S A RANGE
									///////////////////////////////////
									else if(get_post_meta($the_field->ID, 'field_type', true) == 'range') {

										//// MAKES SURE THE VALUE IS AN INTEGER
										if(is_numeric($fields['_sf_field_'.$field_id]) || empty($fields['_sf_field_'.$field_id])) {

											//// GETS MIN AND MAX VALUES
											$min = get_post_meta($field_id, 'range_minimum', true);
											$max = get_post_meta($field_id, 'range_maximum', true);

											//// MAKE SURE IT'S WITHIN THE PERMITTED VALUES
											if($fields['_sf_field_'.$field_id] >= $min && $fields['_sf_field_'.$field_id]<= $max) {

												//// STORES THE VALUES
												update_post_meta($post_id, '_sf_field_'.$the_field->ID, $fields['_sf_field_'.$field_id]);

											} else { $return['error'] = true; $return['error_fields'][] = array('field_id' => '_sf_field_range_'.$field_id.'_wrapper', 'message' => sprintf2(__('Sorry, Your value must be between %min and %max.', 'btoa'),array('min' => $min, 'max' => $max)), 'inside' => 'true'); }

										} else { $return['error'] = true; $return['error_fields'][] = array('field_id' => '_sf_field_range_'.$field_id.'_wrapper', 'message' => __('Your input must be a whole number.', 'btoa'), 'inside' => 'true'); }

									}


									///////////////////////////////////
									//// IF IT'S A MAX VALUE
									///////////////////////////////////
									else if(get_post_meta($the_field->ID, 'field_type', true) == 'max_val') {

										//// IF IS SET
										if(!empty($fields['_sf_field_'.$field_id])) {

											//// MAKES SURE THE VALUE IS AN INTEGER
											if(is_numeric($fields['_sf_field_'.$field_id]) || empty($fields['_sf_field_'.$field_id])) {

												//// STORES THE VALUES
												update_post_meta($post_id, '_sf_field_'.$the_field->ID, $fields['_sf_field_'.$field_id]);

											} else { $return['error'] = true; $return['error_fields'][] = array('field_id' => '_sf_field_'.$field_id.'_field', 'message' => __('Your input must be a number.', 'btoa'), 'inside' => 'true'); }

										}

									}


									///////////////////////////////////
									//// IF IT'S A MIN VALUE
									///////////////////////////////////
									else if(get_post_meta($the_field->ID, 'field_type', true) == 'min_val') {

										//// MAKES SURE THE VALUE IS AN INTEGER
										if(is_numeric($fields['_sf_field_'.$field_id]) || empty($fields['_sf_field_'.$field_id])) {

											//// STORES THE VALUES
											update_post_meta($post_id, '_sf_field_'.$the_field->ID, $fields['_sf_field_'.$field_id]);

										} else { $return['error'] = true; $return['error_fields'][] = array('field_id' => '_sf_field_'.$field_id.'_field', 'message' => __('Your input must be a number.', 'btoa'), 'inside' => 'true'); }

									}


									///////////////////////////////////
									//// IF IT'S A DROPDOWN
									///////////////////////////////////
									else if(get_post_meta($the_field->ID, 'field_type', true) == 'dropdown') {

										//// IF IT'S NOT AN ARRAY - WE MAKE IT AN ARRAY
										if(!is_array($fields['_sf_field_'.$field_id])) { $dropdown_field = array($fields['_sf_field_'.$field_id]); }
										else { $dropdown_field = $fields['_sf_field_'.$field_id]; }

										//// UPDATES IT
										update_post_meta($post_id, '_sf_field_'.$the_field->ID, $dropdown_field);

									}


									///////////////////////////////////
									//// IF IT'S A DEPENDENT
									///////////////////////////////////
									else if(get_post_meta($the_field->ID, 'field_type', true) == 'dependent') {

										//// IF IT'S NOT AN ARRAY - WE MAKE IT AN ARRAY
										if(!is_array($fields['_sf_field_'.$field_id])) { $dependent_field = array($fields['_sf_field_'.$field_id]); }
										else { $dependent_field = $fields['_sf_field_'.$field_id]; }

										//// UPDATES IT
										update_post_meta($post_id, '_sf_field_'.$the_field->ID, $dependent_field);

									}

								}

							}

						}

					}

					$days = array('mon','tue','wed','thu','fri','sat','sun');
					$working_hours = array();
					$custom_fields_days = array();
					$tempCount = 0;
					foreach($days as $day){
						$tempValue = "";
						$seperator = " ";
						for($i = 0; $i < 4; $i++)
						{
							$id = $day."_business_hour_".$i;
							if($fields[$id] != "")
							{
								$tempValue = $tempValue . $seperator . trim($fields[$id]);
								if($seperator == " " || $seperator == "|")
									$seperator = "-";
								else
									$seperator = "|";
							}
						}
						$working_hours[$day] = $tempValue;
						$custom_fields_days[$tempCount++] = array('label' => $day,'value' => $tempValue);
					}
					update_post_meta($post_id, 'weekly_hours', $working_hours);
					update_post_meta($post_id, 'business_hours', $fields['business_hours']);
					update_post_meta($post_id, 'apply_two_set_of_time', $fields['apply_two_set_of_time']);
					update_post_meta($post_id, '_sf_custom_fields', $fields['_sf_custom_fields_array']);


					/* Start -- save advance scedule data   By:Twisha Date:7-12-2013 */

					$final_arr=array();

					//if(!$fields["schedule_date"])
					{
						delete_post_meta($post_id,'schedule_type');
						delete_post_meta($post_id,'schedule_Yearly');
						delete_post_meta($post_id,'schedule_Monthly');
						delete_post_meta($post_id,'schedule_Weekly');
						delete_post_meta($post_id,'schedule_Daily');
						delete_post_meta($post_id,'schedule_FromToDate');
					}
					$s_type=$fields['schedule_type'];
					for($i=0;$i<sizeof($fields["schedule_date_".$s_type]);$i++)
					{
						//echo $fields["schedule_date"][$i]."--".$fields["schedule_from_time"][$i]."---".$fields["schedule_to_time"][$i]."<br>";
						$schedule_arr["date"]=$fields["schedule_date_".$s_type][$i];
						$schedule_arr["from_time_1"]=$fields["schedule_from_time_1"][$i];
						$schedule_arr["to_time_1"]=$fields["schedule_to_time_1"][$i];
						$schedule_arr["from_time_2"]=$fields["schedule_from_time_2"][$i];
						$schedule_arr["to_time_2"]=$fields["schedule_to_time_2"][$i];
						$final_arr[]=$schedule_arr;
					}

					update_post_meta($post_id,'schedule_type',$fields['schedule_type']);
					update_post_meta($post_id,'schedule_'.$s_type,$final_arr);

					/* End -- save advance scedule data   By:Twisha Date:7-12-2013 */

					//// SET CUSTOM FIELDS DATA

					$custom_fields = get_option('wpcf-fields');
					//// LOOPS EACH POST AND MAKES SURE THE META IS SET
					foreach($custom_fields as $field) {

						//// IF THIS FIELD IS SET
						if($fields[$field['id']] != "") {

							//// UPDATES META
							update_post_meta($post_id, 'wpcf-'.$field['id'], $fields[$field['id']]);

						} else {

							update_post_meta($post_id, 'wpcf-'.$field['id'], '');

						}

					}

					if($fields['list_type'] != "")
					{
						update_post_meta($post_id, 'list_type', $fields['list_type']);
					}

					if($fields['business_id'] != "")
					{
						update_post_meta($post_id, 'business_id', $fields['business_id']);
					}

					if($fields['business_id'] != "")
					{
						update_post_meta($post_id, 'business_id', $fields['business_id']);
					}

					//// IF THERE WERE NO ERRORS
					if($return['error'] == false) {

						/// IF EVERYTHING WAS SUCCESSFULL
						$return['result'] = 'success';

						//// SENDS AN EMAIL TO THE ADMIN
						//// IF ITS FOR REVIEW
						if(ddp('pbl_publish') != 'on') {

							$message = sprintf2(__("Dear Admin,

								A submission has been submitted for review at %site_name. Please review it at %review_link.

								Kind Regards,
								The %site_name team.", 'btoa'), array(

									'site_name' => get_bloginfo('name'),
									'review_link' => admin_url('post.php?post='.$post_id.'&action=edit'),

							));

						} else { /// IF PUBLISH DIRECTLY

							$message = sprintf2(__("Dear Admin,

								A submission has been submitted at %site_name. Here is the submission %the_link.

								Kind Regards,
								The %site_name team.", 'btoa'), array(

									'site_name' => get_bloginfo('name'),
									'the_link' => get_permalink($post_id),

							));

						}

						$to = get_bloginfo('admin_email');
						$subject = sprintf2(__('Submission at %site_name', 'btoa'), array('site_name' => get_bloginfo('name')));
						$headers = 'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>' . "\r\n";

						//// SENDS EMAIL
						wp_mail($to, $subject, $message, $headers);

					}

				}

			}

		}

		echo json_encode($return);

		exit;



	}

	function add_spot_columns($columns) {
		return array_merge($columns,
			array('spot_type' => __('Spot Type')));
	}

	add_filter('manage_spot_posts_columns' , 'add_spot_columns');

	add_action( 'manage_spot_posts_custom_column' , 'manage_spot_columns', 10, 2 );

	function manage_spot_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'spot_type' :
				$type = get_post_meta($post_id, 'list_type',true);
				if ( $type != '' )
					echo ucfirst($type);
				else
					echo 'Listing';
				break;
		}
	}

	function update_spot_hours( $post_id ) {

		if ( wp_is_post_revision( $post_id ) )
			return;

		$_sf_custom_fields_array = json_decode(html_entity_decode(stripslashes($_POST['_sf_custom_fields'])));

		$working_hours = array();//get_post_meta($post_id, 'weekly_hours', true);
		if($_sf_custom_fields_array)
		{
			foreach($_sf_custom_fields_array as $custom_field)
			{
				$working_hours[strtolower($custom_field->label)] = $custom_field->value;
			}
			update_post_meta($post_id, 'weekly_hours', $working_hours);
		}

	}
	add_action( 'save_post', 'update_spot_hours' );

	add_action( 'admin_enqueue_scripts', '_prism_admin_enqueue_scripts' );

	function _prism_admin_enqueue_scripts(){
		wp_enqueue_script('prism_admin', get_stylesheet_directory_uri() .'/js/prism_admin.js');
	}

	add_action('_prism_advance_scheduler','AdvanceScheduler');
	function AdvanceScheduler($spot_id)
	{
		$type=get_post_meta($spot_id,'schedule_type',true);
		?>
			<p>
				<label for=''>Advance Scheduler</label>
				<table id="scheduler_type">
					<tr>
						<td>Schedule Type : </td>
						<td><input type="radio" name="schedule_type" id="Yearly" value="Yearly" class="advance_scheduler_type" />&nbsp;Yearly&nbsp;</td>
						<td><input type="radio" name="schedule_type" id="Monthly" value="Monthly" class="advance_scheduler_type" />&nbsp;Monthly&nbsp;</td>
						<td><input type="radio" name="schedule_type" id="Weekly" value="Weekly" class="advance_scheduler_type" />&nbsp;Weekly&nbsp;</td>
						<td><input type="radio" name="schedule_type" id="Daily" value="Daily" class="advance_scheduler_type" />&nbsp;Daily&nbsp;</td>
						<td><input type="radio" name="schedule_type" id="FromToDate" value="FromToDate" class="advance_scheduler_type" />&nbsp;From to To Date&nbsp;</td>
					</tr>
				</table>
			</p>
			<div class="clear"></div>
			<div id="advanced_scheduler_panel" class="advanced_scheduler_panel">
				<div id="schedule_type_Yearly">
					 <ul class="selection_ul">
						<li>From Date</li>
						<li><input type="textbox" name="fromdate" id="fromdate_Yearly" placeholder="From Date" /></li>
						<li>To Date</li>
						<li><input type="textbox" name="todate" id="todate_Yearly" placeholder="To Date" /></li>
						<li><input type="button" name="customize" id="customize" value="Customize" /></li>
						<!--li><input type="button" name="addnew" id="addnew" value="Add New" /></li-->
					 </ul>
					 <span style='color:#e90004;'>Note: Year is not taken in consideration as you have selected Yearly as schedule type.</span><br>
					 <?php
						if(get_post_meta($spot_id,'schedule_'.$type,true) && $type=="Yearly")
						{
					 ?>
					 <br><span><a href='#' class='apply_to_all'>Apply to all</a>&nbsp;|&nbsp;<a href='#' class='clear_all'>Clear all</a>&nbsp;|&nbsp;<a href='#' class='delete_schedule'>Delete All</a>&nbsp;|&nbsp;<a href='#' class='collapse_expand'>Collapse</a></span>
					 <ul class='schedule_area'>
					 <li class='head'>Date</li><li class='head'>From Time 1</li><li class='head'>To Time 1</li><li class='head'>From Time 2</li><li class='head'>To Time 2</li><li></li><li></li>
					 <?php
						$schedule_data=get_post_meta($spot_id,'schedule_'.$type,true);
						//print_r($schedule_data);
						for($i=0;$i<sizeof($schedule_data);$i++)
						{
							$fdt=explode("-",$schedule_data[$i]["date"]);
							$tdt=explode("-",$schedule_data[count($schedule_data)-1]["date"]);
							$rel="del_".$fdt[1]."_".$tdt[1];
							?>
								<li rel='<?php echo $rel; ?>'><input type='text' name='schedule_date_Yearly[]' id='date_<?php echo $fdt[1]; ?>' value='<?php echo $schedule_data[$i]["date"]; ?>' class='min_input' readonly='readonly'/></li>
								<li rel='<?php echo $rel; ?>'><input type='text' placeholder='From Time' name='schedule_from_time_1[]' id='1_from_time_<?php echo $fdt[1]; ?>' value="<?php echo $schedule_data[$i]["from_time_1"]; ?>" class='min_input from_picktime1' /></li>
								<li rel='<?php echo $rel; ?>'><input type='text' placeholder='To Time' name='schedule_to_time_1[]' id='1_to_time_<?php echo $fdt[1]; ?>' value="<?php echo $schedule_data[$i]["to_time_1"]; ?>" class='min_input to_picktime1' /></li>
								<li rel='<?php echo $rel; ?>'><input type='text' placeholder='From Time' name='schedule_from_time_2[]' id='2_from_time_<?php echo $fdt[1]; ?>' value="<?php echo $schedule_data[$i]["from_time_2"]; ?>" class='min_input from_picktime2' /></li>
								<li rel='<?php echo $rel; ?>'><input type='text' placeholder='To Time' name='schedule_to_time_2[]' id='2_to_time_<?php echo $fdt[1]; ?>' value="<?php echo $schedule_data[$i]["to_time_2"]; ?>" class='min_input to_picktime2' /></li>
								<li rel='<?php echo $rel; ?>'><a href='#' rel='<?php echo $fdt[1]; ?>' id='clear_<?php echo $fdt[1]; ?>' class='clear_me'>Clear</a></li>
								<li rel='<?php echo $rel; ?>'>|</li><li rel='<?php echo $rel; ?>'><a href='#' id='<?php echo $fdt[1]."_".$tdt[1]; ?>' class='delete_me'>Delete</a></li>
							<?php
						}
					 ?>
					 </ul>
					 <?php
						}
					 ?>
				</div>
				<div id="schedule_type_Monthly">
					 <ul class="selection_ul">
						<li>From Date</li>
						<li><input type="textbox" name="fromdate" id="fromdate_Monthly" placeholder="From Date" /></li>
						<li>To Date</li>
						<li><input type="textbox" name="todate" id="todate_Monthly" placeholder="To Date" /></li>
						<li><input type="button" name="customize" id="customize" value="Customize" /></li>
						<!--li><input type="button" name="addnew" id="addnew" value="Add New" /></li-->
					 </ul>
					 <span style='color:#e90004;'>Note: Month and Year is not taken in consideration as you have selected Monthly as schedule type.</span><br>
					 <?php
						if(get_post_meta($spot_id,'schedule_'.$type,true) && $type=="Monthly")
						{
					 ?>
					 <br><span><a href='#' class='apply_to_all'>Apply to all</a>&nbsp;|&nbsp;<a href='#' class='clear_all'>Clear all</a>&nbsp;|&nbsp;<a href='#' class='delete_schedule'>Delete All</a>&nbsp;|&nbsp;<a href='#' class='collapse_expand'>Collapse</a></span>
					 <ul class='schedule_area'>
					 <li class='head'>Date</li><li class='head'>From Time 1</li><li class='head'>To Time 1</li><li class='head'>From Time 2</li><li class='head'>To Time 2</li><li></li><li></li>
					 <?php
						$schedule_data=get_post_meta($spot_id,'schedule_'.$type,true);
						//print_r($schedule_data);
						for($i=0;$i<sizeof($schedule_data);$i++)
						{
							$fdt=explode("-",$schedule_data[$i]["date"]);
							$tdt=explode("-",$schedule_data[count($schedule_data)-1]["date"]);
							$rel="del_".$fdt[1]."_".$tdt[1];
							?>
								<li rel='<?php echo $rel; ?>'><input type='text' name='schedule_date_Monthly[]' id='date_<?php echo $fdt[1]; ?>' value='<?php echo $schedule_data[$i]["date"]; ?>' class='min_input' readonly='readonly'/></li>
								<li rel='<?php echo $rel; ?>'><input type='text' placeholder='From Time' name='schedule_from_time1[]' id='1_from_time_<?php echo $fdt[1]; ?>' value="<?php echo $schedule_data[$i]["from_time_1"]; ?>" class='min_input from_picktime1' /></li>
								<li rel='<?php echo $rel; ?>'><input type='text' placeholder='To Time' name='schedule_to_time1[]' id='1_to_time_<?php echo $fdt[1]; ?>' value="<?php echo $schedule_data[$i]["to_time_1"]; ?>" class='min_input to_picktime1' /></li>
								<li rel='<?php echo $rel; ?>'><input type='text' placeholder='From Time' name='schedule_from_time_2[]' id='2_from_time_<?php echo $fdt[1]; ?>' value="<?php echo $schedule_data[$i]["from_time_2"]; ?>" class='min_input from_picktime2' /></li>
								<li rel='<?php echo $rel; ?>'><input type='text' placeholder='To Time' name='schedule_to_time_2[]' id='2_to_time_<?php echo $fdt[1]; ?>' value="<?php echo $schedule_data[$i]["to_time_2"]; ?>" class='min_input to_picktime2' /></li>
								<li rel='<?php echo $rel; ?>'><a href='#' rel='<?php echo $fdt[1]; ?>' id='clear_<?php echo $fdt[1]; ?>' class='clear_me'>Clear</a></li>
								<li rel='<?php echo $rel; ?>'>|</li><li rel='<?php echo $rel; ?>'><a href='#' id='<?php echo $fdt[1]."_".$tdt[1]; ?>' class='delete_me'>Delete</a></li>
							<?php
						}
					 ?>
					 </ul>
					 <?php
						}
					 ?>
				</div>
			</div>
			<script type="text/javascript">
				if("<?php echo get_post_meta($spot_id,'schedule_type',true);?>" != "")
				{
					jQuery("#<?php echo get_post_meta($spot_id,'schedule_type',true);?>").attr("checked",true);
					jQuery("#advanced_scheduler_panel").css("display","inline-block");

					id="<?php echo get_post_meta($spot_id,'schedule_type',true);?>";

					jQuery("#advanced_scheduler_panel div").hide();
					jQuery("#schedule_type_"+id).show();

					jQuery('#fromdate_'+id).datepicker({
						minDate: 0,
						maxDate: "+1y",
						dateFormat: "mm-dd-yy"
					});
					jQuery('#todate_'+id).datepicker({
						minDate: 0,
						maxDate: "+1y",
						dateFormat: "mm-dd-yy"
					});

					jQuery(".from_picktime1").timepicker({ timeFormat : 'hh:mm TT', ampm : true , defaultValue : "08:00 AM" });
					jQuery(".to_picktime1").timepicker({ timeFormat : 'hh:mm TT', ampm : true, defaultValue : "12:00 PM" });

					jQuery(".from_picktime2").timepicker({ timeFormat : 'hh:mm TT', ampm : true , defaultValue : "02:00 PM" });
					jQuery(".to_picktime2").timepicker({ timeFormat : 'hh:mm TT', ampm : true, defaultValue : "08:00 PM" });


				}
			</script>
		<?php
	}
?>