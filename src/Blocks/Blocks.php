<?php

namespace Sitepilot\Blocks;

use Sitepilot\Module;
use Sitepilot\Blocks\Fields\Field;

class Blocks extends Module
{
    /**
     * The loaded blocks.
     *
     * @var array
     */
    private $blocks = array();

    /**
     * Construct the cleanup module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Check if module is enabled */
        if (!sitepilot()->settings->enabled('blocks')) {
            return;
        }

        /* Actions */
        add_action('after_setup_theme', [$this, 'action_load_blocks']);
        add_action('after_setup_theme', [$this, 'action_register_colors']);
        add_action('after_setup_theme', [$this, 'action_register_block_shortcodes']);
        add_action('admin_menu', [$this, 'action_load_blocks_menu'], 14);
        add_action('wp_enqueue_scripts', [$this, 'action_enqueue_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'action_enqueue_editor_assets']);

        /* Filters */
        add_filter('block_categories', [$this, 'filter_block_categories']);
    }

    /**
     * Enqueue plugin scripts and stylesheets for the editor.
     * 
     * @return void
     */
    public function action_enqueue_assets(): void
    {
        /* Enqueue Scripts */
        wp_enqueue_script('sp-blocks');
    }

    /**
     * Enqueue plugin scripts and stylesheets for the editor.
     * 
     * @return void
     */
    public function action_enqueue_editor_assets(): void
    {
        /* Enqueue Styles */
        wp_enqueue_style('sitepilot');
        wp_enqueue_style('sp-blocks');
        wp_enqueue_style('sp-blocks-editor');

        /* Enqueue Scripts */
        wp_enqueue_script('sp-blocks');
        wp_enqueue_script('sp-blocks-editor');
    }

    /**
     * Register blocks.
     *
     * @return void
     */
    public function action_load_blocks(): void
    {
        add_post_type_support('wp_block', 'slug');

        /* Theme Blocks */
        $dir = get_stylesheet_directory() . '/blocks';
        if (file_exists($dir)) {
            $folders = scandir($dir);

            foreach ($folders as $block) {
                $this->load_block_file($dir, $block);
            }
        }

        /* Plugin Blocks */
        $dir = SITEPILOT_DIR . '/blocks';
        if (file_exists($dir)) {
            $folders = scandir($dir);

            foreach ($folders as $block) {
                $this->load_block_file($dir, $block);
            }
        }
    }

    /**
     * Load block from dir.
     *
     * @param string $dir
     * @param string $block
     * @return void
     */
    private function load_block_file($dir, $block): void
    {
        $file = "$dir/$block/$block.php";

        if (file_exists($file)) {
            require_once $file;
        }
    }

    /**
     * Add block to blocks list.
     *
     * @param Block $block
     * @return self
     */
    public function add(Block $block): self
    {
        if (!array_key_exists($block->slug, $this->blocks)) {
            $this->blocks[$block->slug] = $block;
        }

        return $this;
    }

    /**
     * Get the loaded blocks.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->blocks;
    }

    /**
     * Register block shortcodes.
     *
     * @return void
     */
    public function action_register_block_shortcodes()
    {
        foreach ($this->get() as $block) {
            if ($block instanceof Block) {
                add_shortcode($block->slug, [$block, 'render_shortcode']);
            }
        }
    }

    /**
     * Filter Gutenberg block categories.
     * 
     * @return array
     */
    public function filter_block_categories($categories): array
    {
        array_splice($categories, 1, 0, array(
            array(
                'slug' => 'sitepilot',
                'title' => __('Sitepilot', 'sitepilot')
            ),
            array(
                'slug' => 'sitepilot-theme',
                'title' => __('Sitepilot Theme', 'sitepilot')
            ),
        ));

        return $categories;
    }

    /**
     * Register editor colors.
     *
     * @return void
     */
    public function action_register_colors()
    {
        $colors = array();
        if ($color = sitepilot()->model->get_primary_color()) {
            $colors[] = [
                'name' => sitepilot()->model->get_primary_color_name(),
                'slug' => 'primary',
                'color' => $color
            ];
        }

        if ($color = sitepilot()->model->get_secondary_color()) {
            $colors[] = [
                'name' => sitepilot()->model->get_secondary_color_name(),
                'slug' => 'secondary',
                'color' => $color
            ];
        }

        if ($color = sitepilot()->model->get_third_color()) {
            $colors[] = [
                'name' => sitepilot()->model->get_third_color_name(),
                'slug' => 'third',
                'color' => $color
            ];
        }

        if ($color = sitepilot()->model->get_fourth_color()) {
            $colors[] = [
                'name' => sitepilot()->model->get_fourth_color_name(),
                'slug' => 'third',
                'color' => $color
            ];
        }

        $colors[] = [
            'white' => __('White', 'sitepilot'),
            'slug' => 'white',
            'color' => '#ffffff'
        ];

        $colors[] = [
            'white' => __('Black', 'sitepilot'),
            'slug' => 'black',
            'color' => '#000000'
        ];

        add_theme_support('editor-color-palette', $colors);
    }

    /**
     * Add reusable blocks menu item to the Sitepilot menu.
     * 
     * @return void
     */
    public function action_load_blocks_menu(): void
    {
        add_submenu_page(
            'sitepilot-menu',
            __('Blocks', 'sitepilot'),
            'Reusable blocks',
            'publish_posts',
            'edit.php?post_type=wp_block'
        );
    }

    /**
     * Render a reusable block.
     *
     * @param string $slug
     * @param array $data
     * @return string
     */
    public function wp_block(string $slug): string
    {
        $args = array(
            'name' => $slug,
            'post_type'   => 'wp_block',
            'post_status' => 'publish',
            'numberposts' => 1
        );

        $blocks = get_posts($args);

        if ($blocks) {
            $block = $blocks[0];
            return apply_filters('the_content', $block->post_content);
        }
    }

    /**
     * Render multiple reusable blocks.
     *
     * @param string $slug
     * @param array $data
     * @return string
     */
    public function wp_blocks(array $blocks): string
    {
        $content = '';
        foreach ($blocks as $block) {
            $content .= $this->wp_block($block);
        }

        return $content;
    }
}
