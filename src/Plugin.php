<?php

namespace Sitepilot;

use Sitepilot\Model;
use Sitepilot\AdminBar;
use Sitepilot\Modules\Cache;
use Sitepilot\Modules\Branding;

final class Plugin
{
    /**
     * Construct the plugin.
     *
     * @return void
     */
    public function __construct()
    {
        $modules = [
            /* Core */
            'model' => \Sitepilot\Model::class,
            'updater' => \Sitepilot\Updater::class,
            'dashboard' => \Sitepilot\Dashboard::class,
            'admin_bar' => \Sitepilot\AdminBar::class,

            /* Modules */
            'cache' => \Sitepilot\Modules\Cache::class,
            'support' => \Sitepilot\Modules\Support::class,
            'branding' => \Sitepilot\Modules\Branding::class,
            'shortcodes' => \Sitepilot\Modules\Shortcodes::class,
            'client_role' => \Sitepilot\Modules\ClientRole::class,
            'client_site' => \Sitepilot\Modules\ClientSite::class
        ];

        foreach ($modules as $key => $class) {
            $this->$key = new $class;
        }

        add_action('after_setup_theme', function () {
            do_action('sitepilot_init');
        });

        $this->init();
    }

    /**
     * Initialize plugin hooks.
     *
     * @return void
     */
    public function init(): void
    {
        /* Actions */
        add_action('wp_enqueue_scripts', [$this, 'action_register_assets'], 1);
        add_action('admin_enqueue_scripts', [$this, 'action_register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'action_enqueue_admin_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'action_register_assets'], 1);
    }

    /**
     * Register assets.
     *
     * @return void
     */
    public function action_register_assets(): void
    {
        if ($this->model->is_dev()) {
            $version = time();
        } else {
            $version = $this->model()->get_version();
        }

        /* Styles */
        wp_register_style('sp-admin', SITEPILOT_URL . '/assets/dist/css/admin.css', [], $version);
        wp_register_style('sp-editor', SITEPILOT_URL . '/assets/dist/css/editor.css', [], $version);
        wp_register_style('sp-tailwind', SITEPILOT_URL . '/assets/dist/css/tailwind.css', [], $version);
        wp_register_style('sp-frontend', SITEPILOT_URL . '/assets/dist/css/frontend.css', [], $version);
        wp_register_style('sp-dashboard', SITEPILOT_URL . '/assets/dist/css/dashboard.css', ['sp-tailwind', 'wp-components'], $version);

        /* Scripts */
        wp_register_script('sp-editor', SITEPILOT_URL . '/assets/dist/js/editor.js', ['jquery'], $version, true);
        wp_register_script('sp-frontend', SITEPILOT_URL . '/assets/dist/js/frontend.js', ['jquery'], $version, true);
        wp_register_script('sp-dashboard', SITEPILOT_URL . '/assets/dist/js/dashboard.js', ['wp-api', 'wp-i18n', 'wp-components', 'wp-element'], $version, true);
    }

    /**
     * Enqueue admin assets.
     *
     * @return void
     */
    public function action_enqueue_admin_assets(): void
    {
        /* Styles */
        wp_enqueue_style('sp-admin');
    }

    /**
     * Returns the model instance.
     *
     * @return Model
     */
    public function model(): Model
    {
        return $this->model;
    }

    /**
     * Returns the branding instance.
     *
     * @return Branding
     */
    public function branding(): Branding
    {
        return $this->branding;
    }

    /**
     * Returns the admin bar instance.
     *
     * @return AdminBar
     */
    public function admin_bar(): AdminBar
    {
        return $this->admin_bar;
    }

    /**
     * Returns the admin bar instance.
     *
     * @return Cache
     */
    public function cache(): Cache
    {
        return $this->cache;
    }
}
