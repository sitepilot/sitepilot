<?php

namespace Sitepilot\Modules;

final class Branding
{
    /**
     * Initialize branding module.
     * 
     * @return void
     */
    static public function init()
    {
        if (apply_filters('sp_branding_login', false)) {
            add_filter('login_headerurl', __CLASS__ . '::filter_login_url');
            add_action('login_enqueue_scripts', __CLASS__ . '::action_login_style');
        }

        if (apply_filters('sp_branding_head', false)) {
            add_action('wp_head', __CLASS__ . '::action_powered_by_head', 0);
        }

        if (apply_filters('sp_branding_footer', true)) {
            add_filter('admin_footer_text', __CLASS__ . '::filter_admin_footer_text');
            add_filter('update_footer', __CLASS__ . '::filter_admin_footer_version', 11);
        }
    }

    /**
     * Returns the branding name.
     *
     * @return string
     */
    static public function get_name()
    {
        return apply_filters('sp_branding_name', 'Sitepilot');
    }

    /**
     * Returns the branding logo.
     *
     * @return string
     */
    static public function get_logo()
    {
        return apply_filters('sp_branding_logo', SITEPILOT_URL . 'assets/dist/img/sitepilot-logo.png');
    }

    /**
     * Returns the branding icon url.
     *
     * @return string
     */
    static public function get_icon()
    {
        return apply_filters('sp_branding_icon', SITEPILOT_URL . 'assets/dist/img/sitepilot-icon.png');
    }

    /**
     * Returns the branding screenshot.
     *
     * @return string
     */
    static public function get_screenshot()
    {
        return apply_filters('sp_branding_screenshot', SITEPILOT_URL . 'assets/dist/img/sitepilot-screenshot.jpg');
    }

    /**
     * Returns the branding website.
     *
     * @return string
     */
    static public function get_website()
    {
        return apply_filters('sp_branding_website', 'https://sitepilot.io');
    }

    /**
     * Returns the branding url.
     *
     * @return string
     */
    static public function get_support_url()
    {
        return apply_filters('sp_branding_support_url', 'https://help.sitepilot.io');
    }

    /**
     * Returns the branding email.
     *
     * @return string
     */
    static public function get_support_email()
    {
        return apply_filters('sp_branding_support_email', 'support@sitepilot.io');
    }

    /**
     * Returns the branding powered by text.
     *
     * @return string
     */
    static public function get_powered_by_text()
    {
        return apply_filters('sp_branding_powered_by_text', '❤ Proudly hosted and managed by Sitepilot.');
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
                background-image: url(<?= self::get_logo() ?>) !important;
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
