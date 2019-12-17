<?php

namespace Sitepilot\Support;

use Sitepilot\Model;
use Sitepilot\Module;

final class BeaverBuilder extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'support-beaver-builder';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Beaver Builder';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Compatibility settings for the Beaver Builder plugin and theme.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 30;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        /* Theme */
        if (self::is_theme_active()) {
            if (self::is_setting_enabled('filter_theme_branding')) {
                add_filter('wp_prepare_themes_for_js', __CLASS__ . '::filter_themes');
            }
        }

        /* Builder */
        if (self::is_builder_active()) {
            if (self::is_setting_enabled('filter_plugin_branding')) {
                require_once(SITEPILOT_DIR . 'includes/builder/FLBuilderWhiteLabel.php');
                add_filter('all_plugins', __CLASS__ . '::filter_plugins');
            }
            if (self::is_setting_enabled('filter_builder_modules')) {
                add_filter('fl_builder_register_module', __CLASS__ . '::filter_builder_modules', 99, 2);
            }
            if (self::is_setting_enabled('filter_builder_templates')) {
                add_filter('fl_builder_get_templates', __CLASS__ . '::filter_builder_templates', 99, 2);
            }
            if (self::is_setting_enabled('filter_admin_settings_capability')) {
                get_role('administrator')->add_cap('sp_builder_admin_settings');
                add_filter('fl_builder_admin_settings_capability', function () {
                    return "sp_builder_admin_settings";
                });
            }
        }
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
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        return [
            'filter_theme_branding' => [
                'type' => 'checkbox',
                'label' => __('White label theme.', 'sitepilot'),
                'active' => self::is_theme_active()
            ],
            'filter_plugin_branding' => [
                'type' => 'checkbox',
                'label' => __('White label plugin.', 'sitepilot'),
                'active' => self::is_builder_active()
            ],
            'filter_builder_modules' => [
                'type' => 'checkbox',
                'label' => __('Remove all default builder modules.', 'sitepilot'),
                'active' => self::is_builder_active()
            ],
            'filter_builder_templates' => [
                'type' => 'checkbox',
                'label' => __('Remove all default builder templates.', 'sitepilot'),
                'active' => self::is_builder_active()
            ],
            'filter_admin_settings_capability' => [
                'type' => 'checkbox',
                'label' => __('Register custom admin settings capability.', 'sitepilot'),
                'help' => __("Change the admin settings capability of the builder to 'sp_builder_admin_settings'.", 'sitepilot'),
                'active' => self::is_builder_active()
            ]
        ];
    }

    /**
     * Filter plugins list and setup builder branding.
     *
     * @param $plugins
     * @return array $plugins
     */
    public static function filter_plugins($plugins)
    {
        $namespace = 'bb-plugin/fl-builder.php';

        if (isset($plugins[$namespace])) {
            $plugins[$namespace]['Name'] = Model::get_branding_name() . ' Builder';
            $plugins[$namespace]['Description'] = 'A drag and drop frontend page builder plugin that works with almost any theme.';
            $plugins[$namespace]['PluginURI'] = Model::get_branding_website();
            $plugins[$namespace]['Author'] = Model::get_branding_name();
            $plugins[$namespace]['AuthorURI'] = Model::get_branding_website();
            $plugins[$namespace]['Title'] = Model::get_branding_name() . ' Builder';
            $plugins[$namespace]['AuthorName'] = Model::get_branding_name();
        }

        return $plugins;
    }

    /**
     * White labels the builder theme on the themes page.
     *
     * @param array $themes An array data for each theme.
     * @return array
     */
    static public function filter_themes($themes)
    {
        if (isset($themes['bb-theme'])) {
            $themes['bb-theme']['name'] =  Model::get_branding_name() . " Theme";
            $themes['bb-theme']['description'] = "Base theme used for website development.";
            $themes['bb-theme']['author'] = Model::get_branding_name();
            $themes['bb-theme']['authorAndUri'] = '<a href="' . Model::get_branding_website() . '">' . Model::get_branding_name() . '</a>';
            $themes['bb-theme']['screenshot'] = array(Model::get_branding_screenshot());
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
