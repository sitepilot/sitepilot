<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;
use Sitepilot\Module;

/**
 * This module is responsible for setting up a client role.
 *
 * @since 1.0.0
 */
final class ClientRole extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'client-role';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Client Role';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Setup a custom client role and select capabilities.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 401;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        add_action('sp_module_' . self::$module . '_saved', __CLASS__ . '::update_role');
    }

    /**
     * Returns module settings.
     *
     * @return void
     */
    static public function settings()
    {
        $settings = [];
        $capabilities = get_role('administrator')->capabilities;

        foreach ($capabilities as $key => $cap) {
            if (strpos($key, 'level_') === false) {
                $settings[$key] = [
                    'type' => 'checkbox',
                    'label' => $key,
                ];
            }
        }

        return $settings;
    }

    /**
     * Update client role after saving capabilities.
     * 
     * @return void
     */
    static public function update_role()
    {
        remove_role('sitepilot_user');

        $capabilities = [];

        foreach (self::get_enabled_settings() as $cap) {
            $capabilities[$cap] = true;
        }

        add_role(
            'sitepilot_user',
            Model::get_branding_name() . ' Client',
            $capabilities
        );
    }
}
