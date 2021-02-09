<?php

namespace Sitepilot;

class Settings extends Module
{
    /**
     * Construct the settings module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Actions */
        add_action('init', [$this, 'register_settings']);
        add_action('admin_menu', [$this, 'action_admin_menu']);
    }

    /** 
     * Register admin menu. 
     * 
     * @return void
     */
    public function action_admin_menu(): void
    {
        $page_hook_suffix = add_menu_page(
            $this->plugin->branding->get_name(),
            $this->plugin->branding->get_name(),
            'publish_posts',
            'sitepilot-menu',
            '',
            false,
            2
        );

        add_submenu_page(
            'sitepilot-menu',
            $this->plugin->branding->get_name() . ' Info',
            __('Settings', 'sitepilot'),
            'manage_options',
            'sitepilot-menu',
            [$this, 'render_settings_page']
        );

        add_action("admin_print_scripts-{$page_hook_suffix}", [$this, 'enqueue_assets']);
    }

    /**
     * Enqueue setting page assets.
     *
     * @return void
     */
    function enqueue_assets(): void
    {
        /* Styles */
        wp_enqueue_style('sp-blocks');
        wp_enqueue_style('sp-settings');

        /* Settings */
        wp_enqueue_script('sp-settings');

        /* Data */
        wp_localize_script(
            'sp-settings',
            'sitepilot',
            array(
                'version' => $this->plugin->model->get_version(),
                'plugin_url' => SITEPILOT_URL,
                'branding_name' => $this->plugin->branding->get_name(),
                'support_email' => $this->plugin->branding->get_support_email(),
                'modules' => [
                    'blocks' => $this->plugin->ext_acf->is_active()
                ]
            )
        );
    }

    /**
     * Render setting page.
     *
     * @return void
     */
    function render_settings_page()
    {
        echo '<div class="sp-block" id="sitepilot-settings"></div>';
    }

    /**
     * Register plugin settings.
     *
     * @return void
     */
    function register_settings(): void
    {
        register_setting(
            'sitepilot_settings',
            'sitepilot_log_enabled',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => true
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_support_enabled',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => true
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_branding_enabled',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => false
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_blocks_enabled',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => false
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_templates_enabled',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => false
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_client_role_enabled',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => false
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_cleanup_dashboard_enabled',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => false
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_hide_recaptcha_enabled',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => false
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_primary_color',
            array(
                'type'         => 'string',
                'show_in_rest' => true,
                'default'      => ''
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_secondary_color',
            array(
                'type'         => 'string',
                'show_in_rest' => true,
                'default'      => ''
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_container_width',
            array(
                'type'         => 'string',
                'show_in_rest' => true,
                'default'      => ''
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_code_wp_head',
            array(
                'type'         => 'string',
                'show_in_rest' => true,
                'default'      => ''
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_code_wp_body_open',
            array(
                'type'         => 'string',
                'show_in_rest' => true,
                'default'      => ''
            )
        );

        register_setting(
            'sitepilot_settings',
            'sitepilot_code_wp_footer',
            array(
                'type'         => 'string',
                'show_in_rest' => true,
                'default'      => ''
            )
        );
    }

    /**
     * Check if a module is enabled.
     *
     * @param string $module
     * @return boolean
     */
    public function enabled($module): bool
    {
        return get_option('sitepilot_' . $module . '_enabled', false);
    }
}
