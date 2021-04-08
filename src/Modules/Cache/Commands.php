<?php

namespace Sitepilot\Modules\Cache;

use WP_CLI;

/**
 * Perform Sitepilot cache operations.
 *
 * ## EXAMPLES
 *
 *     # Purge the entire Sitepilot page cache
 *     $ wp sitepilot cache purge-site
 */
class Commands
{
    /**
     * Purge the entire Sitepilot page cache.
     *
     * ## EXAMPLES
     *
     *     wp sitepilot cache clear
     *
     * @subcommand clear
     */
    public function clear()
    {
        if (sitepilot()->cache->purge_page_cache()) {
            WP_CLI::success(__('The page cache was purged.', 'sitepilot'));
        } else {
            WP_CLI::error(__('The page cache could not be purged.', 'sitepilot'));
        }
    }

    /**
     * Purge a single post from the Sitepilot page cache.
     *
     * ## OPTIONS
     *
     * <post_id>
     * : The ID of the post to purge.
     *
     * ## EXAMPLES
     *
     *     wp sitepilot cache purge-post 123
     *
     * @subcommand purge-post
     */
    public function purge_post($args)
    {
        $post = get_post($args[0]);

        if (!$post) {
            WP_CLI::error(__('Post not found.', 'sitepilot'));

            return;
        }

        if (sitepilot()->cache->purge_post($post)) {
            WP_CLI::success(__('Post purged from the page cache.', 'sitepilot'));
        } else {
            WP_CLI::error(__('Post could not be purged from the page cache.', 'sitepilot'));
        }
    }

    /**
     * Purge a single URL from the Sitepilot page cache.
     *
     * ## OPTIONS
     *
     * <url>
     * : The URL to purge.
     *
     * ## EXAMPLES
     *
     *     wp sitepilot cache purge-url https://example.com
     *
     * @subcommand purge-url
     */
    public function purge_url($args)
    {
        if (sitepilot()->cache->purge_url($args[0])) {
            WP_CLI::success(__('URL purged from the page cache.', 'sitepilot'));
        } else {
            WP_CLI::error(__('URL could not be purged from the page cache.', 'sitepilot'));
        }
    }
}
