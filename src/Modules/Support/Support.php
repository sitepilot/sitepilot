<?php

namespace Sitepilot\Modules\Support;

use Sitepilot\Module;

class Support extends Module
{
    /**
     * Initialize the support module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->get_setting('enabled')) {
            return;
        }

        /* Actions */
        add_action('in_admin_footer', [$this, 'admin_support_script']);
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_support_settings', [
            'enabled' => apply_filters('sp_support_enabled', sitepilot()->model->is_sitepilot_platform())
        ]);
    }

    /**
     * Add support widget to admin footer.
     *
     * @return void
     */
    public function admin_support_script(): void
    {
        $screen = get_current_screen();

        $user = wp_get_current_user();

        $user_data = [];
        if (!empty($user->display_name)) {
            $user_data['name'] = $user->display_name;
        }

        if (!empty($user->user_email)) {
            $user_data['email'] = $user->user_email;
        }

        if (!empty($screen->id) && in_array($screen->id, ['dashboard', 'sitepilot_page_sitepilot-settings', 'toplevel_page_sitepilot-menu'])) {
            echo "<script type=\"text/javascript\">
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
            </script>";
        }
    }
}
