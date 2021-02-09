<?php

namespace Sitepilot\Blocks;

use Exception;
use ReflectionClass;
use Sitepilot\Plugin;
use Sitepilot\Blocks\Fields\Field;

abstract class Block
{
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
     * The block slug.
     *
     * @var string $slug
     */
    public $slug;

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
     * Wether the block supports full width.
     *
     * @var string $supports_full_width
     */
    public $supports_full_width;

    /**
     * Wether the block supports wide width.
     *
     * @var string $supports_wide_width
     */
    public $supports_wide_width;

    /**
     * The default block width.
     *
     * @var string $default_width
     */
    public $default_width;

    /**
     * The post types where this block can be used.
     *
     * @var array
     */
    public $post_types;

    /**
     * The data passed to the view.
     *
     * @var array
     */
    private $view_data;

    /**
     * The plugin instance.
     *
     * @var Plugin
     */
    protected $plugin;

    /**
     * Cosntruct the block.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $reflectionClass = new ReflectionClass($this);

        $this->plugin = Plugin::make();
        $this->slug = $params['slug'];
        $this->icon = $params['icon'] ?? '';
        $this->name = $params['name'] ?? $params['slug'];
        $this->dir = dirname($reflectionClass->getFileName());
        $this->category = $params['category'] ?? 'sitepilot';
        $this->description = $params['description'] ?? '';
        $this->supports_inner_blocks = $params['supports']['inner_blocks'] ?? false;
        $this->supports_full_width = $params['supports']['full_width'] ?? false;
        $this->supports_wide_width = $params['supports']['wide_width'] ?? false;
        $this->default_width = $params['default']['width'] ?? null;
        $this->post_types = $params['post_types'] ?? null;

        add_shortcode($this->slug, [$this, 'render_shortcode']);
    }

    /**
     * Returns the module fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [];
    }

    /**
     * Get field data from array or object.
     *
     * @param array $data
     * @return array
     */
    private function get_field_data($type, $data): array
    {
        $field_data = array();
        foreach ($this->fields() as $field) {
            if ($field instanceof Field) {
                $value = $field->get_value($type, $data);

                if (!count($field->get_subfields())) {
                    $field_data[$field->get_attribute()] = $value;
                } elseif (is_array($value)) {
                    $index = 0;
                    foreach ($value as $subvalue) {
                        foreach ($field->get_subfields() as $subfield) {
                            if ($subfield instanceof Field) {
                                $value = $subfield->get_value($type, $subvalue);
                                $field_data[$field->get_attribute()][$index][$subfield->get_attribute()] = $value;
                            }
                        }
                        $index++;
                    }
                } else {
                    foreach ($field->get_subfields() as $subfield) {
                        if ($subfield instanceof Field) {
                            $value = $subfield->get_value($type, $data);
                            $field_data[$subfield->get_attribute()] = $value;
                        }
                    }
                }
            }
        }

        return $field_data;
    }

    /**
     * Render block view.
     *
     * @return void
     */
    public function render_block($block, $content = '', $is_preview = false, $post_id = 0): void
    {
        $classes = ['sp-block', $this->slug];

        if (!empty($block['className'])) $classes[] = $block['className'];
        if (!empty($block['align'])) $classes[] = 'align' . $block['align'];
        if (!empty($block['align_text'])) $classes[] = 'has-text-align-' . $block['align_text'];

        $class = 'class="' . implode(" ", $classes) . '"';

        $GLOBALS['post'] = $this->plugin->model->get_post($post_id);

        setup_postdata($GLOBALS['post']);

        $data = $this->get_view_data(array_merge([
            'block' => (array) $this,
            'block_start' => "<div {$class} id='sp-block-" . uniqid() . "'>",
            'block_end' => "</div>",
            'post_id' => $this->plugin->model->get_post_id($post_id)
        ], $this->get_field_data('acf', [])));

        wp_reset_postdata();

        echo $this->render_view($data);
    }

    /**
     * Render shortcode view.
     *
     * @return string
     */
    public function render_shortcode($args = [], $slot = ''): string
    {
        $class = 'class="' . implode(" ", ['sp-block', 'sp-shortcode', $this->slug]) . '"';

        $GLOBALS['post'] = $this->plugin->model->get_post();

        setup_postdata($GLOBALS['post']);

        $data = $this->get_view_data(array_merge([
            'block' => (array) $this,
            'block_start' => "<div {$class}>",
            'block_end' => "</div>",
            'post_id' => $this->plugin->model->get_post_id()
        ], $this->get_field_data('shortcode', $args), ['slot' => !empty($slot) ? $slot : $field_data['slot'] ?? '']));

        wp_reset_postdata();

        return $this->render_view($data);
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
     * Get block view data.
     *
     * @param array $data
     * @return array
     */
    private function get_view_data(array $data): array
    {
        $this->view_data = $data;

        return array_merge($data, $this->view_data($data));
    }

    /**
     * Render blade view.
     *
     * @param array $data
     * @return string
     */
    private function render_view(array $data): string
    {
        $blade = $this->plugin->blade([$this->dir . '/views']);

        try {
            $view = $blade->make($data['layout'] ?? 'frontend', $data)->render();
        } catch (Exception $e) {
            $data['exception'] = $e->getMessage();
        }

        if (empty(trim($view)) && is_admin()) {
            if (empty($data['exception'])) {
                $data['exception'] = "";
            }

            $view = $blade->make('blocks/error', $data)->render();
        }

        return (isset($data['block_start']) ? $data['block_start'] : '') . $view . (isset($data['block_end']) ? $data['block_end'] : '');
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

                if (isset($this->view_data[$field])) {
                    $return[] = $this->view_data[$field];
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
}
