<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;
use Sitepilot\Module;
use Sitepilot\Modules\Mailgun;
use Sitepilot\Modules\Slack;

final class Report extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'report';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Report';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Send a site report to clients.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 71;

    /**
     * Require other modules.
     *
     * @var string
     */
    static protected $require = ['mailgun', 'slack', 'menu', 'log'];

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();

        /* Actions */
        add_action('sp_report_send', __CLASS__ . '::send_report');

        add_action("init", function () {
            if (!wp_next_scheduled('sp_report_send')) {
                wp_schedule_event(time(), "daily", "sp_report_send");
            }
        });
    }

    /**
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        $settings = [];
        $users = get_users();

        $settings['category-1'] = [
            'label' => __('Recipients', 'sitepilot'),
            'type' => 'category'
        ];

        foreach ($users as $user) {
            $settings['send_to_user_' . $user->ID] = [
                'type' => 'checkbox',
                'label' => $user->user_nicename . ' (' . $user->user_email . ')',
            ];
        }

        $settings['category-2'] = [
            'label' => __('Settings', 'sitepilot'),
            'type' => 'category'
        ];

        $settings['send_to_slack'] = [
            'type' => 'checkbox',
            'label' => __('Send a copy of the reports to Slack.', 'sitepilot'),
        ];

        $settings['interval'] =  [
            'type' => 'text',
            'label' => __('Interval (in days)', 'sitepilot'),
            'default' => '7',
            'help' => __('Send site report every x days.', 'sitepilot')
        ];

        $settings['mail_subject'] = [
            'type' => 'text',
            'label' => __('Mail subject', 'sitepilot'),
            'default' => __('Website Care Report: [sp_domain]', 'sitepilot')
        ];

        $settings['mail_message'] =  [
            'type' => 'textarea',
            'label' => __('Mail message', 'sitepilot'),
            'default' => __('<p>Hello [sp_report_name],</p><p>We have performed the following updates to [sp_domain] in the past [sp_report_interval] days:</p>[sp_log_list]<p>Reply to this email if you have any questions about one of these updates, we are happy to help.</p><p>Best regards,<br />Team [sp_branding_name]</p><p><img src="[sp_branding_logo]" width="100px" /></p>', 'sitepilot'),
            'help' => __('The message (HTML) which will be send to the recipients (available shortcodes: [sp_report_name], [sp_domain], [sp_log_list], [sp_branding_name]).', 'sitepilot')
        ];

        return $settings;
    }

    /**
     * Maybe send site report report.
     *
     * @return void
     */
    public static function send_report()
    {
        $seconds_in_day = 86400;
        $interval_days = self::get_setting('interval');

        if ((time() - Model::get_last_report_date()) > ($interval_days * $seconds_in_day)) {
            $log = Log::get(array(
                'date_query' => array(
                    array(
                        'after' => date('c', Model::get_last_report_date())
                    )
                )
            ));

            if ($log->have_posts()) {
                $body = do_shortcode(self::get_setting('mail_message'));
                $log_list = '';

                while ($log->have_posts()) {
                    $log->the_post();
                    $log_list .= '<li>' . get_the_title() . ' (' . get_the_date() . ')<br /><small><i>' . get_the_content() . '</i></small>';
                }

                $body = str_replace("[sp_log_list]", "<ul> $log_list </ul>", $body);
                $body = str_replace("[sp_report_interval]", $interval_days, $body);
                $subject = do_shortcode(self::get_setting('mail_subject'));
                $settings = self::get_enabled_settings();

                foreach ($settings as $setting) {
                    if (strpos($setting, 'send_to_user_') !== false) {
                        $user_id = str_replace('send_to_user_', '', $setting);
                        $user = get_user_by('ID', $user_id);
                        if ($user) {
                            $name = (!empty($user->user_firstname) ? $user->user_firstname : $user->display_name);
                            $send_body = str_replace("[sp_report_name]", $name, $body);
                            Mailgun::send($subject, $send_body, $user->user_email);
                            if (self::is_setting_enabled('send_to_slack')) {
                                Slack::send(':page_with_curl: ' . $subject, $send_body);
                            }
                        }
                    }
                }

                Model::set_last_report_date();
            }
        }
    }
}
