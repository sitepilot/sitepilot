<?php

namespace Sitepilot;

class Template extends Module
{
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
        /* Check if module is enabled */
        add_action('after_setup_theme', function () {
            if (!apply_filters('sp_templates_enabled', false)) {
                return;
            }

            /* Actions */
            add_action('init', [$this, 'action_register_template_post_type']);
            add_action('admin_menu', [$this, 'action_load_template_menu'], 12);
            add_action('admin_init', [$this, 'action_register_capabilities']);

            /* Filters */
            add_filter('template_include', [$this, 'filter_template_include'], 999);
        });
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

            return sitepilot()->blade()->render('template', $data);
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
                return SITEPILOT_DIR . '/includes/template.php';
            }
        }

        return $template;
    }
}
