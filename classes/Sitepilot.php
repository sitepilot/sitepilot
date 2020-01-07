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
        Shortcodes::init();
    }

    /** 
     * Returns the module class based on its name.
     * 
     * @param string $module
     * @return string $class
     */
    static public function get_module_class($module)
    {
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

        return $class;
    }

    /**
     * Initialize modules.
     * 
     * @return void
     */
    static public function init_modules()
    {
        foreach (Modules::get_enabled_settings() as $module) {
            $class = self::get_module_class($module);
            if (class_exists($class) && $class::is_active()) $class::before_init();
        }

        foreach (Modules::get_enabled_settings() as $module) {
            $class = self::get_module_class($module);
            if (class_exists($class) && $class::is_active()) $class::init();
        }
    }
}
