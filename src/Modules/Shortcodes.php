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
        add_shortcode('sitepilot', [$this, 'shortcode']);
    }

    /**
     * Sitepilot shortcode.
     *
     * @param array $args
     * @param string $content
     * @return void
     */
    public function shortcode($args = [], $content = '')
    {
        $function = str_replace('-', '_', $args[0] ?? '');

        $blocklist = ['shortcode'];

        if (!empty($args[0]) && method_exists($this, $function) && !in_array($function, $blocklist)) {
            return $this->$function($args);
        }

        return "Function [$function] does not exist.";
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
    public function date(array $args = []): string
    {
        $args = array_merge([
            'format' => null
        ], $args);

        if (!$args['format']) {
            $format = get_option('date_format');
        }

        return current_time($format);
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
