<?php

use Sitepilot\Plugin;

if (!function_exists('sitepilot')) {
    /**
     * Returns the Sitepilot plugin instance.
     *
     * @return Plugin
     */
    function sitepilot(): Plugin
    {
        static $plugin;

        if (!$plugin) {
            $plugin = new Plugin;
        }

        return $plugin;
    }
}

if (!function_exists('sp')) {
    /**
     * Returns the Sitepilot plugin instance.
     *
     * @return Plugin
     */
    function sp(): Plugin
    {
        return sitepilot();
    }
}
