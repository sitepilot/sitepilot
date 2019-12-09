<?php

namespace Sitepilot;

final class Plugin
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
        Update::init();
        Settings::init();
    }

    /**
     * Initialize module classes.
     * 
     * @return void
     */
    static public function init_modules()
    {
        foreach (Model::get_enabled_modules() as $module) {
            $class = 'Sitepilot\Modules\\';
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
