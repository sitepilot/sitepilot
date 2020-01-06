<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;
use Sitepilot\Module;

final class Log extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'log';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Log';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Log site updates and changes.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 61;

    /**
     * Require other modules.
     *
     * @var string
     */
    static protected $require = ['menu'];

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        /* Actions */
        add_action('admin_menu', __CLASS__ . '::action_load_log_menu', 11);
        add_action('init', __CLASS__ . '::action_load_log_post_type');
        add_action('admin_init', __CLASS__ . '::action_register_capability');
        add_action('upgrader_process_complete', __CLASS__ . '::action_upgrader_process_complete', 10, 2);
    }

    /**
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        return [];
    }

    /**
     * Register 'sp_log_viewer' and 'sp_log_admin' capability.
     *
     * @return void
     */
    static public function action_register_capability()
    {
        $role = get_role('administrator');
        $role->add_cap('sp_log_viewer');
        $role->add_cap('sp_log_admin');
    }

    /** 
     * Register the log post type.
     * 
     * @return void
     */
    public static function action_load_log_post_type()
    {
        $labels = array(
            'name'               => _x('Log', 'post type general name', 'sitepilot'),
            'singular_name'      => _x('Log', 'post type singular name', 'sitepilot'),
            'menu_name'          => _x('Logs', 'admin menu', 'sitepilot'),
            'name_admin_bar'     => _x('Log', 'add new on admin bar', 'sitepilot'),
            'add_new'            => _x('Add New', 'update', 'sitepilot'),
            'add_new_item'       => __('Add New Log', 'sitepilot'),
            'new_item'           => __('New Log', 'sitepilot'),
            'edit_item'          => __('Edit Log', 'sitepilot'),
            'view_item'          => __('View Log', 'sitepilot'),
            'all_items'          => __('All Logs', 'sitepilot'),
            'search_items'       => __('Search Logs', 'sitepilot'),
            'parent_item_colon'  => __('Parent Logs:', 'sitepilot'),
            'not_found'          => __('No logs found.', 'sitepilot'),
            'not_found_in_trash' => __('No logs found in trash.', 'sitepilot')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Description.', 'sitepilot'),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'sitepilot-log'),
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor'),
            'capabilities'       => array(
                'create_posts' => 'sp_log_admin',
                'edit_post' => 'sp_log_viewer',
                'read_post' => 'sp_log_viewer',
                'delete_post' => 'sp_log_admin',
                'delete_posts' => 'sp_log_admin',
                'delete_others_posts' => 'sp_log_admin',
                'edit_posts' => 'sp_log_viewer',
                'edit_others_posts' => 'sp_log_admin',
                'publish_posts' => 'sp_log_admin',
                'read_private_posts' => 'sp_log_admin',
            ),
        );

        register_post_type('sp-log', $args);
    }

    /**
     * Add log menu item to the Sitepilot menu.
     * 
     * @return void
     */
    public static function action_load_log_menu()
    {
        add_submenu_page(
            'sitepilot-menu',
            'Sitepilot Log',
            'Log',
            'sp_log_viewer',
            'edit.php?post_type=sp-log'
        );
    }

    /**
     * Save a message to the log.
     * 
     * @return int|WP_Error
     */
    public static function msg($title, $content)
    {
        $msg = array(
            'post_type' => 'sp-log',
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish'
        );

        return wp_insert_post($msg);
    }

    /**
     * Save plugin, theme and core updates to the log.
     *
     * @param $upgrader_object
     * @param $options
     * @return void
     */
    public static function action_upgrader_process_complete($upgrader_object, $options)
    {
        $replaceName = apply_filters('sp_log_replace_names', []);

        if (isset($options['action']) && $options['action'] == 'update') {
            switch ($options['type']):
                case 'core':
                    self::msg(
                        sprintf(__('Updated WordPress to version %s', 'sitepilot'), get_bloginfo('version')),
                        sprintf(__('The WordPress core was updated to version %s.', 'sitepilot'), get_bloginfo('version'))
                    );
                    break;
                case 'plugin':
                    foreach ($options['plugins'] as $plugin_file) {
                        $plugin = get_plugin_data(WP_CONTENT_DIR . '/plugins/' . $plugin_file);
                        $plugin_name = !empty($replaceName[$plugin_file]) ?  $replaceName[$plugin_file] : $plugin['Name'];
                        self::msg(
                            sprintf(__('Updated %s to version %s', 'sitepilot'), $plugin_name, $plugin['Version']),
                            sprintf(__('The WordPress plugin %s was updated to version %s.', 'sitepilot'), $plugin_name, $plugin['Version'])
                        );
                    }
                    break;
                case 'theme':
                    foreach ($options['themes'] as $theme_file) {
                        $theme = wp_get_theme($theme_file);
                        $theme_name = !empty($replaceName[$theme_file]) ?  $replaceName[$theme_file] : $theme->name;
                        self::msg(
                            sprintf(__('Updated %s to version %s', 'sitepilot'), $theme_name, $theme->version),
                            sprintf(__('The WordPress theme %s was updated to version %s.', 'sitepilot'), $theme_name, $theme->version)
                        );
                    }
                    break;
            endswitch;
            Model::set_last_update_date();
        }
    }

    /**
     * Get log items.
     *
     * @param array $args
     * @return WP_Query
     */
    public static function get($args = [])
    {
        $args = array(
            'post_type' => 'sp-log',
            'orderby' => 'date',
            'order' => 'DESC'
        ) + $args;

        return new \WP_Query($args);
    }
}
