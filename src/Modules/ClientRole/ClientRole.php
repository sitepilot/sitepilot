<?php

namespace Sitepilot\Modules\ClientRole;

use Sitepilot\Module;

class ClientRole extends Module
{
    /**
     * Initialize the client role module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Actions */
        add_action('wp_login', [$this, 'update_role']);
    }

    /**
     * Update the client role.
     *
     * @return void
     */
    public function update_role()
    {
        if (!$this->get_setting('enabled')) {
            remove_role('sitepilot_user');
        } else {
            $capabilities = get_role('administrator')->capabilities;
            $exclude_capabilities = $this->get_setting('exclude_capabilities');

            foreach ($capabilities as $key => $value) {
                if (!in_array($key, $exclude_capabilities)) {
                    $role_capabilities[$key] = $value;
                }
            }

            add_role(
                'sitepilot_user',
                sprintf(__('%s Client', 'sitepilot'), sitepilot()->branding->get_name()),
                $role_capabilities
            );
        }
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_client_role_settings', [
            'enabled' => apply_filters('sp_client_role_enabled', false),
            'exclude_capabilities' => apply_filters('sp_client_role_exclude_capabilities', [
                'switch_themes',
                'edit_themes',
                'activate_plugins',
                'edit_plugins',
                'edit_users',
                'edit_files',
                'delete_users',
                'create_users',
                'update_plugins',
                'delete_plugins',
                'install_plugins',
                'update_themes',
                'install_themes',
                'update_core',
                'remove_users',
                'promote_users',
                'delete_themes',
                sitepilot()->logs->log_admin_cap,
                sitepilot()->templates->template_admin_cap,
                sitepilot()->beaver_builder->admin_settings_cap
            ])
        ]);
    }
}
