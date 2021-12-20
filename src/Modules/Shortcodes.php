<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;

class Shortcodes extends Module
{
    /**
     * Initialize the shortcodes module.
     *
     * @return void
     */
    public function init(): void
    {
        add_shortcode('sp_title', [$this, 'title']);
        add_shortcode('sp_powered_by', [$this, 'powered_by']);
        add_shortcode('sp_date', [$this, 'date']);
    }

    /**
     * Title shortcode.
     *
     * @return string
     */
    public function title(): string
    {
        if (is_home()) {
            $title = get_the_title(get_option('page_for_posts'));
        } elseif (function_exists('is_shop') && is_shop()) {
            $title = get_the_title(get_option('woocommerce_shop_page_id'));
        } elseif ($object = get_queried_object()) {
            $title = $object->name;
        }

        return !empty($title) ? $title : get_the_title();
    }

    /**
     * Date shortcode.
     *
     * @param array $args
     * @return string
     */
    public function date($args = []): string
    {
        $args = array_merge([
            'format' => null
        ], $args ?: []);

        if (!$args['format']) {
            $args['format'] = get_option('date_format');
        }

        return date($args['format']);
    }

    /**
     * Powered by shortcode.
     *
     * @return string
     */
    public function powered_by(): string
    {
        return sitepilot()->branding()->get_powered_by_text();
    }
}
