<?php

namespace Sitepilot;

use Sitepilot\Support\Astra;
use Sitepilot\Support\BeaverBuilder;
use Sitepilot\Support\BeaverPowerPack;
use Sitepilot\Support\BeaverUltimateAddons;

final class Sitepilot
{
    /**
     * Initialize plugin.
     *
     * @return void
     */
    static public function init()
    {
        /* Actions */
        add_action('after_setup_theme', __CLASS__ . '::init_after_theme');

        /* Filters */
        add_filter('admin_enqueue_scripts', __CLASS__ . '::filter_load_admin_scripts');
    }

    /**
     * Initialize module classes.
     *  
     * @return void
     */
    static public function init_after_theme()
    {
        if (apply_filters('sp_client_website', false)) {
            add_filter('sp_client_role', '__return_true');
            add_filter('sp_branding_login', '__return_true');
            add_filter('sp_branding_head', '__return_true');
            add_filter('sp_cleanup_admin_bar', '__return_true');
            add_filter('sp_cleanup_dashboard', '__return_true');
            add_filter('sp_astra_branding', '__return_true');
            add_filter('sp_beaver_builder_branding', '__return_true');
            add_filter('sp_beaver_builder_theme_branding', '__return_true');
            add_filter('sp_beaver_builder_filter_admin_settings_cap', '__return_true');
            add_filter('sp_beaver_power_pack_branding', '__return_true');
            add_filter('sp_beaver_ultimate_addons_branding', '__return_true');
        }

        Modules\Update::init();
        Modules\Support::init();
        Modules\Branding::init();
        Modules\Cleanup::init();
        Modules\Menu::init();
        Modules\Log::init();
        Modules\ClientRole::init();

        Support\Astra::init();
        Support\BeaverBuilder::init();
    }

    /**
     * Load admin stylesheets and scripts.
     *
     * @return void
     */
    public static function filter_load_admin_scripts()
    {
        wp_enqueue_style('sitepilot-admin', SITEPILOT_URL . 'assets/dist/css/admin.css', array(), SITEPILOT_VERSION);
        wp_enqueue_script('sitepilot-admin', SITEPILOT_URL . 'assets/dist/js/admin.js', array(), SITEPILOT_VERSION);
    }
}
