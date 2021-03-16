<?php

namespace Sitepilot\Modules\Theme;

use Sitepilot\Fields\Field;
use Sitepilot\Support\Model;
use Sitepilot\Modules\Blocks\Block;

/**
 * @property string $key
 * @property string $name
 * @property string $dir
 * @property string $url
 * @property string $version
 * @property string $options_provider
 * @property Field[] $fields
 */
abstract class Base extends Model
{
    /**
     * The block's attributes.
     *
     * @var array[]
     */
    protected $attributes = [
        'key' => '',
        'name' => '',
        'dir' => '',
        'url' => '',
        'version' => '',
        'options_provider' => 'acf',
        'fields' => []
    ];

    /**
     * The theme's attributes.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'file', 'dir', 'url', 'version', 'options_provider', 'fields'
    ];

    /**
     * Create a new theme instance.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new static(...$arguments);
        }

        return $instance;
    }

    /**
     * Create theme instance.
     * 
     * @param string $key
     * @param array $attributes
     * @return void
     */
    public function __construct(string $key, array $attributes = [])
    {
        /* Set key */
        $this->key = $key;

        /* Create theme instance */
        parent::__construct($attributes);

        /* Actions */
        add_action('init', [$this, "register_editor_colors"]);
        add_action('init', [$this, 'register_theme_options']);
        add_action('wp_enqueue_scripts', [$this, "enqueue_assets"]);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_assets']);
        add_action('after_setup_theme', [$this, 'register_blocks']);

        /* Initialize */
        $this->init();
    }

    /**
     * Initialize theme.
     *
     * @return void
     */
    protected function init()
    {
        //
    }

    /**
     * Returns the theme's option fields.
     *
     * @return Field[]
     */
    public function fields()
    {
        return [];
    }

    /**
     * Returns the theme's colors.
     * 
     * @return Color[]
     */
    public function colors()
    {
        return [];
    }

    /**
     * Returns the theme's CSS vars.
     *
     * @return CssVar[]
     */
    public function css_vars()
    {
        return [];
    }

    /**
     * Returns the theme's blocks.
     *
     * @return Block[]
     */
    public function blocks()
    {
        return [];
    }

    /**
     * Returns the theme's inline CSS.
     *
     * @return string
     */
    public function inline_css()
    {
        return preg_replace("/\r|\n|\s/", "", sitepilot()->blade()->make('theme.inline-css', ['theme' => $this])->render());
    }

    /**
     * Enqueue theme scripts and stylesheets.
     * 
     * @return void
     */
    public function enqueue_assets()
    {
        wp_add_inline_style($this->key, $this->inline_css());
    }

    /**
     * Format name attribute.
     *
     * @return string
     */
    protected function get_name_attribute($value)
    {
        if (empty($value)) {
            $value = wp_get_theme()->get('Name');
        }

        return $value;
    }

    /**
     * Format dir attribute.
     *
     * @return string
     */
    protected function get_dir_attribute($value)
    {
        if (empty($value)) {
            $value = get_stylesheet_directory();
        }

        return untrailingslashit($value);
    }

    /**
     * Format url attribute.
     *
     * @return string
     */
    protected function get_url_attribute($value)
    {
        if (empty($value)) {
            $value = get_stylesheet_directory_uri();
        }

        return untrailingslashit($value);
    }

    /**
     * Format version attribute.
     *
     * @return string
     */
    protected function get_version_attribute($value)
    {
        if (empty($value)) {
            $value = wp_get_theme()->get('Version');
        }

        return strpos($value, '-dev') !== false ? time() : $value;
    }

    /**
     * Format fields attribute.
     * 
     * @return Field[]
     */
    protected function get_fields_attribute($value)
    {
        if (!is_array($value)) {
            return $this->fields();
        }

        return array_merge($value, $this->fields());
    }

    /**
     * Register theme colors.
     *
     * @return void
     */
    public function register_editor_colors()
    {
        if (count($this->colors())) {
            $colors = [];
            $theme_colors = $this->colors();

            foreach ($theme_colors as $color) {
                $colors[] = [
                    'slug' => $color->key,
                    'name' => $color->name,
                    'color' => $color->value
                ];
            }

            add_theme_support('editor-color-palette', $colors);
        }
    }

    /**
     * Register theme options.
     *
     * @return void
     */
    public function register_theme_options()
    {
        if (
            count($this->fields)
            && 'acf' == $this->options_provider
            && function_exists('acf_add_options_sub_page')
            && function_exists('acf_add_local_field_group')
        ) {
            acf_add_options_sub_page(array(
                'page_title' => sprintf(__('%s Options', 'sitepilot'), $this->name),
                'menu_title' => $this->name,
                'menu_slug' => $this->key,
                'capability' => 'edit_posts',
                'parent_slug' => 'sitepilot-menu'
            ));

            $fields = [];
            foreach ($this->fields as $field) {
                $fields[] = $config = $field->config('acf', $this->key);

                if (isset($config['append_fields'])) {
                    $fields = $fields + $config['append_fields'];
                }
            }

            $config = [
                'key' => 'group_' . $this->key,
                'title' => __('Theme Options', 'sitepilot'),
                'fields' => $fields,
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => $this->key,
                        ),
                    ),
                )
            ];

            acf_add_local_field_group($config);
        }
    }

    /**
     * Register theme blocks.
     *
     * @return void
     */
    public function register_blocks()
    {
        foreach ($this->blocks() as $block) {
            sitepilot()->blocks->register($block);
        }
    }
}
