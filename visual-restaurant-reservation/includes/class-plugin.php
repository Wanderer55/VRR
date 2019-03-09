<?php

namespace VISUAL_RESTAURANT_RESERVATION;

/**
 * The main plugin class.
 */
class Plugin
{

    private $loader;
    private $plugin_slug;
    private $version;
    private $option_name;

    public function __construct() {
        $this->plugin_slug = Info::SLUG;
        $this->version     = Info::VERSION;
        $this->option_name = Info::OPTION_NAME;
        $this->load_dependencies();
        $this->define_public_hooks();
        $this->define_admin_hooks();
        $this->define_frontend_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-public.php';
        // require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-shortcode.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'frontend/class-frontend.php';
        

        $this->loader = new Loader();
    }

    private function define_public_hooks() {
        $plugin_public = new Public_Plugin( $this->plugin_slug, $this->version, $this->option_name );

        $this->loader->add_action('init', $plugin_public, 'vrr_custom_post_status');

        $this->loader->add_action('admin_footer-post-new.php', $plugin_public, 'true_append_post_status_list');
        $this->loader->add_action('admin_footer-post.php', $plugin_public, 'true_append_post_status_list');
        $this->loader->add_action('admin_footer-edit.php', $plugin_public, 'true_append_status');
        $this->loader->add_action('admin_head-post.php', $plugin_public, 'hide_publishing_actions');
        $this->loader->add_action('admin_head-post-new.php', $plugin_public, 'hide_publishing_actions');

        // $this->loader->add_filter('views_edit-vrr', $plugin_public, 'publish_custom_draft_translation', 10, 1 );
        $this->loader->add_filter('wp_insert_post_data', $plugin_public, 'example_insert_using_custom_status', 10, 2 );
        $this->loader->add_filter('display_post_states', $plugin_public, 'vrr_display_archive_state', 10, 2 );

        $this->loader->add_action('plugins_loaded', $plugin_public, 'languages_init');
        $this->loader->add_action( 'init', $plugin_public, 'register_post_types' );
        $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

        

        $this->loader->add_action( 'save_post_vrr', $plugin_public, 'save_metadata' );
        $this->loader->add_action( 'load-post.php', $plugin_public, 'register_post_meta' );
        $this->loader->add_action( 'load-post-new.php', $plugin_public, 'register_post_meta' );
    }

    private function define_admin_hooks() {
        $plugin_admin = new Admin($this->plugin_slug, $this->version, $this->option_name);

        $this->loader->add_action('vc_before_init', $plugin_admin, 'vc_infobox_mapping');

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'assets');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
        $this->loader->add_action('admin_init', $plugin_admin, 'post_type_columns');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_menus');
    }

    private function define_frontend_hooks() {
        $plugin_frontend = new Frontend($this->plugin_slug, $this->version, $this->option_name);
        $this->loader->add_action('wp_ajax_send_book', $plugin_frontend, 'send_book');
        $this->loader->add_action('wp_ajax_nopriv_send_book', $plugin_frontend, 'send_book');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_frontend, 'assets');
        $this->loader->add_action('wp_footer', $plugin_frontend, 'render');
    }

    


    public function run() {
        $this->loader->run();
    }
}


