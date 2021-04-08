<?php

namespace Sitepilot;

use Jenssegers\Blade\Blade;

/**
 * @property \Sitepilot\Model $model
 * @property \Sitepilot\Updater $updater
 * @property \Sitepilot\Branding $branding
 * @property \Sitepilot\Dashboard $dashboard
 * @property \Sitepilot\Modules\Logs\Logs $logs
 * @property \Sitepilot\Modules\Cache\Cache $cache
 * @property \Sitepilot\Modules\Blocks\Blocks $blocks
 * @property \Sitepilot\Modules\Support\Support $support
 * @property \Sitepilot\Modules\Templates\Templates $templates
 * @property \Sitepilot\Modules\BeaverBuilder\BeaverBuilder $beaver_builder
 */
final class Plugin
{
    /**
     * Create a new plugin instance.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new static(...$arguments);

            $instance->init();
        }

        return $instance;
    }

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
            'logs' => \Sitepilot\Modules\Logs\Logs::class,
            'cache' => \Sitepilot\Modules\Cache\Cache::class,
            'blocks' => \Sitepilot\Modules\Blocks\Blocks::class,
            'support' => \Sitepilot\Modules\Support\Support::class,
            'branding' => \Sitepilot\Modules\Branding\Branding::class,
            'templates' => \Sitepilot\Modules\Templates\Templates::class,
            'shortcodes' => \Sitepilot\Modules\Shortcodes\Shortcodes::class,
            'client_role' => \Sitepilot\Modules\ClientRole\ClientRole::class,
            'client_site' => \Sitepilot\Modules\ClientSite\ClientSite::class,
            'beaver_builder' => \Sitepilot\Modules\BeaverBuilder\BeaverBuilder::class
        ];

        foreach ($modules as $key => $class) {
            $this->$key = new $class;
        }

        add_action('after_setup_theme', function () {
            do_action('sitepilot_init');
        });
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
        add_action('enqueue_block_editor_assets', [$this, 'action_register_assets'], 1);
        add_action('admin_enqueue_scripts', [$this, 'action_register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'action_enqueue_admin_assets']);
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
            $version = $this->model->get_version();
        }

        /* Styles */
        wp_register_style('sp-tailwind', SITEPILOT_URL . '/assets/dist/css/tailwind.css', [], $version);
        wp_register_style('sp-admin', SITEPILOT_URL . '/assets/dist/css/admin.css', [], $version);
        wp_register_style('sp-dashboard', SITEPILOT_URL . '/assets/dist/css/dashboard.css', ['sp-tailwind', 'wp-components'], $version);
        wp_register_style('sp-editor', SITEPILOT_URL . '/assets/dist/css/editor.css', [], $version);
        wp_register_style('sp-frontend', SITEPILOT_URL . '/assets/dist/css/frontend.css', [], $version);

        /* Vendor Styles */
        wp_register_script('plyr-3', 'https://cdn.plyr.io/3.6.4/plyr.js', array(), '3.6.4', true);
        wp_register_style('owl-carousel-2', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.carousel.min.css', ['owl-carousel-2-theme'], '2.3.4');
        wp_register_style('owl-carousel-2-theme', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.theme.default.min.css', [], '2.3.4');
        wp_register_style('twenty-twenty', SITEPILOT_URL . '/assets/dist/vendor/twenty-twenty/twentytwenty.css', [], $version);

        /* Scripts */
        wp_register_script('sp-editor', SITEPILOT_URL . '/assets/dist/js/editor.js', ['jquery'], $version, true);
        wp_register_script('sp-dashboard', SITEPILOT_URL . '/assets/dist/js/dashboard.js', ['wp-api', 'wp-i18n', 'wp-components', 'wp-element'], $version, true);
        wp_register_script('sp-frontend', SITEPILOT_URL . '/assets/dist/js/frontend.js', ['jquery'], $version, true);

        /* Vendor Scripts */
        wp_register_style('plyr-3', 'https://cdn.plyr.io/3.6.4/plyr.css', [], '3.6.4');
        wp_register_script('font-awesome-5', 'https://kit.fontawesome.com/ec90000d1a.js');
        wp_register_script('owl-carousel-2', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.carousel.min.js', array(), '2.3.4', true);
        wp_register_script('jquery-event-move', SITEPILOT_URL . '/assets/dist/vendor/twenty-twenty/jquery.event.move.js', ['jquery'], $version, true);
        wp_register_script('twenty-twenty', SITEPILOT_URL . '/assets/dist/vendor/twenty-twenty/jquery.twentytwenty.js', ['jquery-event-move'], $version, true);
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
     * Returns a blade instance.
     *
     * @return Blade
     */
    public function blade($folders = []): Blade
    {
        $core_folders = [SITEPILOT_DIR . '/views'];
        $theme_views = get_stylesheet_directory() . '/views';

        if (file_exists($theme_views)) {
            $core_folders[] = $theme_views;
        }

        return new Blade(array_merge($core_folders, $folders), apply_filters('sp_blade_cache_dir', SITEPILOT_DIR . '/cache'));
    }
}
