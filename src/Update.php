<?php

namespace Sitepilot;

use Puc_v4_Factory;

class Update extends Module
{
    /**
     * Construct the update module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Actions */
        add_action('init', [$this, 'check_updates'], 99);
        add_action('admin_init', [$this, 'action_setup']);

        /* Filters */
        add_filter('sp_update_list', [$this, 'filter_update_list']);
    }

    /**
     * Run updater after theme setup.
     *
     * @return void
     */
    public function action_setup(): void
    {
        // Make sure the user is logged in or if this is a fresh install.
        if (!is_user_logged_in() && sitepilot()->model->get_saved_version()) {
            return;
        }

        // Get the saved version.
        $saved_version = sitepilot()->model->get_saved_version();

        // Only run updates if the version numbers don't match.
        if (!version_compare($saved_version, sitepilot()->model->get_version(), '=')) {
            $this->run($saved_version);

            sitepilot()->model->set_saved_version(sitepilot()->model->get_version());
        }
    }

    /**
     * Runs the update for a specific version.
     *
     * @param string $saved_version
     * @return void
     */
    private function run($saved_version): void
    {
        if (version_compare($saved_version, '1.0.0', '<')) {
            $this->v1_0_0();
        }
    }

    /**
     * Update to version 1.0.0.
     *
     * @return void
     */
    private function v1_0_0(): void
    {
        //
    }

    /**
     * Register plugin to the updater.
     *
     * @param array $list
     * @return array $list
     */
    public function filter_update_list(array $list): array
    {
        if (!sitepilot()->model->is_dev()) {
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
    public static function check_updates(): void
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
