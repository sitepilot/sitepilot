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
        $dir = SITEPILOT_DIR . '/blocks';
        $folders = scandir($dir);

        foreach ($folders as $block) {
            $this->load_block($dir, $block);
        }
    }

    /**
     * Load block from dir.
     *
     * @param string $dir
     * @param string $block
     * @return void
     */
    private function load_block($dir, $block): void
    {
        $class = "\Sitepilot\Blocks\\";
        $file = "$dir/$block/$block.php";
        $words = explode('-', $block);

        foreach ($words as $word) {
            $class .= ucfirst($word);
        }

        if (file_exists($file)) {
            require_once $file;

            if (class_exists($class)) {
                $block = new $class;
                if ($block instanceof Block) {
                    if ($block->enabled()) {
                        $this->blocks[] = $block;
                    }
                }
            }
        }
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
        ));

        return $categories;
    }
}
