<?php
namespace VISUAL_RESTAURANT_RESERVATION;
class Public_Plugin {

	private $plugin_slug;
    private $version;
    private $option_name;
    private $settings;
    private $settings_group;


    public function __construct($plugin_slug, $version, $option_name) {
        $this->plugin_slug = $plugin_slug;
        $this->version = $version;
        $this->option_name = $option_name;
        $this->settings = get_option($this->option_name);
        $this->settings_group = $this->option_name.'_group';
    }

    public function languages_init() {
        load_plugin_textdomain( 'vrr', false, $this->plugin_slug.'/languages' );
    }

    // check if restaurant work on each day of the week
    function daysOff(){
    	$days_off = array();
    	$default_options = INFO::$DEFAULT_OPTIONS;

        $work_days_monday = get_option($this->option_name)['work_days_monday'];
        if(!isset($work_days_monday) || $work_days_monday === ""){
        	$work_days_monday = $default_options['work_days_monday'];
        } 
        $work_days_monday = intval($work_days_monday);
        if($work_days_monday != 1){
        	$days_off[] = "Monday";
        }

        $work_days_tuesday = get_option($this->option_name)['work_days_tuesday'];
        if(!isset($work_days_tuesday) || $work_days_tuesday === ""){
        	$work_days_tuesday = $default_options['work_days_tuesday'];
        } 
        $work_days_tuesday = intval($work_days_tuesday);
        if($work_days_tuesday != 1){
        	$days_off[] = "Tuesday";
        }

        $work_days_wednesday = get_option($this->option_name)['work_days_wednesday'];
        if(!isset($work_days_wednesday) || $work_days_wednesday === ""){
        	$work_days_wednesday = $default_options['work_days_wednesday'];
        } 
        $work_days_wednesday = intval($work_days_wednesday);
        if($work_days_wednesday != 1){
        	$days_off[] = "Wednesday";
        }

        $work_days_thursday = get_option($this->option_name)['work_days_thursday'];
        if(!isset($work_days_thursday) || $work_days_thursday === ""){
        	$work_days_thursday = $default_options['work_days_thursday'];
        } 
        $work_days_thursday = intval($work_days_thursday);
        if($work_days_thursday != 1){
        	$days_off[] = "Thursday";
        }

        $work_days_friday = get_option($this->option_name)['work_days_friday'];
        if(!isset($work_days_friday) || $work_days_friday === ""){
        	$work_days_friday = $default_options['work_days_friday'];
        } 
        $work_days_friday = intval($work_days_friday);
        if($work_days_friday != 1){
        	$days_off[] = "Friday";
        }

        $work_days_saturday = get_option($this->option_name)['work_days_saturday'];
        if(!isset($work_days_saturday) || $work_days_saturday === ""){
        	$work_days_saturday = $default_options['work_days_saturday'];
        } 
        $work_days_saturday = intval($work_days_saturday);
        if($work_days_saturday != 1){
        	$days_off[] = "Saturday";
        }

        $work_days_sunday = get_option($this->option_name)['work_days_sunday'];
        if(!isset($work_days_sunday) || $work_days_sunday === ""){
        	$work_days_sunday = $default_options['work_days_sunday'];
        } 
        $work_days_sunday = intval($work_days_sunday);
        if($work_days_sunday != 1){
        	$days_off[] = "Sunday";
        }

        return $days_off;
       	/*
		array(6) { [0]=> string(7) "Tuesday" [1]=> string(9) "Wednesday" [2]=> string(8) "Thursday" [3]=> string(6) "Friday" [4]=> string(8) "Saturday" [5]=> string(6) "Sunday" }
       	*/
    }

    function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

    // check for on how many days ahead visitor can book table in restaurant
    function daysAhead(){
    	$days_ahead = get_option($this->option_name)['days_ahead'];
        if(!isset($days_ahead) || empty($days_ahead)){
        	$days_ahead = $default_options['days_ahead'];
        } 
        $days_ahead = intval($days_ahead);

        return $days_ahead;
        /*
		int(20)
        */
    }

    // check if restaurant work on each day of the week and in which time
    function timesOff(){
    	$times_off = array();

        $work_days_monday = get_option($this->option_name)['work_days_monday_time_start'];
        $work_days_monday_end = get_option($this->option_name)['work_days_monday_time_end'];
        if(!isset($work_days_monday) || $work_days_monday === ""){
        	$work_days_monday = $default_options['work_days_monday_time_start'];
        } 
        if(!isset($work_days_monday_end) || $work_days_monday_end === ""){
        	$work_days_monday_end = $default_options['work_days_monday_time_end'];
        } 
        if(isset(get_option($this->option_name)['work_days_monday_more']) && !empty(get_option($this->option_name)['work_days_monday_more'])){
            $work_days_monday_more = get_option($this->option_name)['work_days_monday_time_start_more'];
            $work_days_monday_end_more = get_option($this->option_name)['work_days_monday_time_end_more'];
            if(!isset($work_days_monday_more) || $work_days_monday_more === ""){
                $work_days_monday_more = $default_options['work_days_monday_time_start_more'];
            } 
            if(!isset($work_days_monday_end_more) || $work_days_monday_end_more === ""){
                $work_days_monday_end_more = $default_options['work_days_monday_time_end_more'];
            }
            $times_off[0]['time_start_more'] = $work_days_monday_more;
            $times_off[0]['time_end_more'] = $work_days_monday_end_more;
            $times_off[0]['day'] = "Monday";
        }
        $times_off[0]['time_start'] = $work_days_monday;
        $times_off[0]['time_end'] = $work_days_monday_end;
        $times_off[0]['day'] = "Monday";
       

        $work_days_tuesday = get_option($this->option_name)['work_days_tuesday_time_start'];
        $work_days_tuesday_end = get_option($this->option_name)['work_days_tuesday_time_end'];
        if(!isset($work_days_tuesday) || $work_days_tuesday === ""){
        	$work_days_tuesday = $default_options['work_days_tuesday_time_start'];
        } 
        if(!isset($work_days_tuesday_end) || $work_days_tuesday_end === ""){
        	$work_days_tuesday_end = $default_options['work_days_tuesday_time_end'];
        } 
        if(isset(get_option($this->option_name)['work_days_tuesday_more']) && !empty(get_option($this->option_name)['work_days_tuesday_more'])){
            $work_days_tuesday_more = get_option($this->option_name)['work_days_tuesday_time_start_more'];
            $work_days_tuesday_end_more = get_option($this->option_name)['work_days_tuesday_time_end_more'];
            if(!isset($work_days_tuesday_more) || $work_days_tuesday_more === ""){
                $work_days_tuesday_more = $default_options['work_days_tuesday_time_start_more'];
            } 
            if(!isset($work_days_tuesday_end_more) || $work_days_tuesday_end_more === ""){
                $work_days_tuesday_end_more = $default_options['work_days_tuesday_time_end_more'];
            }
            $times_off[2]['time_start_more'] = $work_days_tuesday_more;
            $times_off[2]['time_end_more'] = $work_days_tuesday_end_more;
            $times_off[2]['day'] = "Tuesday";
        }
        $times_off[2]['time_start'] = $work_days_tuesday;
        $times_off[2]['time_end'] = $work_days_tuesday_end;
        $times_off[2]['day'] = "Tuesday";
      

        $work_days_wednesday = get_option($this->option_name)['work_days_wednesday_time_start'];
        $work_days_wednesday_end = get_option($this->option_name)['work_days_wednesday_time_end'];
        if(!isset($work_days_wednesday) || $work_days_wednesday === ""){
        	$work_days_wednesday = $default_options['work_days_wednesday_time_start'];
        } 
        if(!isset($work_days_wednesday_end) || $work_days_wednesday_end === ""){
        	$work_days_wednesday_end = $default_options['work_days_wednesday_time_end'];
        } 
        if(isset(get_option($this->option_name)['work_days_wednesday_more']) && !empty(get_option($this->option_name)['work_days_wednesday_more'])){
            $work_days_wednesday_more = get_option($this->option_name)['work_days_wednesday_time_start_more'];
            $work_days_wednesday_end_more = get_option($this->option_name)['work_days_wednesday_time_end_more'];
            if(!isset($work_days_wednesday_more) || $work_days_wednesday_more === ""){
                $work_days_wednesday_more = $default_options['work_days_wednesday_time_start_more'];
            } 
            if(!isset($work_days_wednesday_end_more) || $work_days_wednesday_end_more === ""){
                $work_days_wednesday_end_more = $default_options['work_days_wednesday_time_end_more'];
            }
            $times_off[4]['time_start_more'] = $work_days_wednesday_more;
            $times_off[4]['time_end_more'] = $work_days_wednesday_end_more;
            $times_off[4]['day'] = "Wednesday";
        }
        $times_off[4]['time_start'] = $work_days_wednesday;
        $times_off[4]['time_end'] = $work_days_wednesday_end;
        $times_off[4]['day'] = "Wednesday";
        

        $work_days_thursday = get_option($this->option_name)['work_days_thursday_time_start'];
        $work_days_thursday_end = get_option($this->option_name)['work_days_thursday_time_end'];
        if(!isset($work_days_thursday) || $work_days_thursday === ""){
        	$work_days_thursday = $default_options['work_days_thursday_time_start'];
        } 
        if(!isset($work_days_thursday_end) || $work_days_thursday_end === ""){
        	$work_days_thursday_end = $default_options['work_days_thursday_time_end'];
        }
        if(isset(get_option($this->option_name)['work_days_thursday_more']) && !empty(get_option($this->option_name)['work_days_thursday_more'])){
            $work_days_thursday_more = get_option($this->option_name)['work_days_thursday_time_start_more'];
            $work_days_thursday_end_more = get_option($this->option_name)['work_days_thursday_time_end_more'];
            if(!isset($work_days_thursday_more) || $work_days_thursday_more === ""){
                $work_days_thursday_more = $default_options['work_days_thursday_time_start_more'];
            } 
            if(!isset($work_days_thursday_end_more) || $work_days_thursday_end_more === ""){
                $work_days_thursday_end_more = $default_options['work_days_thursday_time_end_more'];
            }
            $times_off[6]['time_start_more'] = $work_days_thursday_more;
            $times_off[6]['time_end_more'] = $work_days_thursday_end_more;
            $times_off[6]['day'] = "Thursday";
        }
        $times_off[6]['time_start'] = $work_days_thursday;
        $times_off[6]['time_end'] = $work_days_thursday_end;
        $times_off[6]['day'] = "Thursday";
       

        $work_days_friday = get_option($this->option_name)['work_days_friday_time_start'];
        $work_days_friday_end = get_option($this->option_name)['work_days_friday_time_end'];
        if(!isset($work_days_friday) || $work_days_friday === ""){
        	$work_days_friday = $default_options['work_days_friday_time_start'];
        } 
        if(!isset($work_days_friday_end) || $work_days_friday_end === ""){
        	$work_days_friday_end = $default_options['work_days_friday_time_end'];
        } 
        if(isset(get_option($this->option_name)['work_days_friday_more']) && !empty(get_option($this->option_name)['work_days_friday_more'])){
            $work_days_friday_more = get_option($this->option_name)['work_days_friday_time_start_more'];
            $work_days_friday_end_more = get_option($this->option_name)['work_days_friday_time_end_more'];
            if(!isset($work_days_friday_more) || $work_days_friday_more === ""){
                $work_days_friday_more = $default_options['work_days_friday_time_start_more'];
            } 
            if(!isset($work_days_friday_end_more) || $work_days_friday_end_more === ""){
                $work_days_friday_end_more = $default_options['work_days_friday_time_end_more'];
            }
            $times_off[8]['time_start_more'] = $work_days_friday_more;
            $times_off[8]['time_end_more'] = $work_days_friday_end_more;
            $times_off[8]['day'] = "Friday";
        }
        $times_off[8]['time_start'] = $work_days_friday;
        $times_off[8]['time_end'] = $work_days_friday_end;
        $times_off[8]['day'] = "Friday";
      

        $work_days_saturday = get_option($this->option_name)['work_days_saturday_time_start'];
        $work_days_saturday_end = get_option($this->option_name)['work_days_saturday_time_end'];
        if(!isset($work_days_saturday) || $work_days_saturday === ""){
        	$work_days_saturday = $default_options['work_days_saturday_time_start'];
        } 
        if(!isset($work_days_saturday_end) || $work_days_saturday_end === ""){
        	$work_days_saturday_end = $default_options['work_days_saturday_time_end'];
        } 
        if(isset(get_option($this->option_name)['work_days_saturday_more']) && !empty(get_option($this->option_name)['work_days_saturday_more'])){
            $work_days_saturday_more = get_option($this->option_name)['work_days_saturday_time_start_more'];
            $work_days_saturday_end_more = get_option($this->option_name)['work_days_saturday_time_end_more'];
            if(!isset($work_days_saturday_more) || $work_days_saturday_more === ""){
                $work_days_saturday_more = $default_options['work_days_saturday_time_start_more'];
            } 
            if(!isset($work_days_saturday_end_more) || $work_days_saturday_end_more === ""){
                $work_days_saturday_end_more = $default_options['work_days_saturday_time_end_more'];
            }
            $times_off[10]['time_start_more'] = $work_days_saturday_more;
            $times_off[10]['time_end_more'] = $work_days_saturday_end_more;
            $times_off[10]['day'] = "Saturday";
        }
        $times_off[10]['time_start'] = $work_days_saturday;
        $times_off[10]['time_end'] = $work_days_saturday_end;
        $times_off[10]['day'] = "Saturday";
        

        $work_days_sunday = get_option($this->option_name)['work_days_sunday_time_start'];
        $work_days_sunday_end = get_option($this->option_name)['work_days_sunday_time_end'];
        if(!isset($work_days_sunday) || $work_days_sunday === ""){
        	$work_days_sunday = $default_options['work_days_sunday_time_start'];
        } 
        if(!isset($work_days_sunday_end) || $work_days_sunday_end === ""){
        	$work_days_sunday_end = $default_options['work_days_sunday_time_end'];
        } 
        if(isset(get_option($this->option_name)['work_days_sunday_more']) && !empty(get_option($this->option_name)['work_days_sunday_more'])){
            $work_days_sunday_more = get_option($this->option_name)['work_days_sunday_time_start_more'];
            $work_days_sunday_end_more = get_option($this->option_name)['work_days_sunday_time_end_more'];
            if(!isset($work_days_sunday_more) || $work_days_sunday_more === ""){
                $work_days_sunday_more = $default_options['work_days_sunday_time_start_more'];
            } 
            if(!isset($work_days_sunday_end_more) || $work_days_sunday_end_more === ""){
                $work_days_sunday_end_more = $default_options['work_days_sunday_time_end_more'];
            }
            $times_off[12]['time_start_more'] = $work_days_sunday_more;
            $times_off[12]['time_end_more'] = $work_days_sunday_end_more;
            $times_off[12]['day'] = "Sunday";
        }
        $times_off[12]['time_start'] = $work_days_sunday;
        $times_off[12]['time_end'] = $work_days_sunday_end;
        $times_off[12]['day'] = "Sunday";
        
        return $times_off;
        /*
		array(7) { [0]=> array(3) { ["time_start"]=> string(5) "09:30" ["time_end"]=> string(5) "23:00" ["day"]=> string(6) "Monday" } [1]=> array(3) { ["time_start"]=> string(5) "10:00" ["time_end"]=> string(5) "22:00" ["day"]=> string(7) "Tuesday" } [2]=> array(3) { ["time_start"]=> string(5) "09:00" ["time_end"]=> string(5) "23:00" ["day"]=> string(9) "Wednesday" } [3]=> array(3) { ["time_start"]=> string(5) "00:15" ["time_end"]=> string(5) "00:15" ["day"]=> string(8) "Thursday" } [4]=> array(3) { ["time_start"]=> string(5) "00:15" ["time_end"]=> string(5) "00:15" ["day"]=> string(6) "Friday" } [5]=> array(3) { ["time_start"]=> string(5) "00:15" ["time_end"]=> string(5) "00:15" ["day"]=> string(8) "Saturday" } [6]=> array(3) { ["time_start"]=> string(5) "00:15" ["time_end"]=> string(5) "00:15" ["day"]=> string(6) "Sunday" } }
        */
    }



	function rest_booking( $atts, $content ) {

        extract(
            shortcode_atts(
                array(
                    'vrr_shortcode_class'   => '',
                    'vrr_shortcode_id' => '',
                ), 
                $atts
            )
        );

	    $options = get_option($this->option_name);

	    $time_all = INFO::$TIME_ALL;
        $people_all = array(
            1,2,3,4,5,6,7,8,9,10,
        );
	    $default_options = INFO::$DEFAULT_OPTIONS;
        $output = "";

	    if($options){

            $form_message = '';
            if(isset($options['form_message']) && !empty($options['form_message'])){
                $form_message = $options['form_message'];
            } 

            $form_confirm = '';
            if(isset($options['form_confirm']) && !empty($options['form_confirm'])){
                if(isset($options['form_confirm_text']) && !empty($options['form_confirm_text'])){
                    $form_confirm .= '<div class="vrr-input-wrap">';
                        $form_confirm .= '<div class="vrr-input-inner">';

                            $form_confirm .= '<div class="vrr-confirm-checkbox">';
                                $form_confirm .= '<div class="vrr-confirm-checkbox-wrap">';
                                    $form_confirm .= '<input type="checkbox" class="vrr-checkbox" id="visual_restaurant_reservation_form_confirm" name="visual_restaurant_reservation_form_confirm" value="1">';
                                $form_confirm .= '</div>';
                                $form_confirm .= '<div class="vrr-confirm-checkbox-text-wrap">';
                                    $form_confirm .= '<label class="vrr-confirm-label" for="visual_restaurant_reservation_form_confirm">';
                                        $form_confirm .= $options['form_confirm_text'];
                                    $form_confirm .= '</label>';
                                $form_confirm .= '</div>';
                            $form_confirm .= '</div>';

                            $form_confirm .= '<div class="vrr-error vrr-error-empty vrr-confirm-error">'.__('Please, check the checkbox','vrr').'</div>';

                        $form_confirm .= '</div>';    
                    $form_confirm .= '</div>'; 
                }
            }
               
            


	    	$postion = urldecode($options['position']);
		    $jsonPosition = json_decode($postion, TRUE);

		    if(isset($options['position']) && !empty($options['position'])){

		    	// get all post of already booked tables
		    	$booked = array();
		    	$args = array(
                    'post_type' => 'vrr',
                    'post_status' => 'reserved',
                    'hide_empty' => false,
                    'numberposts' => -1
                );
                $query = get_posts($args);
                if(isset($query)){
                    $count_exist = count($query);
                    foreach ($query as $key => $value) {
                    	$metadata = get_metadata('post', $value->ID);
                        if(isset($metadata["_visual_restaurant_reservation_time_to"][0]) && !empty($metadata["_visual_restaurant_reservation_time_to"][0])){
                            $booked[] = array(
                                'id' => $metadata["_visual_restaurant_reservation_id"][0],
                                'date' => $metadata["_visual_restaurant_reservation_date"][0],
                                'time' => $metadata["_visual_restaurant_reservation_time"][0],
                                'time_to' => $metadata["_visual_restaurant_reservation_time_to"][0],
                                'post_ID' => $value->ID,
                                'unique' => $metadata["_visual_restaurant_reservation_unique"][0],
                            );
                        } else {
                        	$booked[] = array(
                        		'id' => $metadata["_visual_restaurant_reservation_id"][0],
                        		'date' => $metadata["_visual_restaurant_reservation_date"][0],
                        		'time' => $metadata["_visual_restaurant_reservation_time"][0],
                        		'post_ID' => $value->ID,
                        		'unique' => $metadata["_visual_restaurant_reservation_unique"][0],
                        	);
                        }
                    }
                }
		      	
                function getOption($option, $options){
                    $default_options = INFO::$DEFAULT_OPTIONS;
                    $new_option = '';

                    if(isset($options[$option]) && $options[$option] != ""){
                        $new_option = $options[$option];
                    } else {
                        if(isset($default_options[$option])){
                            $new_option = $default_options[$option];
                        }
                    }
                    return $new_option;
                }

                $color_main = getOption('color_main_main', $options);
                $color_tables = getOption('color_main_tables', $options);
                $color_seats = getOption('color_main_seats', $options);
                $color_max_seats = getOption('color_main_max_seats', $options);
                $color_max_seats_text = getOption('color_main_max_seats_text', $options);
                $color_table_id = getOption('color_main_table_id', $options);
                $color_table_id_text = getOption('color_main_table_id_text', $options);

                $color_datepicker_text_all  = getOption('color_datepicker_datepicker_text_all', $options);
                $color_datepicker = getOption('color_datepicker_datepicker', $options);
                $color_datepicker_text = getOption('color_datepicker_datepicker_text', $options);
                $color_datepicker_disabled = getOption('color_datepicker_datepicker_disabled', $options);
                $color_datepicker_text_disabled= getOption('color_datepicker_datepicker_text_disabled',$options);
                $color_datepicker_active = getOption('color_datepicker_datepicker_active', $options);
                $color_datepicker_text_active = getOption('color_datepicker_datepicker_text_active', $options);
                $color_datepicker_arrow = getOption('color_datepicker_datepicker_arrow', $options);
                $color_datepicker_arrow_hover = getOption('color_datepicker_datepicker_arrow_hover', $options);
                $color_datepicker_arrow_background = getOption('color_datepicker_datepicker_arrow_background', $options);
                $color_datepicker_arrow_disabled = getOption('color_datepicker_datepicker_arrow_disabled', $options);
                $color_datepicker_arrow_background_disabled = getOption('color_datepicker_datepicker_arrow_background_disabled', $options);

                $color_form = getOption('color_form_form', $options);
                $color_small_titles = getOption('color_form_small_titles', $options);
                $color_small_descriptions = getOption('color_form_small_descriptions', $options);
                $color_big_titles = getOption('color_form_big_titles', $options);
                $color_booking_title = getOption('color_form_booking_title', $options);
                $color_book_now = getOption('color_form_book_now', $options);
                $color_book_now_text = getOption('color_form_book_now_text', $options);
                $color_inputs = getOption('color_form_inputs', $options);
                $color_inputs_text = getOption('color_form_inputs_text', $options);
                $color_error = getOption('color_form_error', $options);
                $color_error_text = getOption('color_form_error_text', $options);
                $color_message = getOption('color_form_message', $options);
                $color_message_text = getOption('color_form_message_text', $options);
                
                

                $output .= '<style type="text/css">';
                    if($color_main != ""){
                        $output .= '.vrr-tables-datapicker-wrap, 
                        .vrr-element-table:hover, 
                        .vrr-tables-wrap, 
                        .vrr-tables-wrap-third .vrr-input-wrap.vrr-calendar-left, 
                        .vrr-tables-wrap-default .vrr-form-booking, 
                        .vrr-tables-wrap-default .vrr-input-wrap.vrr-calendar-left{
                            background-color: '.$color_main.'!important;
                        }';
                    }

                    if($color_tables != ""){
                        $output .= '.vrr-element .vrr-element-table{
                            background-color: '.$color_tables.'!important;
                        }';
                    }

                    if($color_seats != ""){
                        $output .= '.vrr-element .vrr-element-seats-wrap .vrr-element-seat{
                            background-color: '.$color_seats.'!important;
                        }';
                    }

                    if($color_max_seats != ""){
                        $output .= '.vrr-element-seats{
                            background-color: '.$color_max_seats.'!important;
                        }';
                    }

                    if($color_max_seats_text != ""){
                        $output .= '.vrr-element-seats{
                            color: '.$color_max_seats_text.'!important;
                        }';
                    }

                    if($color_table_id != ""){
                        $output .= '.vrr-element .vrr-element-id{
                            background-color: '.$color_table_id.'!important;
                        }';
                    }

                    if($color_table_id_text != ""){
                        $output .= '.vrr-element .vrr-element-id{
                            color: '.$color_table_id_text.'!important;
                        }';
                    }

                    if($color_book_now != ''){
                        $output .= '.vrr-send-booking{
                            background-color: '.$color_book_now.'!important;
                            background: none;
                        }';
                    }

                    if($color_book_now_text != ""){
                        $output .= '.vrr-send-booking{
                            color: '.$color_book_now_text.'!important;
                        }';
                    }

                    if($color_form != ""){
                        $output .= '.vrr-tables-wrap-default .vrr-order-wrap,
                        .vrr-form-booking-second,
                        .vrr-form-booking-third {
                            background-color: '.$color_form.'!important;
                        }';
                    }

                    if($color_big_titles != ""){
                        $output .= '.vrr-input-label-big{
                            color: '.$color_big_titles.'!important;
                        }';
                    }
                    if($color_booking_title){
                        $output .= '.vrr-form-booking-title{
                            color: '.$color_booking_title.'!important;
                        }';
                    }

                    if($color_small_titles != ""){
                        $output .= '.vrr-input-label{
                            color: '.$color_small_titles.'!important;
                        }';
                    }

                    if($color_small_descriptions != ""){
                        $output .= '.vrr-small-text, .vrr-confirm-checkbox-text-wrap .vrr-confirm-label a, .vrr-confirm-checkbox-text-wrap .vrr-confirm-label{
                            color: '.$color_small_descriptions.'!important;
                        }';
                    }

                    if($color_datepicker_text_all !=''){
                        $output .= '.ui-datepicker .ui-datepicker-title .ui-datepicker-month, .ui-datepicker .ui-datepicker-title .ui-datepicker-year, .ui-datepicker .ui-datepicker-calendar th span{
                            color: '.$color_datepicker_text_all.'!important;
                        }';
                    }
                    if($color_datepicker != ""){
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-calendar td:not(.ui-state-disabled){
                            background-color: '.$color_datepicker.'!important;
                        }';
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-calendar td a::before{
                            border-color: '.$color_datepicker.'!important;
                        }';
                        $output .= '.visual_restaurant_reservation_datepicker td.ui-datepicker-current-day.ui-datepicker-today, .visual_restaurant_reservation_datepicker td.ui-datepicker-current-day.ui-datepicker-today:hover{
                            background-color: '.$color_datepicker.'!important;
                        }';
                    }
                    if($color_datepicker_text != ""){
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-calendar td a{
                            color: '.$color_datepicker_text.'!important;
                        }';
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-calendar td a, 
                        .visual_restaurant_reservation_datepicker, 
                        // .visual_restaurant_reservation_datepicker .ui-datepicker-prev.ui-state-disabled, 
                        // .visual_restaurant_reservation_datepicker .ui-datepicker-next.ui-state-disabled,
                        .visual_restaurant_reservation_datepicker .ui-datepicker-next,
                        .visual_restaurant_reservation_datepicker .ui-datepicker-prev {
                            color: '.$color_datepicker_text.'!important;
                        }';
                    }

                    if($color_datepicker_disabled != ""){
                        $output .= '.visual_restaurant_reservation_datepicker td.ui-state-disabled:not(.ui-datepicker-other-month){
                            background-color: '.$color_datepicker_disabled.'!important;
                        }';
                        $output .= '.visual_restaurant_reservation_datepicker td.ui-state-disabled:not(.ui-datepicker-other-month):after{
                            background-color: '.$color_datepicker_disabled.'!important;
                        }';
                        
                    }
                    if($color_datepicker_text_disabled != ""){
                        $output .= '.visual_restaurant_reservation_datepicker td.ui-state-disabled:not(.ui-datepicker-other-month) span{
                            color: '.$color_datepicker_text_disabled.'!important;
                        }';
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-prev.ui-state-disabled, .visual_restaurant_reservation_datepicker .ui-datepicker-next.ui-state-disabled{
                            color: '.$color_datepicker_text_disabled.'!important;
                        }';
                    }

                    if($color_datepicker_active != ""){
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-calendar td.ui-datepicker-current-day:not(.ui-state-disabled){
                            background-color: '.$color_datepicker_active.'!important;
                        }';
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-calendar td.ui-datepicker-current-day:not(.ui-state-disabled) a::before{
                            border-color: '.$color_datepicker_active.'!important;
                        }';
                    }
                    if($color_datepicker_text_active != ""){
                        $output .= '.visual_restaurant_reservation_datepicker td.ui-datepicker-current-day a{
                            color: '.$color_datepicker_text_active.'!important;
                        }';
                    }
                    if($color_datepicker_arrow != ""){
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-prev:not(.ui-state-disabled), .visual_restaurant_reservation_datepicker .ui-datepicker-next:not(.ui-state-disabled){
                            color: '.$color_datepicker_arrow.'!important;
                        }';
                    }
                    if($color_datepicker_arrow_hover != ""){
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-prev:hover, .visual_restaurant_reservation_datepicker .ui-datepicker-next:not(.ui-state-disabled):hover{
                            background-color: '.$color_datepicker_arrow_hover.'!important;
                        }';
                    }
                    if($color_datepicker_arrow_background != ""){
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-prev:not(.ui-state-disabled), .visual_restaurant_reservation_datepicker .ui-datepicker-next:not(.ui-state-disabled){
                            background-color: '.$color_datepicker_arrow_background.'!important;
                        }';
                    }
                    if($color_datepicker_arrow_disabled != ""){
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-prev.ui-state-disabled, .visual_restaurant_reservation_datepicker .ui-datepicker-next.ui-state-disabled{
                            color: '.$color_datepicker_arrow_disabled.'!important;
                        }';
                    }
                    if($color_datepicker_arrow_background_disabled != ""){
                        $output .= '.visual_restaurant_reservation_datepicker .ui-datepicker-prev.ui-state-disabled, .visual_restaurant_reservation_datepicker .ui-datepicker-next.ui-state-disabled, .visual_restaurant_reservation_datepicker .ui-datepicker-prev.ui-state-disabled:hover, .visual_restaurant_reservation_datepicker .ui-datepicker-next.ui-state-disabled:hover{
                            background-color: '.$color_datepicker_arrow_background_disabled.'!important;
                        }';
                    }
                    

                    if($color_inputs != ""){
                        $output .= '.vrr-tables-wrap .select2-container--default .select2-selection--single,
                        .vrr-tables-wrap input,
                        .vrr-tables-wrap select {
                            background-color: '.$color_inputs.'!important;
                        }';
                    }

                    if($color_inputs_text != ""){
                        $output .= '.select2-container--default .select2-selection--single .select2-selection__rendered,
                        .vrr-tables-wrap input,
                        .vrr-tables-wrap select{
                            color: '.$color_inputs_text.'!important;
                        }';
                    }

                    if($color_error != ""){
                        $output .= '.vrr-error{
                            background-color: '.$color_error.'!important;
                        }';
                        $output .= '.vrr-error:before{
                            border-color: transparent '.$color_error.' transparent transparent!important;
                        }';
                    }

                    if($color_error_text != ""){
                        $output .= '.vrr-error{
                            color: '.$color_error_text.'!important;
                        }';
                    }

                    if($color_message != ""){
                        $output .= '.vrr-message{
                            background-color: '.$color_message.'!important;
                        }';
                    }

                    if($color_message_text != ""){
                        $output .= '.vrr-message{
                            color: '.$color_message_text.'!important;
                        }';
                    }

                $output .= '</style>';

                // test 100% wrap
                $output .= '<div class="vrr-tables-wrap-all">';


                // shortcode output html
                // 2
                if(isset($options['shortcode_layout']) && !empty($options['shortcode_layout']) && $options['shortcode_layout'] == '2'){
                    
                    $output .= '<div class="vrr-tables-wrap vrr-tables-size-'.$options['canvas_elements_size'].' vrr-tables-wrap-second '.$vrr_shortcode_class.'"  id="'.$vrr_shortcode_id.'">';

                        $output .= '<div class="vrr-relative">';

                        $output .= '<div class="vrr-tables-datapicker-wrap">';
                            $output .= '<div class="vrr-tables-datapicker vrr-tables-datapicker-block">';
                                // data-date
                                $data_exclude = array();
                                // data-time
                                $time_exclude = array();

                                $days_ahead = $this->daysAhead();
                                $days_off = $this->daysOff();
                                $times_off = $this->timesOff();



                                // create array with all days before last day - $days_ahead. In number and string view
                                $weak_days_ahead = array();
                                for($i = 0; $i < $days_ahead; $i++) {
                                    $weak_days_ahead[$i]['number'] = date("m/d/Y", strtotime('+'. $i .' days'));
                                    $weak_days_ahead[$i]['string'] = date("l", strtotime('+'. $i .' days'));
                                }
                                
                                
                                // foreach day in allowed period check to exclude times and days from settings of plugin
                                $not_work_time_array = array();
                                $key_start_time = 0;
                                $key_end_time = 0;
                                $time_all_fake = array();
                                $time_all_fake_more = array();
                                $key_not_work_time = 0;
                                foreach ($weak_days_ahead as $key => $value) {
                                    $value_string = $value['string'];
                                    $value_number = $value['number'];
                                    $time_all_fake = $time_all;
                                    $time_all_fake_more = $time_all;
                                    $key_start_time = null;
                                    $key_end_time = null;
                                    $key_start_time_more = null;
                                    $key_end_time_more = null;
                                    if(in_array( $value['string'], $days_off)){ // add days to exclude because of days of the week
                                        $data_exclude[] = $value['number'];
                                    } else { // add times to exclude because of days of the week and work time in this day
                                        if($times_off){
                                            foreach ($times_off as $key => $day) {
                                                if($day['day'] == $value['string']){
                                                    foreach ($time_all as $key => $time_all_value) {
                                                        if(isset($day['time_start']) && !empty($day['time_start'])){
                                                            if($time_all_value == $day['time_start']){
                                                                $key_start_time = $key;
                                                            }
                                                        } 
                                                        if(isset($day['time_end']) && !empty($day['time_end'])){
                                                            if($time_all_value == $day['time_end']){
                                                                $key_end_time = $key;
                                                            }
                                                        } 
                                                        if(isset($day['time_start_more']) && !empty($day['time_start_more'])){
                                                            if($time_all_value == $day['time_start_more']){
                                                                $key_start_time_more = $key;
                                                            }
                                                        } 
                                                        if(isset($day['time_end_more']) && !empty($day['time_end_more'])){
                                                            if($time_all_value == $day['time_end_more']){
                                                                $key_end_time_more = $key;
                                                            }
                                                        } 
                                                    }
                                                }
                                            }

                                            // first times
                                            $length = $key_end_time - $key_start_time;
                                            if($length < 0){
                                                $length = $key_end_time + count($time_all) - $key_start_time;
                                            }
                                            array_splice($time_all_fake, $key_start_time, $length+1);

                                            
                                            // second times
                                            if(isset($key_end_time_more) && !empty($key_end_time_more) 
                                            && isset($key_start_time_more) && !empty($key_start_time_more)
                                            && $key_start_time_more != null && $key_end_time_more != null ){
                                                $length_more = $key_end_time_more - $key_start_time_more;
                                                if($length_more < 0){
                                                    $length_more = $key_end_time_more + count($time_all) - $key_start_time_more;
                                                }
                                                $time_all_fake_more = array_slice($time_all_fake_more, $key_start_time_more, $length_more+1);

                                                if(isset($time_all_fake_more) && !empty($time_all_fake_more)){
                                                    $time_all_new = array(); 
                                                    foreach ($time_all_fake_more as $val) { 
                                                      if (($key = array_search($val, $time_all_fake, TRUE))!==false) { 
                                                         $time_all_new[] = $val; 
                                                         unset($time_all_fake[$key]); 
                                                      } 
                                                    } 
                                                }


                                            }

                                            // add times to exclude
                                            foreach ($time_all_fake as $key => $value_fake) {
                                                $time_exclude[] = array(
                                                    'date' => $value_number,
                                                    'time' => $value_fake
                                                );
                                            }

                                        }
                                    }
                                }


                                // convert to json string
                                $data_exclude = urlencode(json_encode($data_exclude));
                                $time_exclude = urlencode(json_encode($time_exclude));

                                $data_maxdate = '';
                                $data_maxdate = get_option($this->option_name)['days_ahead'];
                                if(!isset($data_maxdate) || $data_maxdate === ""){
                                    $data_maxdate = $default_options['days_ahead'];
                                } 
                                $data_maxdate = intval($data_maxdate);

                                $output .= '<div class="vrr-input-wrap vrr-input-wrap-datepicker">';
                                    $output .= '<label class="vrr-input-label-big">'.__('Choose the Date','vrr').'</label>';
                                    $output .= '<div data-maxdate="'.$data_maxdate.'" data-time="'.$time_exclude.'" data-date="'.$data_exclude.'" id="visual_restaurant_reservation_datepicker" class="visual_restaurant_reservation_datepicker"></div>';
                                    $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, pick the date','vrr').'</div>';
                                $output .= '</div>';

                                $output .= '<div class="vrr-input-wrap vrr-input-wrap-time" style="display: none;">';

                                    $output .= '<div class="vrr-input-wrap">';
                                        $output .= '<label class="vrr-input-label-big">'.__( 'Select the start time', 'vrr' ).'</label>';
                                        $output .= '<div class="vrr-input-inner">';
                                            $output .= '<select class="vrr-select" data-placeholder="'.__('Choose time','vrr').'" id="visual_restaurant_reservation_time_fake" name="visual_restaurant_reservation_time_fake">';
                                                foreach ($time_all as $key => $value) {
                                                    $output .= '<option value="'.$value.'">'.$value.'</option>';
                                                } 
                                            $output .= '</select>';
                                            $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, chose time','vrr').'</div>';
                                        $output .= '</div>';
                                    $output .= '</div>';

                                    $show_time_to = getOption('show_time_to', $options);
                                    if(isset($show_time_to) && $show_time_to == '1'){
                                        $output .= '<div class="vrr-input-wrap vrr-input-wrap-time-to">';
                                            $output .= '<label class="vrr-input-label-big">'.__( 'Select the end time', 'vrr' ).'</label>';
                                            $output .= '<div class="vrr-input-inner">';
                                                $output .= '<select class="vrr-select" data-placeholder="'.__('Choose time','vrr').'" id="visual_restaurant_reservation_time_to" name="visual_restaurant_reservation_time_to">';
                                                    foreach ($time_all as $key => $value) {
                                                        $output .= '<option value="'.$value.'">'.$value.'</option>';
                                                    } 
                                                $output .= '</select>';
                                                $output .= '<div class="vrr-error vrr-error-time" data-error-text="'.__('Invalid end time','vrr').'">'.__('Invalid end time','vrr').'</div>';
                                            $output .= '</div>';
                                        $output .= '</div>';
                                    }

                                $output .= '</div>';

                                

                            $output .= '</div>';
                        $output .= '</div>';

                        $output .= '<div class="vrr-draggable-wrap">';
                            $draggable_style = "";
                            $draggable_style .= ' style="';
                            if($options['canvas_width']){ 
                                $canvas_width_em = (intval($options['canvas_width'])/10).'em;';
                                $draggable_style .= 'width: '.$canvas_width_em;
                            } 
                            if($options['canvas_height']){ 
                                $canvas_height_em = (intval($options['canvas_height'])/10).'em;';
                                $draggable_style .= 'height: '.$canvas_height_em;
                            } 
                            if($options['background-canvas']){  
                                $draggable_style .= 'background-image: url('.$options['background-canvas'] .');';
                            } 
                            $draggable_style .= '"';
                            $output .= '<div data-width="'.intval($options['canvas_width']).'" class="vrr-draggable vrr-loading" '.$draggable_style.'>';
                            $output .= '';
                                $i_array = 0;
                                if(isset($jsonPosition) && !empty($jsonPosition)){
                                    // foreach element
                                    foreach ($jsonPosition as $key => $val) {            
                                        if(is_array($val)) { 
                                            $w = $val['x1']-$val['x'];
                                            $h = $val['y1']-$val['y'];
                                            $class = "";
                                            if($val['class'] != ""){
                                                $class = $val['class'];
                                            }
                                            // style
                                            $el_left = $val['x'];
                                            $el_top = $val['y'];
                                            $element_style = "left: ".$val['x']."px; top: ".$val['y']."px;";
                                            $element_style_table = '';
                                            if($options['background-table']){ 
                                                $element_style_table .= " background-image: url(". $options['background-table'].");";
                                            } 
                                            

                                            // data-date
                                            $data_exclude = array();
                                            // data-time
                                            $time_exclude = array();


                                            // create date array to count times booked in element
                                            $new_array = array();
                                            foreach ($booked as $key => $value) {
                                                if($val['id'] === $value['id']){
                                                    if(!array_key_exists($value['date'], $new_array)){
                                                        $i = 0;
                                                        foreach($booked as $key1 => $value1) {
                                                            if($val['id'] === $value1['id']){
                                                                if (isset($value1['date']) && $value1['date'] === $value['date']) {
                                                                    $i++;
                                                                }     
                                                            }       
                                                        }
                                                        $new_array[$value['date']] = $i;
                                                    }
                                                }
                                            }

                                            // add days to exclude because of booked elements
                                            foreach ($new_array as $key => $value) {
                                                if($value >= count($time_all)){
                                                    $data_exclude[] = $key;
                                                }
                                            }

                                            // add times to exclude because of booked elements
                                            $all_time_booked = 0;
                                            foreach ($booked as $key => $value) {
                                                if($val['id'] == $value['id']){
                                                    $time_exclude[] = array(
                                                        'date' => $value['date'],
                                                        'time' => $value['time']
                                                    );
                                                    $booked_time = array_search($value['time'], $time_all);

                                                    $booked_time_difference = '';
                                                    if(isset($value['time_to'])){
                                                        $booked_time_to = array_search($value['time_to'], $time_all);
                                                        if($booked_time_to > $booked_time){
                                                            $booked_time_difference = $booked_time_to - $booked_time;
                                                        }
                                                    }

                                                    if($booked_time){
                                                        if(isset($options['table_booked_time']) && $options['table_booked_time'] != 0 && $booked_time_difference == ""){
                                                            for ($i=1; $i <= $options['table_booked_time']*2-1; $i++) { 
                                                                
                                                                if(isset($time_all[$booked_time+$i]) && !empty($time_all[$booked_time+$i])){
                                                                    $time_ahead = $time_all[$booked_time+$i];
                                                                    $time_exclude[] = array(
                                                                        'date' => $value['date'],
                                                                        'time' => $time_ahead
                                                                    );
                                                                }
                                                            }
                                                        } else if($booked_time_difference != ""){
                                                            for ($i=1; $i <= $booked_time_difference-1; $i++) {
                                                                if(isset($time_all[$booked_time+$i]) && !empty($time_all[$booked_time+$i])){
                                                                    $time_ahead = $time_all[$booked_time+$i];
                                                                    $time_exclude[] = array(
                                                                        'date' => $value['date'],
                                                                        'time' => $time_ahead
                                                                    );
                                                                }
                                                            }
                                                        }
                                                        if(isset($options['table_before_booked_time']) && $options['table_before_booked_time'] != 0){
                                                            for ($i=1; $i <= $options['table_before_booked_time']*2-1; $i++) { 
                                                                if(isset($time_all[$booked_time-$i]) && !empty($time_all[$booked_time-$i])){
                                                                    $time_ahead = $time_all[$booked_time-$i];
                                                                    $time_exclude[] = array(
                                                                        'date' => $value['date'],
                                                                        'time' => $time_ahead
                                                                    );
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }


                                            // convert to json string
                                            $data_exclude = urlencode(json_encode($data_exclude));
                                            $time_exclude = urlencode(json_encode($time_exclude));


                                            $output .= '<div 
                                                class="vrr-element '.$class.' rotate-'.$val['rotate'].'" 
                                                data-date="'.$data_exclude.'" 
                                                data-time="'.$time_exclude.'" 
                                                data-left="'.$el_left.'" 
                                                data-top="'.$el_top.'" 
                                                data-id="'.$val['id'].'" 
                                                data-unique="'.$val['unique'].'" 
                                                data-seats="'.$val['seats'].'" 
                                                data-max-seats="'.$val['max_seats'].'" 
                                                data-big-side="'. $val['big_side'].'" 
                                                data-type="'. $val['class'].'" 
                                                data-rotate="'. $val['rotate'].'" 
                                                style="'. $element_style.'">
                                                    <div class="vrr-element-table" style="'.$element_style_table.'">
                                                        <div class="vrr-element-id">'. $val['id'].'</div>';
                                                        $show_people = getOption('show_people', $options);
                                                        if(isset($show_people) && $show_people == '1'){
                                                            $output .= '<div class="vrr-element-seats">'. $val['seats'].'</div>';
                                                        }

                                                        $show_seats = getOption('show_seats', $options);
                                                        // var_dump($show_seats);
                                                        // var_dump($options['show_seats']);
                                                        if(isset($show_seats) && $show_seats == '1'){
                                                            $output .= '<div class="vrr-element-seats-wrap">';
                                                                $background_seat = '';
                                                                if($options['background-seat']){ 
                                                                    $background_seat = " background-image: url(". $options['background-seat'].");";
                                                                }
                                                                $seats = intval($val['seats']); 
                                                                for ($i=0; $i < $seats; $i++) { 
                                                                    $output .= '<div class="vrr-element-seat" style="'.$background_seat.'"></div>';
                                                                } 
                                                            $output .= '</div>';
                                                        }
                                                    $output .= '</div>
                                                </div>';

                                            $i_array++;

                                        }
                                    }
                                }
                            $output .= '</div>';

                            

                        $output .= '</div>';    

                        $output .= '<div class="vrr-form-back"></div>';

                        $output .= '<div class="vrr-form-thx">';
                            $output .= '<div class="vrr-close"></div>';
                            $output .= '<div class="vrr-form-thx-title">';
                                if(isset($options['form_thx']) && $options['form_thx'] != ''){
                                    $output .= $options['form_thx'];
                                } else {
                                    $output .= __('Thank You!','vrr').'<br>'.__('We will call you back soon.','vrr');
                                }
                            $output .= '</div>';
                        $output .= '</div>';

                        $output .= '<form class="vrr-form-booking vrr-form-booking-second">';
                            $output .= wp_nonce_field('send_book','_wpnonce',true,false);
                            $output .= '<input type="hidden" name="visual_restaurant_reservation_id" id="visual_restaurant_reservation_id" value="">';
                            $output .= '<input type="hidden" name="visual_restaurant_reservation_unique" id="visual_restaurant_reservation_unique" value="">';
                            $output .= '<input type="hidden" name="visual_restaurant_reservation_seats" id="visual_restaurant_reservation_seats" value="">';
                            $output .= '<div class="vrr-close"></div>';
                            $output .= '<div class="vrr-form-booking-title" data-text="'.__('Book Table','vrr').' #"></div>';

                            $output .= '<input type="hidden" class="vrr-input"  id="visual_restaurant_reservation_date" name="visual_restaurant_reservation_date">';

                            $output .= '<input type="hidden" class="vrr-input"  id="visual_restaurant_reservation_time" name="visual_restaurant_reservation_time">';
                            
                            $shop_people_amount = getOption('shop_people_amount', $options);
                            if(isset($shop_people_amount) && $shop_people_amount == '1'){
                                $output .= '<div class="vrr-input-wrap">';
                                    $output .= '<label class="vrr-input-label">'.__( 'People amount', 'vrr' ).'</label>';
                                    $output .= '<div class="vrr-small-text-wrap">';
                                        $output .= '<span class="vrr-small-text vrr-max-people-amount-text">'.__( 'for this table maximum amount is:', 'vrr' ).'&nbsp;</span><span class="vrr-small-text vrr-max-people-amount"></span>';
                                    $output .= '</div>'; 
                                    $output .= '<div class="vrr-input-inner">';
                                        $output .= '<select class="vrr-people-select" data-placeholder="'.__('Select amount of people','vrr').'" id="visual_restaurant_reservation_people" name="visual_restaurant_reservation_people">';
                                            foreach ($people_all as $key => $value) {
                                                $output .= '<option value="'.$value.'">'.$value.'</option>';
                                            } 
                                        $output .= '</select>';
                                        $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                    $output .= '</div>'; 
                                $output .= '</div>'; 
                            }

                            

                            $output .= '<div class="vrr-input-wrap">';
                                $output .= '<label class="vrr-input-label">'.__( 'Name', 'vrr' ).'</label>';
                                $output .= '<div class="vrr-input-inner">';
                                    $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_name" name="visual_restaurant_reservation_name">';
                                    $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                $output .= '</div>';    
                            $output .= '</div>';    

                            $output .= '<div class="vrr-input-wrap">';
                                $output .= '<label class="vrr-input-label">'.__('Phone Number','vrr').'</label>';
                                $output .= '<div class="vrr-input-inner">';
                                    $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_phone" name="visual_restaurant_reservation_phone">';
                                    $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                    $output .= '<div class="vrr-error vrr-error-phone">'.__('Please, fill correct phone number','vrr').'</div>';
                                $output .= '</div>';
                            $output .= '</div>';    

                            if(isset($options['show_email']) && $options['show_email'] == '1'){
                                $output .= '<div class="vrr-input-wrap">';
                                    $output .= '<label class="vrr-input-label">'.__('E-mail','vrr').'</label>';
                                    $output .= '<div class="vrr-input-inner">';
                                        $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_email" name="visual_restaurant_reservation_email">';
                                        $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                        $output .= '<div class="vrr-error vrr-error-email">'.__('Please, fill correct email','vrr').'</div>';
                                    $output .= '</div>';
                                $output .= '</div>'; 
                            }

                            $output .= $form_confirm;

                            $output .= '<div class="vrr-send-booking-wrap">';
                                $output .= '<div>';
                                $output .= '<div class="vrr-send-booking">'.__('Book Now','vrr').'</div>';
                                $output .= '</div>';
                                if($form_message != ''){
                                    $output .= '<div class="vrr-message">'.$form_message.'</div>';
                                }
                                $output .= '<div class="vrr-send-respons-error"></div>';
                            $output .= '</div>';
                        $output .= '</form>';

                        $output .= '</div>';// relative

                    $output .= '</div>'; 

                // 3
                } else if(isset($options['shortcode_layout']) && !empty($options['shortcode_layout']) && $options['shortcode_layout'] == '3'){ // 3

                    $output .= '<div class="vrr-tables-wrap  vrr-tables-size-'.$options['canvas_elements_size'].'  vrr-tables-wrap-third  '.$vrr_shortcode_class.'"  id="'.$vrr_shortcode_id.'">';

                        $output .= '<div class="vrr-form-back"></div>';

                        $output .= '<div class="vrr-form-thx">';
                            $output .= '<div class="vrr-close"></div>';
                            $output .= '<div class="vrr-form-thx-title">';
                                if(isset($options['form_thx']) && $options['form_thx'] != ''){
                                    $output .= $options['form_thx'];
                                } else {
                                    $output .= __('Thank You!','vrr').'<br>'.__('We will call you back soon.','vrr');
                                }
                            $output .= '</div>';
                        $output .= '</div>';

                        $output .= '<form class="vrr-form-booking">';
                            $output .= wp_nonce_field('send_book','_wpnonce',true,false);
                            
                            // data-date
                            $data_exclude = array();
                            // data-time
                            $time_exclude = array();

                            $days_ahead = $this->daysAhead();
                            $days_off = $this->daysOff();
                            $times_off = $this->timesOff();

                            // create array with all days before last day - $days_ahead. In number and string view
                            $weak_days_ahead = array();
                            for($i = 0; $i < $days_ahead; $i++) {
                                $weak_days_ahead[$i]['number'] = date("m/d/Y", strtotime('+'. $i .' days'));
                                $weak_days_ahead[$i]['string'] = date("l", strtotime('+'. $i .' days'));
                            }
                            
                            
                            // foreach day in allowed period check to exclude times and days from settings of plugin
                            $not_work_time_array = array();
                            $key_start_time = 0;
                            $key_end_time = 0;
                            $time_all_fake = array();
                            $time_all_fake_more = array();
                            $key_not_work_time = 0;
                            foreach ($weak_days_ahead as $key => $value) {
                                $value_string = $value['string'];
                                $value_number = $value['number'];
                                $time_all_fake = $time_all;
                                $time_all_fake_more = $time_all;
                                $key_start_time = null;
                                $key_end_time = null;
                                $key_start_time_more = null;
                                $key_end_time_more = null;
                                if(in_array( $value['string'], $days_off)){ // add days to exclude because of days of the week
                                    $data_exclude[] = $value['number'];
                                } else { // add times to exclude because of days of the week and work time in this day
                                    if($times_off){
                                        foreach ($times_off as $key => $day) {
                                            if($day['day'] == $value['string']){
                                                foreach ($time_all as $key => $time_all_value) {
                                                    if(isset($day['time_start']) && !empty($day['time_start'])){
                                                        if($time_all_value == $day['time_start']){
                                                            $key_start_time = $key;
                                                        }
                                                    } 
                                                    if(isset($day['time_end']) && !empty($day['time_end'])){
                                                        if($time_all_value == $day['time_end']){
                                                            $key_end_time = $key;
                                                        }
                                                    } 
                                                    if(isset($day['time_start_more']) && !empty($day['time_start_more'])){
                                                        if($time_all_value == $day['time_start_more']){
                                                            $key_start_time_more = $key;
                                                        }
                                                    } 
                                                    if(isset($day['time_end_more']) && !empty($day['time_end_more'])){
                                                        if($time_all_value == $day['time_end_more']){
                                                            $key_end_time_more = $key;
                                                        }
                                                    } 
                                                }
                                            }
                                        }

                                        // first times
                                        $length = $key_end_time - $key_start_time;
                                        if($length < 0){
                                            $length = $key_end_time + count($time_all) - $key_start_time;
                                        }
                                        array_splice($time_all_fake, $key_start_time, $length+1);

                                        
                                        // second times
                                        if(isset($key_end_time_more) && !empty($key_end_time_more) 
                                        && isset($key_start_time_more) && !empty($key_start_time_more)
                                        && $key_start_time_more != null && $key_end_time_more != null ){
                                            $length_more = $key_end_time_more - $key_start_time_more;
                                            if($length_more < 0){
                                                $length_more = $key_end_time_more + count($time_all) - $key_start_time_more;
                                            }
                                            $time_all_fake_more = array_slice($time_all_fake_more, $key_start_time_more, $length_more+1);

                                            if(isset($time_all_fake_more) && !empty($time_all_fake_more)){
                                                $time_all_new = array(); 
                                                foreach ($time_all_fake_more as $val) { 
                                                  if (($key = array_search($val, $time_all_fake, TRUE))!==false) { 
                                                     $time_all_new[] = $val; 
                                                     unset($time_all_fake[$key]); 
                                                  } 
                                                } 
                                            }


                                        }

                                        // add times to exclude
                                        foreach ($time_all_fake as $key => $value_fake) {
                                            $time_exclude[] = array(
                                                'date' => $value_number,
                                                'time' => $value_fake
                                            );
                                        }

                                    }
                                }
                            }


                            // convert to json string
                            $data_exclude = urlencode(json_encode($data_exclude));
                            $time_exclude = urlencode(json_encode($time_exclude));

                            $data_maxdate = '';
                            $data_maxdate = get_option($this->option_name)['days_ahead'];
                            if(!isset($data_maxdate) || $data_maxdate === ""){
                                $data_maxdate = $default_options['days_ahead'];
                            } 
                            $data_maxdate = intval($data_maxdate);

                            $time_before = 0;
                            if(isset($options['table_before_booked_time']) && $options['table_before_booked_time'] != 0){
                                $time_before = $options['table_before_booked_time'];
                            }
                            $time_after = 0;
                            if(isset($options['table_booked_time']) && $options['table_booked_time'] != 0){
                                $time_after = $options['table_booked_time'];
                            }

                            $output .= '<div class="vrr-input-wrap vrr-calendar-left vrr-tables-datapicker-block">';
                                $output .= '<label class="vrr-input-label-big">'.__('Choose the Date','vrr').'</label>';
                                $output .= '<input type="hidden" class="vrr-input"  id="visual_restaurant_reservation_date" name="visual_restaurant_reservation_date">';
                                $output .= '<div data-maxdate="'.$data_maxdate.'" data-time="'.$time_exclude.'" data-date="'.$data_exclude.'" data-before-time="'.$time_before.'" data-after-time="'.$time_after.'"  id="visual_restaurant_reservation_datepicker" class="visual_restaurant_reservation_datepicker"></div>';
                                $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, pick the date','vrr').'</div>';
                            $output .= '</div>';

                            $output .= '<div class="vrr-form-booking-third">';

                                // $output .= '<div class="vrr-order-wrap"><div class="vrr-form-booking-title" data-text="'.__('Book Table','vrr').' #"></div>';
                                $output .= '<div class="vrr-input-wrap" style="display: none;">';
                                    $output .= '<label class="vrr-input-label">'.__( 'Select the start time', 'vrr' ).'</label>';
                                    $output .= '<div class="vrr-input-inner">';
                                        $output .= '<select class="vrr-select" data-placeholder="'.__('Choose time','vrr').'" id="visual_restaurant_reservation_time" name="visual_restaurant_reservation_time">';
                                            foreach ($time_all as $key => $value) {
                                                $output .= '<option value="'.$value.'">'.$value.'</option>';
                                            } 
                                        $output .= '</select>';
                                        $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, chose time','vrr').'</div>';
                                    $output .= '</div>';
                                $output .= '</div>';

                                if(isset($options['show_time_to']) && $options['show_time_to'] == '1'){
                                    $output .= '<div class="vrr-input-wrap vrr-input-wrap-time-to" style="display: none;">';
                                        $output .= '<label class="vrr-input-label">'.__( 'Select the end time', 'vrr' ).'</label>';
                                        $output .= '<div class="vrr-input-inner">';
                                            $output .= '<select class="vrr-select" data-placeholder="'.__('Choose time','vrr').'" id="visual_restaurant_reservation_time_to" name="visual_restaurant_reservation_time_to">';
                                                foreach ($time_all as $key => $value) {
                                                    $output .= '<option value="'.$value.'">'.$value.'</option>';
                                                } 
                                            $output .= '</select>';
                                            $output .= '<div class="vrr-error vrr-error-time" data-error-text="'.__('Invalid end time','vrr').'">'.__('Invalid end time','vrr').'</div>';
                                        $output .= '</div>';
                                    $output .= '</div>';
                                }

                                if(isset($options['shop_people_amount']) && $options['shop_people_amount'] == '1'){
                                    $output .= '<div class="vrr-input-wrap">';
                                        $output .= '<label class="vrr-input-label">'.__( 'People amount', 'vrr' ).'</label>';
                                        $output .= '<div class="vrr-small-text-wrap">';
                                            $output .= '<span class="vrr-small-text vrr-max-people-amount-text">'.__( 'for this table maximum amount is:', 'vrr' ).'&nbsp;</span><span class="vrr-small-text vrr-max-people-amount"></span>';
                                        $output .= '</div>'; 
                                        $output .= '<div class="vrr-input-inner">';
                                            $output .= '<select class="vrr-people-select" data-placeholder="'.__('Select amount of people','vrr').'" id="visual_restaurant_reservation_people" name="visual_restaurant_reservation_people">';
                                                foreach ($people_all as $key => $value) {
                                                    $output .= '<option value="'.$value.'">'.$value.'</option>';
                                                } 
                                            $output .= '</select>';
                                            $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                        $output .= '</div>'; 
                                    $output .= '</div>'; 
                                }

                                $output .= '<div class="vrr-input-wrap">';
                                    $output .= '<label class="vrr-input-label">'.__( 'Name', 'vrr' ).'</label>';
                                    $output .= '<div class="vrr-input-inner">';
                                        $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_name" name="visual_restaurant_reservation_name">';
                                        $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                    $output .= '</div>';    
                                $output .= '</div>';    

                                $output .= '<div class="vrr-input-wrap">';
                                    $output .= '<label class="vrr-input-label">Phone</label>';
                                    $output .= '<div class="vrr-input-inner">';
                                    $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_phone" name="visual_restaurant_reservation_phone">';
                                        $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                        $output .= '<div class="vrr-error vrr-error-phone">'.__('Please, fill correct phone number','vrr').'</div>';
                                    $output .= '</div>';
                                $output .= '</div>';

                                if(isset($options['show_email']) && $options['show_email'] == '1'){
                                    $output .= '<div class="vrr-input-wrap">';
                                        $output .= '<label class="vrr-input-label">'.__('E-mail','vrr').'</label>';
                                        $output .= '<div class="vrr-input-inner">';
                                            $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_email" name="visual_restaurant_reservation_email">';
                                            $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                            $output .= '<div class="vrr-error vrr-error-email">'.__('Please, fill correct email','vrr').'</div>';
                                        $output .= '</div>';
                                    $output .= '</div>'; 
                                }

                                $output .= $form_confirm;

                                $output .= '<div class="vrr-send-booking-wrap">';
                                    
                                    $output .= '<div>';
                                    $output .= '<div class="vrr-send-booking">'.__('Book Now','vrr').'</div>';
                                    $output .= '</div>';
                                    if($form_message != ''){
                                        $output .= '<div class="vrr-message">'.$form_message.'</div>';
                                    }
                                    $output .= '<div class="vrr-send-respons-error"></div>';
                                $output .= '</div></div>';

                            $output .= '</div>';

                        $output .= '</form>';
                    $output .= '</div>'; 

                // 1
                } else { // Default
                    $to_small = '';
                    if(intval($options['canvas_width'] < 850)){
                        $to_small = 'vrr-tables-wrap-small-w';
                    }
    			    $output .= '<div class="vrr-tables-wrap vrr-tables-size-'.$options['canvas_elements_size'].' '.$to_small.'  vrr-tables-wrap-default  '.$vrr_shortcode_class.'"  id="'.$vrr_shortcode_id.'">';
    			    	$output .= '<div class="vrr-draggable-wrap">';
                            $draggable_style = "";
                            $draggable_style .= ' style="';
                            if($options['canvas_width']){ 
                                $canvas_width_em = (intval($options['canvas_width'])/10).'em;';
                                $draggable_style .= 'width: '.$canvas_width_em;
                            } 
                            if($options['canvas_height']){ 
                                $canvas_height_em = (intval($options['canvas_height'])/10).'em;';
                                $draggable_style .= 'height: '.$canvas_height_em;
                            }  
                            if($options['background-canvas']){  
                                $draggable_style .= 'background-image: url('.$options['background-canvas'] .');';
                            } 
                            $draggable_style .= '"';
    				        $output .= '<div data-width="'.intval($options['canvas_width']).'"  class="vrr-draggable vrr-loading"  '.$draggable_style.'';
                            if($options['background-canvas']){  
                                $output .= ' style="background-image: url('.$options['background-canvas'] .');"';
                            } 
                            $output .= '>';
    				            $i_array = 0;
    				            if(isset($jsonPosition) && !empty($jsonPosition)){
    				            	// foreach element
    				                foreach ($jsonPosition as $key => $val) {            
    				                    if(is_array($val)) { 
    				                        $w = $val['x1']-$val['x'];
    				                        $h = $val['y1']-$val['y'];
    				                        $class = "";
    				                        if($val['class'] != ""){
    				                        	$class = $val['class'];
    				                        }
    				                        // style
                                            $el_left = $val['x'];
                                            $el_top = $val['y'];
                                            $element_style = "left: ".$val['x']."px; top: ".$val['y']."px;";
                                            $element_style_table = '';
                                            if($options['background-table']){ 
                                                $element_style_table .= " background-image: url(". $options['background-table'].");";
                                            } 
    				                        

    								        // data-date
    								        $data_exclude = array();
    								        // data-time
    				                      	$time_exclude = array();


    								        // create date array to count times booked in element
    										$new_array = array();
    						              	foreach ($booked as $key => $value) {
    						              		if($val['id'] === $value['id']){
    							              		if(!array_key_exists($value['date'], $new_array)){
    							              			$i = 0;
    												    foreach($booked as $key1 => $value1) {
    												    	if($val['id'] === $value1['id']){
    													        if (isset($value1['date']) && $value1['date'] === $value['date']) {
    													        	$i++;
    													        }     
    												        }       
    												    }
    							              			$new_array[$value['date']] = $i;
    							              		}
    							              	}
    								        }

    								        // add days to exclude because of booked elements
    								        foreach ($new_array as $key => $value) {
    								        	if($value >= count($time_all)){
    								        		$data_exclude[] = $key;
    								        	}
    								        }

    								        // add times to exclude because of booked elements
    				                      	$all_time_booked = 0;
    				                      	foreach ($booked as $key => $value) {
    				                      		if($val['id'] == $value['id']){
    				                      			$time_exclude[] = array(
    				                      				'date' => $value['date'],
    				                      				'time' => $value['time']
    				                      			);
                                                    $booked_time = array_search($value['time'], $time_all);

                                                    $booked_time_difference = '';
                                                    if(isset($value['time_to'])){
                                                        $booked_time_to = array_search($value['time_to'], $time_all);
                                                        if($booked_time_to > $booked_time){
                                                            $booked_time_difference = $booked_time_to - $booked_time;
                                                        }
                                                    }

                                                    if($booked_time){
                                                        if(isset($options['table_booked_time']) && $options['table_booked_time'] != 0 && $booked_time_difference == ""){
                                                            for ($i=1; $i <= $options['table_booked_time']*2-1; $i++) { 
                                                                
                                                                if(isset($time_all[$booked_time+$i]) && !empty($time_all[$booked_time+$i])){
                                                                    $time_ahead = $time_all[$booked_time+$i];
                                                                    $time_exclude[] = array(
                                                                        'date' => $value['date'],
                                                                        'time' => $time_ahead
                                                                    );
                                                                }
                                                            }
                                                        } else if($booked_time_difference != ""){
                                                            for ($i=1; $i <= $booked_time_difference-1; $i++) {
                                                                if(isset($time_all[$booked_time+$i]) && !empty($time_all[$booked_time+$i])){
                                                                    $time_ahead = $time_all[$booked_time+$i];
                                                                    $time_exclude[] = array(
                                                                        'date' => $value['date'],
                                                                        'time' => $time_ahead
                                                                    );
                                                                }
                                                            }
                                                        }
                                                        if(isset($options['table_before_booked_time']) && $options['table_before_booked_time'] != 0){
                                                            for ($i=1; $i <= $options['table_before_booked_time']*2-1; $i++) { 
                                                                if(isset($time_all[$booked_time-$i]) && !empty($time_all[$booked_time-$i])){
                                                                    $time_ahead = $time_all[$booked_time-$i];
                                                                    $time_exclude[] = array(
                                                                        'date' => $value['date'],
                                                                        'time' => $time_ahead
                                                                    );
                                                                }
                                                            }
                                                        }
                                                    }
    				                      		}
    				                      	}

    								        // convert to json string
    								        $data_exclude = urlencode(json_encode($data_exclude));
    								        $time_exclude = urlencode(json_encode($time_exclude));



                                            $output .= '<div 
                                                class="vrr-element '.$class.' rotate-'.$val['rotate'].'" 
                                                data-date="'.$data_exclude.'" 
                                                data-time="'.$time_exclude.'"
                                                data-left="'.$el_left.'" 
                                                data-top="'.$el_top.'" 
                                                data-seats-size="'.''.'"
                                                data-id="'.$val['id'].'" 
                                                data-unique="'.$val['unique'].'" 
                                                data-seats="'.$val['seats'].'" 
                                                data-max-seats="'.$val['max_seats'].'" 
                                                data-big-side="'. $val['big_side'].'" 
                                                data-type="'. $val['class'].'" 
                                                data-rotate="'. $val['rotate'].'" 
                                                style="'. $element_style.'">
                                                    <div class="vrr-element-table" style="'.$element_style_table.'">
                                                        <div class="vrr-element-id">'. $val['id'].'</div>';
                                                        $show_people = getOption('show_people', $options);
                                                        if(isset($show_people) && $show_people == '1'){
                                                            $output .= '<div class="vrr-element-seats">'. $val['seats'].'</div>';
                                                        }

                                                        $show_seats = getOption('show_seats', $options);
                                                        if(isset($show_seats) && $show_seats == '1'){
                                                            $output .= '<div class="vrr-element-seats-wrap">';
                                                                $seat_style = '';
                                                                if(isset($options['background-seat']) && !empty($options['background-seat'])){ 
                                                                    $seat_style = "background-image: url(". $options['background-seat'].");";
                                                                } 
                                                                $seats = intval($val['seats']); 
                                                                for ($i=0; $i < $seats; $i++) { 
                                                                    $output .= '<div class="vrr-element-seat" style="'.$seat_style.'"></div>';
                                                                } 
                                                            $output .= '</div>';
                                                        }
                                                    $output .= '</div>
                                                </div>';

    				                        $i_array++;

    				                    }
    				                }
    				            }
    			            $output .= '</div>';

                            // BACK
                            $output .= '<div class="vrr-form-back"></div>';

                            // THX
                            $output .= '<div class="vrr-form-thx">';
                                $output .= '<div class="vrr-close"></div>';
                                $output .= '<div class="vrr-form-thx-title">';
                                    if(isset($options['form_thx']) && $options['form_thx'] != ''){
                                        $output .= $options['form_thx'];
                                    } else {
                                        $output .= __('Thank You!','vrr').'<br>'.__('We will call you back soon.','vrr');
                                    }
                                $output .= '</div>';
                            $output .= '</div>';


                            // FORM
                            $output .= '<form class="vrr-form-booking">';
                                $output .= wp_nonce_field('send_book','_wpnonce',true,false);
                                $output .= '<input type="hidden" name="visual_restaurant_reservation_id" id="visual_restaurant_reservation_id" value="">';
                                $output .= '<input type="hidden" name="visual_restaurant_reservation_unique" id="visual_restaurant_reservation_unique" value="">';
                                $output .= '<input type="hidden" name="visual_restaurant_reservation_seats" id="visual_restaurant_reservation_seats" value="">';
                                $output .= '<div class="vrr-close"></div>';

                                
                                // data-date
                                $data_exclude = array();
                                // data-time
                                $time_exclude = array();

                                $days_ahead = $this->daysAhead();
                                $days_off = $this->daysOff();
                                $times_off = $this->timesOff();

                                // create array with all days before last day - $days_ahead. In number and string view
                                $weak_days_ahead = array();
                                for($i = 0; $i < $days_ahead; $i++) {
                                    $weak_days_ahead[$i]['number'] = date("m/d/Y", strtotime('+'. $i .' days'));
                                    $weak_days_ahead[$i]['string'] = date("l", strtotime('+'. $i .' days'));
                                }
                               
                                // foreach day in allowed period check to exclude times and days from settings of plugin
                                $not_work_time_array = array();
                                $key_start_time = 0;
                                $key_end_time = 0;
                                $time_all_fake = array();
                                $time_all_fake_more = array();
                                $key_not_work_time = 0;
                                foreach ($weak_days_ahead as $key => $value) {
                                    $value_string = $value['string'];
                                    $value_number = $value['number'];
                                    $time_all_fake = $time_all;
                                    $time_all_fake_more = $time_all;
                                    $key_start_time = null;
                                    $key_end_time = null;
                                    $key_start_time_more = null;
                                    $key_end_time_more = null;
                                    if(in_array( $value['string'], $days_off)){ // add days to exclude because of days of the week
                                        $data_exclude[] = $value['number'];
                                    } else { // add times to exclude because of days of the week and work time in this day
                                        if($times_off){
                                            foreach ($times_off as $key => $day) {
                                                if($day['day'] == $value['string']){
                                                    foreach ($time_all as $key => $time_all_value) {
                                                        if(isset($day['time_start']) && !empty($day['time_start'])){
                                                            if($time_all_value == $day['time_start']){
                                                                $key_start_time = $key;
                                                            }
                                                        } 
                                                        if(isset($day['time_end']) && !empty($day['time_end'])){
                                                            if($time_all_value == $day['time_end']){
                                                                $key_end_time = $key;
                                                            }
                                                        } 
                                                        if(isset($day['time_start_more']) && !empty($day['time_start_more'])){
                                                            if($time_all_value == $day['time_start_more']){
                                                                $key_start_time_more = $key;
                                                            }
                                                        } 
                                                        if(isset($day['time_end_more']) && !empty($day['time_end_more'])){
                                                            if($time_all_value == $day['time_end_more']){
                                                                $key_end_time_more = $key;
                                                            }
                                                        } 
                                                    }
                                                }
                                            }

                                            // first times
                                            $length = $key_end_time - $key_start_time;
                                            if($length < 0){
                                                $length = $key_end_time + count($time_all) - $key_start_time;
                                            }
                                            array_splice($time_all_fake, $key_start_time, $length+1);

                                            
                                            // second times
                                            if(isset($key_end_time_more) && !empty($key_end_time_more) 
                                            && isset($key_start_time_more) && !empty($key_start_time_more)
                                            && $key_start_time_more != null && $key_end_time_more != null ){
                                                $length_more = $key_end_time_more - $key_start_time_more;
                                                if($length_more < 0){
                                                    $length_more = $key_end_time_more + count($time_all) - $key_start_time_more;
                                                }
                                                $time_all_fake_more = array_slice($time_all_fake_more, $key_start_time_more, $length_more+1);

                                                if(isset($time_all_fake_more) && !empty($time_all_fake_more)){
                                                    $time_all_new = array(); 
                                                    foreach ($time_all_fake_more as $val) { 
                                                      if (($key = array_search($val, $time_all_fake, TRUE))!==false) { 
                                                         $time_all_new[] = $val; 
                                                         unset($time_all_fake[$key]); 
                                                      } 
                                                    } 
                                                }


                                            }

                                            // add times to exclude
                                            foreach ($time_all_fake as $key => $value_fake) {
                                                $time_exclude[] = array(
                                                    'date' => $value_number,
                                                    'time' => $value_fake
                                                );
                                            }

                                        }
                                    }
                                }

                                // convert to json string
                                $data_exclude = urlencode(json_encode($data_exclude));
                                $time_exclude = urlencode(json_encode($time_exclude));

                                $data_maxdate = '';
                                $data_maxdate = get_option($this->option_name)['days_ahead'];
                                if(!isset($data_maxdate) || $data_maxdate === ""){
                                    $data_maxdate = $default_options['days_ahead'];
                                } 
                                $data_maxdate = intval($data_maxdate);

                                $time_before = 0;
                                if(isset($options['table_before_booked_time']) && $options['table_before_booked_time'] != 0){
                                    $time_before = $options['table_before_booked_time'];
                                }
                                $time_after = 0;
                                if(isset($options['table_booked_time']) && $options['table_booked_time'] != 0){
                                    $time_after = $options['table_booked_time'];
                                }

                                $output .= '<div class="vrr-input-wrap vrr-calendar-left vrr-tables-datapicker-block">';
                                    $output .= '<label class="vrr-input-label-big">'.__('Choose the Date','vrr').'</label>';
                                    $output .= '<input type="hidden" class="vrr-input"  id="visual_restaurant_reservation_date" name="visual_restaurant_reservation_date">';
                                    $output .= '<div data-maxdate="'.$data_maxdate.'" data-time="'.$time_exclude.'" data-date="'.$data_exclude.'" data-before-time="'.$time_before.'" data-after-time="'.$time_after.'"  id="visual_restaurant_reservation_datepicker" class="visual_restaurant_reservation_datepicker"></div>';
                                    $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, pick the date','vrr').'</div>';
                                $output .= '</div>';

                                $output .= '<div class="vrr-order-wrap"><div class="vrr-form-booking-title" data-text="'.__('Book Table','vrr').' #"></div>';

                                $output .= '<div class="vrr-input-wrap">';
                                    $output .= '<label class="vrr-input-label">'.__( 'Select the start time', 'vrr' ).'</label>';
                                    $output .= '<div class="vrr-input-inner">';
                                        $output .= '<select class="vrr-select" data-placeholder="'.__('Choose time','vrr').'" id="visual_restaurant_reservation_time" name="visual_restaurant_reservation_time">';
                                            foreach ($time_all as $key => $value) {
                                                $output .= '<option value="'.$value.'">'.$value.'</option>';
                                            } 
                                        $output .= '</select>';
                                        $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, chose time','vrr').'</div>';
                                    $output .= '</div>';
                                $output .= '</div>';

                                if(isset($options['show_time_to']) && $options['show_time_to'] == '1'){
                                    $output .= '<div class="vrr-input-wrap vrr-input-wrap-time-to" style="display: none;">';
                                        $output .= '<label class="vrr-input-label">'.__( 'Select the end time', 'vrr' ).'</label>';
                                        $output .= '<div class="vrr-input-inner">';
                                            $output .= '<select class="vrr-select" data-placeholder="'.__('Choose time','vrr').'" id="visual_restaurant_reservation_time_to" name="visual_restaurant_reservation_time_to">';
                                                foreach ($time_all as $key => $value) {
                                                    $output .= '<option value="'.$value.'">'.$value.'</option>';
                                                } 
                                            $output .= '</select>';
                                            $output .= '<div class="vrr-error vrr-error-time" data-error-text="'.__('Invalid end time','vrr').'">'.__('Invalid end time','vrr').'</div>';
                                        $output .= '</div>';
                                    $output .= '</div>';
                                }

                                if(isset($options['shop_people_amount']) && $options['shop_people_amount'] == '1'){
                                    $output .= '<div class="vrr-input-wrap">';
                                        $output .= '<label class="vrr-input-label">'.__( 'People amount', 'vrr' ).'</label>';
                                        $output .= '<div class="vrr-small-text-wrap">';
                                            $output .= '<span class="vrr-small-text vrr-max-people-amount-text">'.__( 'for this table maximum amount is:', 'vrr' ).'&nbsp;</span><span class="vrr-small-text vrr-max-people-amount"></span>';
                                        $output .= '</div>';
                                        $output .= '<div class="vrr-input-inner">';
                                            $output .= '<select class="vrr-people-select" data-placeholder="'.__('Select amount of people','vrr').'" id="visual_restaurant_reservation_people" name="visual_restaurant_reservation_people">';
                                                foreach ($people_all as $key => $value) {
                                                    $output .= '<option value="'.$value.'">'.$value.'</option>';
                                                } 
                                            $output .= '</select>';
                                            $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                        $output .= '</div>'; 
                                    $output .= '</div>'; 
                                }

                                $output .= '<div class="vrr-input-wrap">';
                                    $output .= '<label class="vrr-input-label">'.__( 'Name', 'vrr' ).'</label>';
                                    $output .= '<div class="vrr-input-inner">';
                                        $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_name" name="visual_restaurant_reservation_name">';
                                        $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                    $output .= '</div>';    
                                $output .= '</div>';    

                                $output .= '<div class="vrr-input-wrap">';
                                    $output .= '<label class="vrr-input-label">Phone</label>';
                                    $output .= '<div class="vrr-input-inner">';
                                        $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_phone" name="visual_restaurant_reservation_phone">';
                                        $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                        $output .= '<div class="vrr-error vrr-error-phone">'.__('Please, fill correct phone number','vrr').'</div>';
                                    $output .= '</div>';
                                $output .= '</div>';

                                if(isset($options['show_email']) && $options['show_email'] == '1'){
                                    $output .= '<div class="vrr-input-wrap">';
                                        $output .= '<label class="vrr-input-label">'.__('E-mail','vrr').'</label>';
                                        $output .= '<div class="vrr-input-inner">';
                                            $output .= '<input type="text" class="vrr-input" id="visual_restaurant_reservation_email" name="visual_restaurant_reservation_email">';
                                            $output .= '<div class="vrr-error vrr-error-empty">'.__('Please, fill the field','vrr').'</div>';
                                            $output .= '<div class="vrr-error vrr-error-email">'.__('Please, fill correct email','vrr').'</div>';
                                        $output .= '</div>';
                                    $output .= '</div>'; 
                                }

                                $output .= $form_confirm;

                                $output .= '<div class="vrr-send-booking-wrap">';
                                    
                                    $output .= '<div>';
                                    $output .= '<div class="vrr-send-booking">'.__('Book Now','vrr').'</div>';
                                    $output .= '</div>';
                                    if($form_message != ''){
                                        $output .= '<div class="vrr-message">'.$form_message.'</div>';
                                    }
                                    $output .= '<div class="vrr-send-respons-error"></div>';
                                $output .= '</div></div>';
                            $output .= '</form>';

    			        $output .= '</div>';    

    			        

    			        
    			    $output .= '</div>'; 

                } 

                // test 100% wrap
                $output .= '</div>';

			    return $output;
			}
		}
	}


	function register_shortcodes() { 
		add_shortcode( 'visual_restaurant_reservation', array($this,'rest_booking' ));
	}

	function register_post_types() {
		$args = array(
			'labels' => array(
				'name' => __( 'Table Reservations', 'vrr' ),
				'singular_name' => __( 'Table Reservations', 'vrr' ),
				'add_new' => __( 'Add Table Reservation', 'vrr' ),
				'add_new_item' => __( 'Add New Table Reservation', 'vrr' ),
				'edit' => __( 'Edit', 'vrr' ),
				'edit_item' => __( 'Edit Reservation', 'vrr' ),
				'new_item' => __( 'New Reservation', 'vrr' ),
				'view' => __( 'View Reservation', 'vrr' ),
				'view_item' => __( 'View Reservation', 'vrr' ),
				'search_items' => __( 'Search Reservations', 'vrr' ),
				'not_found' => __( 'No Reservations found', 'vrr' ),
				'not_found_in_trash' => __( 'No Reservations found in trash', 'vrr' ),
				'parent' => __( 'Parent Reservations', 'vrr' )
			),
			'description' => __( 'This is where Table Reservations are stored.', 'vrr' ),
			'public' => false,
			'show_ui' => true,
			// 'capability_type' => 'post',
			// 'map_meta_cap' => true,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_in_menu' => false,
			'hierarchical' => false,
			'show_in_nav_menus' => true,
			'rewrite' => false,
			'query_var' => true,
			'supports' => array( 'title' ),
			'has_archive' => false
		);

		register_post_type( 'vrr', $args );
	}
	
	function register_post_meta(){
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	function add_meta_boxes(){
		add_meta_box( 'visual-restaurant-reservation-booking-info', __( 'Table Reservation Details', 'vrr' ), array( $this, 'render_location_info' ), 'vrr', 'normal', 'high' );
	}

	function render_location_info($post){
		$post_id = $post->ID;
		$id = get_post_meta( $post_id, '_visual_restaurant_reservation_id', true );
		$unique = get_post_meta( $post_id, '_visual_restaurant_reservation_unique', true );
		$date = get_post_meta( $post_id, '_visual_restaurant_reservation_date', true );
		$time = get_post_meta( $post_id, '_visual_restaurant_reservation_time', true );
        $time_to = get_post_meta( $post_id, '_visual_restaurant_reservation_time_to', true );
		$seats = get_post_meta( $post_id, '_visual_restaurant_reservation_seats', true );
        $people = get_post_meta( $post_id, '_visual_restaurant_reservation_people', true );
		$name = get_post_meta( $post_id, '_visual_restaurant_reservation_name', true );
		$phone = get_post_meta( $post_id, '_visual_restaurant_reservation_phone', true );
        $email = get_post_meta( $post_id, '_visual_restaurant_reservation_email', true );
		$time_all = INFO::$TIME_ALL;
		?>
		<div class="panel-wrap visual_restaurant_reservation">
            <table class="form-table">
            	<tr class="form-field">
                    <th>
                    	<label><?php _e( 'Table Number', 'vrr' ); ?> </label>
                    </th>
                    <td>
                    	<input type="text" name="_visual_restaurant_reservation_id"  id="_visual_restaurant_reservation_id" value="<?php echo esc_attr( $id ); ?>">
                    </td>
                </tr>
                <tr class="form-field">
                    <th>
                    	<label><?php _e( 'Date', 'vrr' ); ?> </label>
                    </th>
                    <td>
                    	<input type="text" name="_visual_restaurant_reservation_date" placeholder="Example: 05/17/2018" required id="_visual_restaurant_reservation_date" value="<?php echo esc_attr( $date ); ?>">
                    </td>
                </tr>
                <tr class="form-field">
                    <th>
                    	<label><?php _e( 'Time', 'vrr' ); ?> </label>
                    </th>
                    <td>
                    	<select class="vrr-input" id="_visual_restaurant_reservation_time" required name="_visual_restaurant_reservation_time">
                    		<?php foreach ($time_all as $key => $value) {
                    			if($value == esc_attr( $time )){ ?>
                    				<option selected value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    			<?php } else { ?>
                    				<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    			<?php }
                    		} ?>
		        		</select>
                    </td>
                </tr>

                <?php if($time_to){ ?>
                    <tr class="form-field">
                        <th>
                            <label><?php _e( 'Time to reserve to', 'vrr' ); ?> </label>
                        </th>
                        <td>
                            <select class="vrr-input" id="_visual_restaurant_reservation_time_to" required name="_visual_restaurant_reservation_time_to">
                                <?php foreach ($time_all as $key => $value) {
                                    if($value == esc_attr( $time_to )){ ?>
                                        <option selected value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>

                <tr class="form-field">
                    <th>
                    	<label><?php _e( 'Table Seats', 'vrr' ); ?> </label>
                    </th>
                    <td>
                    	<input type="text" name="_visual_restaurant_reservation_seats"  id="_visual_restaurant_reservation_seats" value="<?php echo esc_attr( $seats ); ?>">
                    </td>
                </tr>
                <?php if($people){ ?>
                 <tr class="form-field">
                    <th>
                        <label><?php _e( 'People Amount', 'vrr' ); ?> </label>
                    </th>
                    <td>
                        <input type="text" name="_visual_restaurant_reservation_people"  id="_visual_restaurant_reservation_people" value="<?php echo esc_attr( $people ); ?>">
                    </td>
                </tr>
                <?php } ?>
				<tr class="form-field">
                    <th>
                    	<label><?php _e( 'Name', 'vrr' ); ?> </label>
                    </th>
                    <td>
                    	<input type="text" name="_visual_restaurant_reservation_name" required id="_visual_restaurant_reservation_name" value="<?php echo esc_attr( $name ); ?>">
                    </td>
                </tr>
                <tr class="form-field">
                    <th>
                    	<label><?php _e( 'Phone Number', 'vrr' ); ?> </label>
                    </th>
                    <td>
                    	<input type="text" name="_visual_restaurant_reservation_phone" required id="_visual_restaurant_reservation_phone" value="<?php echo esc_attr( $phone ); ?>">
                    </td>
                </tr>
                <tr class="form-field">
                    <th>
                        <label><?php _e( 'E-mail', 'vrr' ); ?> </label>
                    </th>
                    <td>
                        <input type="text" name="_visual_restaurant_reservation_email"  id="_visual_restaurant_reservation_email" value="<?php echo esc_attr( $email ); ?>">
                    </td>
                </tr>
                
                <input type="hidden" name="_visual_restaurant_reservation_unique" id="_visual_restaurant_reservation_unique" value="<?php echo esc_attr( $unique ); ?>">
                    
            </table>
        </div>
		<?php
	}

	function save_metadata($post_id){
		$fields = array(
			'_visual_restaurant_reservation_id',
			'_visual_restaurant_reservation_unique',
			'_visual_restaurant_reservation_date',
			'_visual_restaurant_reservation_time',
            '_visual_restaurant_reservation_time_to',
			'_visual_restaurant_reservation_seats',
            '_visual_restaurant_reservation_people',
			'_visual_restaurant_reservation_name',
			'_visual_restaurant_reservation_phone',
            '_visual_restaurant_reservation_email',
		);

		foreach ($fields as $key => $value) {
			if(isset($_POST[$value])){
				$meta = get_post_meta( $post_id, $value, true );
				$post = sanitize_text_field($_POST[$value]);
				if($meta == "" && $post == "") {
			    	update_post_meta($post_id, $value, '');
				} else if($meta != "" && $post != "") {
					update_post_meta($post_id, $value, $post);
				} else if($meta == "" && $post != "") {
					update_post_meta($post_id, $value, $post);
				} else if($meta != "" && $post == ""){
					update_post_meta($post_id, $value, '');
				}
			}
		}
	}

    function vrr_custom_post_status(){
        register_post_status( 'requests', array(
          'label'                     => __( 'Requested', 'vrr' ),
          'public'                    => true,
          'show_in_admin_all_list'    => true,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Requested <span class="count">(%s)</span>', 'Requested <span class="count">(%s)</span>' )
        ) );

        register_post_status( 'reserved', array(
          'label'                     => __( 'Reserved', 'vrr' ),
          'public'                    => true,
          'show_in_admin_all_list'    => true,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Reserved <span class="count">(%s)</span>', 'Reserved <span class="count">(%s)</span>' )
        ) );

        register_post_status( 'canceled', array(
          'label'                     => __( 'Canceled', 'vrr' ),
          'public'                    => true,
          'show_in_admin_all_list'    => true,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Canceled <span class="count">(%s)</span>', 'Canceled <span class="count">(%s)</span>' )
        ) );
    }

    function true_append_post_status_list(){
        global $post;
        $optionselected_requests = '';
        $statusname_requests = '';
        $optionselected_reserved = '';
        $statusname_reserved = '';
        $optionselected_canceled = '';
        $statusname_canceled = '';

        $options = "";
        if( $post->post_type == 'vrr' ){ 
            if($post->post_status == 'requests'){ 
                $optionselected_requests = ' selected="selected"';
                $statusname_requests = "$('#post-status-display').text('".__( 'Requested', 'vrr' )."');";
            }
            if($post->post_status == 'reserved'){ 
                $optionselected_reserved = ' selected="selected"';
                $statusname_reserved = "$('#post-status-display').text('".__( 'Reserved', 'vrr' )."');";
            }
            if($post->post_status == 'canceled'){ 
                $optionselected_canceled = ' selected="selected"';
                $statusname_canceled = "$('#post-status-display').text('".__( 'Canceled', 'vrr' )."');";
            }

            $options .= "<option value=\"requests\"$optionselected_requests>".__( 'Requested', 'vrr' )."</option>";
            $options .= "<option value=\"reserved\"$optionselected_reserved>".__( 'Reserved', 'vrr' )."</option>";
            $options .= "<option value=\"reserved\"$optionselected_canceled>".__( 'Canceled', 'vrr' )."</option>";

            echo "<script>
            jQuery(function($){
                $('select#post_status').prepend('".$options."');";
                if($statusname_requests != ""){
                    echo $statusname_requests;
                } else if($statusname_reserved != ""){
                    echo $statusname_reserved;
                } else if($statusname_canceled != ""){
                    echo $statusname_canceled;
                }
            echo "});";
            echo "</script>";
        }
    }
 
    function true_append_status() {
        global $post;
        if(is_object($post)){
            if( $post->post_type == 'vrr' ){
                $options = '';
                $options .= "<option value=\"requests\">".__( 'Requested', 'vrr' )."</option>";
                $options .= "<option value=\"reserved\">".__( 'Reserved', 'vrr' )."</option>";
                $options .= "<option value=\"canceled\">".__( 'Canceled', 'vrr' )."</option>";

                echo "<script>
                jQuery(document).ready( function($) {
                    $( 'select[name=\"_status\"]' ).prepend( '".$options."' );
                });
                </script>";
            }
        }
    }

    function hide_publishing_actions(){
        global $post;
        if($post->post_type == 'vrr'){
            if($post->post_status == "auto-draft"){
                echo '
                    <style type="text/css">
                        #misc-publishing-actions,
                        #minor-publishing-actions{
                            display:none;
                        }
                    </style>
                ';
            }
        }
    }

    

    // function publish_custom_draft_translation( $views ) 
    // {
    //     // $views['publish'] = str_replace('Published ', __( 'Reserved', 'vrr' ), $views['publish']);
    //     return $views;
    // }
    
    function example_insert_using_custom_status( $data = array(), $postarr = array() ) {
        if ( empty( $postarr['publish'] ) ) {
            return $data;
        }
        if ( 'vrr' !== $data['post_type'] ) {
            return $data;
        }
        if ( ! empty( $postarr['_wp_statuses_status'] ) && in_array( $postarr['_wp_statuses_status'], array(
            'requests',
            'reserved',
            'canceled',
        ), true ) ) {
            $data['post_status'] = sanitize_key( $postarr['post_status'] );
        } else { // default
            $data['post_status'] = 'reserved';
        }
        return $data;
    }

    function vrr_display_archive_state( $states ) {
        global $post;
        if($post->post_type == 'vrr'){
            if(get_query_var( 'post_status' ) != 'requests' 
            && get_query_var( 'post_status' ) != 'reserved' 
            && get_query_var( 'post_status' ) != 'canceled'){
                if($post->post_status == "requests"){
                    return array('Requested');
                } else if($post->post_status == "reserved"){
                    return array('Reserved');
                } else if($post->post_status == "canceled"){
                    return array('Canceled');
                }
            }
        }
        return $states;
    }
    

    
	
}