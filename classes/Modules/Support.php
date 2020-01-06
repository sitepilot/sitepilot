<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;
use Sitepilot\Module;
use Sitepilot\Modules\Slack;

final class Support extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'support';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Support';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Functionalities for supporting clients.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 80;

    /**
     * Require other modules.
     *
     * @var string
     */
    static protected $require = ['slack'];

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        if (self::get_setting('support_script')) {
            add_action('in_admin_footer', __CLASS__ . '::action_support_script');
        }

        add_filter('login_headerurl', __CLASS__ . '::filter_login_logo');
        add_action('init', __CLASS__ . '::action_login_by_key');
        add_action('init', __CLASS__ . '::action_generate_key');
    }

    /**
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        return [
            'whitelisted_ips' => [
                'type' => 'text',
                'label' => __('Whitelisted IPs', 'sitepilot'),
                'default' => '',
                'help' => __('A comma separated list of IPs which are allowed to login using the Slack login feature.', 'sitepilot')
            ],
            'support_script' => [
                'type' => 'textarea',
                'label' => __('Support Script', 'sitepilot'),
                'default' => '',
                'help' => __('Insert a (support) script which will be injected into the admin footer (for example the Helpscout Beacon script).', 'sitepilot')
            ]
        ];
    }

    /**
     * Inject a support script into the admin footer.
     *
     * @return void
     */
    public static function action_support_script()
    {
        echo '<script>' . self::get_setting('support_script') . '</script>';
    }

    /**
     * Change login logo URL.
     *
     * @param string $url
     * @return mixed
     */
    public static function filter_login_logo($url)
    {
        if (self::check_ip()) {
            return admin_url('?sitepilot-action=generate_support_login_key');
        }
        return $url;
    }

    /**
     * Generate login key.
     *
     * @since 1.1.4
     */
    public static function action_generate_key()
    {
        if (self::check_ip() && isset($_GET['sitepilot-action']) && $_GET['sitepilot-action'] == 'generate_support_login_key' && !is_user_logged_in()) {
            $key = md5(uniqid());
            $expire = time() + 360;

            update_option('_sp_support_login_key', $key);
            update_option('_sp_support_login_key_expire', $expire);

            Slack::send(
                ":key: Login Key: " . get_bloginfo('url'),
                "A new login key was requested for " . get_bloginfo('name') . " (" . get_bloginfo('url') . ") .\n\nLogin here: " . admin_url() . "?sitepilot-login=" . $key . "\n\nGood luck!",
                ['color' => '#3498db', 'toText' => false]
            );
        }
    }

    /**
     * Provide access to the website (login as administrator).
     *
     * @return void
     */
    public static function action_login_by_key()
    {
        if (isset($_GET['sitepilot-login']) && self::is_valid_login_key($_GET['sitepilot-login'])) {
            if (defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN && !is_ssl()) {
                wp_redirect(admin_url() . '?sitepilot-login=' . $_GET['sitepilot-login']);
                exit;
            }

            $users = get_users(array('role' => 'administrator'));
            $login_user = $users[0]->data->ID;
            wp_set_auth_cookie($login_user);

            Model::set_last_support_login_date();

            wp_redirect(admin_url());
            exit;
        }
    }

    /**
     * Check if the IP address is whitelisted.
     *
     * @return bool
     */
    public static function check_ip()
    {
        $whitelist = explode(',', self::get_setting('whitelisted_ips'));
        $allowed_ips = array_map('trim', array_merge(['127.0.0.1'], $whitelist));

        if (!empty($_SERVER['SERVER_NAME']) && (!defined('WP_CLI') || !WP_CLI)) {
            $domain = explode('.', $_SERVER['SERVER_NAME']);
            $ips = self::get_client_ip();

            foreach ($ips as $ip) {
                if (in_array($ip, $allowed_ips) !== false || in_array('local', $domain) || in_array('dev', $domain)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns the client IP.
     *
     * @return array
     */
    private static function get_client_ip()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return explode(', ', $ipaddress);
    }

    /**
     * Checks if the login key is valid and not expired.
     *
     * @param string $key
     * @return bool $valid
     */
    private static function is_valid_login_key($key)
    {
        $saved_key = get_option('_sp_support_login_key', false);
        $timestamp = get_option('_sp_support_login_key_expire', false);

        if ($saved_key && $timestamp) {
            if ($timestamp > time() && $key == $saved_key) {
                return true;
            }
        }

        return false;
    }
}
