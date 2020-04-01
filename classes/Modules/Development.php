<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;

final class Development extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'development';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Development';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Settings for developing websites.';

    /**
     * Require other modules.
     *
     * @var string
     */
    static protected $require = [];

    /**
     * @return void
     */
    static public function early_init()
    {
        parent::init();

        add_action('plugins_loaded', function () {
            if (self::is_setting_enabled('load_dev_theme') && is_super_admin() || isset($_GET['sitepilot-preview']) || isset($_COOKIE['sitepilot_preview'])) {
                if (file_exists(get_stylesheet_directory() . '-dev')) {
                    if (isset($_GET['sitepilot-preview'])) {
                        setcookie('sitepilot_preview', time(), time() + 600); // Preview is active for 10 minutes
                    }

                    add_filter('template', __CLASS__ . '::filter_template');
                    add_filter('stylesheet', __CLASS__ . '::filter_stylesheet');
                }
            }
        });
    }

    /**
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        return [
            'load_dev_theme' => [
                'type' => 'checkbox',
                'label' => __('Load development theme for Administrators', 'sitepilot'),
                'default' => '',
                'help' => __('Loads <current-theme-name>-dev instead of activated theme when logged in as an Administrator.', 'sitepilot')
            ]
        ];
    }

    /**
     * Filter stylesheet.
     * 
     * @param string $original 
     * @return string $stylesheet
     */
    static public function filter_stylesheet($original)
    {
        return self::get_theme_data($original, 'Stylesheet');
    }

    /**
     * Filter template.
     * 
     * @param string $original 
     * @return string $stylesheet
     */
    static public function filter_template($original)
    {
        return self::get_theme_data($original, 'Template');
    }

    /**
     * Returns theme data by key.
     * 
     * @return string
     */
    static public function get_theme_data($original, $key)
    {
        $theme_data = wp_get_theme(get_option('stylesheet') . "-dev");

        if (!empty($theme_data)) {
            if (isset($theme_data[$key])) {
                return (string) $theme_data[$key];
            }
        }

        return (string) $original;
    }
}
