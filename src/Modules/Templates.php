<?php

namespace Sitepilot\Modules;

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
            'sitepilot-full-width.php' => sprintf(__('%s: Full Width', 'sitepilot'), sitepilot()->branding()->get_name())
        ];

        /* Actions */
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_menu', [$this, 'add_wp_blocks_sumenu'], 14);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);

        /* Filters */
        add_filter('theme_templates', [$this, 'filter_page_templates']);
        add_filter('wp_insert_post_data', [$this, 'filter_insert_post_data']);
        add_filter('template_include', [$this, 'filter_template_include'], 999);

        /* Add slug to WP Blocks */
        add_post_type_support('wp_block', [
            'slug'
        ]);
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_templates_settings', [
            'enabled' => apply_filters('sp_templates_enabled', get_theme_support('sp-templates'))
        ]);
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
     * Filter template include based on template slugs.
     *
     * @param string $template
     * @return string
     */
    public function filter_template_include($template): string
    {
        if ('sitepilot-full-width.php' === get_post_meta(get_the_ID(), '_wp_page_template', true)) {
            return SITEPILOT_DIR . '/includes/templates/full-width.php';
        }

        return $template;
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

    /**
     * Add reusable blocks menu item to the Sitepilot menu.
     * 
     * @return void
     */
    public function add_wp_blocks_sumenu(): void
    {
        add_submenu_page(
            'sitepilot-menu',
            __('Blocks', 'sitepilot'),
            'Reusable blocks',
            'publish_posts',
            'edit.php?post_type=wp_block'
        );
    }
}
