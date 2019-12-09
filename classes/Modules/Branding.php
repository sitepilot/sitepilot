<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;

/**
 * This module is responsible for branding WordPress.
 *
 * @since 1.0.0
 */
final class Branding extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'branding';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Branding';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'White label WordPress and this plugin.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 200;

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        if (self::is_setting_enabled('filter_admin_footer_text')) {
            add_filter('admin_footer_text', __CLASS__ . '::filter_admin_footer_text');
            add_filter('update_footer', __CLASS__ . '::filter_admin_footer_version', 11);
        }

        if (self::is_setting_enabled('filter_login_logo')) {
            add_filter('login_headerurl', __CLASS__ . '::filter_login_url');
            add_action('login_enqueue_scripts', __CLASS__ . '::action_login_style');
        }

        if (self::is_setting_enabled('action_powered_by_head')) {
            add_action('wp_head', __CLASS__ . '::action_powered_by_head', 0);
        }
    }

    /**
     * Returns module settings.
     *
     * @return void
     */
    static public function settings()
    {
        return [
            'action_powered_by_head' => [
                'type' => 'checkbox',
                'label' => __("Show 'powered by' text in theme head.", 'sitepilot'),
            ],
            'filter_admin_footer_text' => [
                'type' => 'checkbox',
                'label' => __("Show 'powered by' text in admin footer.", 'sitepilot'),
            ],
            'filter_login_logo' => [
                'type' => 'checkbox',
                'label' => __("Replace the default WordPress login logo.", 'sitepilot'),
            ],
            'separator' => [
                'type' => 'separator'
            ],
            'name' => [
                'type' => 'text',
                'label' => __('Name', 'sitepilot'),
                'default' => 'Sitepilot'
            ],
            'website' => [
                'type' => 'text',
                'label' => __('Website', 'sitepilot'),
                'default' => 'https://sitepilot.io'
            ],
            'support_url' => [
                'type' => 'text',
                'label' => __('Support URL', 'sitepilot'),
                'default' => 'https://help.sitepilot.io'
            ],
            'powered_by_text' => [
                'type' => 'text',
                'label' => __('Powered By Text', 'sitepilot'),
                'default' => '❤ Proudly developed and managed by Sitepilot.'
            ],
            'icon' => [
                'type' => 'text',
                'label' => __('Icon URL', 'sitepilot'),
                'default' => SITEPILOT_URL . 'assets/img/sitepilot-icon.png'
            ],
            'logo' => [
                'type' => 'text',
                'label' => __('Logo URL', 'sitepilot'),
                'default' => SITEPILOT_URL . 'assets/img/sitepilot-logo.png'
            ],
            'screenshot' => [
                'type' => 'text',
                'label' => __('Sreenshot URL', 'sitepilot'),
                'default' => SITEPILOT_URL . 'assets/img/sitepilot-screenshot.jpg'
            ],
        ];
    }

    /**
     * Returns the custom name.
     *
     * @return string
     */
    static public function get_name()
    {
        return self::get_setting('name', __('Sitepilot', 'sitepilot'));
    }

    /**
     * Returns the custom icon url.
     *
     * @return string
     */
    static public function get_icon()
    {
        return self::get_setting('icon', SITEPILOT_URL . 'assets/img/sitepilot-icon.png');
    }

    /**
     * Returns the custom icon url.
     *
     * @return string
     */
    static public function get_screenshot()
    {
        return self::get_setting('screenshot', SITEPILOT_URL . 'assets/img/sitepilot-screenshot.jpg');
    }

    /**
     * Returns the custom website.
     *
     * @return string
     */
    static public function get_website()
    {
        return self::get_setting('website', 'https://sitepilot.io');
    }

    /**
     * Returns the custom support url.
     *
     * @return string
     */
    static public function get_support_url()
    {
        return self::get_setting('support_url', 'https://help.sitepilot.io');
    }

    /**
     * Returns the custom powered by text.
     *
     * @return string
     */
    static public function get_powered_by_text()
    {
        return self::get_setting('powered_by_text', '❤ Proudly developed and managed by Sitepilot.');
    }

    /**
     * Filter admin footer text.
     *
     * @return string
     */
    static public function filter_admin_footer_text()
    {
        echo self::get_powered_by_text();
    }

    /**
     * Filter admin footer version.
     *
     * @return string
     */
    static public function filter_admin_footer_version()
    {
        global $wp_version;
        $html = '<div style="text-align: right;">WordPress v' . $wp_version . ' &sdot; ' . self::get_name() . ' v' . SITEPILOT_VERSION . '</div>';
        return $html;
    }

    /**
     * Filter the login logo url.
     *
     * @return string
     */
    static public function filter_login_url()
    {
        return self::get_website();
    }

    /**
     * Inject 'powered by' text into theme head.
     *
     * @return void
     */
    static public function action_powered_by_head()
    {
        echo "\n<!-- =================================================================== -->";
        echo "\n<!-- " . self::get_powered_by_text() . " -->";
        echo "\n<!-- =================================================================== -->\n\n";
    }

    /**
     * Change the login style.
     *
     * @return void
     */
    public static function action_login_style()
    {
       ?>
        <style>
            .login h1 a {
                background-image: url(<?= self::get_setting('logo') ?>) !important;
                background-size: 100% !important;
                background-position: center top !important;
                background-repeat: no-repeat !important;
                height: 70px !important;
                width: 220px !important;
                margin-top: 10px !important;
            }
        </style>
       <?php
    }
}
