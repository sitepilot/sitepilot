<?php

namespace Sitepilot\Support;

use Sitepilot\Model;
use Sitepilot\Support\BeaverBuilder;
use BB_PowerPack_Admin_Settings;

final class BeaverPowerPack
{
    /**
     * @return void
     */
    static public function init()
    {
        if (!self::is_active()) {
            return;
        }

        if (BeaverBuilder::is_setting_enabled('filter_admin_settings_capability')) {
            add_action('admin_menu', __CLASS__ . '::action_admin_menu', 99);
        }

        add_action('sp_module_' . BeaverBuilder::get_id() . '_saved', __CLASS__ . '::action_saved');
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
     * Save branding options.
     *
     * @param array $branding
     * @return void
     */
    public static function action_saved()
    {
        Model::disable_cache();

        if (method_exists('BB_PowerPack_Admin_Settings', 'update_option') && BeaverBuilder::is_setting_enabled('filter_power_pack_branding')) {
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_name', BeaverBuilder::get_setting('power_pack_name'));
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_desc', BeaverBuilder::get_setting('power_pack_description'));
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_author', Model::get_branding_name());
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_uri', Model::get_branding_website());
            BB_PowerPack_Admin_Settings::update_option('ppwl_admin_label', BeaverBuilder::get_setting('power_pack_name'));
            BB_PowerPack_Admin_Settings::update_option('ppwl_builder_label', BeaverBuilder::get_setting('power_pack_name'));
        } else {
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_name', '');
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_desc', '');
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_author', '');
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_uri', '');
            BB_PowerPack_Admin_Settings::update_option('ppwl_admin_label', '');
            BB_PowerPack_Admin_Settings::update_option('ppwl_builder_label', '');
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
