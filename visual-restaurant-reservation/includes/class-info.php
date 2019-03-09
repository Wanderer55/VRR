<?php

namespace VISUAL_RESTAURANT_RESERVATION;

/**
 * The class containing informatin about the plugin.
 */
class Info
{
    /**
     * The plugin slug.
     *
     * @var string
     */
    const SLUG = 'visual-restaurant-reservation';

    /**
     * The plugin version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The nae for the entry in the options table.
     *
     * @var string
     */
    const OPTION_NAME = 'visual_restaurant_reservation_settings';

    /**
     * The URL where your update server is located (uses wp-update-server).
     *
     * @var string
     */
    // const UPDATE_URL = 'https://example.com/';

    // const DEFAULT_OPTIONS = array(
    public static $DEFAULT_OPTIONS = array(
        'canvas_width' => '700',
        'canvas_height' => '700',
        'canvas_elements_size' => 'medium',
        'background-canvas' => '',
        'background-table' => '',
        'background-seat' => '',
        'position' => '',

        'form_message' => 'Manager will call you back',
        'form_confirm' => '1',
        'form_confirm_text' => 'I\'m confirm that i give rights to use my personal data <a target="_blank" href="#">Link</a>',
        'form_email' => '',
        'form_thx' => '',

        'shortcode_layout' => '1',
        'shop_people_amount' => '0',
        'show_email' => '0',
        'show_people' => '1',
        'show_seats' => '1',
        'show_time_to' => '0',
        'send_sms' => '0',
        'sms_key' => '',
        'sms_secret' => '',
        'sms_phone' => '',

        'days_ahead' => '30',
        'table_booked_time' => '2',
        'table_before_booked_time' => '2',
        'work_days_monday' => '1', 
        'work_days_tuesday' => '1', 
        'work_days_wednesday' => '1', 
        'work_days_thursday' => '1', 
        'work_days_friday' => '1', 
        'work_days_saturday' => '1', 
        'work_days_sunday' => '1', 

        'work_days_monday_more' => '0', 
        'work_days_tuesday_more' => '0', 
        'work_days_wednesday_more' => '0', 
        'work_days_thursday_more' => '0', 
        'work_days_friday_more' => '0', 
        'work_days_saturday_more' => '0', 
        'work_days_sunday_more' => '0', 

        'work_days_monday_time_start' => '09:00', 
        'work_days_tuesday_time_start' => '09:00', 
        'work_days_wednesday_time_start' => '09:00', 
        'work_days_thursday_time_start' => '09:00', 
        'work_days_friday_time_start' => '09:00', 
        'work_days_saturday_time_start' => '09:00', 
        'work_days_sunday_time_start' => '09:00', 

        'work_days_monday_time_end' => '23:00', 
        'work_days_tuesday_time_end' => '23:00', 
        'work_days_wednesday_time_end' => '23:00', 
        'work_days_thursday_time_end' => '23:00', 
        'work_days_friday_time_end' => '23:00', 
        'work_days_saturday_time_end' => '23:00', 
        'work_days_sunday_time_end' => '23:00', 

        'work_days_monday_time_start_more' => '09:00', 
        'work_days_tuesday_time_start_more' => '09:00', 
        'work_days_wednesday_time_start_more' => '09:00', 
        'work_days_thursday_time_start_more' => '09:00', 
        'work_days_friday_time_start_more' => '09:00', 
        'work_days_saturday_time_start_more' => '09:00', 
        'work_days_sunday_time_start_more' => '09:00', 

        'work_days_monday_time_end_more' => '23:00', 
        'work_days_tuesday_time_end_more' => '23:00', 
        'work_days_wednesday_time_end_more' => '23:00', 
        'work_days_thursday_time_end_more' => '23:00', 
        'work_days_friday_time_end_more' => '23:00', 
        'work_days_saturday_time_end_more' => '23:00', 
        'work_days_sunday_time_end_more' => '23:00', 

        'color_main_main' => '#7fffd4',
        'color_main_tables' => '#e3e3ed',
        'color_main_seats' => '#aeb1c1',
        'color_main_max_seats' => '#fff',
        'color_main_max_seats_text' => '#888daa',
        'color_main_table_id' => '#898daa',
        'color_main_table_id_text' => '#fff',

        'color_datepicker_datepicker_text_all' => '#333',
        'color_datepicker_datepicker' => '#fff',
        'color_datepicker_datepicker_text' => '#333',
        'color_datepicker_datepicker_disabled' => '#82ffc9',
        'color_datepicker_datepicker_text_disabled' => '#5ab28c',
        'color_datepicker_datepicker_active' => '#fff',
        'color_datepicker_datepicker_text_active' => '#333',
        'color_datepicker_datepicker_arrow' => '#fff',
        'color_datepicker_datepicker_arrow_hover' => '#333',
        'color_datepicker_datepicker_arrow_background' => 'transparent',
        'color_datepicker_datepicker_arrow_disabled' => '#333',
        'color_datepicker_datepicker_arrow_background_disabled' => '#333',

        'color_form_book_now' => '',
        'color_form_book_now_text' => '#fff',
        'color_form_form' => '#fff',
        'color_form_big_titles' => '#000',
        'color_form_booking_title' => '#333',
        'color_form_small_titles' => '#777',
        'color_form_small_descriptions' => '#bbb',
        'color_form_inputs' => '#e3e3e3',
        'color_form_inputs_text' => '#333',
        'color_form_error' => '#e75e5c',
        'color_form_error_text' => '#fff',
        'color_form_message' => '#f5f5dc',
        'color_form_message_text' => '#a7a495',
    );

    // const TIME_ALL = array(
    public static $TIME_ALL = array(
        0  => '00:30',
        1  => '01:00',
        2  => '01:30',
        3  => '02:00',
        4  => '02:30',
        5  => '03:00',
        6  => '03:30',
        7  => '04:00',
        8  => '04:30',
        9  => '05:00',
        10  => '05:30',
        11  => '06:00',
        12  => '06:30',
        13  => '07:00',
        14  => '07:30',
        15  => '08:00',
        16  => '08:30',
        17  => '09:00',
        18  => '09:30',
        19  => '10:00',
        20  => '10:30',
        21  => '11:00',
        22  => '11:30',
        23  => '12:00',
        24  => '12:30',
        25  => '13:00',
        26  => '13:30',
        27  => '14:00',
        28  => '14:30',
        29  => '15:00',
        30  => '15:30',
        31  => '16:00',
        32  => '16:30',
        33  => '17:00',
        34  => '17:30',
        35  => '18:00',
        36  => '18:30',
        37  => '19:00',
        38  => '19:30',
        39  => '20:00',
        40  => '20:30',
        41  => '21:00',
        42  => '21:30',
        43  => '22:00',
        44  => '22:30',
        45  => '23:00',
        46  => '23:30',
        47  => '00:00',
    );

    // const TABLES = array(
    public static $TABLES = array(
        array(
            'data-type' => 'type-1',
            'data-max-seats' => '8',
            'data-seats' => '4',
            'data-rotate' => '1',
            'data-big-side' => 'w',
        ),
        array(
            'data-type' => 'type-3',
            'data-max-seats' => '6',
            'data-seats' => '4',
            'data-rotate' => '1',
            'data-big-side' => 'h',
        ),
        array(
            'data-type' => 'type-4',
            'data-max-seats' => '4',
            'data-seats' => '4',
            'data-rotate' => '1',
            'data-big-side' => 'w',
        ),
        array(
            'data-type' => 'type-5',
            'data-max-seats' => '10',
            'data-seats' => '9',
            'data-rotate' => '1',
            'data-big-side' => 'h',
        ),
    );
    /*
    const TIME_ALL = array(
        0  => '00:15',
        1  => '00:30',
        2  => '00:45',
        3  => '01:00',
        4  => '01:15',
        5  => '01:30',
        6  => '01:45',
        7  => '02:00',
        8  => '02:15',
        9  => '02:30',
        10  => '02:45',
        11  => '03:00',
        12  => '03:15',
        13  => '03:30',
        14  => '03:45',
        15  => '04:00',
        16  => '04:15',
        17  => '04:30',
        18  => '04:45',
        19  => '05:00',
        20  => '05:15',
        21  => '05:30',
        22  => '05:45',
        23  => '06:00',
        24  => '06:15',
        25  => '06:30',
        26  => '06:45',
        27  => '07:00',
        28  => '07:15',
        29  => '07:30',
        30  => '07:45',
        31  => '08:00',
        32  => '08:15',
        33  => '08:30',
        34  => '08:45',
        35  => '09:00',
        36  => '09:15',
        37  => '09:30',
        38  => '09:45',
        39  => '10:00',
        40  => '10:15',
        41  => '10:30',
        42  => '10:45',
        43  => '11:00',
        44  => '11:15',
        45  => '11:30',
        46  => '11:45',
        47  => '12:00',
        48  => '12:15',
        49  => '12:30',
        50  => '12:45',
        51  => '13:00',
        52  => '13:15',
        53  => '13:30',
        54  => '13:45',
        55  => '14:00',
        56  => '14:15',
        57  => '14:30',
        58  => '14:45',
        59  => '15:00',
        60  => '15:15',
        61  => '15:30',
        62  => '15:45',
        63  => '16:00',
        64  => '16:15',
        65  => '16:30',
        66  => '16:45',
        67  => '17:00',
        68  => '17:15',
        69  => '17:30',
        70  => '17:45',
        71  => '18:00',
        72  => '18:15',
        73  => '18:30',
        74  => '18:45',
        75  => '19:00',
        76  => '19:15',
        77  => '19:30',
        78  => '19:45',
        79  => '20:00',
        80  => '20:15',
        81  => '20:30',
        82  => '20:45',
        83  => '21:00',
        84  => '21:15',
        85  => '21:30',
        86  => '21:45',
        87  => '22:00',
        88  => '22:15',
        89  => '22:30',
        90  => '22:45',
        91  => '23:00',
        92  => '23:15',
        93  => '23:30',
        94  => '23:45',
        95  => '24:00',
        96  => '00:00',
    );
    */

    /**
     * Retrieves the plugin title from the main plugin file.
     *
     * @return string The plugin title
     */
    public static function get_plugin_title() {
        $path = plugin_dir_path(dirname(__FILE__)).self::SLUG.'.php';
        return get_plugin_data($path)['Name'];
    }

    public static function get_plugin_path() {
        $path = plugin_dir_path(dirname(__FILE__));
        return $path;
    }
}
