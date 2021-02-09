<?php

namespace Sitepilot;

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
        add_action('wp_login', function () {
            remove_role('sitepilot_user');

            if ($this->plugin->settings->enabled('client_role')) {
                $this->action_update_role();
            }
        });
    }

    /**
     * Add or update client role.
     *
     * @return void
     */
    public function action_update_role(): void
    {
        $capabilities = get_role('administrator')->capabilities;

        $role_capabilities = [];

        $exclude = apply_filters('sp_client_role_exlude_capabilities', [
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
            $this->plugin->log->log_admin_cap,
            $this->plugin->template->template_admin_cap,
            $this->plugin->ext_beaver_builder->admin_settings_cap
        ]);

        foreach ($capabilities as $key => $cap) {
            if (!in_array($key, $exclude)) {
                $role_capabilities[$key] = true;
            }
        }

        add_role(
            'sitepilot_user',
            $this->plugin->branding->get_name() . ' Client',
            $role_capabilities
        );
    }
}
