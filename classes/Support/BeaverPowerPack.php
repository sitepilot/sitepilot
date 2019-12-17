<?php

namespace Sitepilot\Support;

use Sitepilot\Model;
use Sitepilot\Module;
use Sitepilot\Support\BeaverBuilder;
use BB_PowerPack_Admin_Settings;

final class BeaverPowerPack extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'support-beaver-power-pack';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Power Pack';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Support settings for the Beaver Builder Power Pack plugin.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 31;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

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
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_name', Model::get_branding_name() . ' Power Pack');
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_desc', 'A set of custom, creative, unique modules for '  . Model::get_branding_name() . ' Builder to speed up your web design and development process.');
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_author', Model::get_branding_name());
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_uri', Model::get_branding_website());
            BB_PowerPack_Admin_Settings::update_option('ppwl_admin_label', 'Power Pack');
            BB_PowerPack_Admin_Settings::update_option('ppwl_builder_label', 'Power Pack');
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