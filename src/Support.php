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
        echo "<script type=\"text/javascript\">
        window.Trengo = window.Trengo || {};
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
