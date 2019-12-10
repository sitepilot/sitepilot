<?php

namespace Sitepilot;

use Puc_v4_Factory;

/**
 * Responsible for updating the plugin.
 *
 * @since 1.0.0
 */
final class Update
{
    public static $isBeta = false;

    /**
     * Initialize update hooks.
     *
     * @return void
     */
    static public function init()
    {
        /* Actions */
        add_action('after_setup_theme', __CLASS__ . '::action_setup');
        add_action('init', __CLASS__ . '::check_plugin_theme_updates', 99);
    }

    /**
     * Setup menu after theme is loaded.
     *
     * @return void
     */
    static public function action_setup()
    {
        // Make sure the user is logged in or if this is a fresh install.
        if (!is_user_logged_in() && Model::get_version()) {
            return;
        }

        // Check if this is a beta version.
        if (strpos(SITEPILOT_VERSION, 'SP_VERSION') !== false) {
            self::$isBeta = true;
        }

        // Get the saved version.
        $saved_version = Model::get_version();

        // Only run updates if the version numbers don't match.
        if (!version_compare($saved_version, SITEPILOT_VERSION, '=') || self::$isBeta) {
            self::run($saved_version);
            Model::set_version(SITEPILOT_VERSION);
        }
    }

    /**
     * Runs the update for a specific version.
     *
     * @param string $saved_version
     * @return void
     */
    private static function run($saved_version)
    {
        if (version_compare($saved_version, '1.0.0', '<')) {
            self::v1_0_0();
        }
    }

    /**
     * Update to version 1.0.0.
     *
     * @return void
     */
    private static function v1_0_0()
    {
        //
    }

    /**
     * Check for plugin and theme updates.
     *
     * @return void
     */
    public static function check_plugin_theme_updates()
    {
        if (strpos(SITEPILOT_VERSION, 'SP_VERSION') === false) {
            @Puc_v4_Factory::buildUpdateChecker(
                Model::get_update_server_url(),
                SITEPILOT_FILE,
                'sitepilot'
            );
        }

        foreach (apply_filters('sp_update_plugins', []) as $plugin) {
            $plugin_version = (get_plugin_data($plugin['file']))['Version'];
            if (strpos($plugin_version, 'SP_VERSION') === false) {
                @Puc_v4_Factory::buildUpdateChecker(
                    trailingslashit(Model::get_update_server_url()) . '?action=get_metadata&slug=' . $plugin['slug'],
                    $plugin['file'],
                    $plugin['slug']
                );
            }
        }

        foreach (apply_filters('sp_update_themes', []) as $theme) {
            $theme_version = (wp_get_theme($theme['slug']))->get('Version');
            if (strpos($theme_version, 'SP_VERSION') === false) {
                @Puc_v4_Factory::buildUpdateChecker(
                    trailingslashit(Model::get_update_server_url()) . '?action=get_metadata&slug=' . $theme['slug'],
                    $theme['file'],
                    $theme['slug']
                );
            }
        }
    }
}
