<?php

namespace Sitepilot;

class Shortcodes extends Module
{
    /**
     * Prevent content loop.
     *
     * @var integer
     */
    private $content_loop_count = 0;

    /**
     * Initialize the shortcode module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Shortcodes */
        add_shortcode('sp-date', [$this, 'shortcode_date']);
        add_shortcode('sp-powered-by', [$this, 'shortcode_powered_by']);
        add_shortcode('sp-post-title', [$this, 'shortcode_post_title']);
        add_shortcode('sp-post-content', [$this, 'shortcode_post_content']);
    }

    /**
     * Date shortcode.
     * 
     * @return string
     */
    public function shortcode_date($data = []): string
    {
        $data = array_merge([
            'format' => 'Y-m-d'
        ], $data);

        return date($data['format']);
    }

    /**
     * Powered by shortcode.
     *
     * @return string
     */
    public function shortcode_powered_by(): string
    {
        return sitepilot()->branding->get_powered_by_text();
    }

    /**
     * Post title shortcode.
     *
     * @return string
     */
    public function shortcode_post_title($data): string
    {
        $object = get_queried_object();

        $data = array_merge([
            'shop' => __('Webshop', 'sitepilot'),
            'blog' => __('Blog', 'sitepilot'),
            'search' => __('Search results for: %s', 'sitepilot'),
            'not_found' => __('Page not found', 'sitepilot')
        ], is_array($data) ? $data : []);

        $title = '';
        if ($object instanceof \WP_Term) {
            $title =  $object->name;
        } elseif (function_exists('is_search') && is_search()) {
            $title = sprintf($data['search'], get_search_query());
        } elseif (function_exists('is_404') && is_404()) {
            $title = $data['not_found'];
        } elseif (function_exists('is_shop') && is_shop()) {
            $title = $data['shop'];
        } else {
            $title = get_the_title();
        }

        return $title;
    }

    /**
     * Post content shortcode.
     *
     * @return string
     */
    public function shortcode_post_content(): string
    {
        $post = sitepilot()->model->get_post();

        if (!$this->content_loop_count) {
            $this->content_loop_count++;
            return apply_filters('the_content', $post->post_content);
        }

        return '';
    }
}
