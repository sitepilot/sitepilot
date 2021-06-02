<?php

namespace Sitepilot;

use Sitepilot\Model;
use Sitepilot\Modules\Acf;
use Sitepilot\Modules\Logs;
use Sitepilot\Modules\Theme;
use Sitepilot\Modules\Branding;
use Sitepilot\Modules\Templates;
use Sitepilot\Modules\Extend\BeaverBuilder;

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

            /* Modules */
            'acf' => \Sitepilot\Modules\Acf::class,
            'logs' => \Sitepilot\Modules\Logs::class,
            'theme' => \Sitepilot\Modules\Theme::class,
            'support' => \Sitepilot\Modules\Support::class,
            'branding' => \Sitepilot\Modules\Branding::class,
            'templates' => \Sitepilot\Modules\Templates::class,
            'shortcodes' => \Sitepilot\Modules\Shortcodes::class,
            'client_role' => \Sitepilot\Modules\ClientRole::class,
            'client_site' => \Sitepilot\Modules\ClientSite::class,
            'primary_key_fixer' => \Sitepilot\Modules\PrimaryKeyFixer::class,

            /* Extend */
            'beaver_builder' => \Sitepilot\Modules\Extend\BeaverBuilder::class,
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
     * Returns the ACF instance.
     *
     * @return Acf
     */
    public function acf(): Acf
    {
        return $this->acf;
    }

    /**
     * Returns the theme instance.
     *
     * @return Theme
     */
    public function theme(): Theme
    {
        return $this->theme;
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
     * Returns the logs instance.
     *
     * @return Logs
     */
    public function logs(): Logs
    {
        return $this->logs;
    }

    /**
     * Returns the tempalates instance.
     *
     * @return Templates
     */
    public function templates(): Templates
    {
        return $this->templates;
    }

    /**
     * Returns the beaver builder instance.
     *
     * @return BeaverBuilder
     */
    public function beaver_builder(): BeaverBuilder
    {
        return $this->beaver_builder;
    }
}
