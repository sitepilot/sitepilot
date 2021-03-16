<?php

namespace Sitepilot\Modules\BeaverBuilder;

use Sitepilot\Module;

class UltimateAddons extends Module
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
            WP_PLUGIN_DIR . '/bb-ultimate-addon/bb-ultimate-addon.php',
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
        return apply_filters('sp_beaver_builder_ultimate_addons_settings', [
            'enabled' => defined("BB_ULTIMATE_ADDON_VER"),
            'branding_enabled' => apply_filters('sp_beaver_builder_ultimate_addons_branding_enabled', false),
            'branding_name' => apply_filters('sp_beaver_builder_ultimate_addons_name', __('Ultimate Addons', 'sitepilot')),
            'branding_description' => apply_filters('sp_beaver_builder_ultimate_addons_branding_description', __('A set of custom, creative, unique modules to speed up the web design and development process.', 'sitepilot'))
        ]);
    }

    /**
     * Save branding options.
     *
     * @return void
     */
    public function action_update_branding(): void
    {
        $branding = [];
        if ($this->get_setting('branding_enabled')) {
            $branding['uabb-plugin-name'] = $this->get_setting('branding_name');
            $branding['uabb-plugin-short-name'] = $this->get_setting('branding_name');
            $branding['uabb-plugin-desc'] = $this->get_setting('branding_description');
            $branding['uabb-author-name'] = sitepilot()->branding->get_name();
            $branding['uabb-author-url'] = sitepilot()->branding->get_website();
        }

        update_option('_fl_builder_uabb_branding', $branding);
    }

    /**
     * Remove menu if user doesn't have the custom admin settings capability.
     * 
     * @return void
     */
    public function action_admin_menu(): void
    {
        if (!current_user_can(sitepilot()->beaver_builder->admin_settings_cap)) {
            remove_submenu_page('options-general.php', 'uabb-builder-settings');
        }
    }
}
