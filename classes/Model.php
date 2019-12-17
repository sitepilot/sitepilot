<?php

namespace Sitepilot;

final class Model
{
    /**
     * When enabled the module uses an internal cache to serve settings.
     *
     * @var boolean
     */
    private static $cache_enabled = true;

    /**
     * Holds cache data.
     *
     * @var array
     */
    private static $cache = [];

    /**
     * Disable the cache.
     *
     * @return void
     */
    public static function disable_cache()
    {
        self::$cache_enabled = false;
    }

    /**
     * Enable the cache.
     *
     * @return void
     */
    public static function cache_enable()
    {
        self::$cache_enabled = true;
    }

    /**
     * Get plugin version.
     *
     * @return string
     */
    public static function get_version()
    {
        return get_site_option('_sp_version');
    }

    /**
     * Save plugin version.
     *
     * @param $version
     * @return void
     */
    public static function set_version($version)
    {
        return update_site_option('_sp_version', $version);
    }

    /**
     * Returns the custom branding string.
     *
     * @return string
     */
    static public function get_branding_name()
    {
        $class = 'Sitepilot\Modules\Branding';

        if (method_exists($class, 'get_name')) {
            return $class::get_name();
        }

        return 'Sitepilot';
    }

    /**
     * Returns the custom branding icon URL.
     *
     * @return string
     */
    static public function get_branding_icon()
    {
        $class = 'Sitepilot\Modules\Branding';

        if (method_exists($class, 'get_icon')) {
            return $class::get_icon();
        }

        return SITEPILOT_URL . 'assets/dist/img/sitepilot-icon.png';
    }

    /**
     * Returns the custom branding screenshot URL.
     *
     * @return string
     */
    static public function get_branding_screenshot()
    {
        $class = 'Sitepilot\Modules\Branding';

        if (method_exists($class, 'get_screenshot')) {
            return $class::get_screenshot();
        }

        return SITEPILOT_URL . 'assets/dist/img/sitepilot-screenshot.jpg';
    }

    /**
     * Returns the custom branding website.
     *
     * @return string
     */
    static public function get_branding_website()
    {
        $class = 'Sitepilot\Modules\Branding';

        if (method_exists($class, 'get_website')) {
            return $class::get_website();
        }

        return 'https://sitepilot.io';
    }

    /**
     * Returns the custom branding support url.
     *
     * @return string
     */
    static public function get_branding_support_url()
    {
        $class = 'Sitepilot\Modules\Branding';

        if (method_exists($class, 'get_support_url')) {
            return $class::get_support_url();
        }

        return 'https://help.sitepilot.io';
    }

    /**
     * Returns plugin / theme update server url.
     *
     * @param bool $disable_filter
     * @return string
     */
    static public function get_update_server_url($disable_filter = false)
    {
        $url = 'https://update.sitepilot.io/public/v1';

        if ($disable_filter) {
            return $url;
        }

        return apply_filters('sp_update_server_url', $url);
    }

    /**
     * Define capability.
     *
     * @return string
     */
    static public function admin_settings_capability()
    {
        return apply_filters('sp_admin_settings_capability', 'manage_options');
    }

    /**
     * Restrict settings accessibility based on the defined capability.
     *
     * @return void
     */
    static public function current_user_can_access_settings()
    {
        $cap_check = current_user_can(self::admin_settings_capability());
        if ($cap_check && in_array('administrator', wp_get_current_user()->roles)) {
            return true;
        }

        return false;
    }

    /**
     * Returns an option from the database for
     * the admin settings page.
     *
     * @param string $key The option key.
     * @param bool $network_override Whether to allow the network admin setting to be overridden on subsites.
     * @return mixed
     */
    static public function get_admin_settings_option($key, $network_override = true, $default = false)
    {
        if (self::$cache_enabled && isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        if (is_network_admin()) {
            $value = get_site_option($key, $default);
        } elseif (!$network_override && self::is_multisite()) {
            $value = get_site_option($key, $default);
        } elseif (self::is_multisite()) {
            $value = get_option($key, $default);
            $value = false === $value ? get_site_option($key, $default) : $value;
        } else {
            $value = get_option($key, $default);
        }

        if ($value != $default) {
            self::$cache[$key] = $value;
        }

        return $value;
    }

    /**
     * Updates an option from the admin settings page.
     *
     * @param string $key The option key.
     * @param mixed $value The value to update.
     * @param bool $network_override Whether to allow the network admin setting to be overridden on subsites.
     * @return mixed
     */
    static public function update_admin_settings_option($key, $value, $network_override = true)
    {
        if (is_network_admin()) {
            update_site_option($key, $value);
        } elseif ($network_override && self::is_multisite() && !isset($_POST['sp-override-ms'])) {
            delete_option($key);
        } else {
            update_option($key, $value);
        }
    }

    /**
     * Returns an array of all modules that are enabled.
     *
     * @return array
     */
    static public function get_enabled_modules()
    {
        $enabled_modules = self::get_admin_settings_option('_sp_enabled_modules', false, []);

        return apply_filters('sp_enabled_modules', $enabled_modules);
    }

    /**
     * Checks to see if a module is enabled.
     *
     * @param string $module
     * @return boolean
     */
    static public function is_module_enabled($module)
    {
        return in_array($module, self::get_enabled_modules());
    }

    /**
     * Check if multisite is enabled.
     *
     * @return bool
     */
    static public function is_multisite()
    {
        return is_multisite();
    }
}
