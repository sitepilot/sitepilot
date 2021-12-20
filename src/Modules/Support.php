<?php

namespace Sitepilot\Modules;

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
            'enabled' => apply_filters('sp_support_enabled', sitepilot()->model()->is_sitepilot_platform())
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
?>
            <script type="text/javascript">
                ! function(e, t, n) {
                    function a() {
                        var e = t.getElementsByTagName("script")[0],
                            n = t.createElement("script");
                        n.type = "text/javascript", n.async = !0, n.src = "https://beacon-v2.helpscout.net", e.parentNode.insertBefore(n, e)
                    }
                    if (e.Beacon = n = function(t, n, a) {
                            e.Beacon.readyQueue.push({
                                method: t,
                                options: n,
                                data: a
                            })
                        }, n.readyQueue = [], "complete" === t.readyState) return a();
                    e.attachEvent ? e.attachEvent("onload", a) : e.addEventListener("load", a, !1)
                }(window, document, window.Beacon || function() {});
            </script>
            <script type="text/javascript">
                window.Beacon('init', '43962daf-3958-4eea-b3d8-b030020fb2ce')
            </script>
<?php
        }
    }
}
