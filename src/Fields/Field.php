<?php

namespace Sitepilot\Fields;

use Sitepilot\Support\Model;
use Sitepilot\Support\Str;

/**
 * @property string $key
 * @property string $name
 * @property string $description
 * @property mixed $default
 * @property boolean $required
 * @property boolean $repeater
 * @property FieldConditional[] $conditionals
 * @property Field[] $fields
 */
abstract class Field extends Model
{
    /**
     * The block's attributes.
     *
     * @var array[]
     */
    protected $attributes = [
        'key' => '',
        'name' => '',
        'type' => '',
        'description' => '',
        'default' => null,
        'required' => false,
        'repeater' => false,
        'conditionals' => [],
        'fields' => []
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'key', 'name', 'description', 'default', 'required',
        'repeater', 'conditionals', 'fields'
    ];

    /**
     * Create a new field instance.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(string $key, array $config = [])
    {
        $this->key = $key;

        parent::__construct($config);
    }

    /**
     * Returns the field's fields.
     *
     * @return array
     */
    protected function fields()
    {
        return [];
    }

    /**
     * Returns the field's value.
     *
     * @param array $data
     * @return mixed
     */
    public function value($data)
    {
        $value = $data[$this->key] ?? null;

        if (is_string($value) && !is_null(json_decode($value, true))) {
            $value = json_decode($value, true);
        }

        return $this->format_value(
            is_string($value) && $value == 'default' || is_null($value) ? $this->default : $value
        );
    }

    /**
     * Format the field's value.
     *
     * @param mixed $value
     * @return mixed
     */
    protected function format_value($value)
    {
        return $value;
    }

    /**
     * Returns the field's configuration.
     * 
     * @param string $type
     * @return array
     */
    public function config(string $type, string $namespace = ''): ?array
    {
        if ($type == 'acf') {
            if (count($this->conditionals)) {
                $conditionals = array();
                foreach ($this->conditionals as $conditional) {
                    if ($conditional instanceof FieldConditional) {
                        $conditionals[0][] = [
                            'field' => $namespace . '_' . $conditional->key,
                            'operator' => $conditional->operator . ($conditional->value == 'empty' ? $conditional->value : ''),
                            'value' => $conditional->value
                        ];
                    }
                }
            } else {
                $conditionals = null;
            }

            return array_merge([
                'key' => $namespace . '_' . $this->key,
                'label' =>  $this->name,
                'name' => $this->key,
                'instructions' => $this->description,
                'default_value' => $this->default,
                'required' => $this->required,
                'conditional_logic' => $conditionals
            ], $this->acf_config($namespace));
        }

        return null;
    }

    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function acf_config(string $namespace): array
    {
        return [];
    }

    /**
     * Format type attribute.
     * 
     * @param string $value
     * @return string
     */
    protected function get_type_attribute($value)
    {
        if (empty($value)) {
            return Str::snake(class_basename($this));
        }

        return $value;
    }

    /**
     * Format name attribute.
     * 
     * @param string $value
     * @return string
     */
    protected function get_name_attribute($value)
    {
        if (empty($value)) {
            $value = Str::title($this->key);
        }

        return $value;
    }

    /**
     * Format conditionals attribute.
     *
     * @param array $value
     * @return array
     */
    protected function get_conditionals_attribute($value)
    {
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
}
