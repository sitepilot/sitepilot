<?php

/**
 * Plugin Name: Sitepilot
 * Plugin URI: https://sitepilot.io/
 * Description: A plugin for managing and developing WordPress websites.
 * Version: 1.0.0
 * Author: Sitepilot
 * Author URI: https://sitepilot.io/
 * Copyright: (c) 2019 Sitepilot
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sitepilot
 */

namespace Sitepilot;

if (!class_exists('Sitepilot\Sitepilot')) {

    final class Sitepilot
    {
        /**
         * Initialize the plugin.
         *
         * @return void
         */
        static public function init()
        {
            self::define_constants();
            self::load_files();
            self::init_classes();

            add_action('after_setup_theme', __CLASS__ . '::init_modules');
        }

        /**
         * Define plugin constants.
         *
         * @return void
         */
        static private function define_constants()
        {
            define('SITEPILOT_VERSION', '{{SP_VERSION}}');
            define('SITEPILOT_FILE', trailingslashit(dirname(__FILE__)) . 'sitepilot.php');
            define('SITEPILOT_DIR', plugin_dir_path(SITEPILOT_FILE));
            define('SITEPILOT_URL', plugins_url('/', SITEPILOT_FILE));
        }

        /**
         * Load classes and includes.
         *
         * @return void
         */
        static private function load_files()
        {
            /* Composer */
            require_once SITEPILOT_DIR . 'vendor/autoload.php';
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

    Sitepilot::init();
}
