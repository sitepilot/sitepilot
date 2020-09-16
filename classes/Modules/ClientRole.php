<?php

namespace Sitepilot\Modules;

final class ClientRole
{
    /**
     * Initialize client role module.
     * 
     * @return void
     */
    static public function init()
    {
        if (apply_filters('sp_client_role', false)) {
            add_action('wp_login', __CLASS__ . '::update_role');
        }
    }

    /**
     * Update client role after saving capabilities.
     * 
     * @return void
     */
    static public function update_role()
    {
        remove_role('sitepilot_user');

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
            'edit_theme_options',
            'delete_themes',
            'sp_builder_admin_settings'
        ]);

        foreach ($capabilities as $key => $cap) {
            if (!in_array($key, $exclude)) {
                $role_capabilities[$key] = true;
            }
        }

        add_role(
            'sitepilot_user',
            Branding::get_name() . ' Client',
            $role_capabilities
        );
    }
}
