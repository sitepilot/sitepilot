<?php

namespace Sitepilot;

use WP_Post;

class Model extends Module
{
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
     * Returns the primary color.
     *
     * @return string
     */
    public function get_primary_color(): ?string
    {
        $primary_color = get_option('sitepilot_primary_color');

        return apply_filters('sp_primary_color', $primary_color ? $primary_color : '#1062fe');
    }

    /** 
     * Returns the secondary color.
     * 
     * @return string
     */
    public function get_secondary_color(): ?string
    {
        $secondary_color = get_option('sitepilot_secondary_color');

        return apply_filters('sp_secondary_color', $secondary_color ? $secondary_color : '#0156f4');
    }

    /**
     * Returns the max container width.
     *
     * @return string
     */
    public function get_container_width(): ?string
    {
        $container_width = get_option('sitepilot_container_width');

        return apply_filters('sp_container_width', $container_width ? $container_width : '1200px');
    }

    /**
     * Returns the block margin defaults.
     *
     * @return array
     */
    public function get_block_margin(): ?array
    {
        return apply_filters('sp_block_margin', [
            'top' => ['mobile' => 0],
            'bottom' => ['mobile' => 4],
            'left' => ['mobile' => 0],
            'right' => ['mobile' => 0]
        ]);
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

        global $post;

        return $post;
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
}
