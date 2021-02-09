<?php

namespace Sitepilot\Extension;

use Sitepilot\Module;
use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Field;
use Sitepilot\Extension\Acf\Fields\ResponsiveSelect;

class Acf extends Module
{
    /**
     * Construct the ACF module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->is_active()) {
            return;
        }

        /* Actions */
        add_action('acf/init', [$this, 'action_register_blocks']);
        add_action('acf/include_field_types', [$this, 'action_include_field_types']);
    }

    /**
     * Check if ACF plugin is active.
     *
     * @return bool
     */
    public function is_active(): bool
    {
        return class_exists("ACF_PRO");
    }

    /**
     * Register blocks.
     *
     * @return void
     */
    public function action_register_blocks(): void
    {
        foreach ($this->plugin->blocks->get() as $block) {
            $fields = array();
            if ($block instanceof Block) {
                foreach ($block->fields() as $field) {
                    if ($field instanceof Field) {
                        $fields[$field->get_attribute()] = $field->get_config('acf', $block->slug);

                        if ($field->register_subfields) {
                            foreach ($field->get_subfields() as $subfield) {
                                if ($subfield instanceof Field) {
                                    $fields[$subfield->get_attribute()] = $subfield->get_config('acf', $block->slug);
                                }
                            }
                        }
                    }
                }

                $align = array();
                if ($block->supports_full_width) $align[] = 'full';
                if ($block->supports_wide_width) $align[] = 'wide';

                if ($block->icon) {
                    $icon = $block->icon;
                } else {
                    $icon = [
                        'src' => $block->icon ? $block->icon : 'insert',
                        'foreground' => '#1062fe',
                        'background' => '#fff'
                    ];
                }

                acf_register_block_type([
                    'name' => $block->slug,
                    'title' => $block->name,
                    'render_callback' => array($block, 'render_block'),
                    'category' => $block->category,
                    'align' => $block->default_width,
                    'supports' => [
                        'jsx' => $block->supports_inner_blocks,
                        'align' => count($align) ? $align : false
                    ],
                    'icon' => $icon,
                    'enqueue_assets' => array($block, 'enqueue_assets'),
                    'post_types' => $block->post_types
                ]);

                acf_add_local_field_group([
                    'key' => 'group_' . $block->slug,
                    'title' => $block->name,
                    'fields' => $fields,
                    'location' => array(
                        array(
                            array(
                                'param' => 'block',
                                'operator' => '==',
                                'value' => 'acf/' . $block->slug,
                            ),
                        ),
                    )
                ]);

                add_filter('allowed_block_types', function ($blocks) use ($block) {
                    if (is_array($blocks)) {
                        $blocks[] = 'acf/' . $block->slug;
                    }
                    return $blocks;
                }, 99, 1);
            }
        }
    }

    /**
     * Register custom ACF field types.
     *
     * @param int $version
     * @return void
     */
    public function action_include_field_types($version): void
    {
        if ($version == 5) {
            new ResponsiveSelect([
                'version'    => '1.0.0',
                'url'        => plugin_dir_url(__FILE__),
                'path'        => plugin_dir_path(__FILE__)
            ]);
        }
    }
}
