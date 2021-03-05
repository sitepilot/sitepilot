<?php

namespace Sitepilot;

use WP_Post;

class Model extends Module
{
    /**
     * The module init priority.
     *
     * @var int
     */
    protected $priority = 6;

    /**
     * The current post object.
     *
     * @var WP_Post
     */
    private $post;

    /**
     * Construct the model module.
     * 
     * @return void
     */
    public function init(): void
    {
        //
    }

    /**
     * Returns the plugin version.
     *
     * @return string
     */
    public function get_version(): string
    {
        $data = get_plugin_data(SITEPILOT_FILE);

        return $data['Version'];
    }

    /**
     * Check if the plugin is in development mode.
     *
     * @return boolean
     */
    public function is_dev(): bool
    {
        return strpos($this->get_version(), '-dev') !== false ? true : false;
    }

    /**
     * Get saved plugin version.
     *
     * @return string
     */
    public function get_saved_version(): ?string
    {
        return get_site_option('_sp_version');
    }

    /**
     * Save plugin version.
     *
     * @param $version
     * @return bool
     */
    public function set_saved_version($version): bool
    {
        return update_site_option('_sp_version', $version);
    }

    /**
     * Returns the theme colors.
     *
     * @return array
     */
    public function get_theme_colors(): array
    {
        return apply_filters('sp_theme_colors', [
            'primary' => [
                'name' => __('Primary', 'sitepilot'),
                'color' => '#1062fe'
            ],
            'secondary' => [
                'name' => __('Secondary', 'sitepilot'),
                'color' => '#0156f4'
            ],
        ]);
    }

    /**
     * Returns the theme css vars.
     *
     * @return array
     */
    public function get_theme_vars(): array
    {
        return apply_filters('sp_theme_vars', [
            'sp-container-width' => '1200px'
        ]);
    }

    /**
     * Returns wether the Google Recaptcha badge needs to be hidden.
     *
     * @return void
     */
    public function get_hide_recaptcha_badge()
    {
        return apply_filters('sp_hide_recaptcha_badge', false);
    }

    /**
     * Save the last update timestamp.
     *
     * @return bool
     */
    public function set_last_update_date(): bool
    {
        return update_option('_sp_last_update_date', time());
    }

    /**
     * Returns the last update date timestamp.
     * 
     * @return int
     */
    public function get_last_update_date(): ?int
    {
        return get_option('_sp_last_update_date');
    }

    /**
     * Returns custom head code.
     *
     * @return string|null
     */
    public function get_code_wp_head(): ?string
    {
        $code = get_option('sitepilot_code_wp_head');

        if (!empty(trim($code))) {
            return $code;
        }

        return null;
    }

    /**
     * Returns custom body open code.
     *
     * @return string|null
     */
    public function get_code_wp_body_open(): ?string
    {
        $code = get_option('sitepilot_code_wp_body_open');

        if (!empty(trim($code))) {
            return $code;
        }

        return null;
    }

    /**
     * Returns custom footer code.
     *
     * @return string|null
     */
    public function get_code_wp_footer(): ?string
    {
        $code = get_option('sitepilot_code_wp_footer');

        if (!empty(trim($code))) {
            return $code;
        }

        return null;
    }

    /**
     * Set the current post.
     *
     * @param WP_Post $post
     * @return self
     */
    public function set_post(\WP_Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get the current post.
     *
     * @param int $fallback_post_id
     * @return WP_Post
     */
    public function get_post($fallback_post_id = null): ?WP_Post
    {
        if ($this->post) {
            return $this->post;
        }

        if ($fallback_post_id) {
            return get_post($fallback_post_id);
        }

        return $GLOBALS['post'];
    }

    /**
     * Get the current post ID.
     *
     * @param int $fallback_post_id
     * @return int
     */
    public function get_post_id($fallback_post_id = null): ?int
    {
        $post = $this->get_post($fallback_post_id);

        if ($post) {
            return $post->ID;
        }

        return null;
    }

    /**
     * Returns an array with available post types.
     *
     * @param boolean $filtered
     * @return array
     */
    public function get_post_types($filtered = false): array
    {
        $post_types = array();

        foreach (get_post_types() as $post_type) {
            if (!$filtered || (substr($post_type, 0, 3) == 'sp-' || in_array($post_type, ['post', 'page'])) && !in_array($post_type, ['sp-log', 'sp-template'])) {
                $object = get_post_type_object($post_type);
                $post_types[$post_type] = $object->labels->singular_name;
            }
        }

        return $post_types;
    }
}
