<?php

namespace Sitepilot;

/**
 * Handles logic for the admin settings page.
 *
 * @since 1.0
 */
final class Settings
{
    /**
     * Holds any errors that may arise from
     * saving admin settings.
     *
     * @var array $errors
     */
    static public $errors = array();

    /**
     * Initializes the admin settings.
     *
     * @return void
     */
    static public function init()
    {
        add_action('init', __CLASS__ . '::init_hooks', 11);
    }

    /**
     * Adds the admin menu and enqueues CSS/JS if we are on
     * the builder admin settings page.
     *
     * @return void
     */
    static public function init_hooks()
    {
        if (!is_admin()) {
            return;
        }

        add_action('admin_menu', __CLASS__ . '::menu');

        if (isset($_REQUEST['page']) && 'sitepilot-settings' == $_REQUEST['page']) {
            add_action('admin_enqueue_scripts', __CLASS__ . '::styles_scripts');
            self::save();
        }
    }

    /**
     * Enqueues the needed CSS/JS for the admin settings page.
     *
     * @return void
     */
    static public function styles_scripts()
    {
        // Styles
        wp_enqueue_style('sitepilot-plugin', SITEPILOT_URL . 'assets/dist/css/admin-settings.css', array(), SITEPILOT_VERSION);
        
        // Scripts
        wp_enqueue_script('sitepilot-plugin', SITEPILOT_URL . 'assets/dist/js/admin-settings.js', array(), SITEPILOT_VERSION);
    }

    /**
     * Renders the admin settings menu.
     *
     * @return void
     */
    static public function menu()
    {
        if (Model::current_user_can_access_settings()) {
            $title = Model::get_branding_name() . ' ' . __('Settings', 'sitepilot');
            $cap   = Model::admin_settings_capability();
            $slug  = 'sitepilot-settings';
            $func  = __CLASS__ . '::render';

            add_submenu_page('options-general.php', $title, $title, $cap, $slug, $func);
        }
    }

    /**
     * Renders the admin settings.
     *
     * @since 1.0
     * @return void
     */
    static public function render()
    {
        include SITEPILOT_DIR . 'includes/admin-settings-js-config.php';
        include SITEPILOT_DIR . 'includes/admin-settings.php';
    }

    /**
     * Renders the page class for network installs and single site installs.
     *
     * @return void
     */
    static public function render_page_class()
    {
        if (Model::is_multisite()) {
            echo 'sp-settings-network-admin';
        } else {
            echo 'sp-settings-single-install';
        }
    }

    /**
     * Renders the admin settings page heading.
     *
     * @return void
     */
    static public function render_page_heading()
    {
        $icon = Model::get_branding_icon();
        $name = Model::get_branding_name();

        if (!empty($icon)) {
            echo '<img role="presentation" src="' . $icon . '" />';
        }
        /* translators: %s: builder branded name */
        echo '<span>' . sprintf(_x('%s Settings', '%s stands for custom branded "Sitepilot" name.', 'sitepilot '), $name) . '</span>';
    }

    /**
     * Renders the update message.
     *
     * @return void
     */
    static public function render_update_message()
    {
        if (!empty(self::$errors)) {
            foreach (self::$errors as $message) {
                echo '<div class="error"><p>' . $message . '</p></div>';
            }
        } elseif (!empty($_POST) && !isset($_POST['email'])) {
            echo '<div class="updated"><p>' . __('Settings updated!', 'sitepilot') . '</p></div>';
        }
    }

    /**
     * Renders the nav items for the admin settings menu.
     *
     * @return void
     */
    static public function render_nav_items()
    {
        $item_data = apply_filters('sp_admin_settings_nav_items', array(
            'modules'     => array(
                'title'    => __('Modules', 'sitepilot'),
                'show'     => true,
                'priority' => 100,
            )
        ));

        $sorted_data = array();

        foreach ($item_data as $key => $data) {
            $data['key'] = $key;
            $sorted_data[$data['priority']] = $data;
        }

        ksort($sorted_data);

        foreach ($sorted_data as $data) {
            if ($data['show']) {
                echo '<li><a href="#' . $data['key'] . '">' . $data['title'] . '</a></li>';
            }
        }
    }

    /**
     * Renders the admin settings forms.
     *
     * @return void
     */
    static public function render_forms()
    {
        // Modules
        self::render_form('modules');

        /**
         * Let extensions hook into form rendering.
         * @see sp_admin_settings_render_forms
         */
        do_action('sp_admin_settings_render_forms');
    }

    /**
     * Renders an admin settings form based on the type specified.
     *
     * @param string $type The type of form to render.
     * @return void
     */
    static public function render_form($type)
    {
        if (self::has_support($type)) {
            include SITEPILOT_DIR . 'includes/admin-settings-' . $type . '.php';
        }
    }

    /**
     * Renders the action for a form.
     *
     * @param string $type The type of form being rendered.
     * @return void
     */
    static public function render_form_action($type = '')
    {
        if (is_network_admin()) {
            echo network_admin_url('/settings.php?page=sitepilot-multisite-settings#' . $type);
        } else {
            echo admin_url('/options-general.php?page=sitepilot-settings#' . $type);
        }
    }

    /**
     * Returns the action for a form.
     *
     * @param string $type The type of form being rendered.
     * @return string The URL for the form action.
     */
    static public function get_form_action($type = '')
    {
        if (is_network_admin()) {
            return network_admin_url('/settings.php?page=sitepilot-multisite-settings#' . $type);
        } else {
            return admin_url('/options-general.php?page=sitepilot-settings#' . $type);
        }
    }

    /**
     * Checks to see if a settings form is supported.
     *
     * @param string $type The type of form to check.
     * @return bool
     */
    static public function has_support($type)
    {
        return file_exists(SITEPILOT_DIR . 'includes/admin-settings-' . $type . '.php');
    }

    /**
     * Adds an error message to be rendered.
     *
     * @param string $message The error message to add.
     * @return void
     */
    static public function add_error($message)
    {
        self::$errors[] = $message;
    }

    /**
     * Saves the admin settings.
     *
     * @return void
     */
    static public function save()
    {
        // Only admins can save settings.
        if (!Model::current_user_can_access_settings()) {
            return;
        }

        self::save_enabled_modules();

        /**
         * Let extensions hook into saving.
         * @see sp_admin_settings_save
         */
        do_action('sp_admin_settings_save');
    }

    /**
     * Saves the enabled modules.
     *
     * @access private
     * @return void
     */
    static private function save_enabled_modules()
    {
        if (isset($_POST['sp-modules-nonce']) && wp_verify_nonce($_POST['sp-modules-nonce'], 'modules')) {

            $modules = array();

            if (isset($_POST['sp-modules']) && is_array($_POST['sp-modules'])) {
                $modules = array_map('sanitize_text_field', $_POST['sp-modules']);
            }

            Model::update_admin_settings_option('_sp_enabled_modules', $modules, true);

            // Redirect after we saved the module settings (because some functions need to load early)
            wp_redirect(get_admin_url(null, 'options-general.php?page=sitepilot-settings'));
        }
    }
}
