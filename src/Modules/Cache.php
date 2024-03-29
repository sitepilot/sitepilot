<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;

class Cache extends Module
{
    /**
     * @var string
     */
    private $cache_path;

    /**
     * Initialize the client site module.
     *
     * @return void
     */
    public function init(): void
    {
        if (!$this->is_page_cache_enabled() && !$this->is_object_cache_enabled()) {
            return;
        }

        $this->set_cache_path();

        if ($this->is_object_cache_enabled() && $this->is_page_cache_enabled()) {
            sitepilot()->admin_bar()->add_item(__('Purge All Caches', 'sitepilot'), 'purge-all');
        }

        if ($this->is_object_cache_enabled()) {
            sitepilot()->admin_bar()->add_item(__('Purge Object Cache', 'sitepilot'), 'purge-object');
        }

        if ($this->is_page_cache_enabled()) {
            sitepilot()->admin_bar()->add_item(__('Purge Page Cache', 'sitepilot'), 'purge-page');
        }

        add_action('sitepilot_purge_object_cache', array($this, 'purge_object_cache'));
        add_action('sitepilot_purge_page_cache', array($this, 'purge_page_cache'));
        add_action('admin_init', array($this, 'handle_manual_purge_action'));
        add_action('transition_post_status', array($this, 'purge_post_on_update'), 10, 3);
        add_action('delete_post', array($this, 'purge_post_on_delete'), 10, 1);
        add_action('switch_theme', array($this, 'purge_page_cache'));
        add_action('comment_post', array($this, 'purge_post_on_comment'), 10, 2);
        add_action('wp_set_comment_status', array($this, 'purge_post_by_comment'));
    }

    /**
     * Handle manual purge actions.
     */
    public function handle_manual_purge_action()
    {
        if (!current_user_can(apply_filters('sitepilot_purge_cache_capability', 'manage_options'))) {
            return;
        }

        $action = filter_input(INPUT_GET, 'sitepilot_action');

        if (!$action || !in_array($action, array('purge-all', 'purge-object', 'purge-page'))) {
            return;
        }

        if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce'), $action)) {
            return;
        }

        if ('purge-all' === $action) {
            $purge = $this->purge_object_cache() && $this->purge_page_cache();
            $type  = 'all';
        }

        if ('purge-object' === $action) {
            $purge = $this->purge_object_cache();
            $type  = 'object';
        }

        if ('purge-page' === $action) {
            $purge = $this->purge_page_cache();
            $type  = 'page';
        }

        $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : admin_url();

        wp_safe_redirect(add_query_arg(array(
            'purge_success' => (int) $purge,
            'cache_type'    => $type,
        ), $redirect_url));
    }

    /**
     * When a post is transitioned to 'publish' for the first time purge the
     * entire site cache. This ensures blog pages, category archives, author archives,
     * search results and the 'Latest Posts' footer section is accurate. Otherwise,
     * only update the current post URL.
     *
     * @param string $new_status
     * @param string $old_status
     * @param WP_Post $post
     *
     * @return bool
     */
    public function purge_post_on_update($new_status, $old_status, $post)
    {
        $post_type = get_post_type($post);

        if (!in_array($post_type, $this->get_public_post_types())) {
            return false;
        }

        if (in_array($post_type, $this->get_post_types_excluded_from_purge())) {
            return false;
        }

        if (!$this->should_purge_post_status($new_status, $old_status)) {
            return false;
        }

        if ($post_type === 'customize_changeset' && $new_status === 'trash') {
            return false;
        }

        if (in_array($post_type, $this->get_post_types_needing_single_purge())) {
            return $this->purge_post($post);
        }

        return $this->purge_page_cache();
    }

    /**
     * Purge the entire cache when a post type is deleted.
     *
     * @param int $post_id
     *
     * @return bool
     */
    public function purge_post_on_delete($post_id)
    {
        $post_type = get_post_type($post_id);

        if (!in_array($post_type, $this->get_public_post_types())) {
            return false;
        }

        if (in_array($post_type, $this->get_post_types_excluded_from_purge())) {
            return false;
        }

        $post_status = get_post_status($post_id);

        if (in_array($post_status, array('auto-draft', 'draft', 'trash'))) {
            return false;
        }

        return $this->purge_page_cache();
    }

    /**
     * Purge a post on new comment (if approved).
     *
     * @param int $comment_id
     * @param bool $comment_approved
     *
     * @return bool
     */
    public function purge_post_on_comment($comment_id, $comment_approved)
    {
        if (!$comment_approved) {
            return false;
        }

        return $this->purge_post_by_comment($comment_id);
    }

    /**
     * Purge a post by comment ID.
     *
     * @param int $comment_id
     *
     * @return bool
     */
    public function purge_post_by_comment($comment_id)
    {
        $comment = get_comment($comment_id);

        if ($comment && $comment->comment_post_ID) {
            $post = get_post($comment->comment_post_ID);

            return $this->purge_post($post);
        }

        return false;
    }

    /**
     * Set the base cache path.
     */
    private function set_cache_path()
    {
        if (getenv('SITEPILOT_CACHE_PATH')) {
            $this->cache_path = getenv('SITEPILOT_CACHE_PATH');
        }
    }

    /**
     * Is the object cache enabled?
     *
     * @return bool
     */
    public function is_object_cache_enabled()
    {
        return wp_using_ext_object_cache();
    }

    /**
     * Is the page cache enabled?
     *
     * @return bool
     */
    public function is_page_cache_enabled()
    {
        return getenv('SITEPILOT_CACHE_STATUS') && in_array(getenv('SITEPILOT_CACHE_STATUS'), ['Enabled', 'On']);
    }

    /**
     * Purge entire object cache.
     *
     * @return bool
     */
    public function purge_object_cache()
    {
        return wp_cache_flush();
    }

    /**
     * Purge entire cache.
     *
     * @return bool
     */
    public function purge_page_cache()
    {
        $result = $this->delete($this->cache_path, true);

        do_action('sitepilot_site_purged', $result);

        return $result;
    }

    /**
     * Purge the current post URL.
     *
     * @param WP_Post $post
     *
     * @return bool
     */
    public function purge_post($post)
    {
        $result = $this->purge_url(get_permalink($post));

        do_action('sitepilot_post_purged', $post, $result);

        return $result;
    }

    /**
     * Purge a single URL from the cache.
     *
     * @param string $url
     *
     * @return bool
     */
    public function purge_url($url)
    {
        $path   = $this->get_cache_path_for_url($url);
        $result = $this->delete($path);

        do_action('sitepilot_url_purged', $url, $result);

        return $result;
    }

    /**
     * Get's the cache file path for a given URL.
     *
     * Must be using the default Nginx cache options (levels=1:2)
     * and (fastcgi_cache_key "$scheme$request_method$host$request_uri").
     * https://www.digitalocean.com/community/tutorials/how-to-setup-fastcgi-caching-with-nginx-on-your-vps#purging-the-cache
     *
     * @param string $url
     *
     * @return string
     */
    private function get_cache_path_for_url($url)
    {
        $parsed_url = parse_url(trailingslashit($url));
        $cache_key  = md5($parsed_url['scheme'] . 'GET' . $parsed_url['host'] . $parsed_url['path']);
        $cache_path = substr($cache_key, -1) . '/' . substr($cache_key, -3, 2) . '/' . $cache_key;

        return trailingslashit($this->cache_path) . $cache_path;
    }

    /**
     * Delete a file/dir from the local filesystem.
     *
     * @param string $path Absolute path to file
     * @param bool $recursive
     *
     * @return bool
     */
    private function delete($path, $recursive = false)
    {
        if (file_exists($path) && is_writable($path)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');

            $context = $path;
            if (is_file($path)) {
                $context = dirname($path);
            }

            if (!WP_Filesystem(false, $context, true)) {
                return false;
            }

            global $wp_filesystem;
            $wp_filesystem->delete($path, $recursive);

            return true;
        }
    }

    /**
     * Should a post be purged based on the new/old status.
     *
     * @param string $new_status
     * @param string $old_status
     *
     * @return bool
     */
    private function should_purge_post_status($new_status, $old_status)
    {
        // A newly created post with no content
        if ($new_status === 'auto-draft') {
            return false;
        }

        // A post in draft status
        if ($new_status === 'draft' && in_array($old_status, array('auto-draft', 'draft', 'trash'))) {
            return false;
        }

        // A post in trash status
        if ($new_status === 'trash' && in_array($old_status, array('auto-draft', 'draft', 'trash'))) {
            return false;
        }

        return apply_filters('sitepilot_should_purge_post_status', true);
    }

    /**
     * Get public post types that should trigger a cache purge.
     *
     * @return array
     */
    private function get_public_post_types()
    {
        $post_types = get_post_types([
            'public' => true,
        ]);

        return apply_filters('sitepilot_public_post_types', $post_types);
    }

    /**
     * Get post types that should only purge their own public facing URL.
     *
     * @return array
     */
    private function get_post_types_needing_single_purge()
    {
        return apply_filters('sitepilot_post_types_needing_single_purge', array());
    }

    /**
     * Get post types that should never trigger a cache purge.
     *
     * @return array
     */
    private function get_post_types_excluded_from_purge()
    {
        return apply_filters('sitepilot_post_types_excluded_from_purge', array(
            'attachment',
            'custom_css',
            'revision',
            'user_request'
        ));
    }
}
