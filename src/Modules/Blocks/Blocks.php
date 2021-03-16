<?php

namespace Sitepilot\Modules\Blocks;

use Sitepilot\Module;

/**
 * @property \Sitepilot\Modules\Blocks\Loop $loop
 */
class Blocks extends Module
{
    /**
     * The loaded blocks.
     *
     * @var Block[]
     */
    private $blocks = array();

    /**
     * Construct the blocks module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->get_setting('enabled')) {
            return;
        }

        /* Modules */
        $modules = [
            'loop' => \Sitepilot\Modules\Blocks\Loop::class,
        ];

        foreach ($modules as $key => $class) {
            $this->$key = new $class;
        }

        /* Actions */
        add_action('acf/init', [$this, 'register_acf_blocks']);
        add_action('init', [$this, 'register_block_shortcodes']);
        add_action('admin_menu', [$this, 'add_wp_blocks_sumenu'], 14);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);

        /* Filters */
        add_filter('block_categories', [$this, 'filter_block_categories']);
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_blocks_settings', [
            'enabled' => apply_filters('sp_blocks_enabled', false)
        ]);
    }

    /**
     * Register a block.
     *
     * @param Block $block
     * @return self
     */
    public function register(Block $block): self
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Register block shortcodes.
     *
     * @return void
     */
    public function register_block_shortcodes()
    {
        foreach ($this->blocks as $block) {
            if (in_array('shortcode', $block->type)) {
                add_shortcode($block->key, [$block, 'render_shortcode']);
            }
        }
    }

    /**
     * Register ACF blocks.
     *
     * @return void
     */
    public function register_acf_blocks()
    {
        if (function_exists('acf_register_block_type')) {
            foreach ($this->blocks as $block) {
                if (in_array('acf', $block->type)) {
                    acf_register_block_type([
                        'name' => $block->key,
                        'title' => $block->name,
                        'description' => $block->description,
                        'category' => $block->category,
                        'icon' => $block->icon ? $block->icon : 'insert',
                        'post_types' => $block->post_types,
                        'align' => $block->align,
                        'render_callback' => [$block, 'render_acf'],
                        'enqueue_assets' => [$block, 'enqueue_assets'],
                        'supports' => [
                            'align' => $block->supports['align'] ?? null,
                            'color' => $block->supports['color'] ?? null,
                            'jsx' => $block->supports['inner_blocks'] ?? false,
                        ]
                    ]);

                    $fields = array();
                    foreach ($block->fields as $field) {
                        $config = $field->config('acf', $block->key);
                        $fields[$field->key] = $config;

                        if (isset($config['append_fields'])) {
                            $fields = $fields + $config['append_fields'];
                        }
                    }

                    acf_add_local_field_group([
                        'key' => 'group_' . $block->key,
                        'title' => $block->name,
                        'fields' => $fields,
                        'location' => array(
                            array(
                                array(
                                    'param' => 'block',
                                    'operator' => '==',
                                    'value' => 'acf/' . $block->key,
                                ),
                            ),
                        )
                    ]);
                }
            }
        }
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
                'title' => sitepilot()->branding->get_name()
            )
        ));

        return $categories;
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
    }

    /**
     * Render a reusable block.
     *
     * @param string $slug
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

        return apply_filters('the_content', $blocks[0]->post_content ?? '');
    }

    /**
     * Render multiple reusable blocks.
     *
     * @param array $blocks
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

    /**
     * Get the reusable block ID by slug.
     *
     * @param string $slug
     * @return string
     */
    public function wp_block_id(string $slug): ?int
    {
        $args = array(
            'name' => $slug,
            'post_type'   => 'wp_block',
            'post_status' => 'publish',
            'numberposts' => 1
        );

        $blocks = get_posts($args);

        return $blocks[0]->ID ?? null;
    }
}
