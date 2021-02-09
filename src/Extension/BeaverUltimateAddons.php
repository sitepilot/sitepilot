<?php

namespace Sitepilot\Extension;

use Sitepilot\Module;

class BeaverUltimateAddons extends Module
{
    /**
     * Initialize Beaver Ultimate Addons extension.
     * 
     * @return void
     */
    public function init(): void
    {
        register_activation_hook(
            WP_PLUGIN_DIR . '/bb-ultimate-addon/bb-ultimate-addon.php',
            [$this, 'action_update_branding']
        );

        add_action('after_setup_theme', function () {
            if (!$this->is_active()) {
                return;
            }

            if (apply_filters('sp_beaver_ultimate_addons_branding', false)) {
                add_filter('sp_log_replace_names', function ($replace) {
                    return array_merge($replace, [
                        'bb-ultimate-addon/bb-ultimate-addon.php' => $this->get_branding_name(),
                    ]);
                });
            }

            if (apply_filters('sp_beaver_builder_filter_admin_settings_cap', false)) {
                add_action('admin_menu', [$this, 'action_admin_menu'], 99);
            }
        });
    }

    /**
     * Check if Beaver Ultimate Addons plugin is active.
     *
     * @return bool
     */
    public function is_active(): bool
    {
        return defined("BB_ULTIMATE_ADDON_VER");
    }

    /**
     * Returns the branding name.
     * 
     * @return string
     */
    public function get_branding_name(): string
    {
        return apply_filters('sp_beaver_ultimate_addons_branding_name', __('Ultimate Addons', 'sitepilot'));
    }

    /**
     * Returns the branding description.
     * 
     * @return string
     */
    public function get_branding_description(): string
    {
        return apply_filters('sp_beaver_ultimate_addons_branding_description', __('A set of custom, creative, unique modules to speed up the web design and development process.', 'sitepilot'));
    }

    /**
     * Save branding options.
     *
     * @return void
     */
    public function action_update_branding(): void
    {
        $branding = [];
        if (apply_filters('sp_beaver_ultimate_addons_branding', false)) {
            $branding['uabb-plugin-name'] = $this->get_branding_name();
            $branding['uabb-plugin-short-name'] = $this->get_branding_name();
            $branding['uabb-plugin-desc'] = $this->get_branding_description();
            $branding['uabb-author-name'] = $this->plugin->branding->get_name();
            $branding['uabb-author-url'] = $this->plugin->branding->get_website();
        }

        update_option('_fl_builder_uabb_branding', $branding);
    }

    /**
     * Remove Ultimate Addons menu if user doesn't have the custom admin settings capability.
     * 
     * @return void
     */
    public function action_admin_menu(): void
    {
        if (!current_user_can($this->plugin->ext_beaver_builder->admin_settings_cap)) {
            remove_submenu_page('options-general.php', 'uabb-builder-settings');
        }
    }
}
