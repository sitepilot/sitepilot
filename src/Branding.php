<?php

namespace Sitepilot;

class Branding extends Module
{
    /**
     * Initialize the template module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Check if module is enabled */
        if (!sitepilot()->settings->enabled('branding')) {
            return;
        }

        /* Actions */
        add_action('wp_head', [$this, 'action_powered_by_head'], 0);
        add_action('login_enqueue_scripts', [$this, 'action_login_style']);

        /* Filters */
        add_filter('login_headerurl', [$this, 'filter_login_url']);
        add_filter('admin_footer_text', [$this, 'filter_admin_footer_text']);
        add_filter('update_footer', [$this, 'filter_admin_footer_version'], 11);
    }

    /**
     * Returns the branding name.
     *
     * @return string
     */
    public function get_name(): string
    {
        return apply_filters('sp_branding_name', 'Sitepilot');
    }

    /**
     * Returns the branding logo.
     *
     * @return string
     */
    public function get_logo(): string
    {
        return apply_filters('sp_branding_logo', SITEPILOT_URL . '/assets/dist/img/sitepilot-logo.png');
    }

    /**
     * Returns the branding icon url.
     *
     * @return string
     */
    public function get_icon(): string
    {
        return apply_filters('sp_branding_icon', SITEPILOT_URL . '/assets/dist/img/sitepilot-icon.png');
    }

    /**
     * Returns the branding screenshot.
     *
     * @return string
     */
    public function get_screenshot(): string
    {
        return apply_filters('sp_branding_screenshot', SITEPILOT_URL . '/assets/dist/img/sitepilot-screenshot.jpg');
    }

    /**
     * Returns the branding website.
     *
     * @return string
     */
    public function get_website(): string
    {
        return apply_filters('sp_branding_website', 'https://sitepilot.io');
    }

    /**
     * Returns the branding url.
     *
     * @return string
     */
    public function get_support_url(): string
    {
        return apply_filters('sp_branding_support_url', 'https://help.sitepilot.io');
    }

    /**
     * Returns the branding email.
     *
     * @return string
     */
    public function get_support_email(): string
    {
        return apply_filters('sp_branding_support_email', 'support@sitepilot.io');
    }

    /**
     * Returns the branding powered by text.
     *
     * @return string
     */
    public function get_powered_by_text(): string
    {
        return apply_filters('sp_branding_powered_by_text', sprintf(__('❤ Proudly managed and hosted by %s.', 'sitepilot'), '<a href="' . sitepilot()->branding->get_website() . '" target="_blank">Sitepilot</a>'));
    }

    /**
     * Filter admin footer text.
     *
     * @return void
     */
    public function filter_admin_footer_text(): void
    {
        echo $this->get_powered_by_text();
    }

    /**
     * Filter admin footer version.
     *
     * @return string
     */
    public function filter_admin_footer_version(): string
    {
        global $wp_version;
        $html = '<div style="text-align: right;">WordPress v' . $wp_version . ' &sdot; ' . $this->get_name() . ' v' . sitepilot()->model->get_version() . '</div>';
        return $html;
    }

    /**
     * Filter the login logo url.
     *
     * @return string
     */
    public function filter_login_url(): string
    {
        return $this->get_website();
    }

    /**
     * Inject 'powered by' text into theme head.
     *
     * @return void
     */
    public function action_powered_by_head(): void
    {
        echo "\n<!-- =================================================================== -->";
        echo "\n<!-- " . $this->get_powered_by_text() . " -->";
        echo "\n<!-- =================================================================== -->\n\n";
    }

    /**
     * Change the login style.
     *
     * @return void
     */
    public function action_login_style(): void
    {
?>
        <style>
            .login h1 a {
                background-image: url(<?= $this->get_logo() ?>) !important;
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
