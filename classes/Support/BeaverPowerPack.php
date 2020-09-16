<?php

namespace Sitepilot\Support;

use Sitepilot\Modules\Branding;
use BB_PowerPack_Admin_Settings;
use Sitepilot\Support\BeaverBuilder;

final class BeaverPowerPack
{
    /**
     * Initialize Beaver Power Pack support.
     * 
     * @return void
     */
    static public function init()
    {
        register_activation_hook(
            WP_PLUGIN_DIR . '/bbpowerpack/bb-powerpack.php',
            __CLASS__ . '::action_update_branding'
        );

        if (!self::is_active()) {
            return;
        }

        if (apply_filters('sp_beaver_builder_filter_admin_settings_cap', false)) {
            add_action('admin_menu', __CLASS__ . '::action_admin_menu', 99);
        }

        if (apply_filters('sp_beaver_power_pack_branding', false)) {
            add_filter('sp_log_replace_names', function ($replace) {
                return array_merge($replace, [
                    'bbpowerpack/bb-powerpack.php' =>  self::get_branding_name(),
                ]);
            });
        }
    }

    /**
     * Check if the module is active.
     *
     * @return boolean
     */
    static public function is_active()
    {
        return defined('BB_POWERPACK_VER');
    }

    /**
     * Returns branding name.
     * 
     * @return string
     */
    public static function get_branding_name()
    {
        return apply_filters('sp_beaver_power_pack_branding_name', 'Power Pack');
    }

    /**
     * Returns branding description.
     * 
     * @return string
     */
    public static function get_branding_description()
    {
        return apply_filters('sp_beaver_power_pack_branding_description', 'A set of custom, creative, unique modules to speed up the web design and development process.');
    }

    /**
     * Save branding options.
     *
     * @param array $branding
     * @return void
     */
    public static function action_update_branding()
    {
        if (apply_filters('sp_beaver_power_pack_branding', false) && method_exists('BB_PowerPack_Admin_Settings', 'update_option')) {
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_name', self::get_branding_name());
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_desc', self::get_branding_description());
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_author', Branding::get_name());
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_uri', Branding::get_website());
            BB_PowerPack_Admin_Settings::update_option('ppwl_admin_label', self::get_branding_name());
            BB_PowerPack_Admin_Settings::update_option('ppwl_builder_label', self::get_branding_name());
        }
    }

    /**
     * Remove Power Pack menu if user doesn't have the custom admin settings capability.
     * 
     * @return void
     */
    public static function action_admin_menu()
    {
        if (!current_user_can(BeaverBuilder::$admin_settings_cap)) {
            remove_submenu_page('options-general.php', 'ppbb-settings');
        }
    }
}
