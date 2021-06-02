<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;

class Acf extends Module
{
    public array $blocks = [];

    public array $field_groups = [];

    /**
     * Initialize the acf module.
     * 
     * @return void
     */
    public function init(): void
    {
        add_action('acf/init', [$this, 'action_acf_init']);
    }

    /**
     * ACF init action.
     *
     * @return void
     */
    public function action_acf_init()
    {
        foreach ($this->blocks as $block) {
            acf_register_block_type($block);
        }

        foreach ($this->field_groups as $group) {
            acf_add_local_field_group($group);
        }
    }

    /**
     * Registers a block type. 
     * 
     * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
     * @link https://www.advancedcustomfields.com/resources/register-fields-via-php/
     * 
     * @param string $id
     * @param string $name
     * @param array $fields
     * @param array $config
     * @return void
     */
    public function block(string $id, string $name, array $fields = [], array $config = [])
    {
        $folder = str_replace('sp-block-', '', $id);
        $dir = get_stylesheet_directory() . "/blocks/$folder";

        $this->blocks[] = array_merge([
            'name' => $id,
            'title' => $name,
            'description' => __("A custom $name block.", 'sitepilot'),
            'render_template' => "$dir/includes/frontend.php",
        ], $config);

        if (count($fields)) {
            foreach ($fields as $field_key => $field) {
                if (is_array($field['sub_fields'] ?? null)) {
                    foreach ($field['sub_fields'] as $subfield_key => $subfield) {
                        if (empty($subfield['key'])) {
                            $subfield['key'] = $subfield['name'];
                        }

                        $field['sub_fields'][$subfield_key] = $subfield;
                    }
                }

                if (empty($field['key'])) {
                    $field['key'] = $id . '_' . $field['name'];
                }

                $fields[$field_key] = $field;
            }

            $this->field_groups[] = [
                'key' => $id,
                'title' => $name,
                'fields' => $fields,
                'location' => array(
                    array(
                        array(
                            'param' => 'block',
                            'operator' => '==',
                            'value' => "acf/$id",
                        ),
                    ),
                )
            ];
        }
    }

    /**
     * Returns ACF block classes.
     *
     * @param string $name
     * @param array $block
     * @return string
     */
    public function block_classes(array $block): string
    {
        if (!empty($block['name'])) $classes[] = str_replace('acf/', '', $block['name']);
        if (!empty($block['className'])) $classes[] = $block['className'];
        if (!empty($block['align'])) $classes[] = 'align' . $block['align'];
        if (!empty($block['align_text'])) $classes[] = 'has-text-align-' . $block['align_text'];
        if (!empty($block['backgroundColor'])) $classes[] = 'has-' . $block['backgroundColor'] . '-background-color';
        if (!empty($block['textColor'])) $classes[] = 'has-' . $block['textColor'] . '-text-color';

        return implode(" ", $classes);
    }

    /**
     * Get post / block field.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function field(string $name, $default = null, $post_id = false)
    {
        if (function_exists('get_field')) {
            $value = get_field($name, $post_id);
        }

        if ($default && is_null($value ?? null)) {
            return $default;
        }

        return $value;
    }

    /**
     * Render ACF block.
     *
     * @param string $id
     * @param array $data
     * @return void
     */
    public function render_block(string $id, array $data = [])
    {
        $html = '';

        $data = [
            'id' => 'block_' . uniqid(),
            'name' => "acf/$id",
            'data' => $data
        ];

        foreach (parse_blocks("<!-- wp:acf/$id " .  wp_json_encode($data) . " /-->") as $block) {
            $html .= render_block($block);
        }

        return $html;
    }
}
