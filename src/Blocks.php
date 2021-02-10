<?php

namespace Sitepilot;

use Sitepilot\Blocks\Block;

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
        if (!$this->plugin->settings->enabled('blocks')) {
            return;
        }

        /* Actions */
        add_action('plugins_loaded', [$this, 'action_register_blocks']);
        add_action('enqueue_block_editor_assets', [$this, 'action_enqueue_editor_scripts']);

        /* Filters */
        add_filter('block_categories', [$this, 'filter_block_categories']);
    }

    /**
     * Enqueue plugin scripts and stylesheets for the editor.
     * 
     * @return void
     */
    public function action_enqueue_editor_scripts(): void
    {
        /* Enqueue Styles */
        wp_enqueue_style('sitepilot');
        wp_enqueue_style('sp-blocks-editor');

        /* Enqueue Scripts */
        wp_enqueue_script('sitepilot');
        wp_enqueue_script('sp-blocks-editor');
        wp_enqueue_script('font-awesome-5');
    }

    /**
     * Register blocks.
     *
     * @return void
     */
    public function action_register_blocks(): void
    {
        /* Theme Blocks */
        $dir = get_stylesheet_directory() . '/blocks';
        if (file_exists($dir)) {
            $folders = scandir($dir);
        }

        foreach ($folders as $block) {
            $this->load_block_file($dir, $block);
        }

        /* Plugin Blocks */
        $dir = SITEPILOT_DIR . '/blocks';
        $folders = scandir($dir);

        foreach ($folders as $block) {
            $this->load_block_file($dir, $block);
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
}
