<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;
use Sitepilot\Support\Astra;
use Sitepilot\Support\BeaverBuilder;

final class Modules extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'modules';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Modules';

     /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Select which modules you would like to enable.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 2;

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
            'category-1' => [
                'label' => __('Modules', 'sitepilot'),
                'type' => 'category'
            ],
            'autopilot' => [
                'type' => 'checkbox',
                'label' => __("Autopilot", 'sitepilot'),
            ],
            'branding' => [
                'type' => 'checkbox',
                'label' => __("Branding", 'sitepilot'),
                'help' => __('White label WordPress and this plugin.', 'sitepilot')
            ],
            'cleanup' => [
                'type' => 'checkbox',
                'label' => __("Cleanup", 'sitepilot'),
            ],
            'client-role' => [
                'type' => 'checkbox',
                'label' => __("Client Role", 'sitepilot'),
            ],
            'menu' => [
                'type' => 'checkbox',
                'label' => __("Menu", 'sitepilot'),
            ],
            'support' => [
                'type' => 'checkbox',
                'label' => __("Support", 'sitepilot'),
            ],
            'user-switching' => [
                'type' => 'checkbox',
                'label' => __("User Switching", 'sitepilot'),
            ],
            'category-2' => [
                'label' => __('Theme & Plugin Support', 'sitepilot'),
                'type' => 'category',
                'active' => (Astra::is_active() || BeaverBuilder::is_active())
            ],
            'support-astra' => [
                'type' => 'checkbox',
                'label' => __("Astra", 'sitepilot'),
                'active' => Astra::is_active(),
                'help' => __('Enable support for Astra theme.', 'sitepilot')
            ],
            'support-beaver-builder' => [
                'type' => 'checkbox',
                'label' => __("Beaver Builder", 'sitepilot'),
                'active' => BeaverBuilder::is_active(),
                'help' => __('Enable support for Beaver Builder plugin, themer, theme and add-ons.', 'sitepilot')
            ],
        ];
    }
}
