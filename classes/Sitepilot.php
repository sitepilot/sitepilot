<?php

namespace Sitepilot;

use Sitepilot\Modules\Modules;

final class Sitepilot
{
    /**
     * Initialize plugin.
     *
     * @return void
     */
    static public function init()
    {
        self::init_classes();

        /* Actions */
        add_action('after_setup_theme', __CLASS__ . '::init_modules');
    }

    /**
     * Initialize plugin classes.
     *
     * @return void
     */
    static private function init_classes()
    {
        // Load defaults (only used for Sitepilot clients)
        $defaults_class = '\Sitepilot\Defaults';
        if (method_exists($defaults_class, 'init')) {
            ($defaults_class)::init();
        }

        Update::init();
        Settings::init();
    }

    /**
     * Initialize plugin modules.
     * 
     * @return void
     */
    static public function init_modules()
    {
        foreach (Modules::get_enabled_settings() as $module) {
            $class = 'Sitepilot\\';

            if (strpos($module, 'support-') !== false) {
                $module = str_replace('support-', '', $module);
                $class .= 'Support\\';
            } else {
                $class .= 'Modules\\';
            }

            $class_words = explode('-', $module);
            foreach ($class_words as $word) {
                $class .= ucfirst($word);
            }

            if (method_exists($class, 'init')) {
                if (method_exists($class, 'is_active')) {
                    if ($class::is_active()) $class::init();
                } else {
                    $class::init();
                }
            }
        }
    }
}
