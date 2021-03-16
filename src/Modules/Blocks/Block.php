<?php

namespace Sitepilot\Modules\Blocks;

use Sitepilot\Modules\Blocks\Fields\Field;
use Sitepilot\Support\Model;

/**
 * @property string $key
 * @property string $version
 * @property array $type
 * @property string $name
 * @property string $description
 * @property string $dir
 * @property string $url
 * @property string $icon
 * @property string $category
 * @property string $align
 * @property array $post_types
 * @property array $classes
 * @property array $supports
 * @property Field[] $fields
 */
abstract class Block extends Model
{
    /**
     * The block's attributes.
     *
     * @var array[]
     */
    protected $attributes = [
        'key' => '',
        'version' => 1,
        'type' => [],
        'name' => '',
        'description' => '',
        'dir' => null,
        'url' => null,
        'icon' => null,
        'align' => null,
        'category' => null,
        'enabled' => true,
        'classes' => [],
        'post_types' => [],
        'supports' => [],
        'fields' => []
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'key', 'type', 'version', 'name', 'description', 'dir', 'url', 'icon',
        'category', 'enabled', 'align', 'post_types', 'fields',
        'classes', 'supports'
    ];

    /**
     * Create a new block instance.
     * 
     * @param string $key
     * @param array $config
     * @return void
     */
    public function __construct(string $key, array $config)
    {
        parent::__construct(array_merge(['key' => $key], $config));
    }

    /**
     * Returns the block's fields.
     *
     * @return array
     */
    protected function fields()
    {
        return [];
    }

    /**
     * Enqueue block styles and scripts.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        //
    }

    /**
     * Render ACF block.
     *
     * @return void
     */
    public function render_acf($block)
    {
        $classes = array();
        if (!empty($block['className'])) $classes[] = $block['className'];
        if (!empty($block['align'])) $classes[] = 'align' . $block['align'];
        if (!empty($block['align_text'])) $classes[] = 'has-text-align-' . $block['align_text'];
        if (!empty($block['backgroundColor'])) $classes[] = 'has-' . $block['backgroundColor'] . '-background-color';
        if (!empty($block['textColor'])) $classes[] = 'has-' . $block['textColor'] . '-text-color';

        echo $this->render_view(get_fields(), $classes);
    }

    /**
     * Render shortcode.
     *
     * @return void
     */
    public function render_shortcode($data, $content = null)
    {
        if (!is_array($data)) {
            $data = array();
        }

        if ($json_data = json_decode($content, true)) {
            $data = array_merge($data, $json_data);
        } else {
            $data['slot'] = $content;
        }

        $this->enqueue_assets();

        return $this->render_view($data);
    }

    /**
     * Render blade view.
     *
     * @param array $data
     * @return string
     */
    private function render_view($data, $classes = []): string
    {
        $classes = array_merge($this->classes, $classes);
        $class_attr = 'class="' . implode(" ", $classes) . '"';
        $data_attr = 'data-block="' . $this->key . '" data-init="true"';

        $data = $this->get_view_data(array_merge([
            'block' => (array) $this,
            'block_start' => "<div {$class_attr} {$data_attr}>",
            'block_end' => "</div>",
            'post_id' => $GLOBALS['post']->ID ?? null,
        ], $this->get_field_data(is_array($data) ? $data : [])));

        $blade = sitepilot()->blade([$this->dir . '/views']);

        try {
            $view = $blade->make($data['layout'] ?? 'frontend', $data)->render();
        } catch (\Exception $e) {
            $data['exception'] = $e->getMessage();
        }

        if (!empty($data['exception']) || empty(trim($view)) && (is_admin() || 'sp-template' == get_post_type())) {
            if (empty($data['exception'])) {
                $data['exception'] = "";
            }

            $data['block_title'] = $this->name;
            if (isset($data['layout'])) {
                foreach ($this->fields() as $field) {
                    if ($field->get_attribute() == 'layout') {
                        $layout = $field->options[$data['layout']] ?? null;
                    }
                }

                $data['block_title']  .= ' - ' . $layout;
            }

            $view = $blade->make('blocks.error', $data)->render();
        }

        return (isset($data['block_start']) ? $data['block_start'] : '') . $view . (isset($data['block_end']) ? $data['block_end'] : '');
    }

    /**
     * Get field data from array.
     *
     * @param array $data
     * @return array
     */
    private function get_field_data($data): array
    {
        $field_data = array();
        foreach ($this->fields as $field) {
            $value = $field->value($data);

            // Field is a repeater field
            if ($field->repeater) {
                $field_data[$field->key] = array();

                if (is_array($value)) {
                    for ($i = 0; $i < count($value); $i++) {
                        foreach ($field->fields as $subfield) {
                            $field_data[$field->key][$i][$subfield->key] = $subfield->value($value[$i]);
                        }
                    }
                }
            }

            // Field has subfields
            elseif ($field->fields) {
                foreach ($field->fields as $subfield) {
                    $field_data[$subfield->key] = $subfield->value($data);
                }
            }

            // Normal field
            else {
                $field_data[$field->key] = $field->value($data);
            }
        }

        return $field_data;
    }

    /**
     * Get block view data.
     *
     * @param array $data
     * @return array
     */
    private function get_view_data(array $data): array
    {
        $this->view_data_cache = $data;

        return array_merge($data, $this->view_data($data));
    }

    /**
     * Returns block view data.
     *
     * @param arrray $data
     * @return array
     */
    protected function view_data(array $data): array
    {
        return $data;
    }

    /**
     * Returns class elements.
     *
     * @param array $classes
     * @return void
     */
    public function get_classes(array $classes): string
    {
        $return = array();
        foreach ($classes as $class) {
            if (substr($class, 0, 6) == 'field:') {
                $field = str_replace('field:', '', $class);

                if (isset($this->view_data_cache[$field])) {
                    $return[] = $this->view_data_cache[$field];
                }
            } else {
                $return[] = $class;
            }
        }

        return implode(" ", array_filter($return));
    }

    /**
     * Set type attribute.
     * 
     * @param mixed $value
     */
    protected function set_type_attribute($value)
    {
        if (is_string($value)) {
            $this->attributes['type'] = [$value];
        } elseif (is_array($value)) {
            $this->attribute['type'] = $value;
        }
    }

    /**
     * Format dir attribute.
     * 
     * @param string $value
     * @return string
     */
    protected function get_dir_attribute($value)
    {
        if (empty($value)) {
            $value = get_stylesheet_directory() . '/blocks/' . str_replace('sp-block-', '', $this->key);
        }

        return untrailingslashit($value);
    }

    /**
     * Format url attribute.
     * 
     * @param string $value
     * @return string
     */
    protected function get_url_attribute($value)
    {
        if (empty($value)) {
            $value = get_stylesheet_directory_uri() . '/blocks/' . str_replace('sp-block-', '', $this->key);
        }

        return untrailingslashit($value);
    }

    /**
     * Format category attribute.
     *
     * @param string $value
     * @return string
     */
    protected function get_category_attribute($value)
    {
        if (empty($value)) {
            return 'sitepilot';
        }

        return $value;
    }

    /**
     * Format fields attribute.
     *
     * @param array $value
     * @return array
     */
    protected function get_fields_attribute($value)
    {
        if (!is_array($value)) {
            return $this->fields();
        }

        return array_merge($value, $this->fields());
    }

    /**
     * Format category attribute.
     *
     * @param string $value
     * @return array
     */
    protected function get_classes_attribute($value)
    {
        if (!is_array($value)) {
            $value = array();
        }

        return array_merge([$this->key, 'sp-block'], $value);
    }

    /**
     * Format supports attribute.
     * 
     * @param array $value
     * @return array
     */
    protected function get_supports_attribute($value)
    {
        if (!is_array($value)) {
            return array();
        }

        return $value;
    }
}
