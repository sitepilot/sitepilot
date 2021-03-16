<?php

namespace Sitepilot\Modules\Logs;

use WP_Query;
use Sitepilot\Module;

class Logs extends Module
{
    /**
     * The log admin capability.
     *
     * @var string
     */
    public $log_admin_cap = 'sp_log_admin';

    /**
     * The log viewer capability.
     *
     * @var string
     */
    public $log_viewer_cap = 'sp_log_viewer';

    /**
     * Initialize the log module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->get_setting('enabled')) {
            return;
        }

        /* Actions */
        add_action('init', [$this, 'action_load_log_post_type']);
        add_action('admin_init', [$this, 'action_register_capability']);
        add_action('admin_menu', [$this, 'action_load_log_menu'], 11);
        add_action('upgrader_process_complete', [$this, 'action_upgrader_process_complete'], 10, 2);
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_logs_settings', [
            'enabled' => apply_filters('sp_logs_enabled', false),
            'replace_names' => apply_filters('sp_logs_replace_names', [])
        ]);
    }

    /**
     * Register log viewer and admin capabilities.
     *
     * @return void
     */
    public function action_register_capability(): void
    {
        $role = get_role('administrator');

        $role->add_cap($this->log_admin_cap);
        $role->add_cap($this->log_viewer_cap);
    }

    /** 
     * Register the log post type.
     * 
     * @return void
     */
    public function action_load_log_post_type(): void
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
            'labels' => $labels,
            'description' => __('Description.', 'sitepilot'),
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => array('slug' => 'sp-log'),
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor'),
            'capabilities' => array(
                'read' => $this->log_viewer_cap,
                'create_posts' => $this->log_admin_cap,
                'edit_posts' => $this->log_viewer_cap,
                'edit_published_posts' => $this->log_viewer_cap,
                'delete_published_posts' => $this->log_admin_cap,
                'edit_others_posts' => $this->log_viewer_cap,
                'delete_others_posts' => $this->log_admin_cap,
            ),
        );

        register_post_type('sp-log', $args);
    }

    /**
     * Add log menu item to the Sitepilot menu.
     * 
     * @return void
     */
    public function action_load_log_menu(): void
    {
        add_submenu_page(
            'sitepilot-menu',
            'Sitepilot Log',
            'Log',
            $this->log_viewer_cap,
            'edit.php?post_type=sp-log'
        );
    }

    /**
     * Save a message to the log.
     * 
     * @return int|WP_Error
     */
    public function msg($title, $content)
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
    public function action_upgrader_process_complete($upgrader_object, $options): void
    {
        $replaceName = $this->get_setting('replace_names');

        if (isset($options['action']) && $options['action'] == 'update') {
            switch ($options['type']):
                case 'core':
                    $this->msg(
                        sprintf(__('Updated WordPress to version %s', 'sitepilot'), get_bloginfo('version')),
                        sprintf(__('The WordPress core was updated to version %s.', 'sitepilot'), get_bloginfo('version'))
                    );
                    break;
                case 'plugin':
                    foreach ($options['plugins'] as $plugin_file) {
                        $plugin = get_plugin_data(WP_CONTENT_DIR . '/plugins/' . $plugin_file);
                        $plugin_name = !empty($replaceName[$plugin_file]) ?  $replaceName[$plugin_file] : $plugin['Name'];
                        $this->msg(
                            sprintf(__('Updated %s to version %s', 'sitepilot'), $plugin_name, $plugin['Version']),
                            sprintf(__('The WordPress plugin %s was updated to version %s.', 'sitepilot'), $plugin_name, $plugin['Version'])
                        );
                    }
                    break;
                case 'theme':
                    foreach ($options['themes'] as $theme_file) {
                        $theme = wp_get_theme($theme_file);
                        $theme_name = !empty($replaceName[$theme_file]) ?  $replaceName[$theme_file] : $theme->name;
                        $this->msg(
                            sprintf(__('Updated %s to version %s', 'sitepilot'), $theme_name, $theme->version),
                            sprintf(__('The WordPress theme %s was updated to version %s.', 'sitepilot'), $theme_name, $theme->version)
                        );
                    }
                    break;
            endswitch;

            sitepilot()->model->set_last_update_date();
        }
    }

    /**
     * Get log items.
     *
     * @param array $args
     * @return WP_Query
     */
    public function get($args = []): WP_Query
    {
        $args = array(
            'post_type' => 'sp-log',
            'orderby' => 'date',
            'order' => 'DESC'
        ) + $args;

        return new WP_Query($args);
    }
}
