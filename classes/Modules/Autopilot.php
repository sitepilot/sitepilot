<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;

final class Autopilot extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'autopilot';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Autopilot';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Remote management settings for <a href="https://github.com/sitepilot/autopilot" target="_blank">Autopilot</a>.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 20;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();
    }

    /**
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        return [
            'api_key' => [
                'type' => 'textarea',
                'label' => __('API Key', 'sitepilot'),
                'default' => '',
                'help' => __('Enter the Autopilot site API key.', 'sitepilot')
            ]
        ];
    }
}
