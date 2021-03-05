<?php

namespace Sitepilot;

class Branding extends Module
{
    /**
     * The options cache.
     *
     * @return array
     */
    private $options;

    /**
     * Initialize the template module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Branding */
        add_action('after_setup_theme', function () {
            if ($this->get_option('wp_head_enabled')) {
                add_action('wp_head', [$this, 'action_powered_by_head'], 0);
            }

            if ($this->get_option('login_enabled')) {
                add_action('login_enqueue_scripts', [$this, 'action_login_style']);
                add_filter('login_headerurl', [$this, 'filter_login_url']);
            }

            if ($this->get_option('admin_footer_enabled')) {
                add_filter('admin_footer_text', [$this, 'filter_admin_footer_text']);
            }

            if ($this->get_option('admin_bar_enabled')) {
                add_filter('wp_before_admin_bar_render', [$this, 'filter_admin_bar']);
            }
        });

        /* Filters */
        add_filter('update_footer', [$this, 'filter_admin_footer_version'], 11);
    }

    /**
     * Returns the branding options.
     *
     * @return array
     */
    private function get_options(): array
    {
        if (!$this->options) {
            $this->options = apply_filters('sp_branding_options', [
                'name' => apply_filters('sp_branding_name', 'Sitepilot'),
                'logo' => apply_filters('sp_branding_logo', SITEPILOT_URL . '/assets/dist/img/sitepilot-logo.png'),
                'icon' => apply_filters('sp_branding_icon', SITEPILOT_URL . '/assets/dist/img/sitepilot-icon.png'),
                'screenshot' => apply_filters('sp_branding_screenshot', SITEPILOT_URL . '/assets/dist/img/sitepilot-screenshot.jpg'),
                'website' => apply_filters('sp_branding_website', 'https://sitepilot.io'),
                'support_url' => apply_filters('sp_branding_support_url', 'https://help.sitepilot.io'),
                'support_email' => apply_filters('sp_branding_support_email', 'support@sitepilot.io'),
                'powered_by_text' => apply_filters('sp_branding_powered_by_text', sprintf(__('Powered by %s', 'sitepilot'), '<a href="https://sitepilot.io" target="_blank">Sitepilot</a>')),
                'wp_head_enabled' => apply_filters('sp_branding_wp_head_enabled', false),
                'login_enabled' => apply_filters('sp_branding_login_enabled', false),
                'admin_footer_enabled' => apply_filters('sp_branding_admin_footer_enabled', false),
                'admin_bar_enabled' => apply_filters('sp_branding_admin_bar_enabled', false)
            ]);
        }

        return $this->options;
    }

    /**
     * Returns a branding option by key.
     *
     * @return string
     */
    private function get_option($key): ?string
    {
        $options = $this->get_options();

        return $options[$key] ?? null;
    }

    /**
     * Returns the branding name.
     *
     * @return string
     */
    public function get_name(): string
    {
        return $this->get_option('name');
    }

    /**
     * Returns the branding logo.
     *
     * @return string
     */
    public function get_logo(): string
    {
        return $this->get_option('logo');
    }

    /**
     * Returns the branding icon url.
     *
     * @return string
     */
    public function get_icon(): string
    {
        return $this->get_option('icon');
    }

    /**
     * Returns the branding screenshot.
     *
     * @return string
     */
    public function get_screenshot(): string
    {
        return $this->get_option('screenshot');
    }

    /**
     * Returns the branding website.
     *
     * @return string
     */
    public function get_website(): string
    {
        return $this->get_option('website');
    }

    /**
     * Returns the branding url.
     *
     * @return string
     */
    public function get_support_url(): string
    {
        return $this->get_option('support_url');
    }

    /**
     * Returns the branding email.
     *
     * @return string
     */
    public function get_support_email(): string
    {
        return $this->get_option('support_email');
    }

    /**
     * Returns the branding powered by text.
     *
     * @return string
     */
    public function get_powered_by_text($link = true): string
    {
        return $this->get_option('powered_by_text');
    }

    /**
     * Returns the support widget script.
     *
     * @return string
     */
    public function get_support_widget_script(): string
    {
        $user = wp_get_current_user();

        $user_data = [];
        if (!empty($user->display_name)) {
            $user_data['name'] = $user->display_name;
        }

        if (!empty($user->user_email)) {
            $user_data['email'] = $user->user_email;
        }

        return apply_filters('sp_branding_support_widget', "<script type=\"text/javascript\">
        window.Trengo = window.Trengo || {};
        window.Trengo.contact_data = " . wp_json_encode($user_data) . ";
        window.Trengo.key = 'IN6SAcEF9cjuK5HvP1TC';
        (function(d, script, t) {
            script = d.createElement('script');
            script.type = 'text/javascript';
            script.async = true;
            script.src = 'https://static.widget.trengo.eu/embed.js';
            d.getElementsByTagName('head')[0].appendChild(script);
        }(document));
        </script>");
    }

    /**
     * Filter admin footer text.
     *
     * @return void
     */
    public function filter_admin_footer_text(): void
    {
        echo '❤ ' . $this->get_powered_by_text();
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
        echo "\n<!-- " . strip_tags($this->get_powered_by_text(false)) . " -->";
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

    /**
     * Remove menu items from the admin bar.
     *
     * @return void
     */
    public function filter_admin_bar(): void
    {
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('wp-logo');
    }
}
