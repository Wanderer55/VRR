<?php

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
// $option_name = INFO::OPTION_NAME;
// Delete options
delete_option('visual_restaurant_reservation_settings');

// Delete options in Multisite
delete_site_option('visual_restaurant_reservation_settings');
