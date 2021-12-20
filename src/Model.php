<?php

namespace Sitepilot;

class Model extends Module
{
    /**
     * The module init priority.
     *
     * @var int
     */
    protected $priority = 6;

    /**
     * Initialize the model module.
     *
     * @return void
     */
    public function init(): void
    {
        //
    }

    /**
     * Returns the plugin version.
     *
     * @return string
     */
    public function get_version(): string
    {
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $data = get_plugin_data(SITEPILOT_FILE);

        return $data['Version'];
    }

    /**
     * Get saved plugin version.
     *
     * @return string
     */
    public function get_saved_version(): ?string
    {
        return get_site_option('_sp_version');
    }

    /**
     * Save plugin version.
     *
     * @param $version
     * @return bool
     */
    public function set_saved_version($version): bool
    {
        return update_site_option('_sp_version', $version);
    }

    /**
     * Check if the plugin is in development mode.
     *
     * @return boolean
     */
    public function is_dev(): bool
    {
        return strpos($this->get_version(), '-dev') !== false ? true : false;
    }

    /**
     * Save the last update timestamp.
     *
     * @return bool
     */
    public function set_last_update_date(): bool
    {
        return update_option('_sp_last_update_date', time());
    }

    /**
     * Returns the last update date timestamp.
     *
     * @return int
     */
    public function get_last_update_date(): ?int
    {
        return get_option('_sp_last_update_date');
    }

    /**
     * Checks if site is on the Sitepilot platform.
     *
     * @return bool
     */
    public function is_sitepilot_platform(): bool
    {
        return !empty(getenv('PLATFORM') && getenv('PLATFORM') == 'Sitepilot') ? true : false;
    }
}
