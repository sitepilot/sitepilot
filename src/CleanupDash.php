<?php

namespace Sitepilot;

class CleanupDash extends Module
{
    /**
     * Initialize the cleanup module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Check if module is enabled */
        if (!$this->plugin->settings->enabled('cleanup_dashboard')) {
            return;
        }

        /* Filters */
        add_filter('wp_before_admin_bar_render', [$this, 'filter_admin_bar']);
        add_filter('wp_dashboard_setup', [$this, 'filter_dashboard_widgets']);
    }

    /**
     * Remove menu items from the admin bar.
     *
     * @return void
     */
    public function filter_admin_bar(): void
    {
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('wp-logo');
    }

    /**
     * Remove default dashboard widgets.
     *
     * @return void
     */
    public function filter_dashboard_widgets(): void
    {
        global $wp_meta_boxes;

        remove_action('welcome_panel', 'wp_welcome_panel');

        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    }
}
