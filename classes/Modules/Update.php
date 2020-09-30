<?php

namespace Sitepilot\Modules;

use Puc_v4_Factory;
use Sitepilot\Model;

final class Update
{
    /**
     * Initialize update hooks.
     *
     * @return void
     */
    static public function init()
    {
        /* Actions */
        add_action('init', __CLASS__ . '::check_updates', 99);
        add_action('after_setup_theme', __CLASS__ . '::action_setup');

        /* Filters */
        add_filter('sp_update_list', __CLASS__ . '::filter_update_list');
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

        // Get the saved version.
        $saved_version = Model::get_version();

        // Only run updates if the version numbers don't match.
        if (!version_compare($saved_version, SITEPILOT_VERSION, '=')) {
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
     * Register plugin to the updater.
     *
     * @param array $list
     * @return array $list
     */
    public static function filter_update_list(array $list)
    {
        if (!Model::is_dev()) {
            $plugin['file'] = SITEPILOT_FILE;
            $plugin['slug'] = 'sitepilot';

            array_push($list, $plugin);
        }

        return $list;
    }

    /**
     * Check for plugin and theme updates.
     *
     * @return void
     */
    public static function check_updates()
    {
        foreach (apply_filters('sp_update_list', []) as $item) {
            if (isset($item['file']) && isset($item['slug'])) {
                if (!isset($item['repo'])) $item['repo'] = 'https://update.sitepilot.io/v1/?action=get_metadata&slug=' . $item['slug'];

                Puc_v4_Factory::buildUpdateChecker(
                    $item['repo'],
                    $item['file'],
                    $item['slug']
                );
            }
        }
    }
}
