<?php

namespace Sitepilot\Modules\ClientSite;

use Sitepilot\Module;

/**
 * This module presets filters for client sites build by Sitepilot.
 */
class ClientSite extends Module
{
    /**
     * The module's init priority.
     *
     * @var int
     */
    protected $priority = 1;

    /**
     * Initialize the client site module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->get_setting('enabled')) {
            return;
        }

        /* Actions */
        add_filter('sp_logs_enabled', '__return_true');
        add_filter('sp_client_role_enabled', '__return_true');
        add_filter('sp_branding_login_enabled', '__return_true');
        add_filter('sp_branding_wp_head_enabled', '__return_true');
        add_filter('sp_branding_admin_footer_enabled', '__return_true');
        add_filter('sp_branding_admin_bar_enabled', '__return_true');
        add_filter('sp_beaver_builder_branding_enabled', '__return_true');
        add_filter('sp_beaver_builder_remove_default_templates', '__return_true');
        add_filter('sp_beaver_builder_filter_admin_settings_cap', '__return_true');
        add_filter('sp_beaver_builder_power_pack_branding_enabled', '__return_true');
        add_filter('sp_beaver_builder_ultimate_addons_branding_enabled', '__return_true');
        add_filter('sp_branding_login_enabled', '__return_true');
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_client_site_settings', [
            'enabled' => apply_filters('sp_client_website', false)
        ]);
    }
}
