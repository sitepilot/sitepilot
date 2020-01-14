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
    static protected $description = 'Support settings for the Beaver Builder plugin, theme and add-ons.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 90;

    /**
     * 
     */
    static public $admin_settings_cap = 'sp_builder_admin_settings';

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
                add_filter('sp_settings_module_title_' . self::$module, __CLASS__ . '::filter_module_title');
                add_filter('sp_settings_module_description_' . self::$module, __CLASS__ . '::filter_module_description');
            }
            if (self::is_setting_enabled('filter_builder_modules')) {
                add_filter('fl_builder_register_module', __CLASS__ . '::filter_builder_modules', 99, 2);
            }
            if (self::is_setting_enabled('filter_builder_templates')) {
                add_filter('fl_builder_get_templates', __CLASS__ . '::filter_builder_templates', 99, 2);
            }
            if (self::is_setting_enabled('filter_admin_settings_capability')) {
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
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        return [
            /* Plugin */
            'category-plugin' => [
                'label' => __('Builder Plugin', 'sitepilot'),
                'type' => 'category',
                'active' => self::is_builder_active()
            ],
            'filter_plugin_branding' => [
                'type' => 'checkbox',
                'label' => sprintf(__('White label %s plugin.', 'sitepilot'), self::get_setting('plugin_name')),
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
                'label' => __('Use custom admin settings capability.', 'sitepilot'),
                'help' => sprintf(__("Change the admin settings capability of the builder (and add-ons) to '%s' to prevent client access.", 'sitepilot'), self::$admin_settings_cap),
                'active' => self::is_builder_active()
            ],
            'plugin_name' => [
                'type' => 'text',
                'label' => __('White label plugin name', 'sitepilot'),
                'active' => self::is_builder_active() && self::is_setting_enabled('filter_plugin_branding'),
                'default' => sprintf(__('%s Builder', 'sitepilot'), Model::get_branding_name())
            ],
            'plugin_description' => [
                'type' => 'text',
                'label' => __('White label plugin description', 'sitepilot'),
                'active' => self::is_builder_active() && self::is_setting_enabled('filter_plugin_branding'),
                'default' => __('A drag and drop frontend page builder plugin that works with almost any theme.', 'sitepilot')
            ],

            /* Theme */
            'category-theme' => [
                'label' => __('Builder Theme', 'sitepilot'),
                'type' => 'category',
                'active' => self::is_theme_active()
            ],
            'filter_theme_branding' => [
                'type' => 'checkbox',
                'label' => sprintf(__('White label %s theme.', 'sitepilot'), self::get_setting('plugin_name')),
                'active' => self::is_theme_active()
            ],
            'theme_name' => [
                'type' => 'text',
                'label' => __('White label theme name', 'sitepilot'),
                'active' => self::is_setting_enabled('filter_theme_branding'),
                'default' => sprintf(__('%s Theme', 'sitepilot'), Model::get_branding_name())
            ],
            'theme_description' => [
                'type' => 'text',
                'label' => __('White label theme description', 'sitepilot'),
                'active' => self::is_setting_enabled('filter_theme_branding'),
                'default' => __('Base theme used for website development.', 'sitepilot')
            ],

            /* Power Pack */
            'category-power-pack' => [
                'label' => __('Power Pack Plugin', 'sitepilot'),
                'type' => 'category',
                'active' => BeaverPowerPack::is_active()
            ],
            'filter_power_pack_branding' => [
                'type' => 'checkbox',
                'label' => __('White label Power Pack plugin.', 'sitepilot'),
                'active' => BeaverPowerPack::is_active()
            ],
            'power_pack_name' => [
                'type' => 'text',
                'label' => __('White label Power Pack plugin name', 'sitepilot'),
                'active' => BeaverPowerPack::is_active() && self::is_setting_enabled('filter_power_pack_branding'),
                'default' => __('Power Pack', 'sitepilot')
            ],
            'power_pack_description' => [
                'type' => 'text',
                'label' => __('White label Power Pack plugin description', 'sitepilot'),
                'active' => BeaverPowerPack::is_active() && self::is_setting_enabled('filter_power_pack_branding'),
                'default' => __('A set of custom, creative, unique modules to speed up the web design and development process.', 'sitepilot')
            ],

            /* Ultimate Addons */
            'category-ultimate-addons' => [
                'label' => __('Ultimate Addons Plugin', 'sitepilot'),
                'type' => 'category',
                'active' => BeaverUltimateAddons::is_active()
            ],
            'filter_ultimate_addons_branding' => [
                'type' => 'checkbox',
                'label' => __('White label Ultimate Addons plugin.', 'sitepilot'),
                'active' => BeaverUltimateAddons::is_active()
            ],
            'ultimate_addons_name' => [
                'type' => 'text',
                'label' => __('White label Ultimate Addons plugin name', 'sitepilot'),
                'active' => BeaverUltimateAddons::is_active() && self::is_setting_enabled('filter_ultimate_addons_branding'),
                'default' => __('Ultimate Addons', 'sitepilot')
            ],
            'ultimate_addons_description' => [
                'type' => 'text',
                'label' => __('White label Ultimate Addons plugin description', 'sitepilot'),
                'active' => BeaverUltimateAddons::is_active() && self::is_setting_enabled('filter_ultimate_addons_branding'),
                'default' => __('A set of custom, creative, unique modules to speed up the web design and development process.', 'sitepilot')
            ],
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
            $plugins[$namespace]['Name'] = self::get_setting('plugin_name');
            $plugins[$namespace]['Description'] = self::get_setting('plugin_description');
            $plugins[$namespace]['PluginURI'] = Model::get_branding_website();
            $plugins[$namespace]['Author'] = Model::get_branding_name();
            $plugins[$namespace]['AuthorURI'] = Model::get_branding_website();
            $plugins[$namespace]['Title'] = self::get_setting('plugin_name');
            $plugins[$namespace]['AuthorName'] = Model::get_branding_name();
        }

        return $plugins;
    }

    /**
     * Filter the module title when white label is active.
     * 
     * @param string $title 
     * @return string 
     */
    public static function filter_module_title($title)
    {
        return self::get_setting('plugin_name');
    }

    /**
     * Filter the module description when white label is active.
     *
     * @param string $description
     * @return string
     */
    public static function filter_module_description($description)
    {
        return self::get_setting('plugin_description');
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
            $themes['bb-theme']['name'] =  self::get_setting('theme_name');
            $themes['bb-theme']['description'] = self::get_setting('theme_description');
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
