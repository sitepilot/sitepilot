<?php

namespace Sitepilot;

use WP_Post;
use WP_Query;

class Template extends Module
{
    /**
     * The array of templates that this plugin tracks.
     * 
     * @var array
     */
    protected $templates;

    /**
     * The template locations meta key.
     *
     * @var string
     */
    protected $template_locations_key = 'sp-template-locations';

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
        if (!sitepilot()->settings->enabled('templates')) {
            return;
        }

        /* Actions */
        add_action('init', [$this, 'action_register_template_post_type']);
        add_action('admin_menu', [$this, 'action_load_template_menu'], 12);
        add_action('add_meta_boxes', [$this, 'action_add_meta_box']);
        add_action('save_post', [$this, 'action_save_meta']);
        add_action('admin_init', [$this, 'action_register_capabilities']);

        /* Filters */
        add_filter('body_class', [$this, 'filter_body_class']);
        add_filter('admin_body_class', [$this, 'filter_body_class']);
        add_filter('template_include', [$this, 'filter_template_include']);

        add_filter('theme_page_templates', [$this, 'filter_templates']);
        add_filter('theme_post_templates', [$this, 'filter_templates']);
        add_filter('theme_sp-template_templates', [$this, 'filter_templates']);

        /* Variables */
        $this->templates = [
            'sp-template-full-width' => sprintf(__('%s: Full Width', 'sitepilot'), 'Sitepilot')
        ];
    }

    /**
     * Filter the template list.
     *
     * @param array $templates
     * @return array
     */
    public function filter_templates(array $templates): array
    {
        $templates = array_merge($templates, $this->templates);

        return $templates;
    }

    /**
     * Returns wether the post template is full width.
     *
     * @return bool
     */
    public function is_full_width(): bool
    {
        global $post;

        if (!$post) {
            return false;
        }

        return 'sp-template-full-width' == get_post_meta($post->ID, '_wp_page_template', true);
    }

    /**
     * Add classes to backend and frontend.
     *
     * @param string|array $classes
     * @return void
     */
    public function filter_body_class($classes)
    {
        if ($this->is_full_width()) {
            if (is_string($classes)) {
                $classes .= ' sp-template-full-width';
            } elseif (is_array($classes)) {
                $classes[] = 'sp-template-full-width';
            }
        }

        return $classes;
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
        $labels = array(
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
        );

        $args = array(
            'labels' => $labels,
            'has_archive' => false,
            'public' => true,
            'hierarchical' => false,
            'supports' => array(
                'title',
                'editor'
            ),
            'taxonomies' => [],
            'rewrite' => array('slug' => 'sp-template'),
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-welcome-widgets-menus',
            'show_in_menu' => false,
            'capabilities' => array(
                'edit_post' => $this->template_viewer_cap,
                'read_post' => $this->template_viewer_cap,
                'delete_post' => $this->template_admin_cap,
                'create_posts' => $this->template_admin_cap
            ),
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
            'publish_posts',
            'edit.php?post_type=sp-template'
        );
    }

    /**
     * Returns the template ID for the current page.
     *
     * @return int|null
     */
    public function get_template_id(): ?int
    {
        $templates = new \WP_Query([
            'post_type' => 'sp-template',
            'post_status' => 'publish'
        ]);

        $post_types = get_post_types();
        foreach ($templates->posts as $template) {
            $locations = get_post_meta($template->ID, $this->template_locations_key, true);
            $locations = is_array($locations) ? $locations : [];

            foreach ($post_types as $post_type) {
                if (in_array("{$post_type}-single", $locations) && is_singular($post_type)) {
                    $template_id = $template->ID;
                }

                if (in_array("{$post_type}-archive", $locations) && is_post_type_archive($post_type)) {
                    $template_id = $template->ID;
                }
            }

            if (in_array('search', $locations) && is_search()) {
                $template_id = $template->ID;
            }

            if (in_array('post-archive', $locations) && is_home()) {
                $template_id = $template->ID;
            }

            if (in_array('not-found', $locations) && is_404()) {
                $template_id = $template->ID;
            }
        }

        return $template_id ?? null;
    }

    /**
     * Returns available template locations.
     *
     * @return array
     */
    public function get_template_locations(): array
    {
        $locations = [];
        foreach (get_post_types() as $post_type) {
            if ((substr($post_type, 0, 3) == 'sp-' || in_array($post_type, ['post', 'page'])) && !in_array($post_type, ['sp-log', 'sp-template'])) {
                $object = get_post_type_object($post_type);
                $locations[$post_type . '-archive'] = $object->labels->singular_name . ' ' . __('Archive', 'sitepilot');
                $locations[$post_type . '-single'] = $object->labels->singular_name . ' ' . __('Single', 'sitepilot');
            }
        }

        $locations = array_merge($locations, [
            'search' => __('Search Results', 'sitepilot'),
            'not-found' => __('Page Not Found', 'sitepilot')
        ]);

        return $locations;
    }

    /**
     * Filter template include.
     *
     * @param string $template
     * @return string
     */
    public function filter_template_include(string $template): string
    {
        global $post;
        global $blocks_template_query;

        if ($template_id = $this->get_template_id()) {
            if (is_singular()) {
                // Set the original post
                sitepilot()->model->set_post($post);
            }

            $post = get_post($template_id);

            $blocks_template_query = new WP_Query([
                'p' => $template_id,
                'post_type' => 'sp-template'
            ]);

            if (!is_singular()) {
                $template = get_page_template();
            }
        } elseif (!$post || is_search()) {
            return $template;
        }

        if (isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
            $file =  SITEPILOT_DIR . '/templates/' . str_replace('sp-', '', get_post_meta($post->ID, '_wp_page_template', true)) . '.php';

            if (file_exists($file)) {
                return $file;
            }
        }

        return $template;
    }

    /**
     * Register template meta box.
     *
     * @return void
     */
    public function action_add_meta_box(): void
    {
        add_meta_box(
            'sitepilot_template_meta',
            __('Template', 'sitepilot'),
            [$this, 'render_meta_box'],
            'sp-template',
            'side',
            'default'
        );
    }

    /**
     * Render template meta box.
     *
     * @param WP_Post $post
     * @return void
     */
    public function render_meta_box(WP_Post $post): void
    {
        $data['locations'] = $this->get_template_locations();
        $data['value'] = get_post_meta($post->ID, $this->template_locations_key, true);
        $data['value'] = is_array($data['value']) ? $data['value'] : [];
        $data['template_locations_key'] = $this->template_locations_key;

        $blade = sitepilot()->blade();

        echo $blade->make('editor/template-locations-meta', $data)->render();
    }

    /** 
     * Save template meta.
     * 
     * @param int $post_id
     * @return void
     */
    public function action_save_meta(int $post_id): void
    {
        if (array_key_exists($this->template_locations_key, $_POST) && is_array($_POST[$this->template_locations_key])) {
            $save = array();
            foreach ($_POST[$this->template_locations_key] as $location => $value) {
                if ($value == 'enabled') {
                    $save[] = $location;
                }
            }

            update_post_meta(
                $post_id,
                $this->template_locations_key,
                $save
            );
        }
    }
}
