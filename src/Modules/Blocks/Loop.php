<?php

namespace Sitepilot\Modules\Blocks;

use WP_Query;

class Loop
{
    /**
     * Loop query counter
     *
     * @var int
     */
    public $loop_counter = 0;

    /**
     * Custom pagination regex base.
     *
     * @var string
     */
    public $paged_regex_base = 'paged-[0-9]{1,}';

    /**
     * Cache the custom pagination data.
     * Format:
     *      array(
     *          'current_page' => '',
     *          'current_loop' => '',
     *          'paged' => ''
     *      )
     *
     * @var array
     */
    public $custom_paged_data = array();

    /**
     * Set random seed to avoid duplicate posts in pagination.
     *
     * @var int
     */
    private $rand_seed = 0;

    /**
     * Flag for flushing post type rewrite rules.
     *
     * @var bool
     */
    private $rewrote_post_type = false;

    /**
     * Construct the loop class.
     * 
     * @return void
     */
    public function __construct()
    {
        /* Actions */
        add_action('init', [$this, 'init_rewrite_rules'], 20);
        add_action('registered_post_type', [$this, 'post_type_rewrite_rules'], 10, 2);
        add_action('wp_loaded', [$this, 'flush_rewrite_rules'], 1);

        /* Filters */
        add_filter('redirect_canonical', [$this, 'override_canonical'], 1, 2);
    }

    /**
     * Returns either a clone of the main query or a new instance of
     * WP_Query based on the provided block settings.
     *
     * @param array $data
     * @return WP_Query
     */
    public function query(array $data): WP_Query
    {
        $this->loop_counter++;

        if (isset($data['query_order_by']) && 'rand' == $data['query_order_by']) {
            if (!isset($_GET['sp_rand_seed'])) {
                $this->rand_seed = rand();
            } else {
                $this->rand_seed = $_GET['sp_rand_seed'];
            }
        }

        if (isset($data['query_source']) && 'main_query' == $data['query_source']) {
            $query = $this->main_query();
        } else {
            $query = $this->custom_query($data);
        }

        return apply_filters('sitepilot_loop_query', $query, $data);
    }

    /**
     * Returns main query.
     *
     * @return WP_Query
     */
    public function main_query(): WP_Query
    {
        global $wp_query, $wp_the_query;

        if ($this->loop_counter > 1) {
            $query_args = $wp_query->query_vars;

            $query_args['paged'] = $this->get_paged();
            $query_args['sp_original_offset'] = 0;
            $query_args['sp_blocks_loop'] = true;

            $query = new WP_Query($query_args);
        } else {
            $query = clone $wp_query;
            $query->rewind_posts();
            $query->reset_postdata();
        }

        return $query;
    }

    /**
     * Returns custom query.
     *
     * @param array $data
     * @return WP_Query
     */
    public function custom_query(array $data): WP_Query
    {
        global $post;

        $order = empty($data['query_order']) ? 'DESC' : $data['query_order'];
        $order_by = empty($data['query_order_by']) ? 'date' : $data['query_order_by'];
        $post_type = empty($data['query_post_type']) ? 'post' : $data['query_post_type'];
        $posts_per_page = empty($data['query_posts_per_page']) ? 10 : $data['query_posts_per_page'];
        $this->has_custom_query = true;

        // Get the offset
        $offset = isset($data['query_offset']) ? intval($data['query_offset']) : 0;

        // Get the paged offset
        $paged = $this->get_paged();
        if ($paged < 2) {
            $paged_offset = $offset;
        } else {
            $paged_offset = $offset + (($paged - 1) * $posts_per_page);
        }

        // Build the query args
        $args = array(
            'paged' => $paged,
            'posts_per_page' => $posts_per_page,
            'post_type' => $post_type,
            'orderby' => $order_by,
            'order' => $order,
            'tax_query' => array(
                'relation' => 'AND',
            ),
            'ignore_sticky_posts' => true,
            'offset' => $paged_offset,
            'sp_original_offset' => $offset,
            'sp_blocks_loop' => true,
            'data' => $data
        );

        // Set query keywords if specified in the settings
        if (isset($data['query_keyword']) && !empty($data['query_keyword'])) {
            $args['s'] = $data['query_keyword'];
        }

        // Random order seed
        if ('rand' == $order_by && $this->rand_seed > 0) {
            $args['orderby'] = 'RAND(' . $this->rand_seed . ')';
        }

        // Order by author
        if ('author' == $order_by) {
            $args['orderby'] = array(
                'author' => $order,
                'date'   => $order,
            );
        }

        // Exclude self
        if ($post && isset($data['query_exclude_self']) && 'yes' == $data['query_exclude_self']) {
            $args['post__not_in'][] = $post->ID;
        }

        // Build the query
        $query = new WP_Query($args);

        // Return the query
        return $query;
    }

    /**
     * Returns the paged number for the query.
     *
     * @return int
     */
    public function get_paged(): int
    {
        global $wp_the_query, $paged;

        // Check first for custom pagination from post module
        $sp_paged = $wp_the_query->get('sp_paged');

        // In case the site is using default permalink structure and it has multiple paginations.
        $permalink_structure = get_option('permalink_structure');
        $base                = html_entity_decode(get_pagenum_link());

        if (is_numeric($sp_paged) && $this->is_paginated_loop()) {
            return $sp_paged;
        } elseif (empty($permalink_structure) && strrpos($base, 'paged-') && $this->loop_counter > 1) {

            $sp_paged   = 0;
            $url_parts = wp_parse_url($base, PHP_URL_QUERY);
            wp_parse_str($url_parts, $url_params);

            foreach ($url_params as $paged_key => $paged_val) {
                $get_paged_loop = explode('-', $paged_key);

                if (false === strpos($paged_key, 'paged-') || !isset($get_paged_loop[1])) {
                    continue;
                }

                if ($get_paged_loop[1] == $this->loop_counter) {
                    $sp_paged = $paged_val;
                    break;
                }
            }

            return $sp_paged;
        } elseif ($this->loop_counter > 1) {
            // If we have multiple paginations, make sure it won't affect the other loops.
            return 0;
        }

        // Check the 'paged' query var.
        $paged_qv = $wp_the_query->get('paged');

        if (is_numeric($paged_qv)) {
            return $paged_qv;
        }

        // Check the 'page' query var.
        $page_qv = $wp_the_query->get('page');

        if (is_numeric($page_qv)) {
            return $page_qv;
        }

        // Check the $paged global?
        if (is_numeric($paged)) {
            return $paged;
        }

        return 0;
    }

    /**
     * Check to see if the posts loop is currently paginated.
     *
     * @return bool
     */
    public function is_paginated_loop(): bool
    {
        $custom_paged = $this->get_custom_paged();

        if (!isset($custom_paged['current_loop'])) {
            return false;
        }

        if ($custom_paged['current_loop'] == $this->loop_counter) {
            return true;
        }

        return false;
    }

    /**
     * Returns the custom pagination request data.
     *
     * @return array|bool
     */
    public function get_custom_paged()
    {
        if (!empty($this->custom_paged_data)) {
            return $this->custom_paged_data;
        }

        if (did_action('wp')) {
            global $wp;
            $current_url = home_url($wp->request);
        } else {
            $current_url = $_SERVER['REQUEST_URI'];
        }

        // Do a quick test if the current request URL contains the custom `paged-` var
        if (false === strpos($current_url, 'paged-')) {
            return false;
        }

        // Check the current URL if it matches our custom pagination var.
        $paged_matches = preg_match('/([^.\/]*?)(?:\/)?([^.\/]*?)\/paged-([0-9]{1,})(?:\=|\/)([0-9]{1,})/', $current_url, $matches);

        if ($paged_matches) {
            $this->custom_paged_data = array(
                'parent_page'  => $matches[1],
                'current_page' => $matches[2],
                'current_loop' => $matches[3],
                'paged'        => $matches[4],
            );
        }

        return $this->custom_paged_data;
    }

    /**
     * Add rewrite rules for custom pagination that allows post modules
     * on the same page to be paged independently.
     *
     * @return void
     */
    public function init_rewrite_rules(): void
    {
        $fronts      = $this->get_rewrite_fronts();
        $paged_regex = $this->paged_regex_base;

        $sp_paged_rules = array(
            // Category archive
            $fronts['category'] . '/(.+?)/' . $paged_regex . '/?([0-9]{1,})/?$' => 'index.php?category_name=$matches[1]&sp_paged=$matches[2]',

            // Tag archive
            $fronts['tag'] . '/([^/]+)/' . $paged_regex . '/?([0-9]{1,})/?$' => 'index.php?tag=$matches[1]&sp_paged=$matches[2]',

            // Year archive
            $fronts['date'] . '([0-9]{4})/' . $paged_regex . '/?([0-9]{1,})/?$' => 'index.php?year=$matches[1]&sp_paged=$matches[2]',

            // Year/month archive
            $fronts['date'] . '([0-9]{4})/([0-9]{1,2})/' . $paged_regex . '/?([0-9]{1,})/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&sp_paged=$matches[3]',

            // Day archive
            $fronts['date'] . '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/' . $paged_regex . '/?([0-9]{1,})/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&sp_paged=$matches[4]',

            // Author archive
            $fronts['author'] . '([^/]+)/' . $paged_regex . '/?([0-9]{1,})/?$' => 'index.php?author_name=$matches[1]&sp_paged=$matches[2]',

            // Post single - Numeric permastruct (/archives/%post_id%)
            $fronts['default'] . '([0-9]+)/' . $paged_regex . '/?([0-9]{1,})/?$' => 'index.php?p=$matches[1]&sp_paged=$matches[2]',

            // Page
            '(.?.+?)/' . $paged_regex . '/?([0-9]{1,})/?$' => 'index.php?pagename=$matches[1]&sp_paged=$matches[2]',

            // Post single
            '(.+?)/' . $paged_regex . '/?([0-9]{1,})/?$'   => 'index.php?name=$matches[1]&sp_paged=$matches[2]',
        );

        // Frontpage static
        if (get_option('page_on_front')) {
            $sp_paged_rules[$paged_regex . '/([0-9]*)/?'] = 'index.php?page_id=' . get_option('page_on_front') . '&sp_paged=$matches[1]';
        }

        // Generic Rule for Homepage / Search
        $sp_paged_rules[$paged_regex . '/?([0-9]{1,})/?$'] = 'index.php?&sp_paged=$matches[1]';

        $sp_paged_rules = apply_filters('sp_blocks_loop_rewrite_rules', $sp_paged_rules);

        foreach ($sp_paged_rules as $regex => $redirect) {
            add_rewrite_rule($regex, $redirect, 'top');
        }

        add_rewrite_tag('%sp_paged%', '([^&]+)');
    }

    /**
     * Get the rewrite front for the generic rules.
     *
     * @return array
     */
    public function get_rewrite_fronts(): array
    {
        global $wp_rewrite;

        $front = substr($wp_rewrite->front, 1);

        $category_base = get_option('category_base');
        if (!$category_base) {
            $category_base = $front . 'category';
        }

        $tag_base = get_option('tag_base');
        if (!$tag_base) {
            $tag_base = $front . 'tag';
        }

        $date_base = $front;
        if (strpos($wp_rewrite->permalink_structure, '%post_id%') !== false) {
            $date_base = $front . 'date/';
        }

        $author_base = $front . $wp_rewrite->author_base . '/';

        return array(
            'category' => $category_base,
            'tag'      => $tag_base,
            'date'     => $date_base,
            'author'   => $author_base,
            'default'  => $front,
        );
    }

    /**
     * Builds and renders the pagination for a query.
     *
     * @param object $query An instance of WP_Query.
     * @return string
     */
    public function pagination($query): string
    {
        $total_pages = $query->max_num_pages;
        $permalink_structure = get_option('permalink_structure');
        $paged = $this->get_paged();
        $base = html_entity_decode(get_pagenum_link());
        $add_args = false;

        if ($total_pages > 1) {
            if (!$current_page = $paged) { // @codingStandardsIgnoreLine
                $current_page = 1;
            }

            $base   = $this->build_base_url($permalink_structure, $base);
            $format = $this->paged_format($permalink_structure, $base);

            // Add random order seed for scroll and load more.
            if ($this->rand_seed > 0) {
                $add_args['sp_rand_seed'] = $this->rand_seed;
            }

            $args = apply_filters('sp_blocks_loop_paginate_links_args', array(
                'base'     => $base . '%_%',
                'format'   => $format,
                'current'  => $current_page,
                'total'    => $total_pages,
                'type'     => 'plain',
                'add_args' => $add_args,
            ), $query);

            return paginate_links($args);
        }

        return '';
    }

    /**
     * Build base URL for our custom pagination.
     *
     * @param string $permalink_structure The current permalink structure.
     * @param string $base  The base URL to parse
     * @return string
     */
    public function build_base_url($permalink_structure, $base): string
    {
        // Check to see if we are using pretty permalinks
        if (!empty($permalink_structure)) {
            if (strrpos($base, 'paged-')) {
                $base = substr_replace($base, '', strrpos($base, 'paged-'), strlen($base));
            }

            // Remove query string from base URL since paginate_links() adds it automatically.
            // This should also fix the WPML pagination issue that was added since 1.10.2.
            if (count($_GET) > 0) {
                $base = strtok($base, '?');
            }

            // Add trailing slash when necessary.
            if ('/' == substr($permalink_structure, -1)) {
                $base = trailingslashit($base);
            } else {
                $base = untrailingslashit($base);
            }
        } else {
            $url_params = wp_parse_url($base, PHP_URL_QUERY);

            if (empty($url_params)) {
                $base = trailingslashit($base);
            }
        }

        return $base;
    }

    /**
     * Build the custom pagination format.
     *
     * @param string $permalink_structure
     * @param string $base
     * @return string
     */
    public function paged_format($permalink_structure, $base): string
    {
        if ($this->loop_counter > 1) {
            $page_prefix = 'paged-' . $this->loop_counter;
        } else {
            $page_prefix = empty($permalink_structure) ? 'paged' : 'page';
        }

        if (!empty($permalink_structure)) {
            $format  = substr($base, -1) != '/' ? '/' : '';
            $format .= $page_prefix . '/';
            $format .= '%#%';
            $format .= substr($permalink_structure, -1) == '/' ? '/' : '';
        } elseif (empty($permalink_structure) || is_search()) {
            $parse_url = wp_parse_url($base, PHP_URL_QUERY);
            $format    = empty($parse_url) ? '?' : '&';
            $format   .= $page_prefix . '=%#%';
        }

        return $format;
    }

    /**
     * Adding custom rewrite rules for the current post type pagination.
     *
     * @param string $post_type
     * @param object $args
     * @return void
     */
    public function post_type_rewrite_rules($post_type, $args): void
    {
        global $wp_rewrite;

        if ($args->_builtin or !$args->publicly_queryable) {
            return;
        }

        if (false === $args->rewrite) {
            return;
        }

        // Get our custom pagination if sets.
        $custom_paged = $this->get_custom_paged();

        if (!$custom_paged || empty($custom_paged) || !isset($custom_paged['current_page'])) {
            return;
        }

        $has_archive = is_string($args->has_archive) ? $args->has_archive : false;
        $is_single   = false;

        // Check if it's a CPT archive or CPT single.
        if ($custom_paged['current_page'] != $post_type && $has_archive != $custom_paged['current_page']) {

            // Is a child post of the current post type?
            $post_object = get_page_by_path($custom_paged['current_page'], OBJECT, $post_type);

            if ($post_object) {
                $is_single = true;
            } else {
                return;
            }
        }

        $slug = $args->rewrite['slug'];

        if (is_string($args->has_archive)) {
            $slug = $args->has_archive;
        }

        if ($args->rewrite['with_front']) {
            $slug = substr($wp_rewrite->front, 1) . $slug;
        }

        // Append $custom_paged[ 'current_page' ] to slug if it's single.
        if ($is_single) {
            $regex    = $slug . '/' . $custom_paged['current_page'] . '/' . $this->paged_regex_base . '/?([0-9]{1,})/?$';
            $redirect = 'index.php?post_type=' . $post_type . '&name=' . $custom_paged['current_page'] . '&sp_paged=$matches[1]';
        } else {
            $regex    = $slug . '/' . $this->paged_regex_base . '/?([0-9]{1,})/?$';
            $redirect = 'index.php?post_type=' . $post_type . '&sp_paged=$matches[1]';
        }

        add_rewrite_rule($regex, $redirect, 'top');

        // Set true for flushing.
        $this->rewrote_post_type = true;
    }

    /**
     * Flush rewrite rules ONLY when necessary.
     *
     * @return void
     */
    public function flush_rewrite_rules(): void
    {
        global $wp_rewrite;

        if ($this->rewrote_post_type) {
            // Need to flush (soft) so our custom rules will work.
            $wp_rewrite->flush_rules(false);
        }

        $this->rewrote_post_type = false;
    }

    /**
     * Disable canonical redirection on the frontpage when query var 'sp_paged' is found.
     *
     * Disable canonical on supported CPT single.
     *
     * @param  string $redirect_url  The redirect URL.
     * @param  string $requested_url The requested URL.
     * @return bool|string
     */
    public function override_canonical($redirect_url, $requested_url)
    {
        global $wp_the_query, $post;

        if (is_array($wp_the_query->query)) {
            foreach ($wp_the_query->query as $key => $value) {
                if (strpos($key, 'sp_paged') === 0 && is_page() && get_option('page_on_front')) {
                    return false;
                }
            }

            // Checks for paginated singular posts
            if (
                false === $wp_the_query->is_singular
                || -1 != $wp_the_query->current_post
                || false === $wp_the_query->is_paged
            ) {
                return $redirect_url;
            }

            // Check if any of the blocks uses a custom query
            $blocks = sitepilot()->blocks->get();
            foreach ($blocks as $block) {
                if ((has_block('acf/' . $block->slug) || has_block('sitepilot/' . $block->slug)) && $block->has_query()) {
                    return false;
                }
            }
        }

        return $redirect_url;
    }
}
