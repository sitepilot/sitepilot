<?php

namespace Sitepilot;

use Jenssegers\Blade\Blade;
use Sitepilot\Blocks\Blocks;
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
     * The loop instance.
     */
    public Loop $loop;

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
     * The shortcodes instance.
     */
    public Shortcodes $shortcodes;

    /**
     * The client role instance.
     */
    public ClientRole $client_role;

    /**
     * The custom code instance.
     */
    public CustomCode $custom_code;

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

            self::$instance->init();
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
        $this->log = new Log;
        $this->loop = new Loop;
        $this->model = new Model;
        $this->blocks = new Blocks;
        $this->update = new Update;
        $this->support = new Support;
        $this->settings = new Settings;
        $this->template = new Template;
        $this->branding = new Branding;
        $this->dashboard = new Dashboard;
        $this->shortcodes = new Shortcodes;
        $this->client_role = new ClientRole;
        $this->custom_code = new CustomCode;

        /* Extensions */
        $this->ext_acf = new Acf;
        $this->ext_astra = new Astra;
        $this->ext_beaver_builder = new BeaverBuilder;
        $this->ext_beaver_power_pack = new BeaverPowerPack;
        $this->ext_beaver_ultimate_addons = new BeaverUltimateAddons;
    }

    /**
     * Initialize plugin hooks.
     *
     * @return void
     */
    public function init(): void
    {
        /* Actions */
        add_action('wp_enqueue_scripts', [$this, 'action_register_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'action_register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'action_register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'action_enqueue_scripts']);

        /* Internal: Client Site */
        add_action('after_setup_theme', function () {
            if (apply_filters('sp_client_website', false)) {
                add_filter('sp_branding_login_enabled', '__return_true');
                add_filter('sp_branding_wp_head_enabled', '__return_true');
                add_filter('sp_branding_admin_bar_enabled', '__return_true');
                add_filter('sp_branding_admin_footer_enabled', '__return_true');
                add_filter('sp_hide_recaptcha_badge', '__return_true');
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

        wp_register_style('plyr-3', 'https://cdn.plyr.io/3.6.4/plyr.css', [], '3.6.4');
        wp_register_style('owl-carousel-2', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.carousel.min.css', ['owl-carousel-2-theme'], '2.3.4');
        wp_register_style('owl-carousel-2-theme', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.theme.default.min.css', [], '2.3.4');
        wp_register_style('twenty-twenty', SITEPILOT_URL . '/assets/dist/vendor/twenty-twenty/twentytwenty.css', [], $version);

        /* Register Inline Styles */
        wp_enqueue_style('sitepilot');
        wp_add_inline_style('sitepilot', $this->get_inline_css());

        /* Register Scripts */
        wp_register_script('sp-blocks', SITEPILOT_URL . '/assets/dist/js/blocks.js', ['jquery'], $version, true);
        wp_register_script('sp-blocks-editor', SITEPILOT_URL . '/assets/dist/js/editor.js', array(), $version, true);
        wp_register_script('sp-settings', SITEPILOT_URL . '/assets/dist/js/settings.js', array('wp-api', 'wp-i18n', 'wp-components', 'wp-element'), $version, true);
        wp_register_script('sp-dashboard', SITEPILOT_URL . '/assets/dist/js/dashboard.js', array('wp-api', 'wp-i18n', 'wp-components', 'wp-element'), $version, true);

        wp_register_script('font-awesome-5', 'https://kit.fontawesome.com/ec90000d1a.js');
        wp_register_script('plyr-3', 'https://cdn.plyr.io/3.6.4/plyr.js', array(), '3.6.4', true);
        wp_register_script('owl-carousel-2', SITEPILOT_URL . '/assets/dist/vendor/owl-carousel/owl.carousel.min.js', array(), '2.3.4', true);
        wp_register_script('jquery-event-move', SITEPILOT_URL . '/assets/dist/vendor/twenty-twenty/jquery.event.move.js', ['jquery'], $version, true);
        wp_register_script('twenty-twenty', SITEPILOT_URL . '/assets/dist/vendor/twenty-twenty/jquery.twentytwenty.js', ['jquery-event-move'], $version, true);
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
        $core_folders = [SITEPILOT_DIR . '/views'];
        $theme_views = get_stylesheet_directory() . '/views';

        if (file_exists($theme_views)) {
            $core_folders[] = $theme_views;
        }

        return new Blade(array_merge($core_folders, $folders), apply_filters('sp_blocks_cache_dir', SITEPILOT_DIR . '/cache'));
    }

    /**
     * Returns plugin inline CSS.
     *
     * @return string
     */
    public function get_inline_css(): string
    {
        return preg_replace("/\r|\n/", " ", $this->blade()->make('inline-css')->render());
    }
}
