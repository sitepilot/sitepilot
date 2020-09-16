<?php

namespace Sitepilot\Support;

use Sitepilot\Modules\Branding;

final class Astra
{
    /**
     * Initialize Astra support.
     * 
     * @return void
     */
    static public function init()
    {
        if (!self::is_active()) {
            return;
        }

        if (apply_filters('sp_astra_branding', false)) {
            add_filter('astra_addon_get_white_labels', __CLASS__ . '::filter_branding_options', 99);
            add_filter('sp_log_replace_names', function ($replace) {
                return array_merge($replace, [
                    'astra' => self::get_branding_name(),
                ]);
            });
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
     * Returns branding name.
     * 
     * @return string
     */
    public static function get_branding_name()
    {
        return apply_filters('sp_astra_branding_name', Branding::get_name() . ' Theme');
    }

    /**
     * Returns branding description.
     * 
     * @return string
     */
    public static function get_branding_description()
    {
        return apply_filters('sp_astra_branding_description', 'Base theme used for website development.');
    }

    /**
     * Filter branding options.
     *
     * @param array $branding
     * @return array $branding
     */
    public static function filter_branding_options($branding)
    {
        if (isset($branding['astra-agency'])) {
            $branding['astra-agency']['author'] = Branding::get_name();
            $branding['astra-agency']['author_url'] = Branding::get_website();
            $branding['astra-agency']['hide_branding'] = true;
        }

        if (isset($branding['astra'])) {
            $branding['astra']['name'] = self::get_branding_name();
            $branding['astra']['description'] = self::get_branding_description();
            $branding['astra']['screenshot'] = Branding::get_screenshot();
        }

        return $branding;
    }
}
