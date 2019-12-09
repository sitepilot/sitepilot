<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;
use Sitepilot\Module;

/**
 * Compatibility settings for the Beaver Builder plugin.
 *
 * @since 1.0.0
 */
final class PluginBeaverBuilder extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'plugin-beaver-builder';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Beaver Builder Plugin';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Compatibility settings for the Beaver Builder plugin.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 601;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

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
    }

    /**
     * Check if the module is active.
     *
     * @return boolean
     */
    static public function is_active()
    {
        return defined('FL_BUILDER_VERSION');
    }

    /**
     * Returns module settings.
     *
     * @return void
     */
    static public function settings()
    {
        return [
            'filter_plugin_branding' => [
                'type' => 'checkbox',
                'label' => __('White label plugin.', 'sitepilot'),
            ],
            'filter_builder_modules' => [
                'type' => 'checkbox',
                'label' => __('Remove all default builder modules.', 'sitepilot'),
            ],
            'filter_builder_templates' => [
                'type' => 'checkbox',
                'label' => __('Remove all default builder templates.', 'sitepilot'),
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
