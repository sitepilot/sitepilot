<?php

namespace Sitepilot\Support;

use Sitepilot\Model;
use Sitepilot\Module;
use Sitepilot\Support\BeaverBuilder;

final class BeaverUltimateAddons extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'support-beaver-ultimate-addons';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Ultimate Addons';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Support settings for the Beaver Builder Ultimate Addons plugin.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 32;

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
        return defined('BB_ULTIMATE_ADDON_VER');
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

        $branding = [];
        if (BeaverBuilder::is_setting_enabled('filter_ultimate_addons_branding')) {
            $branding['uabb-plugin-name'] = Model::get_branding_name() . ' Ultimate Addons';
            $branding['uabb-plugin-short-name'] = "Ultimate Addons";
            $branding['uabb-plugin-desc'] = 'A set of custom, creative, unique modules for ' . Model::get_branding_name() . ' Builder to speed up your web design and development process.';
            $branding['uabb-author-name'] = Model::get_branding_name();
            $branding['uabb-author-url'] = Model::get_branding_website();;
        }

        Model::update_admin_settings_option('_fl_builder_uabb_branding', $branding);
    }

    /**
     * Remove Ultimate Addon menu if user doesn't have the custom admin settings capability.
     * 
     * @return void
     */
    public static function action_admin_menu()
    {
        if (!current_user_can(BeaverBuilder::$admin_settings_cap)) {
            remove_submenu_page('options-general.php', 'uabb-builder-settings');
        }
    }
}
