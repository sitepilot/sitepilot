<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;

final class Menu
{
    /**
     * Initialize menu module.
     * 
     * @return void
     */
    static public function init()
    {
        /* Actions */
        add_action('admin_menu', __CLASS__ . '::action_admin_menu');
    }

    /**
     * Load admin menu.
     *
     * @return void;
     */
    static public function action_admin_menu()
    {
        add_menu_page(
            Branding::get_name(),
            Branding::get_name(),
            'publish_posts',
            'sitepilot-menu',
            __CLASS__ . '::render_info_page',
            false,
            2
        );

        add_submenu_page(
            'sitepilot-menu',
            Branding::get_name() . ' Info',
            'Info',
            'publish_posts',
            'sitepilot-menu',
            __CLASS__ . '::render_info_page'
        );
    }

    /**
     * Render info page.
     *
     * @return void
     */
    public static function render_info_page()
    {
        global $wp_version;

        echo '<div class="wrap">';
        echo '<h1>' . Branding::get_name() . '</h1>';

        echo '<p>';
        echo "WordPress: v$wp_version <br />";
        echo 'Server: ' . gethostname() . '<br />';
        echo __('PHP Version', 'sitepilot') . ': v' . phpversion();
        echo '</p>';

        $last_update = Model::get_last_update_date();
        $last_login = Model::get_last_support_login_date();
        $last_report = Model::get_last_report_date();

        echo '<p>';
        echo __('Last update', 'sitepilot') . ': ' . (!empty($last_update) ? date_i18n(get_option('date_format') .
            ' ' . get_option('time_format'), $last_update) : '-') . '<br />';
        echo __('Last report', 'sitepilot') . ': ' . (!empty($last_report) ? date_i18n(get_option('date_format') .
            ' ' . get_option('time_format'), $last_report) : '-') . '<br />';
        echo __('Last support login', 'sitepilot') . ': ' . (!empty($last_login) ? date_i18n(get_option('date_format') .
            ' ' . get_option('time_format'), $last_login) : '-') . '<br />';
        echo '</p>';

        echo '<h3 style="font-weight: 400;">' . __('Contact', 'sitepilot') . '</h3>';
        echo 'Website: <a href="' . Branding::get_website() . '" target="_blank">' . Branding::get_website() . '</a><br />';
        echo 'Help: <a href="' . Branding::get_support_url() . '" target="_blank">' . Branding::get_support_url() . '</a><br />';

        echo '<h3 style="font-weight: 400;">' . __('Debug Log', 'sitepilot') . '</h3>';

        echo "<div class='sp_log_output'>" . self::read_log(get_home_path() . '../logs/php-error.log') . "</div>";

        echo '</div>';
    }

    /**
     * Read log file.
     * 
     * @param string $file 
     * @return string $log 
     */
    public static function read_log($file)
    {
        if (file_exists($file)) {
            $log = '';
            $file = @file($file);

            if (is_array($file)) {
                $readLines = max(0, count($file) - 25);

                if ($readLines > 0) {
                    for ($i = $readLines; $i < count($file); $i++) {
                        $log .= $file[$i];
                        $log .= nl2br("\n");
                    }
                    return $log;
                }
            }
        }

        return 'No debug log found.';
    }
}
