<?php

namespace Sitepilot\Extension;

use Sitepilot\Module;
use BB_PowerPack_Admin_Settings;

class BeaverPowerPack extends Module
{
    /**
     * Initialize Beaver Power Pack extension.
     * 
     * @return void
     */
    public function init(): void
    {
        register_activation_hook(
            WP_PLUGIN_DIR . '/bbpowerpack/bb-powerpack.php',
            [$this, 'action_update_branding']
        );

        add_action('after_setup_theme', function () {
            if (!$this->is_active()) {
                return;
            }

            if (apply_filters('sp_beaver_power_pack_branding', false)) {
                add_filter('sp_log_replace_names', function ($replace) {
                    return array_merge($replace, [
                        'bbpowerpack/bb-powerpack.php' => $this->get_branding_name(),
                    ]);
                });
            }

            if (apply_filters('sp_beaver_builder_filter_admin_settings_cap', false)) {
                add_action('admin_menu', [$this, 'action_admin_menu'], 99);
            }
        });
    }

    /**
     * Check if Beaver Power Pack plugin is active.
     *
     * @return bool
     */
    public function is_active(): bool
    {
        return defined("BB_POWERPACK_VER");
    }

    /**
     * Returns the branding name.
     * 
     * @return string
     */
    public function get_branding_name(): string
    {
        return apply_filters('sp_beaver_power_pack_branding_name', __('Power Pack', 'sitepilot'));
    }

    /**
     * Returns the branding description.
     * 
     * @return string
     */
    public function get_branding_description(): string
    {
        return apply_filters('sp_beaver_power_pack_branding_description', __('A set of custom, creative, unique modules to speed up the web design and development process.', 'sitepilot'));
    }

    /**
     * Save branding options.
     *
     * @return void
     */
    public function action_update_branding(): void
    {
        if (apply_filters('sp_beaver_power_pack_branding', false) && method_exists('BB_PowerPack_Admin_Settings', 'update_option')) {
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_name', $this->get_branding_name());
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_desc', $this->get_branding_description());
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_author', sitepilot()->branding->get_name());
            BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_uri', sitepilot()->branding->get_website());
            BB_PowerPack_Admin_Settings::update_option('ppwl_admin_label', $this->get_branding_name());
            BB_PowerPack_Admin_Settings::update_option('ppwl_builder_label', $this->get_branding_name());
        }
    }

    /**
     * Remove Power Pack menu if user doesn't have the custom admin settings capability.
     * 
     * @return void
     */
    public function action_admin_menu(): void
    {
        if (!current_user_can(sitepilot()->ext_beaver_builder->admin_settings_cap)) {
            remove_submenu_page('options-general.php', 'ppbb-settings');
        }
    }
}
