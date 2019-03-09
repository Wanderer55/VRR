<?php

namespace VISUAL_RESTAURANT_RESERVATION;

class Frontend
{
    private $plugin_slug;
    private $version;
    private $option_name;
    private $settings;

    public function __construct($plugin_slug, $version, $option_name) {
        $this->plugin_slug = $plugin_slug;
        $this->version = $version;
        $this->option_name = $option_name;
        $this->settings = get_option($this->option_name);
    }

    public function assets() {
        wp_enqueue_style('vrr-select2-css', plugin_dir_url(dirname(__FILE__)).'assets/css/select2.css', [], $this->version);
        wp_enqueue_style($this->plugin_slug, plugin_dir_url(__FILE__).'css/visual-restaurant-reservation-frontend.css', [], $this->version);
        wp_enqueue_script('vrr-jquery-ui', plugin_dir_url(dirname(__FILE__)).'assets/js/jquery-ui.min.js', ['jquery'], $this->version, true);
        wp_enqueue_script('vrr-select2-js', plugin_dir_url(dirname(__FILE__)).'assets/js/select2.js', ['jquery'], $this->version, true);
        
        wp_enqueue_script($this->plugin_slug, plugin_dir_url(__FILE__).'js/visual-restaurant-reservation-frontend.js', ['jquery'], $this->version, true);
        wp_localize_script( $this->plugin_slug, 'myajax', array('url' => admin_url('admin-ajax.php')));  
    }
    
    public function send_book() {
        $data = $_POST;
        $error_empty = 0;
        $error_exist = 0;

        $sms_message = "";

        $options = get_option($this->option_name);

        if(isset($data)){

            if(isset($data['visual_restaurant_reservation_mob']) && $data['visual_restaurant_reservation_mob'] == '1'){
                foreach ($data as $key => $value) {
                    if($value != "" && $key == 'visual_restaurant_reservation_phone'){
                        if(preg_match("/^[0-9\-\+]{7,15}$/", $value)) {

                        } else {
                            $error_empty = 1;
                            $echo = array(
                                'echo' => 0,
                                'error' => __('Error. Phone number is wrong','vrr'),
                            );
                        }
                    } if($value != "" && $key == 'visual_restaurant_reservation_email'){
                        if(is_email($value)){

                        } else {
                            $error_empty = 1;
                            $echo = array(
                                'echo' => 0,
                                'error' => __('Error. E-mail is wrong','vrr'),
                            );
                        }
                    } else if($key != 'visual_restaurant_reservation_id' 
                        && $key != 'visual_restaurant_reservation_unique' 
                        && $key != 'visual_restaurant_reservation_seats' 
                        && $key != 'visual_restaurant_reservation_people') {
                        if($value == ""){
                            $error_empty = 1;
                            $echo = array(
                                'echo' => 0,
                                'error' => __('Error. One or more fields is empty','vrr'),
                            );
                        } else {
                            $data[$key] = sanitize_text_field($value);
                        }
                    } else {
                        $data[$key] = sanitize_text_field($value);
                    }
                }
            } else if(!isset($data['visual_restaurant_reservation_mob']) || $data['visual_restaurant_reservation_mob'] != '1'){
                foreach ($data as $key => $value) {
                    if($value != "" && $key == 'visual_restaurant_reservation_phone'){
                        if(preg_match("/^[0-9\-\+]{7,15}$/", $value)) {

                        } else {
                            $error_empty = 1;
                            $echo = array(
                                'echo' => 0,
                                'error' => __('Error. Phone number is wrong','vrr'),
                            );
                        }
                    } if($value != "" && $key == 'visual_restaurant_reservation_email'){
                        if(is_email($value)){

                        } else {
                            $error_empty = 1;
                            $echo = array(
                                'echo' => 0,
                                'error' => __('Error. E-mail is wrong','vrr'),
                            );
                        }
                    } else if($value == ""){
                        $error_empty = 1;
                        $echo = array(
                            'echo' => 0,
                            'error' => __('Error. One or more fields is empty','vrr'),
                        );
                    } else {
                        $data[$key] = sanitize_text_field($value);
                    }
                }
            }  

            if(isset($options['form_confirm']) && !empty($options['form_confirm'])){
                if(!isset($data['visual_restaurant_reservation_form_confirm']) || empty($data['visual_restaurant_reservation_form_confirm'])){
                    $error_empty = 1;
                    $echo = array(
                        'echo' => 0,
                        'error' => __('Error. Please, confirm that you give rights to use your personal data','vrr'),
                    );
                }
            }

            if($error_empty == 0){
                if(isset($data['visual_restaurant_reservation_mob']) && $data['visual_restaurant_reservation_mob'] == '1'){
                  // dont check on exist if mob
                } else if(!isset($data['visual_restaurant_reservation_mob']) || $data['visual_restaurant_reservation_mob'] != '1'){
                    $args = array(
                    'post_type' => 'vrr',
                    'post_status' => 'reserved',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                           'key' => '_visual_restaurant_reservation_date',
                           'value' => $data['visual_restaurant_reservation_date'],
                           'compare' => '=',
                        ),
                        array(
                           'key' => '_visual_restaurant_reservation_time',
                           'value' => $data['visual_restaurant_reservation_time'],
                           'compare' => '=',
                        ),
                        array(
                           'key' => '_visual_restaurant_reservation_unique',
                           'value' => $data['visual_restaurant_reservation_unique'],
                           'compare' => '=',
                        )
                    )
                    );
                    $query = get_posts($args);
                    if(isset($query)){
                        $count_exist = count($query);
                        if($count_exist > 0){
                            $error_exist = 1;
                            $echo = array(
                                'echo' => 0,
                                'error' => __('Error. On this date and time table already booked','vrr'),
                                'test' => $query
                            );
                        }
                    }
                } 
            }

            if($error_exist == 0 && $error_empty == 0){
            	$sms_content = '';
                $content = "";
                if(isset($data['visual_restaurant_reservation_id']) && !empty($data['visual_restaurant_reservation_id'])){
	                $content .= '<p>';
	                $content .= 'Table Number : '.$data['visual_restaurant_reservation_id'];
	                $sms_content .= 'Table Number: '.$data['visual_restaurant_reservation_id'].'. ';
	                $content .= '</p>';
	            }
                $content .= '<p>';
                $content .= 'Date : '.$data['visual_restaurant_reservation_date'];
                $sms_content .= 'Date: '.$data['visual_restaurant_reservation_date'].'. ';
                $content .= '</p>';
                $content .= '<p>';
                $content .= 'Time : '.$data['visual_restaurant_reservation_time'];
                $sms_content .= 'Time: '.$data['visual_restaurant_reservation_time'].'. ';
                $content .= '</p>';
                if(isset($data['visual_restaurant_reservation_time_to']) && !empty($data['visual_restaurant_reservation_time_to'])){
                    $content .= '<p>';
                    $content .= 'Time to reserve to: '.$data['visual_restaurant_reservation_time_to'];
                    $sms_content .= 'Time to: '.$data['visual_restaurant_reservation_time_to'].'. ';
                    $content .= '</p>';
                }
                $content .= '<p>';
                $content .= 'Name : '.$data['visual_restaurant_reservation_name'];
                $sms_content .= 'Name: '.$data['visual_restaurant_reservation_name'].'. ';
                $content .= '</p>';
                $content .= '<p>';
                $content .= 'Phone Number : '.$data['visual_restaurant_reservation_phone'];
                $sms_content .= 'Phone Number: '.$data['visual_restaurant_reservation_phone'].'. ';
                $content .= '</p>';
                if(isset($data['visual_restaurant_reservation_email']) && !empty($data['visual_restaurant_reservation_email'])){
	                $content .= '<p>';
	                $content .= 'E-mail : '.$data['visual_restaurant_reservation_email'];
	                $sms_content .= 'E-mail: '.$data['visual_restaurant_reservation_email'].'. ';
	                $content .= '</p>';
	            }
                $content .= '<p>';
                $content .= 'Max Seats : '.$data['visual_restaurant_reservation_seats'];
                $content .= '</p>';
                if(isset($data['visual_restaurant_reservation_people']) && !empty($data['visual_restaurant_reservation_people'])){
                    $content .= '<p>';
                    $content .= 'People Amount : '.$data['visual_restaurant_reservation_people'];
                    $sms_content .= 'People Amount : '.$data['visual_restaurant_reservation_people'];
                    $content .= '</p>';
                }

                if(isset($data['visual_restaurant_reservation_mob']) && $data['visual_restaurant_reservation_mob'] == '1'){
                    if(isset($data['visual_restaurant_reservation_time_to']) && !empty($data['visual_restaurant_reservation_time_to'])){
                        $title = "Request on - ".$data['visual_restaurant_reservation_date']."|".$data['visual_restaurant_reservation_time']."/".$data['visual_restaurant_reservation_time_to'].". Request by - ".$data['visual_restaurant_reservation_name']."";
                    } else {
                        $title = "Request on - ".$data['visual_restaurant_reservation_date']."|".$data['visual_restaurant_reservation_time'].". Request by - ".$data['visual_restaurant_reservation_name']."";
                    }
                } else if(!isset($data['visual_restaurant_reservation_mob']) || $data['visual_restaurant_reservation_mob'] != '1'){
                    if(isset($data['visual_restaurant_reservation_time_to']) && !empty($data['visual_restaurant_reservation_time_to'])){
                        $title = "Table #".$data['visual_restaurant_reservation_id']." - ".$data['visual_restaurant_reservation_date']."|".$data['visual_restaurant_reservation_time']."/".$data['visual_restaurant_reservation_time_to'].". Booked by - ".$data['visual_restaurant_reservation_name']."";
                    } else {
                        $title = "Table #".$data['visual_restaurant_reservation_id']." - ".$data['visual_restaurant_reservation_date']."|".$data['visual_restaurant_reservation_time'].". Booked by - ".$data['visual_restaurant_reservation_name']."";
                    }
                }
                $post_data = array(
                    'post_title'    => $title,
                    'post_type' => 'vrr',
                    'post_status'   => 'requests',
                );

                
                
                if(isset($options['form_email']) && !empty($options['form_email'])){
                    $to = $options['form_email'];
                } else  if(!isset($options['form_email']) || empty($options['form_email'])){
                    $to = get_option('admin_email');
                } else {
                    $to = get_option('admin_email');
                }
                
                $subject = '"'.$data['visual_restaurant_reservation_name'].'" reserved a table no.'.$data['visual_restaurant_reservation_id'].' on '.$data['visual_restaurant_reservation_date'].' for '.$data['visual_restaurant_reservation_time'].'';

                $input = get_bloginfo('url');
                $input = trim($input, '/');
                if (!preg_match('#^http(s)?://#', $input)) {
                    $input = 'http://' . $input;
                }
                $urlParts = parse_url($input);
                $from_url = preg_replace('/^www\./', '', $urlParts['host']);
                $headers[] = 'MIME-Version: 1.0.';
                $headers[] = 'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>'; 
                $headers[] = 'Content-type: text/html; charset=utf-8'; 

                $test = false;
                // send mail
                $test = wp_mail( $to, $subject, $content, $headers );

                // send SMS
                if(isset($options['send_sms'])){
	               	$send_sms = $options['send_sms'];
				    if($send_sms == '1'){
				    	if(isset($options['sms_key']) && !empty($options['sms_key']) 
			    		&& isset($options['sms_secret']) && !empty($options['sms_secret'])
			    		&& isset($options['sms_phone']) && !empty($options['sms_phone'])) {
					    	$sms_key = $options['sms_key'];
					    	$sms_secret = $options['sms_secret'];
					    	$sms_phone = $options['sms_phone'];
					    	$sine_name = get_bloginfo('name');
					    	
					    	$path = INFO::get_plugin_path();
					    	if(file_exists($path.'\includes\nexmo\vendor\autoload.php')){
						    	include_once($path.'\includes\nexmo\vendor\autoload.php');
						        $basic  = new \Nexmo\Client\Credentials\Basic($sms_key, $sms_secret);
						        $client = new \Nexmo\Client($basic);

						        $url = 'https://rest.nexmo.com/sms/json?' . http_build_query([
								        'api_key' => $sms_key,
								        'api_secret' => $sms_secret,
								        'to' => $sms_phone,
								        'from' => 'VRR | Reservation',
								        'text' => $sms_content
								    ]);

								$ch = curl_init($url);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								$response = curl_exec($ch);
								$response = json_decode($response, true);

								if(isset($response['messages'][0]['error-text'])){
									$sms_message = $response['messages'][0]['error-text'];
								} else {
									$sms_message = $response['messages'][0];
								}
					        }
				    	}
				    }
				}


                // create post
                $post_id = wp_insert_post( $post_data );

                if(isset($post_id) && $post_id != 0){
                    update_post_meta($post_id, '_visual_restaurant_reservation_id' , $data['visual_restaurant_reservation_id']);
                    update_post_meta($post_id, '_visual_restaurant_reservation_unique' , $data['visual_restaurant_reservation_unique']);
                    update_post_meta($post_id, '_visual_restaurant_reservation_date' , $data['visual_restaurant_reservation_date']);
                    update_post_meta($post_id, '_visual_restaurant_reservation_time' , $data['visual_restaurant_reservation_time']);
                    if(isset($data['visual_restaurant_reservation_time_to']) && !empty($data['visual_restaurant_reservation_time_to'])){
                        update_post_meta($post_id, '_visual_restaurant_reservation_time_to' , $data['visual_restaurant_reservation_time_to']);
                    }
                    update_post_meta($post_id, '_visual_restaurant_reservation_seats' , $data['visual_restaurant_reservation_seats']);
                    if(isset($data['visual_restaurant_reservation_people']) && !empty($data['visual_restaurant_reservation_people'])){
                        update_post_meta($post_id, '_visual_restaurant_reservation_people' , $data['visual_restaurant_reservation_people']);
                    }
                    update_post_meta($post_id, '_visual_restaurant_reservation_name' , $data['visual_restaurant_reservation_name']);
                    update_post_meta($post_id, '_visual_restaurant_reservation_phone' , $data['visual_restaurant_reservation_phone']);
                    if(isset($data['visual_restaurant_reservation_email']) && !empty($data['visual_restaurant_reservation_email'])){
                        update_post_meta($post_id, '_visual_restaurant_reservation_email' , $data['visual_restaurant_reservation_email']);
                    }

                    $echo = array(
                        'echo' => $post_id,
                        'test' => $sms_content,
                        // 'headers' => $headers,
                        'nexmo' => $sms_message,
                    );
                } else {
                    $echo = array(
                        'echo' => 0,
                        'error' => __('Not added','vrr')
                    );
                }

            }

        } else {
            $echo = array(
                'echo' => 0,
                'error' => __('Security error TEST','vrr')
            );
        }
        echo json_encode($echo);
        wp_die();
    }

    /**
     * Render the view using MVC pattern.
     */
    public function render() {
        // Model
        $settings = $this->settings;

    }
}
