<?php

namespace Sitepilot\Modules;

final class Support
{
    /**
     * Initialize support module.
     * 
     * @return void
     */
    static public function init()
    {
        if (apply_filters('sp_support_beacon', true)) {
            add_action('in_admin_footer', __CLASS__ . '::action_support_script');
        }
    }

    /**
     * Inject a support script into the admin footer.
     *
     * @return void
     */
    public static function action_support_script()
    {
        $beacon = '!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});window.Beacon(\'init\', \'87e4f1e4-66fe-401d-88a5-34c9cd2c2c4f\')';

        echo "<script>$beacon</script>";
    }
}
