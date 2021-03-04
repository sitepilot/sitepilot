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
        add_action('update_option_sitepilot_client_role_caps', [$this, 'action_update_role']);
        add_action('update_option_sitepilot_client_role_enabled', [$this, 'action_update_role']);
    }

    /**
     * Add or update client role.
     *
     * @return void
     */
    public function action_update_role(): void
    {
        if (!sitepilot()->settings->enabled('client_role')) {
            remove_role('sitepilot_user');
            return;
        }

        remove_role('sitepilot_user');

        $capabilities = get_option('sitepilot_client_role_caps', []);
        $client_capabilities = array();

        foreach ($capabilities as $cap) {
            $client_capabilities[$cap] = true;
        }

        add_role(
            'sitepilot_user',
            sitepilot()->branding->get_name() . ' ' . __('Client', 'sitepilot'),
            $client_capabilities
        );
    }

    /**
     * Returns all available capabilities.
     *
     * @return void
     */
    public function get_all_capabilities()
    {
        $caps = array();
        $capabilities = get_role('administrator')->capabilities;

        foreach ($capabilities as $key => $enabled) {
            if (strpos($key, 'level_') === false) {
                $caps[$key] = ucwords(implode(" ", explode('_', str_replace('sp_', 'sitepilot_', $key))));
            }
        }

        asort($caps);

        return $caps;
    }
}
