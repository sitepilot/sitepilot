<?php

namespace Sitepilot\Support;

use Sitepilot\Modules\Branding;
use Sitepilot\Support\BeaverBuilder;

final class BeaverUltimateAddons
{
    /**
     * Initialize Beaver Ultimate Addons support.
     * 
     * @return void
     */
    static public function init()
    {
        register_activation_hook(
            WP_PLUGIN_DIR . '/bb-ultimate-addon/bb-ultimate-addon.php',
            __CLASS__ . '::action_update_branding'
        );

        if (!self::is_active()) {
            return;
        }

        if (apply_filters('sp_beaver_builder_filter_admin_settings_cap', false)) {
            add_action('admin_menu', __CLASS__ . '::action_admin_menu', 99);
        }

        if (apply_filters('sp_beaver_ultimate_addons_branding', false)) {
            add_filter('sp_log_replace_names', function ($replace) {
                return array_merge($replace, [
                    'bb-ultimate-addon/bb-ultimate-addon.php' => self::get_branding_name()
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
        return defined('BB_ULTIMATE_ADDON_VER');
    }

    /**
     * Returns branding name.
     * 
     * @return string
     */
    public static function get_branding_name()
    {
        return apply_filters('sp_beaver_ultimate_addons_branding_name', 'Ultimate Addons');
    }

    /**
     * Returns branding description.
     * 
     * @return string
     */
    public static function get_branding_description()
    {
        return apply_filters('sp_beaver_ultimate_addons_branding_description', 'A set of custom, creative, unique modules to speed up the web design and development process.');
    }

    /**
     * Save branding options.
     *
     * @param array $branding
     * @return void
     */
    public static function action_update_branding()
    {
        $branding = [];
        if (apply_filters('sp_beaver_ultimate_addons_branding', false)) {
            $branding['uabb-plugin-name'] = self::get_branding_name();
            $branding['uabb-plugin-short-name'] = self::get_branding_name();
            $branding['uabb-plugin-desc'] = self::get_branding_description();
            $branding['uabb-author-name'] = Branding::get_name();
            $branding['uabb-author-url'] = Branding::get_website();
        }

        update_option('_fl_builder_uabb_branding', $branding);
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
