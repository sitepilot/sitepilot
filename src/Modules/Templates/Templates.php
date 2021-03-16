<?php

namespace Sitepilot\Modules\Templates;

use Sitepilot\Module;

class Templates extends Module
{
    /**
     * The array of templates.
     *
     * @var string
     */
    protected $templates = array();

    /**
     * The template admin capability.
     *
     * @var string
     */
    public $template_admin_cap = 'sp_template_admin';

    /**
     * The template viewer capability.
     *
     * @var string
     */
    public $template_viewer_cap = 'sp_template_viewer';

    /**
     * Construct the template module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->get_setting('enabled')) {
            return;
        }

        /* Templates */
        $this->templates = [
            'sitepilot-full-width.php' => sprintf(__('%s: Full Width', 'sitepilot'), sitepilot()->branding->get_name())
        ];

        /* Actions */
        add_action('init', [$this, 'action_register_template_post_type']);
        add_action('admin_menu', [$this, 'action_load_template_menu'], 12);
        add_action('admin_init', [$this, 'action_register_capabilities']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);

        /* Filters */
        add_filter('theme_templates', [$this, 'filter_page_templates']);
        add_filter('wp_insert_post_data', [$this, 'filter_insert_post_data']);
        add_filter('template_include', [$this, 'filter_template_include'], 999);
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_templates_settings', [
            'enabled' => apply_filters('sp_templates_enabled', false)
        ]);
    }

    /**
     * Register template viewer and admin capabilities.
     *
     * @return void
     */
    public function action_register_capabilities(): void
    {
        $role = get_role('administrator');

        $role->add_cap($this->template_admin_cap);
        $role->add_cap($this->template_viewer_cap);
    }

    /**
     * Register template custom post type.
     *
     * @return void
     */
    public function action_register_template_post_type(): void
    {
        $args = array(
            'labels' => [
                'name' => __('Templates', 'sitepilot'),
                'singular_name' => __('Template', 'sitepilot'),
                'add_new' => __('New Template', 'sitepilot'),
                'add_new_item' => __('New Template', 'sitepilot'),
                'edit_item' => __('Edit Template', 'sitepilot'),
                'new_item' => __('New Template', 'sitepilot'),
                'view_item' => __('View Template', 'sitepilot'),
                'search_items' => __('Search Templates', 'sitepilot'),
                'not_found' =>  __('No Templates Found', 'sitepilot'),
                'not_found_in_trash' => __('No templates in trash.', 'sitepilot'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'rewrite' => false,
            'show_in_rest' => true,
            'capability_type' => 'block',
            'capabilities' => array(
                'read' => $this->template_viewer_cap,
                'create_posts' => $this->template_admin_cap,
                'edit_posts' => $this->template_viewer_cap,
                'edit_published_posts' => $this->template_viewer_cap,
                'delete_published_posts' => $this->template_admin_cap,
                'edit_others_posts' => $this->template_viewer_cap,
                'delete_others_posts' => $this->template_admin_cap,
            ),
            'map_meta_cap' => true,
            'supports' => array(
                'title',
                'editor',
                'slug'
            )
        );

        register_post_type('sp-template', $args);
    }

    /**
     * Add template menu item to the Sitepilot menu.
     * 
     * @return void
     */
    public function action_load_template_menu(): void
    {
        add_submenu_page(
            'sitepilot-menu',
            __('Templates', 'sitepilot'),
            'Templates',
            $this->template_viewer_cap,
            'edit.php?post_type=sp-template'
        );
    }

    /**
     * Render a template.
     *
     * @param string $slug
     * @param array $data
     * @return string
     */
    public function render(string $slug, array $data = []): string
    {
        $data = array_merge([
            'content' => ''
        ], $data);

        $args = array(
            'name' => $slug,
            'post_type'   => 'sp-template',
            'post_status' => 'publish',
            'numberposts' => 1
        );

        $templates = get_posts($args);

        if ($templates) {
            $template = $templates[0];

            $data['content'] = apply_filters('the_content', $template->post_content);

            return sitepilot()->blade()->render('templates.full-width', $data);
        } else {
            return sprintf(__('Could not find template: %s.', 'sitepilot'), $slug);
        }
    }

    /**
     * Filter template include based on template slugs.
     *
     * @param string $template
     * @return string
     */
    public function filter_template_include($template): string
    {
        global $template_name;

        if (is_home()) {
            // Blog
            $template_name = 'home';
        } elseif (is_404()) {
            // Page not found
            $template_name = '404';
        } elseif (is_search()) {
            // Search template
            $template_name = 'search';
        } elseif (function_exists('is_product') && is_product()) {
            // Product
            $template_name = get_post_type();
        } elseif (is_singular()) {
            if (is_page_template()) {
                // Custom post template
                $template_name = basename($template, '.php');
            } else {
                // Single post template
                $template_name = get_post_type();
            }
        } elseif (is_archive()) {
            // Archive
            $template_name = 'archive-' . get_post_type();
        }

        if ($template_name) {
            $args = array(
                'name' => $template_name,
                'post_type'   => 'sp-template',
                'post_status' => 'publish',
                'numberposts' => 1
            );

            $templates = get_posts($args);

            if ($templates) {
                return SITEPILOT_DIR . '/includes/templates/render.php';
            } elseif ('sitepilot-full-width.php' === get_post_meta(get_the_ID(), '_wp_page_template', true)) {
                return SITEPILOT_DIR . '/includes/templates/full-width.php';
            }
        }

        return $template;
    }

    /**
     * Enqueue block editor assets.
     *
     * @return void
     */
    public function enqueue_assets()
    {
        /* Styles */
        wp_enqueue_style('sp-frontend');
    }

    /**
     * Enqueue block editor assets.
     *
     * @return void
     */
    public function enqueue_block_editor_assets()
    {
        /* Styles */
        wp_enqueue_style('sp-editor');

        /* Scripts */
        wp_enqueue_script('sp-editor');
    }

    /**
     * Add plugin templates to template dropdown.
     *
     * @param array $templates
     * @return void
     */
    public function filter_page_templates($templates): array
    {
        if (is_array($templates) && (is_admin() && in_array(get_post_type(), ['post', 'page', 'wp_block'])) || (defined('REST_REQUEST') && REST_REQUEST)) {
            $templates = array_merge($templates, $this->templates);
        }

        return $templates;
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     * 
     * @param array $atts
     * @return array
     */
    public function filter_insert_post_data($atts)
    {
        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete($cache_key, 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, $this->templates);

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;
    }
}
