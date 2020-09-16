<?php

namespace Sitepilot\Support;

use Sitepilot\Modules\Branding;

final class BeaverBuilder
{
    static public $admin_settings_cap = 'sp_builder_admin_settings';

    /**
     * Initialize Beaver Builder support.
     * 
     * @return void
     */
    static public function init()
    {
        if (!self::is_active()) {
            return;
        }

        /* Theme */
        if (self::is_theme_active()) {
            if (apply_filters('sp_beaver_builder_theme_branding', false)) {
                add_filter('wp_prepare_themes_for_js', __CLASS__ . '::filter_themes');
                add_filter('sp_log_replace_names', function ($replace) {
                    return array_merge($replace, [
                        'bb-theme' => self::get_branding_theme_name(),
                    ]);
                });
            }
        }

        /* Builder */
        if (self::is_builder_active()) {
            if (apply_filters('sp_beaver_builder_branding', false)) {
                require_once(SITEPILOT_DIR . 'includes/builder/FLBuilderWhiteLabel.php');
                add_filter('all_plugins', __CLASS__ . '::filter_plugins');
                add_filter('sp_log_replace_names', function ($replace) {
                    return array_merge($replace, [
                        'bb-plugin/fl-builder.php' => self::get_branding_name(),
                        'bb-theme-builder/bb-theme-builder.php' => self::get_branding_name() . ' - Themer Add-on'
                    ]);
                });
            }

            if (apply_filters('sp_beaver_builder_remove_default_modules', false)) {
                add_filter('fl_builder_register_module', __CLASS__ . '::filter_builder_modules', 99, 2);
            }

            if (apply_filters('sp_beaver_builder_remove_default_templates', false)) {
                add_filter('fl_builder_get_templates', __CLASS__ . '::filter_builder_templates', 99, 2);
            }

            if (apply_filters('sp_beaver_builder_filter_admin_settings_cap', false)) {
                get_role('administrator')->add_cap(self::$admin_settings_cap);
                add_filter('fl_builder_admin_settings_capability', function () {
                    return \Sitepilot\Support\BeaverBuilder::$admin_settings_cap;
                });
            }
        }

        /* Add-ons */
        BeaverPowerPack::init();
        BeaverUltimateAddons::init();
    }

    /**
     * Check if the module is active.
     *
     * @return boolean
     */
    static public function is_active()
    {
        return self::is_builder_active() || self::is_theme_active();
    }

    /**
     * Checks if Beaver Builder plugin is active.
     *
     * @return boolean
     */
    static public function is_builder_active()
    {
        return defined('FL_BUILDER_VERSION');
    }

    /**
     * Checks if Beaver Builder theme is active.
     *
     * @return boolean
     */
    static public function is_theme_active()
    {
        return defined('FL_THEME_VERSION');
    }

    /**
     * Returns branding name.
     * 
     * @return string
     */
    public static function get_branding_name()
    {
        return apply_filters('sp_beaver_builder_branding_name', Branding::get_name() . ' Builder');
    }

    /**
     * Returns theme branding name.
     * 
     * @return string
     */
    public static function get_branding_theme_name()
    {
        return apply_filters('sp_beaver_builder_branding_theme_name', Branding::get_name() . ' Theme');
    }

    /**
     * Returns branding description.
     * 
     * @return string
     */
    public static function get_branding_description()
    {
        return apply_filters('sp_beaver_builder_branding_description', 'A drag and drop frontend page builder plugin that works with almost any theme.');
    }

    /**
     * Returns theme branding description.
     * 
     * @return string
     */
    public static function get_branding_theme_description()
    {
        return apply_filters('sp_beaver_builder_branding_theme_description', 'Base theme used for website development.');
    }

    /**
     * Filter builder branding in plugins list.
     *
     * @param $plugins
     * @return array $plugins
     */
    public static function filter_plugins($plugins)
    {
        $namespace = 'bb-plugin/fl-builder.php';

        if (isset($plugins[$namespace])) {
            $plugins[$namespace]['Name'] = self::get_branding_name();
            $plugins[$namespace]['Description'] = self::get_branding_description();
            $plugins[$namespace]['PluginURI'] = Branding::get_website();
            $plugins[$namespace]['Author'] = Branding::get_name();
            $plugins[$namespace]['AuthorURI'] = Branding::get_website();
            $plugins[$namespace]['Title'] = self::get_branding_name();
            $plugins[$namespace]['AuthorName'] = Branding::get_name();
        }

        return $plugins;
    }

    /**
     * Filter builder theme branding in themes list.
     *
     * @param array $themes
     * @return array $themes
     */
    static public function filter_themes($themes)
    {
        if (isset($themes['bb-theme'])) {
            $themes['bb-theme']['name'] =  self::get_branding_theme_name();
            $themes['bb-theme']['description'] = self::get_branding_theme_description();
            $themes['bb-theme']['author'] = Branding::get_name();
            $themes['bb-theme']['authorAndUri'] = '<a href="' . Branding::get_website() . '">' . Branding::get_name() . '</a>';
            $themes['bb-theme']['screenshot'] = array(Branding::get_screenshot());
        }

        return $themes;
    }

    /**
     * Remove default modules.
     *
     * @param bool $enabled
     * @param object $instance
     * @return mixed
     */
    static public function filter_builder_modules($enabled, $instance)
    {
        $class = get_class($instance);
        $prefix = substr($class, 0, 2);

        if ($prefix == 'FL') {
            return false;
        }

        return $enabled;
    }

    /**
     * Remove default templates.
     *
     * @param array $data
     * @return array
     */
    static public function filter_builder_templates($data)
    {
        $return = [];
        foreach ($data as $item) {
            if (isset($item->image) && strpos($item->image, "demos.wpbeaverbuilder.com") === false) {
                $return[] = $item;
            }
        }
        return $return;
    }
}
