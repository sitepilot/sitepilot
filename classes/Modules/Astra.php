<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;
use Sitepilot\Module;

final class Astra extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'astra';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Astra';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Compatibility settings for the Astra theme.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 10;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        if (self::is_setting_enabled('filter_theme_branding')) {
            add_filter('astra_addon_branding_options', __CLASS__ . '::filter_branding_options');
        }
    }

    /**
     * Check if the module is active.
     *
     * @return boolean
     */
    static public function is_active()
    {
        return defined('ASTRA_THEME_VERSION');
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
            ]
        ];
    }

    /**
     * Filter branding options.
     *
     * @param array $branding
     * @return void
     */
    public static function filter_branding_options($branding)
    {
        if (isset($branding['astra-agency'])) {
            $branding['astra-agency']['author'] = Model::get_branding_name();
            $branding['astra-agency']['author_url'] = Model::get_branding_website();
            $branding['astra-agency']['hide_branding'] = true;
        }

        if (isset($branding['astra'])) {
            $branding['astra']['name'] = Model::get_branding_name() . " Theme";
            $branding['astra']['description'] = "Base theme used for website development.";
            $branding['astra']['screenshot'] = Model::get_branding_screenshot();
        }

        return $branding;
    }
}
