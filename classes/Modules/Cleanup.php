<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;

/**
 * This module is responsible for cleaning up WordPress.
 *
 * @since 1.0.0
 */
final class Cleanup extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'cleanup';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Cleanup';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Cleanup the WordPress admin interface.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 300;
    
    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        if (self::is_setting_enabled('filter_admin_bar')) {
            add_filter('wp_before_admin_bar_render', __CLASS__ . '::filter_admin_bar');
        }
        
        if (self::is_setting_enabled('filter_dashboard_widgets')) {
            add_filter('wp_dashboard_setup', __CLASS__ . '::filter_dashboard_widgets');
        }
    }

    /**
     * Returns module settings.
     *
     * @return void
     */
    static public function settings()
    {
        return [
            'filter_admin_bar' => [
                'type' => 'checkbox',
                'label' => __('Deactivate WordPress logo in the admin bar.', 'sitepilot'),
            ],
            'filter_dashboard_widgets' => [
                'type' => 'checkbox',
                'label' => __('Deactivate default WordPress dashboard widgets.', 'sitepilot'),
            ]
        ];
    }

    /**
     * Remove menu items from the admin bar.
     *
     * @return void
     */
    public static function filter_admin_bar()
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_node('wp-logo');
        $wp_admin_bar->remove_menu('comments');
    }

    /**
     * Remove default dashboard widgets.
     *
     * @return void
     */
    public static function filter_dashboard_widgets()
    {
        global $wp_meta_boxes;

        remove_action('welcome_panel', 'wp_welcome_panel');

        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_welcome']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    }
}
