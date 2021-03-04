<?php

namespace Sitepilot;

class Support extends Module
{
    /**
     * Initialize the support module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Check if module is enabled */
        if (!sitepilot()->settings->enabled('support')) {
            return;
        }

        /* Actions */
        add_action('in_admin_footer', [$this, 'action_support_script']);
    }

    /**
     * Add support widget to admin footer.
     *
     * @return void
     */
    public function action_support_script(): void
    {
        $screen = get_current_screen();

        if (!empty($screen->id) && in_array($screen->id, ['dashboard', 'sitepilot_page_sitepilot-settings', 'toplevel_page_sitepilot-menu'])) {
            echo sitepilot()->branding->get_support_widget_script();
        }
    }
}
