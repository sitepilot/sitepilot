<?php

namespace Sitepilot;

use Jenssegers\Blade\Blade;
use Sitepilot\Extension\Acf;
use Sitepilot\Extension\Astra;
use Sitepilot\Extension\BeaverBuilder;
use Sitepilot\Extension\BeaverPowerPack;
use Sitepilot\Extension\BeaverUltimateAddons;

final class Plugin
{
    /**
     * The plugin instance.
     */
    private static Plugin $instance;

    /**
     * The log instance.
     */
    public Log $log;

    /**
     * The model instance.
     */
    public Model $model;

    /**
     * The blocks instance.
     */
    public Blocks $blocks;

    /**
     * The update instance.
     */
    public Update $update;

    /**
     * The support instance.
     */
    public Support $support;

    /**
     * The settings instance.
     */
    public Settings $settings;

    /**
     * The template instance.
     */
    public Template $template;

    /**
     * The branding instance.
     */
    public Branding $branding;

    /**
     * The dashboard instance.
     */
    public Dashboard $dashboard;

    /**
     * The client role instance.
     */
    public ClientRole $client_role;

    /**
     * The custom code instance.
     */
    public CustomCode $custom_code;

    /**
     * The cleanup dash instance.
     */
    public CleanupDash $cleanup_dash;

    /**
     * The acf extension instance.
     */
    public Acf $ext_acf;

    /**
     * The astra extension instance.
     */
    public Astra $ext_astra;

    /**
     * The beaver builder extension instance.
     */
    public BeaverBuilder $ext_beaver_builder;

    /**
     * The beaver power pack extension instance.
     */
    public BeaverPowerPack $ext_beaver_power_pack;

    /**
     * The beaver ultimate addons extension instance.
     */
    public BeaverUltimateAddons $ext_beaver_ultimate_addons;

    /**
     * Create a new plugin instance.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(...$arguments);
        }

        return self::$instance;
    }

    /**
     * Construct the plugin.
     * 
     * @return void
     */
    public function __construct()
    {
        /* Modules */
        $this->settings = new Settings($this);
        $this->log = new Log($this);
        $this->model = new Model($this);
        $this->blocks = new Blocks($this);
        $this->update = new Update($this);
        $this->support = new Support($this);
        $this->template = new Template($this);
        $this->branding = new Branding($this);
        $this->dashboard = new Dashboard($this);
        $this->client_role = new ClientRole($this);
        $this->custom_code = new CustomCode($this);
        $this->cleanup_dash = new CleanupDash($this);

        /* Extensions */
        $this->ext_acf = new Acf($this);
        $this->ext_astra = new Astra($this);
        $this->ext_beaver_builder = new BeaverBuilder($this);
        $this->ext_beaver_power_pack = new BeaverPowerPack($this);
        $this->ext_beaver_ultimate_addons = new BeaverUltimateAddons($this);

        /* Actions */
        add_action('wp_enqueue_scripts', [$this, 'action_register_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'action_register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'action_register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'action_enqueue_scripts']);

        /* Internal: Client Site */
        add_action('after_setup_theme', function () {
            if (apply_filters('sp_client_website', true)) {
                add_filter('sp_astra_branding', '__return_true');
                add_filter('sp_beaver_builder_branding', '__return_true');
                add_filter('sp_beaver_builder_filter_admin_settings_cap', '__return_true');
                add_filter('sp_beaver_builder_remove_default_templates', '__return_true');
                add_filter('sp_beaver_power_pack_branding', '__return_true');
                add_filter('sp_beaver_ultimate_addons_branding', '__return_true');
            }
        });

        /* Init Modules */
        do_action('sitepilot_init');
    }

    /**
     * Register plugin assets.
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

        /* Register Styles */
        wp_register_style('sitepilot', false);
        wp_register_style('sp-blocks', SITEPILOT_URL . '/assets/dist/css/blocks.css', [], $version);
        wp_register_style('sp-blocks-editor', SITEPILOT_URL . '/assets/dist/css/editor.css', [], $version);
        wp_register_style('sp-admin', SITEPILOT_URL . '/assets/dist/css/admin.css', [], $version);
        wp_register_style('sp-settings', SITEPILOT_URL . '/assets/dist/css/settings.css', array('wp-components'), $version);
        wp_register_style('sp-dashboard', SITEPILOT_URL . '/assets/dist/css/dashboard.css', array('wp-components'), $version);
        wp_register_style('owl-carousel-2', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.carousel.min.css', ['owl-carousel-2-theme'], '2.3.4');
        wp_register_style('owl-carousel-2-theme', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.theme.default.min.css', [], '2.3.4');

        /* Register Inline Styles */
        wp_enqueue_style('sitepilot');
        wp_add_inline_style('sitepilot', $this->get_inline_css());

        /* Register Scripts */
        wp_register_script('font-awesome-5', 'https://kit.fontawesome.com/ec90000d1a.js');
        wp_register_script('sp-blocks-editor', SITEPILOT_URL . '/assets/dist/js/editor.js', array(), $version, true);
        wp_register_script('sp-settings', SITEPILOT_URL . '/assets/dist/js/settings.js', array('wp-api', 'wp-i18n', 'wp-components', 'wp-element'), $version, true);
        wp_register_script('sp-dashboard', SITEPILOT_URL . '/assets/dist/js/dashboard.js', array('wp-api', 'wp-i18n', 'wp-components', 'wp-element'), $version, true);
        wp_register_script('owl-carousel-2', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.carousel.min.js', array(), '2.3.4', true);
    }

    /**
     * Enqueue admin scripts and stylesheets.
     * 
     * @return void
     */
    public function action_enqueue_scripts(): void
    {
        /* Enqueue Styles */
        wp_enqueue_style('sp-admin');
    }

    /**
     * Returns a blade instance.
     *
     * @return Blade
     */
    public function blade($folders = []): Blade
    {
        return new Blade(array_merge([SITEPILOT_DIR . '/views'], $folders), apply_filters('sp_blocks_cache_dir', SITEPILOT_DIR . '/cache'));
    }

    /**
     * Returns plugin inline CSS.
     *
     * @return string
     */
    public function get_inline_css(): string
    {
        return preg_replace("/\r|\n/", " ", $this->blade()->make('inline-css', ['plugin' => $this])->render());
    }
}
