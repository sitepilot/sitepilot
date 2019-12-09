<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;
use Sitepilot\Module;

/**
 * Compatibility settings for the Beaver Builder theme.
 *
 * @since 1.0.0
 */
final class ThemeBeaverBuilder extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'theme-beaver-builder';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Beaver Builder Theme';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Compatibility settings for the Beaver Builder theme.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 600;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        if (self::is_setting_enabled('filter_theme_branding')) {
            add_filter('wp_prepare_themes_for_js', __CLASS__ . '::filter_themes');
        }
    }

    /**
     * Check if module is active.
     *
     * @return boolean
     */
    static public function is_active()
    {
        return defined('FL_THEME_VERSION');
    }

    /**
     * Returns module settings.
     *
     * @return void
     */
    static public function settings()
    {
        return [
            'filter_theme_branding' => [
                'type' => 'checkbox',
                'label' => __('White label theme.', 'sitepilot'),
            ]
        ];
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
}
