<?php

namespace VISUAL_RESTAURANT_RESERVATION;

class Admin
{
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

   
    private function custom_settings_fields($field_args, $settings) {
        $output = '';
        $output_return = '';
        $output_tab = '';
        $output_tab_1 = '';
        $output_tab_2 = '';
        $output_tab_3 = '';
        $output_tab_4 = '';
        $output_tab_1_fake = '';
        $output_tab_2_fake = '';
        $output_tab_3_fake = '';
        $output_tab_4_fake = '';
        $tab = "";
        $output_all = "";
        $option_name = INFO::OPTION_NAME;
        $default_options = INFO::$DEFAULT_OPTIONS;

        

        

        foreach ($field_args as $key_field => $field) {
            // clear output
            $output = "";

            $slug = $field['slug'];
            $tab = "";
            if(isset($field['tab']) && !empty($field['tab'])){
                $tab = $field['tab'];
            }
            $setting = $this->option_name.'['.$slug.']';
            $label = esc_attr__($field['label'], 'vrr');
            $after = '';
            if(isset($field['after']) && !empty($field['after'])){
                $after = esc_attr__($field['after'], 'vrr');
            }
            $class= '';
            if($field['type'] === 'colors'){
                $class = "settings-group-wrap-colors";
            }
            $output .= '<div id="settings-'.$slug.'" class="settings-'.$slug.' '.$class.' settings-group-wrap">';
            $output .= '<h3><label for="'.$setting.'">'.$label.'</label></h3>';
            if(isset($field['description']) && !empty($field['description'])){
                $output .= '<div class="settings-field-description">'.$field['description'].'</div>';
            }
            
            if(!isset($settings[$slug])){
                if(isset($default_options[$slug])){
                    $settings[$slug] = $default_options[$slug];
                }
            }

            

            if ($field['type'] === 'text') { // text
                $output .= '<p>';
                $output .= '<input type="text" id="'.$setting.'" name="'.$setting.'" value="'.$settings[$slug].'">';
                if($after != ''){
                    $output .= '<span class="settings-input-after">'.$after.'</span>';
                }
                $output .= '</p>';
            } elseif ($field['type'] === 'textarea') { // textarea
                $output .= '<p>';
                $output .= '<textarea id="'.$setting.'" name="'.$setting.'" rows="10">'.$settings[$slug].'</textarea>';
                if($after != ''){
                    $output .= '<span class="settings-input-after">'.$after.'</span>';
                }
                $output .= '</p>';
            } elseif ($field['type'] === 'number') { // number
                $output .= '<p>';
                $output .= '<input id="'.$setting.'" name="'.$setting.'" type="number" min="0" value="'.$settings[$slug].'">';
                if($after != ''){
                    $output .= '<span class="settings-input-after">'.$after.'</span>';
                }
                $output .= '</p>';
            } elseif ($field['type'] === 'select') { // select
                $output .= '<p>';
                    $output .= '<select id="'.$setting.'" name="'.$setting.'" value="'.$settings[$slug].'">';
                        foreach ($field['options'] as $key => $value) {
                            $selected = '';
                            if($settings[$slug] == $value){
                                $selected = 'selected';
                            }
                            $output .= '<option '.$selected.' value="'.$value.'">'.$value.'</option>';
                        }
                    $output .= '</select>';
                    if($after != ''){
                        $output .= '<span class="settings-input-after">'.$after.'</span>';
                    }
                $output .= '</p>';
            } else if($field['type'] === 'radio'){ // radio

                
                foreach ($field['inputs'] as $key => $input) {
                    $checked = '';
                    if($settings[$slug] == $input['value']){
                        $checked = 'checked';
                    }
                    $output .= '<p>';
                    $output .= '<div class="settings-radio-wrap">';
                        $output .= '<label for="'.$setting.'_'.$key.'">'.$input['title'].'</label>';
                        $output .= '<input type="radio" id="'.$setting.'_'.$key.'" name="'.$setting.'" value="'.$input['value'].'" '.$checked.'>';
                    $output .= '</div>';
                    if(isset($input['description']) && !empty($input['description'])){
                        $output .= '<div class="settings-radio-wrap">';
                            $output .= '<div class="settings-field-description">'.$input['description'].'</div>';
                        $output .= '</div>';
                    }
                    $output .= '</p>';
                }
                
            } else if($field['type'] === 'image'){ // image

                $output .= $this->custom_image_uploader_field( $setting, $settings[$slug] );
                
            } else if($field['type'] === 'checkboxes'){ // checkboxes
                $output .= '<fieldset>';
                    foreach ($field['checkboxes'] as $key => $value) {
                        $checkbox_name = $this->option_name.'['.$slug.'_'.$value['name'].']';
                        $checkbox_value = '';
                        if(isset($settings[$slug.'_'.$value['name']])){
                        	$checkbox_value = $settings[$slug.'_'.$value['name']];
                        }
                        $checked = '';
                        if($checkbox_value == "1"){
                            $checked = 'checked';
                        }
                        $output .= '<div class="vrr-checkboxes-fields-wrap">';
                            $output .= '<div class="rb-label-wrap">';
                                $output .= '<label for="'.$checkbox_name.'">'.$value['title'].'</label>';
                            $output .= '</div>';
                            if(isset($value['selects'])){
                                foreach ($value['selects'] as $key => $sel) {
                                    $checkbox_select_name = $this->option_name.'['.$slug.'_'.$value['name'].'_'.$sel['name'].']';
                                    $checkbox_select_value = '';
                                    if(isset($settings[$slug.'_'.$value['name'].'_'.$sel['name']]) ) {
                                        $checkbox_select_value = $settings[$slug.'_'.$value['name'].'_'.$sel['name']];
                                    } else {
                                        $checkbox_select_value = $default_options[$slug.'_'.$value['name'].'_'.$sel['name']];
                                    }

                                    $output .= '<select id="'.$checkbox_select_name.'" name="'.$checkbox_select_name.'" value="'. $checkbox_select_value .'">';
                                        foreach ($sel['options'] as $key1 => $value1) {
                                            $selected = '';
                                            if(isset($settings[$slug.'_'.$value['name'].'_'.$sel['name']])){
                                                // $day_work_time = $settings[$slug.'_'.$value['name'].'_'.$sel['name']];
                                                
                                                if($checkbox_select_value == $value1){
                                                    $selected = 'selected';
                                                }
                                            }
                                            $output .= '<option '.$selected.' value="'.$value1.'">'.$value1.'</option>';
                                        }
                                    $output .= '</select>';

                                }
                            }
                            if(isset($value['selects'])){
                                $output .= '<div class="vrr-more-time-wrap-all vrr-inline">';

                                    $checkbox_name_more = $this->option_name.'['.$slug.'_'.$value['name'].'_more]';
                                    $checkbox_value_more = '';
                                    if(isset($settings[$slug.'_'.$value['name'].'_more'])){
                                        $checkbox_value_more = $settings[$slug.'_'.$value['name'].'_more'];
                                    }
                                    $checked_more = '';
                                    if($checkbox_value_more == "1"){
                                        $checked_more = 'checked';
                                    }

                                    $output .= '<div class="vrr-more-time-wrap vrr-inline">';
                                        $output .= '<span class="">'.__('More time interval', 'vrr').'</span>';
                                        $output .= '<input type="checkbox" id="'.$checkbox_name_more.'" name="'.$checkbox_name_more.'" '.$checked_more.' value="'.$checkbox_value_more.'"/>';
                                    $output .= '</div>';

                                    $output .= '<div class="vrr-more-time-selects-wrap vrr-inline" style="';
                                        if($checked_more == ""){
                                            $output .= 'display: none;';
                                        }
                                    $output .= '">';
                                        foreach ($value['selects'] as $key => $sel) { // _more
                                            $checkbox_select_name = $this->option_name.'['.$slug.'_'.$value['name'].'_'.$sel['name'].'_more]';
                                            $checkbox_select_value = '';
                                            if(isset($settings[$slug.'_'.$value['name'].'_'.$sel['name'].'_more']) ) {
                                                $checkbox_select_value = $settings[$slug.'_'.$value['name'].'_'.$sel['name'].'_more'];
                                            } else {
                                                $checkbox_select_value = $default_options[$slug.'_'.$value['name'].'_'.$sel['name'].'_more'];
                                            }

                                            $output .= '<select id="'.$checkbox_select_name.'" name="'.$checkbox_select_name.'" value="'. $checkbox_select_value .'">';
                                                foreach ($sel['options'] as $key1 => $value1) {
                                                    $selected = '';
                                                    if(isset($settings[$slug.'_'.$value['name'].'_'.$sel['name'].'_more'])){
                                                        // $day_work_time = $settings[$slug.'_'.$value['name'].'_'.$sel['name']];
                                                        
                                                        if($checkbox_select_value == $value1){
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                    $output .= '<option '.$selected.' value="'.$value1.'">'.$value1.'</option>';
                                                }
                                            $output .= '</select>';
                                        }
                                    $output .= '</div>';
                                $output .= '</div>';
                            }

                            $output .= '<div class="vrr-inline">';
                                $output .= '<span class="">'.__('Enable', 'vrr').'</span>';
                                $output .= '<input type="checkbox" id="'.$checkbox_name.'" name="'.$checkbox_name.'" '.$checked.' value="'.$checkbox_value.'"/>';
                            $output .= '</div>';
                        $output .= '</div>';
                    }  
                $output .= '</fieldset>';
            } else if($field['type'] === 'checkbox'){ // checkbox
                $checkbox_name = $this->option_name.'['.$slug.']';
                $checkbox_value = '';
                if(isset($settings[$slug])){
	                $checkbox_value = $settings[$slug];
	            }
                $checked = '';
                if($checkbox_value == "1"){
                    $checked = 'checked';
                }
                $output .= '<p class="settings-checkbox-wrap">';
                    $output .= '<input type="checkbox" id="'.$checkbox_name.'" name="'.$checkbox_name.'" '.$checked.' value="'.$checkbox_value.'"/>';
                $output .= '</p>';
            } else if($field['type'] === 'colors'){ // colors
                $output .= '<div class="settings-colors-wrap">';
                    foreach ($field['inputs'] as $key => $value) {
                        $color_name = $this->option_name.'['.$slug.'_'.$value['name'].']';
                        $color_title = $value['title'];
                        $color_value = '';
                        if(isset($settings[$slug.'_'.$value['name']])){
                            $color_value = $settings[$slug.'_'.$value['name']];
                        } else {
                            if(isset($default_options[$slug.'_'.$value['name']])){
                                $color_value = $default_options[$slug.'_'.$value['name']];
                            }
                        }
                        $output .= '<div class="settings-color-wrap">';
                            $output .= '<div class="settings-color-title-wrap">';
                                $output .= '<label for="'.$color_name.'">'.$color_title.'</label>';
                            $output .= '</div>';
                            $output .= '<div class="settings-color-input-wrap">';
                                $output .= '<input type="text" class="vrr-settings-color" id="'.$color_name.'" name="'.$color_name.'" value="'.$color_value.'"/>';
                            $output .= '</div>';
                        $output .= '</div>';
                    }
                $output .= '</div>';
            } // end of choises

            $output .= '</div>';


            if($tab == "1"){
                $output_tab_1_fake .= $output;
            } else if($tab == "2"){
                $output_tab_2_fake .= $output;
            } else if($tab == "3"){
                $output_tab_3_fake .= $output;
            } else if($tab == "4"){
                $output_tab_4_fake .= $output;
            } else if($tab == ""){
                $output_all .= $output;
            }

        } // foreach
            
        if($output_all == ""){
            $output_tab .= '<div class="vrr-settings-tabs-wrap">';
                $output_tab .= '<div class="vrr-settings-tabs-header">';
                    $active = '';
                    $active_1 = '';
                    $active_2 = '';
                    $active_3 = '';
                    $active_4 = '';
                    $tab_1 = 'display:none;';
                    $tab_2 = 'display:none;';
                    $tab_3 = 'display:none;';
                    $tab_4 = 'display:none;';

                    if(isset($_GET['vrr-tab']) && !empty($_GET['vrr-tab'])){
                        $active_tab = $_GET['vrr-tab'];
                        if($active_tab == "1"){
                            $active_1 = "active";
                            $tab_1 = "";
                        } else if($active_tab == "2"){
                            $active_2 = "active";
                            $tab_2 = "";
                        } else if($active_tab == "3"){
                            $active_3 = "active";
                            $tab_3 = "";
                        } else if($active_tab == "4"){
                            $active_4 = "active";
                            $tab_4 = "";
                        } else {
                            $active_1 = "active";
                            $tab_1 = "";
                        }
                    } else {
                        $active_1 = "active";
                        $tab_1 = "";
                    }
                    if($output_tab_1_fake != ""){
                        $output_tab .= '<a href="#vrr-settings-tab-1" data-tab="1" class="vrr-settings-tabs-header-btn btn '.$active_1.'">'.__('Main','vrr').'</a>';
                    }
                    if($output_tab_2_fake != ""){
                        $output_tab .= '<a href="#vrr-settings-tab-2" data-tab="2" class="vrr-settings-tabs-header-btn '.$active_2.'">'.__('Work Days & Form','vrr').'</a>';
                    }
                    if($output_tab_3_fake != ""){
                        $output_tab .= '<a href="#vrr-settings-tab-3" data-tab="3" class="vrr-settings-tabs-header-btn '.$active_3.'">'.__('SMS','vrr').'</a>';
                    }
                    if($output_tab_4_fake != ""){
                        $output_tab .= '<a href="#vrr-settings-tab-4" data-tab="4" class="vrr-settings-tabs-header-btn '.$active_4.'">'.__('Layout Colors','vrr').'</a>';
                    }
                $output_tab .= '</div>';

                $output_tab .= '<div class="vrr-settings-tabs-wrap-inner">';
                    if($output_tab_1_fake != ""){
                        $output_tab .= '<div style="'.$tab_1.'" data-tab="1" id="vrr-settings-tab-1" class="vrr-settings-tab vrr-settings-tab-1">';
                            $output_tab .= $output_tab_1_fake;
                        $output_tab .= '</div>';
                    }
                    if($output_tab_2_fake != ""){
                        $output_tab .= '<div style="'.$tab_2.'" data-tab="2" id="vrr-settings-tab-2" class="vrr-settings-tab vrr-settings-tab-2">';
                            $output_tab .= $output_tab_2_fake;
                        $output_tab .= '</div>';
                    }
                    if($output_tab_3_fake != ""){
                        $output_tab .= '<div style="'.$tab_3.'" data-tab="3" id="vrr-settings-tab-3" class="vrr-settings-tab vrr-settings-tab-3">';
                            $output_tab .= $output_tab_3_fake;
                        $output_tab .= '</div>';
                    }
                    if($output_tab_4_fake != ""){
                        $output_tab .= '<div style="'.$tab_4.'" data-tab="4" id="vrr-settings-tab-4" class="vrr-settings-tab vrr-settings-tab-4">';
                            $output_tab .= $output_tab_4_fake;
                        $output_tab .= '</div>';
                    }
                $output_tab .= '</div>';
            $output_tab .= '</div>';
            
            return $output_tab;
        } else {
            return $output_all;
        }

        
    }

    function custom_image_uploader_field( $name, $value = '') {
        $image_button_text = __('Upload image','vrr');
        $image_remove_button_text = __('Remove image','vrr');
        $image = '';
        $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
        $display = 'none'; // display state ot the "Remove image" button
     
        if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
     
            // $image_attributes[0] - image URL
            // $image_attributes[1] - image width
            // $image_attributes[2] - image height
     
            $image = $image_attributes[0];
            $display = 'inline-block';
     
        }  else if(isset($value) && !empty($value)) {
            $image = $value;
            $display = 'inline-block';
        }
        
        $output = '';
        $output .='
        <div class="vrr-background-field-wrap-inner">
            <div class="vrr-background-buttons-wrap">
                <a href="#" class="vrr-custom_upload_image_button button"><span>' . $image_button_text . '</span></a>
                <a href="#" class="vrr-custom_remove_image_button button" style="display:' . $display . '"><span>' . $image_remove_button_text . '</span></a>
            </div>
            <div class="vrr-background-image-holder-wrap" style="background-image: url('. $image .');"></div>
            <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
        </div>';

        return $output;
    }

    public function assets() {
        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }

        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_style('vrr-select2-css', plugin_dir_url(dirname(__FILE__)).'assets/css/select2.css', [], $this->version);
        wp_enqueue_style($this->plugin_slug, plugin_dir_url(__FILE__).'css/visual-restaurant-reservation-admin.css', [], $this->version);
        // wp_deregister_script( 'jquery-ui-core' );
        wp_enqueue_script('vrr-jquery-ui', plugin_dir_url(dirname(__FILE__)).'assets/js/jquery-ui.min.js', ['jquery'], $this->version, true);
        wp_enqueue_script('vrr-select2-js', plugin_dir_url(dirname(__FILE__)).'assets/js/select2.js', ['jquery'], $this->version, true);
        wp_enqueue_script('vrr-custom-image-loader', plugin_dir_url(__FILE__).'js/custom_image_loader.js', ['jquery'], $this->version, true);
        wp_enqueue_script('vrr-clipboard-js', plugin_dir_url(dirname(__FILE__)).'assets/js/clipboard.js', ['jquery'], $this->version, true);
        wp_enqueue_script($this->plugin_slug, plugin_dir_url(__FILE__).'js/visual-restaurant-reservation-admin.js', ['jquery'], $this->version, true);
        
    }

    
    


    public function register_settings() {
        register_setting($this->settings_group, $this->option_name, array($this, 'settings_save_validate'));
        

    }

    

    function set_custom_columns($columns) {
        $columns = array();
        $columns["cb"] = "<input type=\"checkbox\" />";
        $columns["title"] = __( "Title", 'vrr' );
        $columns["visual_restaurant_reservation_id"] = __( "Table Number", 'vrr' );
        $columns["visual_restaurant_reservation_date"] = __( "Date", 'vrr' );
        $columns["visual_restaurant_reservation_time"] = __( "Time", 'vrr' );
        $columns["visual_restaurant_reservation_name"] = __( "Name", 'vrr' );
        $columns["visual_restaurant_reservation_phone"] = __( "Phone Number", 'vrr' );

        return $columns;
    }

    public function post_type_columns() {
        
        add_filter( 'manage_vrr_posts_columns', array($this, 'set_custom_columns') );
        add_filter( 'manage_edit-vrr_sortable_columns', array($this, 'sortable_columns') );
        add_action( 'pre_get_posts',  array($this, 'sortable_columns_orderby') );
        add_action( 'manage_vrr_posts_custom_column', array($this, 'render_custom_columns'), 10, 2 );

    }

    public function render_custom_columns($column, $post_id) {

        if ( $column === "visual_restaurant_reservation_id" ) {
            $id = get_post_meta( $post_id, '_visual_restaurant_reservation_id', true );
            $value = $id;
        } else {
            $value = get_post_meta( $post_id, '_' . $column, true );
        }

        echo $value;
    }

    function sortable_columns( $columns ) {
        $columns['visual_restaurant_reservation_id'] = 'id';
        $columns['visual_restaurant_reservation_date'] = 'date';
        $columns['visual_restaurant_reservation_time'] = 'time';
     
        //To make a column 'un-sortable' remove it from the array
        //unset($columns['date']);
     
        return $columns;
    }

    function sortable_columns_orderby( $query ) {
        if( ! is_admin() ){
            return;
        }
        if( $query->is_post_type_vrr){
            $orderby = $query->get( 'orderby');
            if( 'date' == $orderby ) {
                $query->set('meta_key','_visual_restaurant_reservation_date');
                $query->set('orderby','meta_value date');
            } else if('time' == $orderby){
                $query->set('meta_key','_visual_restaurant_reservation_time');
                $query->set('orderby','meta_value time');
            } else if('id' == $orderby){
                $query->set('meta_key','_visual_restaurant_reservation_id');
                $query->set('orderby','meta_value_num');
            }
        }
    }


    public function settings_save_validate($input){
        $options_array = get_option($this->option_name);

        if(isset($options_array) && !empty($options_array)){
            
        } else {
            $options_array = array();
        }

        

        if(isset($input)){
        	$sms_error = 0;
        	$sms_same = 0;
        	$sms_message = '';
            foreach ($input as $key => $value) {
                if( isset( $input[$key])){
                    if($key == "canvas_width" || $key == "canvas_height"){ // check canvas width and height
                        if($value == ""){
                            $value = '500';
                        } else if(intval($value) > 1500){
                            $value = '1500';
                        } else if(intval($value) < 500){
                            $value = '500';
                        }
                    }
                    if($key == "form_email"){ // check email
                        if(is_email($value)){

                        } else {
                            $value = get_bloginfo('admin_email');
                        }
                    }
                    if(isset($input['send_sms']) && $input['send_sms'] == '1'){
	                    if($key == "sms_key" && $value == ""){
	                    	$sms_error = 1;
	                    } else if($key == "sms_key" && $value == $options_array['sms_key']){
	                    	$sms_same++;
	                    }
	                    if($key == "sms_secret" && $value == ""){
	                    	$sms_error = 1;
	                    } else if($key == "sms_secret" && $value == $options_array['sms_secret']){
	                    	$sms_same++;
	                    }
	                    if($key == "sms_phone" && $value == ""){
	                    	$sms_error = 1;
	                    } else if($key == "sms_phone" && $value == $options_array['sms_phone']){
	                    	$sms_same++;
	                    }
	                } 
	               
	                

	                // not sanitaze
                    if($key == "position" || $key == "form_confirm_text" || $key == "form_thx" || $key == "sms_key" || $key == "sms_secret" || $key == "sms_phone"){
                        $options_array[$key] = $value;
                    } else { // sanitaze ALL the others
                        $options_array[$key] = sanitize_text_field($value);
                    }
                }
            }

            if(isset($input['send_sms']) && $input['send_sms'] == '1'){
	            if($sms_error == 1){
		        	add_settings_error( 'sms_invalid_data', 'sms_invalid_data', 'Some of SMS settings data is empty', 'error' );
		        } else {

		        	if($sms_same < 3){
				    	if(isset($input['sms_key']) && !empty($input['sms_key']) 
			    		&& isset($input['sms_secret']) && !empty($input['sms_secret'])
			    		&& isset($input['sms_phone']) && !empty($input['sms_phone'])) {
					    	$sms_key = $input['sms_key'];
					    	$sms_secret = $input['sms_secret'];
					    	$sms_phone = $input['sms_phone'];
					    	$sine_name = get_bloginfo('name');
					    	
					    	$path = INFO::get_plugin_path();
					    	if(file_exists($path.'\includes\nexmo\vendor\autoload.php')){
						    	include_once($path.'\includes\nexmo\vendor\autoload.php');
						        $basic  = new \Nexmo\Client\Credentials\Basic($sms_key, $sms_secret);
						        $client = new \Nexmo\Client($basic);

						        $url = 'https://rest.nexmo.com/account/get-balance?' . http_build_query([
								        'api_key' => $sms_key,
								        'api_secret' => $sms_secret
								    ]);

								$ch = curl_init($url);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								$response = curl_exec($ch);
								$response = json_decode($response, true);

								if(isset($response['error-code-label'])){
									$sms_message = $response['error-code-label'];
									add_settings_error( 'sms_invalid_data', 'sms_invalid_data', 'SMS settings has invalid data. ('.$sms_message.')', 'error' );
								} else {
									$sms_message = $response['value'];
									add_settings_error( 'sms_invalid_data', 'sms_invalid_data', 'SMS key and secret settings is valid. Balance - '.$sms_message, 'update' );
								}
					        }
				    	}
				    }
		        }
		    }

            // checkbox
            $work_days = array(
                "work_days_monday",
                "work_days_tuesday",
                "work_days_wednesday",
                "work_days_thursday",
                "work_days_friday",
                "work_days_saturday",
                "work_days_sunday", 

                "work_days_monday_more",
                "work_days_tuesday_more",
                "work_days_wednesday_more",
                "work_days_thursday_more",
                "work_days_friday_more",
                "work_days_saturday_more",
                "work_days_sunday_more",

                "shop_people_amount",
                "form_confirm",
                "send_sms",
                "show_email",
                "show_people",
                "show_seats",
                'show_time_to',
            );

            foreach ($work_days as $key => $value) {
                if(array_key_exists($value, $input)){
                    $options_array[$value] = sanitize_text_field('1');
                } else {
                    if(array_key_exists('position', $input) ){
                        
                    } else if(!array_key_exists('position', $input) ) {
                        $options_array[$value] = sanitize_text_field('0');
                    }
                }
            }
        }

        
        return $options_array;
        
    }


   
	/*function vrr_notice() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e('Settings updated','vrr'); ?></p>
		</div>
		<?php
	}
*/


    // Element Mapping
    public function vc_infobox_mapping() {
         
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }
         
        // Map the block with vc_map()
        vc_map( 
            array(
                'name' => __('Visual Restaurant Reservation', 'vrr'),
                'base' => 'visual_restaurant_reservation',
                'description' => __('Visual Restaurant Reservation', 'vrr'), 
                'category' => 'Content',   
                'icon' => plugin_dir_url(dirname(__FILE__)).'assets/img/composer-icon.png',            
                'params' => array(   
                         
                    array(
                        'type' => 'textfield',
                        // 'holder' => 'span',
                        'class' => 'title-class',
                        'heading' => 'Element ID',
                        'param_name' => 'vrr_shortcode_id',
                        'value' => '',
                        'description' => 'Enter element ID',
                        'admin_label' => true,
                        'weight' => 0,
                        // 'group' => 'Custom Group',
                    ),  
                    array(
                        'type' => 'textfield',
                        // 'holder' => 'span',
                        'class' => 'title-class',
                        'heading' => 'Extra class name',
                        'param_name' => 'vrr_shortcode_class',
                        'value' => '',
                        'description' => 'Style particular content element differently - add a class name and refer to it in custom CSS.',
                        'admin_label' => true,
                        'weight' => 0,
                        // 'group' => 'Custom Group',
                    ),  
                ),
            )
        );                                
        
    }
  

    
     
    


    public function add_menus() {
        $plugin_name = Info::get_plugin_title();

        add_menu_page( 
            $plugin_name, 
            $plugin_name, 
            'manage_options', 
            '/edit.php?post_type=vrr', 
            '', 
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAVRJREFUeNqs08srBWEYx/EZZ8ilsLA4C0VYWLCykI1/wIrFWVgoydLWjlJELGwsZOGSW47IRslSthZyidyTpDTWKHyfetRpzDsz5sxTnzNvc975zXsb23VdK6BsdGMAjTjHNDZNDxQY7hehH1dYQSsq0IYsTpDRF4YG9uISc6g3vLAJGzhGl1/g74gkaB61VrRqxhZOdWlSNms4TGMQZVb+dS0jrEKplUw1OPzs4AAluh4dKIybKFN+5VqJGezhA+3oQ3WcwDvPJhxiDWdI6/HoDDhifwJvuNb5/PeiB3gfXxhBSz6BuSVLsaSHPBXUMco0HrGKpyhTDgq8QA9qdGRO2Ogs7eQt2YwxrHvuf0cZoQSWa/sIE9jWTYhVEjgL2ZjFJD4VCRxKIEdmtIxJ5x8P+a3hJxYwjnvTppiqOKf9rkFTuA3bZVO94Rm7GMWDX6cfAQYA9hxOkHh/0poAAAAASUVORK5CYII=',
            // plugin_dir_url(dirname(__FILE__)).'assets/img/menu-icon.svg', 
            81.4
        );
        add_submenu_page( 
            '/edit.php?post_type=vrr', 
            __( 'Table Reservation','vrr' ), 
            __( 'Table Reservation','vrr' ), 
            'manage_options', 
            '/edit.php?post_type=vrr', 
            ''
        );

        add_submenu_page( 
            '/edit.php?post_type=vrr', 
            __( 'Set Layout', 'vrr'), 
            __( 'Set Layout', 'vrr'), 
            'manage_options', 
            'layout', 
            array($this, 'render_layout')
        );

        add_submenu_page( 
            '/edit.php?post_type=vrr', 
            __( 'Settings','vrr'), 
            __( 'Settings','vrr'), 
            'manage_options', 
            'settings', 
            array($this, 'render_settings')
        );
    }

    public function render_settings() {
        $time_all = INFO::$TIME_ALL;
        $path = INFO::get_plugin_path();
        $field_args = [
            [
                'label' => __('Number of days to book ahead','vrr'),
                'slug'  => 'days_ahead',
                'type'  => 'number',
                'after' => __('day(s)','vrr'),
                'tab'   => '1',
            ],
            [
                'label' => __('Hours to book table after reservation','vrr'),
                'slug'  => 'table_booked_time',
                'type'  => 'number',
                'after' => __('hour(s)','vrr'),
                'tab'   => '1',
            ],
            [
                'label' => __('Hours to book table before reservation','vrr'),
                'slug'  => 'table_before_booked_time',
                'type'  => 'number',
                'after' => __('hour(s)','vrr'),
                'tab'   => '1',
            ],
            [
                'label' => __('Let visitors to pick amount of people for reserved table','vrr'),
                'slug'  => 'shop_people_amount',
                'type'  => 'checkbox',
                'description' => '',//__('this is always available on mobile devices','vrr')
                'tab'   => '1',
            ],
            [
                'label' => __('Show E-mail field in the form','vrr'),
                'slug'  => 'show_email',
                'type'  => 'checkbox',
                'description' => '',
                'tab'   => '1',
            ],
            [
                'label' => __('Show Table seats on layout','vrr'),
                'slug'  => 'show_seats',
                'type'  => 'checkbox',
                'description' => '',
                'tab'   => '1',
            ],
            [
                'label' => __('Show Table max amount of persons on layout','vrr'),
                'slug'  => 'show_people',
                'type'  => 'checkbox',
                'description' => '',
                'tab'   => '1',
            ],
            [
                'label' => __('Show the selected time up to the table reservation','vrr'),
                'slug'  => 'show_time_to',
                'type'  => 'checkbox',
                'description' => '',
                'tab'   => '1',
            ],
            [
                'label' => __('Plugin layouts types','vrr'),
                'slug'  => 'shortcode_layout',
                'type'  => 'radio',
                'inputs' => array(
                    0 => array(
                        'title' => __('Classic Layout','vrr'),
                        'value' => '1',
                        'description' => __('first you pick table and then in popup you need to pick date, time, etc.','vrr'),
                    ),
                    1 => array(
                        'title' => __('Smart Layout','vrr'),
                        'value' => '2',
                        'description' => __('first you pick the date then you need to pick time and the table witch available on this date and time. Then in popup you need to fill name, phone, etc.','vrr'),
                    ),
                    2 => array(
                        'title' => __('Simple Layout','vrr'),
                        'value' => '3',
                        'description' => __('in this layout disabled option to pick the table. All you need is pick the date, time and fill the name, phone, etc.','vrr'),
                    ),
                ),
                'tab'   => '1',
            ],
            [
                'label' => __('Work days','vrr'),
                'slug'  => 'work_days',
                'type'  => 'checkboxes',
                'tab'   => '2',
                'description' => __('setting up work of each day of the week for your restaurant(work time and work days)','vrr'),
                'checkboxes' => array(
                    0 => array(
                        'title' => __('Monday','vrr'),
                        'name' => 'monday',
                        'selects' => array(
                            array(
                                'options' => $time_all,
                                'name' => 'time_start'
                            ),
                            array(
                                'options' => $time_all,
                                'name' => 'time_end'
                            )
                        )
                    ),
                    1 => array(
                        'title' => __('Tuesday','vrr'),
                        'name' => 'tuesday',
                        'selects' => array(
                            array(
                                'options' => $time_all,
                                'name' => 'time_start'
                            ),
                            array(
                                'options' => $time_all,
                                'name' => 'time_end'
                            )
                        )
                    ),
                    2 => array(
                        'title' => __('Wednesday','vrr'),
                        'name' => 'wednesday',
                        'selects' => array(
                            array(
                                'options' => $time_all,
                                'name' => 'time_start'
                            ),
                            array(
                                'options' => $time_all,
                                'name' => 'time_end'
                            )
                        )
                    ),
                    3 => array(
                        'title' => __('Thursday','vrr'),
                        'name' => 'thursday',
                        'selects' => array(
                            array(
                                'options' => $time_all,
                                'name' => 'time_start'
                            ),
                            array(
                                'options' => $time_all,
                                'name' => 'time_end'
                            )
                        )
                    ),
                    4 => array(
                        'title' => __('Friday','vrr'),
                        'name' => 'friday',
                        'selects' => array(
                            array(
                                'options' => $time_all,
                                'name' => 'time_start'
                            ),
                            array(
                                'options' => $time_all,
                                'name' => 'time_end'
                            )
                        )
                    ),
                    5 => array(
                        'title' => __('Saturday','vrr'),
                        'name' => 'saturday',
                        'selects' => array(
                            array(
                                'options' => $time_all,
                                'name' => 'time_start'
                            ),
                            array(
                                'options' => $time_all,
                                'name' => 'time_end'
                            )
                        )
                    ),
                    6 => array(
                        'title' => __('Sunday','vrr'),
                        'name' => 'sunday',
                        'selects' => array(
                            array(
                                'options' => $time_all,
                                'name' => 'time_start'
                            ),
                            array(
                                'options' => $time_all,
                                'name' => 'time_end'
                            )
                        )
                    ),
                )
            ],
            [
                'label' => __('Reservation Form message','vrr'),
                'slug'  => 'form_message',
                'type'  => 'text',
                'description' => __('Appears after "Book Now" button','vrr'),
                'tab'   => '2',
            ],
            [
                'label' => __('Output Form Confirmation checkbox','vrr'),
                'slug'  => 'form_confirm',
                'type'  => 'checkbox',
                'description' => __('Confirm usage of personal data checkbox','vrr'),
                'tab'   => '2',
            ],
            [
                'label' => __('Form Confirmation text','vrr'),
                'slug'  => 'form_confirm_text',
                'type'  => 'textarea',
                'description' => __('Outputs if Form Confirmation checkbox is checked','vrr'),
                'tab'   => '2',
            ],
            [
                'label' => __('Form success "Thank You" message','vrr'),
                'slug'  => 'form_thx',
                'type'  => 'textarea',
                'description' => __('Leave empty to use default','vrr'),
                'tab'   => '2',
            ],
            [
                'label' => __('E-mail to send tables reservation notifications','vrr'),
                'description' => __('if leave empty, will send to admin email settings field','vrr'),
                'slug'  => 'form_email',
                'type'  => 'text',
                'tab'   => '2',
            ],
            [
                'label' => __('Send SMS notification on table reservation','vrr'),
                'slug'  => 'send_sms',
                'type'  => 'checkbox',
                'description' => __('send SMS through Nexmo service. First you need to setup Nexmo data. ','vrr').'<a target="_blank" href="https://www.nexmo.com/">Nexmo site</a>',
                'tab'   => '3',
            ],
            [
                'label' => __('Nexmo SMS key','vrr'),
                'description' => '',
                'slug'  => 'sms_key',
                'type'  => 'text',
                'tab'   => '3',
            ],
            [
                'label' => __('Nexmo SMS secret','vrr'),
                'description' => '',
                'slug'  => 'sms_secret',
                'type'  => 'text',
                'tab'   => '3',
            ],
            [
                'label' => __('Phone number to send SMS','vrr'),
                'description' => __('The number you are sending the SMS to in E.164 format. For example 447700900000','vrr'),
                'slug'  => 'sms_phone',
                'type'  => 'text',
                'tab'   => '3',
            ],
            [
                'label' => __('Colors Main','vrr'),
                'slug'  => 'color_main',
                'type'  => 'colors',
                'tab'   => '4',
                'description' => __('All settings of color applies only on frontend layout of the shortcode','vrr'),
                'inputs' => array(
                    0 => array(
                        'title' => __('Main layout color','vrr'),
                        'name' => 'main'
                    ),
                    1 => array(
                        'title' => __('Tables background color','vrr'),
                        'name' => 'tables'
                    ),
                    15 => array(
                        'title' => __('Seats background color','vrr'),
                        'name' => 'seats'
                    ),
                    16 => array(
                        'title' => __('Max seats circle background color','vrr'),
                        'name' => 'max_seats'
                    ),
                    17 => array(
                        'title' => __('Max seats circle text color','vrr'),
                        'name' => 'max_seats_text'
                    ),
                    18 => array(
                        'title' => __('Table Number background color','vrr'),
                        'name' => 'table_id'
                    ),
                    19 => array(
                        'title' => __('Table Number text color','vrr'),
                        'name' => 'table_id_text'
                    ),
                ),
            ],
            [
                'label' => __('Colors Datepicker','vrr'),
                'slug'  => 'color_datepicker',
                'type'  => 'colors',
                'tab'   => '4',
                'description' => __('All settings of color applies only on frontend layout of the shortcode','vrr'),
                'inputs' => array(
                    1 => array(
                        'title' => __('Datepicker text color','vrr'),
                        'name' => 'datepicker_text_all'
                    ),
                    2 => array(
                        'title' => __('Datepicker dates background color','vrr'),
                        'name' => 'datepicker'
                    ),
                    3 => array(
                        'title' => __('Datepicker disabled dates background color','vrr'),
                        'name' => 'datepicker_disabled'
                    ),
                    4 => array(
                        'title' => __('Datepicker active dates background color','vrr'),
                        'name' => 'datepicker_active'
                    ),
                    5 => array(
                        'title' => __('Datepicker dates text color','vrr'),
                        'name' => 'datepicker_text'
                    ),
                    6 => array(
                        'title' => __('Datepicker disabled dates text color','vrr'),
                        'name' => 'datepicker_text_disabled'
                    ),
                    7 => array(
                        'title' => __('Datepicker active dates text color','vrr'),
                        'name' => 'datepicker_text_active'
                    ),
                    8 => array(
                        'title' => __('Datepicker arrow color','vrr'),
                        'name' => 'datepicker_arrow'
                    ),
                    9 => array(
                        'title' => __('Datepicker arrow background color on hover','vrr'),
                        'name' => 'datepicker_arrow_hover'
                    ),
                    10 => array(
                        'title' => __('Datepicker background arrow color','vrr'),
                        'name' => 'datepicker_arrow_background'
                    ),
                    11 => array(
                        'title' => __('Datepicker disabled arrow color','vrr'),
                        'name' => 'datepicker_arrow_disabled'
                    ),
                    12 => array(
                        'title' => __('Datepicker disabled background arrow color','vrr'),
                        'name' => 'datepicker_arrow_background_disabled'
                    ),
                )
            ],
            [
                'label' => __('Colors Form','vrr'),
                'slug'  => 'color_form',
                'type'  => 'colors',
                'tab'   => '4',
                'description' => __('All settings of color applies only on frontend layout of the shortcode','vrr'),
                'inputs' => array(
                    2 => array(
                        'title' => __('Button "Book Now" background color','vrr'),
                        'name' => 'book_now'
                    ),
                    3 => array(
                        'title' => __('Button "Book Now" text color','vrr'),
                        'name' => 'book_now_text'
                    ),
                    4 => array(
                        'title' => __('Form background color','vrr'),
                        'name' => 'form'
                    ),
                    50 => array(
                        'title' => __('Booking form Big titles color','vrr'),
                        'name' => 'booking_title'
                    ),
                    5 => array(
                        'title' => __('Big titles color','vrr'),
                        'name' => 'big_titles'
                    ),
                    6 => array(
                        'title' => __('Small titles color','vrr'),
                        'name' => 'small_titles'
                    ),
                    20 => array(
                        'title' => __('Descriptions text color','vrr'),
                        'name' => 'small_descriptions'
                    ),
                    
                    9 => array(
                        'title' => __('Inputs and selects background color','vrr'),
                        'name' => 'inputs'
                    ),
                    10 => array(
                        'title' => __('Inputs and selects text color','vrr'),
                        'name' => 'inputs_text'
                    ),
                    11 => array(
                        'title' => __('Error messages background color','vrr'),
                        'name' => 'error'
                    ),
                    12 => array(
                        'title' => __('Error messages text color','vrr'),
                        'name' => 'error_text'
                    ),
                    13 => array(
                        'title' => __('Info messages background color','vrr'),
                        'name' => 'message'
                    ),
                    14 => array(
                        'title' => __('Info messages and confirm text color','vrr'),
                        'name' => 'message_text'
                    ),//21 next
                    
                ),
            ],
        ];

        // Model
        $settings = $this->settings;

        // Controller
        $fields = $this->custom_settings_fields($field_args, $settings);
        $settings_group = $this->settings_group;
        $option_name = INFO::OPTION_NAME;
        $heading = Info::get_plugin_title();
        $plugin_name = Info::get_plugin_title();
        $submit_text = __('Submit', 'vrr');

        // View
        require_once plugin_dir_path(dirname(__FILE__)).'admin/partials/view_settings.php';
    }

    /**
     * Render the view using MVC pattern.
     */
    public function render_layout() {

        // Generate the settings fields
        $field_args = [
            [
                'label' => __('Render','vrr'),
                'slug'  => 'position',
                'type'  => 'text'
            ]
        ];

        // Model
        $settings = $this->settings;

        // Controller
        $fields = $this->custom_settings_fields($field_args, $settings);
        $settings_group = $this->settings_group;
        $option_name = INFO::OPTION_NAME;
        $heading = Info::get_plugin_title();
        $plugin_name = Info::get_plugin_title();
        $submit_text = __('Submit', 'vrr');
        $tables = INFO::$TABLES;

        // $fields_background = $this->custom_background_settings_fields($field_background_args, $settings);
        $background_canvas = $this->custom_image_uploader_field( $this->option_name.'[background-canvas]', $settings['background-canvas'] );
        $background_table = $this->custom_image_uploader_field( $this->option_name.'[background-table]', $settings['background-table'] );
        $background_seat = $this->custom_image_uploader_field( $this->option_name.'[background-seat]', $settings['background-seat'] );

        // View
        require_once plugin_dir_path(dirname(__FILE__)).'admin/partials/view_layout.php';
    }
}
