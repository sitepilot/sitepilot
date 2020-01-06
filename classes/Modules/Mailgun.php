<?php

namespace Sitepilot\Modules;

use Sitepilot\Model;
use Sitepilot\Module;
use Mailgun\Mailgun as Client;

final class Mailgun extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'mailgun';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Mailgun';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Settings for sending email through Mailgun.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 62;

    /**
     * Require other modules.
     *
     * @var string
     */
    static protected $require = [];

    /**
     * @return void
     */
    static public function init()
    {
        parent::init();
    }

    /**
     * Returns module setting fields.
     *
     * @return void
     */
    static public function fields()
    {
        return [
            'key' => [
                'type' => 'text',
                'label' => __('Mailgun Key', 'sitepilot'),
                'default' => '',
                'help' => __('Enter a valid Mailgun API key to send support emails through Mailgun.', 'sitepilot')
            ],
            'domain' => [
                'type' => 'text',
                'label' => __('Mailgun Domain', 'sitepilot'),
                'default' => '',
                'help' => __('Enter a valid Mailgun domain to send support emails through Mailgun.', 'sitepilot')
            ]
        ];
    }

    /**
     * Send an email to a specific address through Mailgun.
     *
     * @param $subject
     * @param $body
     * @param $to
     * @param array $params
     */
    public static function send($subject, $body, $to, $params = [])
    {
        $key = self::get_setting('key');
        $domain = self::get_setting('domain');

        if ($key && $domain) {
            $defaults = [
                'from' => Model::get_branding_name() . ' <website@' . $domain . '>',
                'replyTo' => Model::get_branding_support_email()
            ];

            $params = $params + $defaults;
            $mg = Client::create($key);

            $mg->messages()->send($domain, [
                'from' => $params['from'],
                'to' => $to,
                'subject' => $subject,
                'html' => $body,
                'h:Reply-To' => $params['replyTo']
            ]);
        }
    }
}
