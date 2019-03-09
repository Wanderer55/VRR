<?php
namespace VISUAL_RESTAURANT_RESERVATION;
/**
 * This class defines all code necessary to run during the plugin's activation.
 */
class Activator
{
    /**
     * Sets the default options in the options table on activation.
     */
    public static function activate() {
        $option_name = INFO::OPTION_NAME;
        $default_options = INFO::$DEFAULT_OPTIONS;
        if (empty(get_option($option_name))) {
            update_option($option_name, $default_options);
        }else {
            $options = get_option($option_name);
            $new_options = array();
            foreach ($default_options as $key => $value) {
                if(isset($options[$key])){
                    $new_options[$key] = $options[$key];
                } else {
                    $new_options[$key] = $default_options[$key];
                }
            }
            // var_dump($new_options);
            update_option($option_name, $new_options);
        }
    }
}
