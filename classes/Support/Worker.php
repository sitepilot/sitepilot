<?php

namespace Sitepilot\Support;

use Sitepilot\Model;
use Sitepilot\Module;

final class Worker extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'support-worker';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Worker';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Support settings for the ManageWP worker plugin.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 90;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        /* Filters */
        if (self::is_setting_enabled('filter_plugin_branding')) {
            add_filter('all_plugins', __CLASS__ . '::filter_plugins');
            add_filter('sp_settings_module_title_' . self::$module, __CLASS__ . '::filter_module_title');
            add_filter('sp_settings_module_description_' . self::$module, __CLASS__ . '::filter_module_description');
        }
    }

    /**
     * Check if the module is active.
     *
     * @return boolean
     */
    static public function is_active()
    {
        return function_exists('mwp_init');
    }

    /**
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        return [
            'filter_plugin_branding' => [
                'type' => 'checkbox',
                'label' => __('White label plugin.', 'sitepilot'),
            ],
            'category-1' => [
                'label' => __('White Label Settings', 'sitepilot'),
                'type' => 'category',
                'active' => self::is_setting_enabled('filter_plugin_branding')
            ],
            'plugin_name' => [
                'type' => 'text',
                'label' => __('White label plugin name', 'sitepilot'),
                'active' => self::is_setting_enabled('filter_plugin_branding'),
                'default' => sprintf(__('Autopilot', 'sitepilot'), Model::get_branding_name())
            ],
            'plugin_description' => [
                'type' => 'text',
                'label' => __('White label plugin description', 'sitepilot'),
                'active' => self::is_setting_enabled('filter_plugin_branding'),
                'default' => __('Autopilot keeps your website up-to-date and secure.', 'sitepilot')
            ],
        ];
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
     * Filter plugins list and white label worker plugin.
     *
     * @param $plugins
     * @return array $plugins
     */
    public static function filter_plugins($plugins)
    {
        $namespace = 'worker/init.php';

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
}
