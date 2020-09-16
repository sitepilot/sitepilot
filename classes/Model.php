<?php

namespace Sitepilot;

final class Model
{
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
     * Returns the last update timestamp.
     *
     * @return int $time
     */
    static public function get_last_update_date()
    {
        return get_option('_sp_last_update_date');
    }

    /**
     * Save the last update timestamp.
     *
     * @return void
     */
    static public function set_last_update_date()
    {
        return update_option('_sp_last_update_date', time());
    }

    /**
     * Returns the last report timestamp.
     *
     * @return int $time
     */
    static public function get_last_report_date()
    {
        return get_option('_sp_last_report_date');
    }

    /**
     * Save the last report timestamp.
     *
     * @return void
     */
    static public function set_last_report_date()
    {
        return update_option('_sp_last_report_date', time());
    }

    /**
     * Returns the last support login timestamp.
     *
     * @return int $time
     */
    static public function get_last_support_login_date()
    {
        return get_option('_sp_last_support_login_date');
    }

    /**
     * Save the last support login timestamp.
     *
     * @return void
     */
    static public function set_last_support_login_date()
    {
        return update_option('_sp_last_support_login_date', time());
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
     * Check if multisite is enabled.
     *
     * @return bool
     */
    static public function is_multisite()
    {
        return is_multisite();
    }
}
