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
        /* Check if module is enabled */
        if (!$this->plugin->settings->enabled('client_role')) {
            add_action('admin_init', function () {
                remove_role('sitepilot_user');
            });

            return;
        }

        /* Actions */
        add_action('update_option_sitepilot_client_role_caps', [$this, 'action_update_role']);
    }

    /**
     * Add or update client role.
     *
     * @return void
     */
    public function action_update_role(): void
    {
        remove_role('sitepilot_user');

        $capabilities = get_option('sitepilot_client_role_caps', []);
        $client_capabilities = array();

        foreach($capabilities as $cap) {
            $client_capabilities[$cap] = true;
        }

        add_role(
            'sitepilot_user',
            $this->plugin->branding->get_name() . ' Client',
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
