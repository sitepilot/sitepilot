<?php

namespace Sitepilot\Extension;

use Sitepilot\Module;
use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Field;

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
        if (function_exists('acf_register_block_type')) {
            foreach (sitepilot()->blocks->get() as $block) {
                if ($block instanceof Block) {
                    // Register block type
                    if ($block->icon) {
                        $icon = $block->icon;
                    } else {
                        $icon = 'insert';
                    }

                    acf_register_block_type([
                        'name' => $block->slug,
                        'title' => $block->name,
                        'description' => $block->description,
                        'category' => $block->category,
                        'icon' => $block->icon,
                        'post_types' => $block->post_types,
                        'align' => $block->align,
                        'render_callback' => [$block, 'render_acf'],
                        'enqueue_assets' => [$block, 'enqueue_assets'],
                        'supports' => [
                            'jsx' => $block->supports_inner_blocks,
                            'align' => $block->supports_align,
                            'color' => $block->supports_color
                        ],
                        'icon' => $icon
                    ]);

                    // Register block fields
                    $fields = array();
                    foreach ($block->fields() as $field) {
                        if ($field instanceof Field) {
                            $config = $field->get_config('acf', $block->slug);
                            $fields[$field->get_attribute()] = $config;

                            if (isset($config['append_fields'])) {
                                $fields = $fields + $config['append_fields'];
                            }
                        }
                    }

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
                }
            }
        }
    }
}
