<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;
use Html2Text\Html2Text;
use Maknz\Slack\Client as Client;

final class Slack extends Module
{
    /**
     * The unique module id.
     *
     * @var string
     */
    static protected $module = 'slack';

    /**
     * The module name.
     *
     * @var string
     */
    static protected $name = 'Slack';

    /**
     * The module description.
     *
     * @var string
     */
    static protected $description = 'Settings for sending messages to Slack.';

    /**
     * The module menu priority.
     *
     * @var string
     */
    static protected $priority = 72;

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
            'webhook' => [
                'type' => 'text',
                'label' => __('Webhook URL', 'sitepilot'),
                'default' => '',
                'help' => __('Webhook url which will be used to send messages to Slack.', 'sitepilot')
            ]
        ];
    }

    /**
     * Send the login URL to Slack.
     *
     * @param $title
     * @param $text
     * @param array $params
     */
    public static function send($title, $text, $params = array())
    {
        $webhook = self::get_setting('webhook');

        if ($webhook) {
            $defaults = [
                'color' => 'success',
                'toText' => true
            ];
            $params = $params + $defaults;

            if ($params['toText']) {
                $text = new Html2Text($text);
                $text = $text->getText();
            }

            $client = new Client($webhook);
            $client->attach([
                'fallback' => $title,
                'text' => $text,
                'color' => $params['color'],
            ])->send($title);
        }
    }
}
