<?php

namespace Sitepilot\Modules;

use WP_user;
use Sitepilot\Module;

final class UserSwitching extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'user-switching';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'User Switching';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 90;

    /**
     * The name used to identify the application during a WordPress redirect.
     *
     * @var string
     */
    static protected $application = 'Sitepilot/Module/UserSwitching';

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        /* Actions */
        add_action('init', __CLASS__ . '::action_init');
        add_action('admin_init', __CLASS__ . '::action_register_capability');

        /* Filters */
        add_filter('user_row_actions', __CLASS__ . '::filter_user_row_actions', 10, 4);
    }

    /**
     * Register 'sp_user_switching' capability.
     *
     * @return void
     */
    static public function action_register_capability()
    {
        $role = get_role('administrator');
        $role->add_cap('sp_user_switching');
    }

    /**
     * Adds a 'Switch To' link to each list of user actions on the Users screen.
     *
     * @param array $actions
     * @param WP_User $user
     * @return array
     */
    static public function filter_user_row_actions(array $actions, WP_User $user)
    {
        if (current_user_can('sp_user_switching', $user->ID) && get_current_user_id() != $user->ID) {
            $link = wp_nonce_url(add_query_arg(array(
                'action'  => 'switch_to_user',
                'user_id' => $user->ID,
                'nr'      => 1,
            ), wp_login_url()), "switch_to_user_{$user->ID}");

            $actions['switch_to_user'] = sprintf(
                '<a href="%s">%s</a>',
                esc_url($link),
                esc_html__('Switch&nbsp;To', 'sitepilot')
            );
        }

        return $actions;
    }

    /**
     * Load actions depending on the 'action' query var.
     * 
     * @return void
     */
    static public function action_init()
    {
        if (!isset($_REQUEST['action'])) {
            return;
        }

        $current_user = (is_user_logged_in()) ? wp_get_current_user() : null;

        switch ($_REQUEST['action']) {

            case 'switch_to_user':
                if (isset($_REQUEST['user_id'])) {
                    $user_id = absint($_REQUEST['user_id']);
                } else {
                    $user_id = 0;
                }

                if (!current_user_can('sp_user_switching', $user_id)) {
                    wp_die(esc_html__('Could not switch users.', 'sitepilot'), 403);
                }

                check_admin_referer("switch_to_user_{$user_id}");

                $user = self::switch_to_user($user_id);
                if ($user) {
                    $redirect_to = self::get_redirect($user, $current_user);

                    $args = array(
                        'user_switched' => 'true',
                    );

                    if ($redirect_to) {
                        wp_safe_redirect(add_query_arg($args, $redirect_to), 302, self::$application);
                    } elseif (!current_user_can('read')) {
                        wp_safe_redirect(add_query_arg($args, home_url()), 302, self::$application);
                    } else {
                        wp_safe_redirect(add_query_arg($args, admin_url()), 302, self::$application);
                    }
                    exit;
                } else {
                    wp_die(esc_html__('Could not switch users.', 'sitepilot'), 404);
                }
                break;
        }
    }

    /**
     * Fetches the URL to redirect to for a given user (used after switching).
     *
     * @param  WP_User $new_user 
     * @param  WP_User $old_user
     * @return string
     */
    static private function get_redirect(WP_User $new_user = null, WP_User $old_user = null)
    {
        if (!empty($_REQUEST['redirect_to'])) {
            $redirect_to           = self::remove_query_args(wp_unslash($_REQUEST['redirect_to']));
            $requested_redirect_to = wp_unslash($_REQUEST['redirect_to']);
        } else {
            $redirect_to           = '';
            $requested_redirect_to = '';
        }

        if (!$new_user) {
            $redirect_to = apply_filters('logout_redirect', $redirect_to, $requested_redirect_to, $old_user);
        } else {
            $redirect_to = apply_filters('login_redirect', $redirect_to, $requested_redirect_to, $new_user);
        }

        return $redirect_to;
    }

    /**
     * Removes a list of common confirmation-style query args from an URL.
     *
     * @param  string $url
     * @return string
     */
    static private function remove_query_args($url)
    {
        if (function_exists('wp_removable_query_args')) {
            $url = remove_query_arg(wp_removable_query_args(), $url);
        }

        return $url;
    }

    /**
     * This function is used to determine whether to set a secure auth cookie or not.
     *
     * @return bool
     */
    static private function secure_auth_cookie()
    {
        return (is_ssl() && ('https' === parse_url(wp_login_url(), PHP_URL_SCHEME)));
    }

    /**
     * Gets the value of the auth cookie containing the list of originating users.
     *
     * @return array $cookie
     */
    static private function user_switching_get_auth_cookie()
    {
        if (UserSwitching::secure_auth_cookie()) {
            $auth_cookie_name = 'wordpress_user_sw_secure_' . COOKIEHASH;
        } else {
            $auth_cookie_name = 'wordpress_user_sw_' . COOKIEHASH;
        }

        if (isset($_COOKIE[$auth_cookie_name]) && is_string($_COOKIE[$auth_cookie_name])) {
            $cookie = json_decode(wp_unslash($_COOKIE[$auth_cookie_name]));
        }
        if (!isset($cookie) || !is_array($cookie)) {
            $cookie = array();
        }
        return $cookie;
    }

    /**
     * Switches the current logged in user to the specified user.
     *
     * @param int $user_id
     * @param bool $remember
     * @return false|WP_User
     */
    static public function switch_to_user($user_id, $remember = false)
    {
        $user = get_userdata($user_id);

        if (!$user) {
            return false;
        }

        $auth_cookie  = self::user_switching_get_auth_cookie();
        $cookie_parts = wp_parse_auth_cookie(end($auth_cookie));
        $new_token = isset($cookie_parts['token']) ? $cookie_parts['token'] : '';

        wp_clear_auth_cookie();
        wp_set_auth_cookie($user_id, $remember, '', $new_token);
        wp_set_current_user($user_id);

        return $user;
    }
}
