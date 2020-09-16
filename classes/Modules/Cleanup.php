<?php

namespace Sitepilot\Modules;

final class Cleanup
{
    /**
     * Initialize cleanup module.
     * 
     * @return void
     */
    static public function init()
    {
        if (apply_filters('sp_cleanup_admin_bar', false)) {
            add_filter('wp_before_admin_bar_render', __CLASS__ . '::filter_admin_bar');
        }

        if (apply_filters('sp_cleanup_dashboard', false)) {
            add_filter('wp_dashboard_setup', __CLASS__ . '::filter_dashboard_widgets');
        }
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
