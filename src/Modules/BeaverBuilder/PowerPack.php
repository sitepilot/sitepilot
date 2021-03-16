<?php

namespace Sitepilot\Modules\BeaverBuilder;

use Sitepilot\Module;

class PowerPack extends Module
{
    /**
     * Construct the module.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        register_activation_hook(
            WP_PLUGIN_DIR . '/bbpowerpack/bb-powerpack.php',
            [$this, 'action_update_branding']
        );
    }

    /**
     * Initialize the module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->get_setting('enabled')) {
            return;
        }

        if ($this->get_setting('branding_enabled')) {
            add_filter('sp_logs_replace_names', function ($replace) {
                return array_merge($replace, [
                    'bbpowerpack/bb-powerpack.php' => $this->get_setting('branding_name'),
                ]);
            });
        }

        if (sitepilot()->beaver_builder->get_setting('filter_admin_settings_cap')) {
            add_action('admin_menu', [$this, 'action_admin_menu'], 99);
        }
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_beaver_builder_power_pack_settings', [
            'enabled' => defined("BB_POWERPACK_VER"),
            'branding_enabled' => apply_filters('sp_beaver_builder_power_pack_branding_enabled', false),
            'branding_name' => apply_filters('sp_beaver_power_pack_branding_name', __('Power Pack', 'sitepilot')),
            'branding_description' => apply_filters('sp_beaver_power_pack_branding_description', __('A set of custom, creative, unique modules to speed up the web design and development process.', 'sitepilot'))
        ]);
    }

    /**
     * Save branding options.
     *
     * @return void
     */
    public function action_update_branding(): void
    {
        if ($this->get_setting('branding_enabled') && class_exists('BB_PowerPack_Admin_Settings') && method_exists('BB_PowerPack_Admin_Settings', 'update_option')) {
            \BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_name', $this->get_setting('branding_name'));
            \BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_desc', $this->get_setting('branding_description'));
            \BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_author', sitepilot()->branding->get_name());
            \BB_PowerPack_Admin_Settings::update_option('ppwl_plugin_uri', sitepilot()->branding->get_website());
            \BB_PowerPack_Admin_Settings::update_option('ppwl_admin_label', $this->get_setting('branding_name'));
            \BB_PowerPack_Admin_Settings::update_option('ppwl_builder_label', $this->get_setting('branding_name'));
        }
    }

    /**
     * Remove menu if user doesn't have the custom admin settings capability.
     * 
     * @return void
     */
    public function action_admin_menu(): void
    {
        if (!current_user_can(sitepilot()->beaver_builder->admin_settings_cap)) {
            remove_submenu_page('options-general.php', 'ppbb-settings');
        }
    }
}
