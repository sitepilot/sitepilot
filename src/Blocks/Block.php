<?php

namespace Sitepilot\Blocks;

use Exception;
use ReflectionClass;
use Sitepilot\Blocks\Fields\Field;

abstract class Block
{
    /**
     * The block's slug.
     *
     * @var string $slug
     */
    public $slug;

    /**
     * The block display name.
     *
     * @var string $name
     */
    public $name;

    /**
     * The block description.
     *
     * @var string $description
     */
    public $description;

    /**
     * The block icon.
     *
     * @var string $description
     */
    public $icon;

    /**
     * The block category.
     *
     * @var string $category
     */
    public $category;

    /**
     * The block directory.
     *
     * @var string $dir
     */
    public $dir;

    /**
     * Wether the block supports inner blocks.
     *
     * @var string $supports_inner_blocks
     */
    public $supports_inner_blocks;

    /**
     * Wether the block supports full and wide width.
     *
     * @var array
     */
    public $supports_align;

    /**
     * Wether the block supports colors.
     *
     * @var array
     */
    public $supports_color;

    /**
     * The default block alignment.
     *
     * @var string $align
     */
    public $align;

    /**
     * The post types where this block can be used.
     *
     * @var array
     */
    public $post_types;

    /**
     * The block fields.
     *
     * @var array
     */
    public $fields = [];

    /**
     * Additional block classes.
     *
     * @var array
     */
    public $classes;

    /**
     * The data passed to the view.
     *
     * @var array
     */
    private $view_data_cache;

    /**
     * Create a new block instance.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Cosntruct the block.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        if (!$this->enabled()) {
            return;
        }

        $reflectionClass = new ReflectionClass($this);

        $this->slug = $params['slug'];
        $this->icon = $params['icon'] ?? '';
        $this->name = $params['name'] ?? $params['slug'];
        $this->dir = dirname($reflectionClass->getFileName());
        $this->category = $params['category'] ?? 'sitepilot';
        $this->description = $params['description'] ?? '';
        $this->align = $params['align'] ?? null;
        $this->post_types = $params['post_types'] ?? [];
        $this->supports_inner_blocks = $params['supports']['inner_blocks'] ?? false;
        $this->supports_align = $params['supports']['align'] ?? false;
        $this->supports_color = $params['supports']['color'] ?? false;
        $this->classes = array_merge([$this->slug, 'sp-block'], $params['classes'] ?? []);
        $this->fields = $params['fields'] ?? [];

        sitepilot()->blocks->add($this);
    }

    /**
     * Returns the module fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * Add field to the block.
     *
     * @param Field $field
     * @return self
     */
    public function add_field(Field $field): self
    {
        if ($field instanceof Field) {
            $this->fields[$field->get_attribute()] = $field;
        }

        return $this;
    }

    /**
     * Add multiple fields to the block.
     *
     * @param array $fields
     * @return self
     */
    public function add_fields(array $fields): self
    {
        foreach ($fields as $field) {
            $this->add_field($field);
        }

        return $this;
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
        foreach ($this->fields() as $field) {
            $value = $field->get_value($data);

            // Field is a repeater field
            if ($field->is_repeater()) {
                $field_data[$field->get_attribute()] = array();

                if (is_array($value)) {
                    for ($i = 0; $i < count($value); $i++) {
                        foreach ($field->get_fields() as $subfield) {
                            $field_data[$field->get_attribute()][$i][$subfield->get_attribute()] = $subfield->get_value($value[$i]);
                        }
                    }
                }
            }

            // Field has subfields
            elseif ($field->get_fields()) {
                foreach ($field->get_fields() as $subfield) {
                    $field_data[$subfield->get_attribute()] = $subfield->get_value($data);
                }
            }

            // Normal field
            else {
                $field_data[$field->get_attribute()] = $field->get_value($data);
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
     * Enqueue block styles and scripts.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        /* Enqueue Styles */
        wp_enqueue_style('sp-blocks');

        /* Enqueue Scripts */
        wp_enqueue_script('sp-blocks');
    }

    /**
     * Wether the block should be active or not.
     *
     * @return bool
     */
    public function enabled(): bool
    {
        return true;
    }

    /**
     * Wether the block has a query field.
     * 
     * @return bool
     */
    public function has_query(): bool
    {
        $key = 'query_source';

        foreach ($this->fields() as $field) {
            if ($field->get_attribute() == $key) {
                return true;
            }
        }

        return false;
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
        if (isset($data['layout'])) {
            $classes[] = $this->slug . '__' . $data['layout'];
        }

        $classes = array_merge($this->classes, $classes);
        $class_attr = 'class="' . implode(" ", $classes) . '"';
        $data_attr = 'data-block="' . $this->slug . '" data-init="true"';

        $GLOBALS['post'] = sitepilot()->model->get_post();

        setup_postdata($GLOBALS['post']);

        $data = $this->get_view_data(array_merge([
            'block' => (array) $this,
            'block_start' => "<div {$class_attr} {$data_attr}>",
            'block_end' => "</div>",
            'post_id' => sitepilot()->model->get_post_id()
        ], $this->get_field_data(is_array($data) ? $data : [])));

        wp_reset_postdata();

        $blade = sitepilot()->blade([$this->dir . '/views']);

        try {
            $view = $blade->make($data['layout'] ?? 'frontend', $data)->render();
        } catch (Exception $e) {
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

            $view = $blade->make('blocks/error', $data)->render();
        }

        return (isset($data['block_start']) ? $data['block_start'] : '') . $view . (isset($data['block_end']) ? $data['block_end'] : '');
    }
}
