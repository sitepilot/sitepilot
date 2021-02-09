<?php

namespace Sitepilot\Blocks\Fields;

abstract class Field
{
    /**
     * The field's name.
     *
     * @var string $name
     */
    public $name;

    /**
     * The field's attribute.
     *
     * @var string $attribute
     */
    public $attribute;

    /**
     * The field's default value.
     *
     * @var string $default
     */
    public $default;

    /**
     * The field's description.
     *
     * @var string
     */
    public $description;

    /**
     * Wether the field is required or not.
     *
     * @var bool
     */
    public $required;

    /**
     * The field's subfields.
     *
     * @var array
     */
    public $subfields = [];

    /**
     * Wether to register subfields.
     * 
     * @var bool
     */
    public $register_subfields = true;

    /**
     * The field's conditional rules.
     * 
     * @var array
     */
    public $conditional_rules = [];

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
     * Create a new field.
     *
     * @param string $name
     * @param string $attribute
     * @return void
     */
    public function __construct(string $name, string $attribute)
    {
        $this->name = $name;
        $this->attribute = $attribute;
    }

    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    abstract protected function acf_config(): array;

    /**
     * Get the field's configuration.
     * 
     * @param string $type
     * @return array
     */
    public function get_config(string $type, string $namespace = ''): ?array
    {
        if ($type == 'acf') {
            if (count($this->conditional_rules)) {
                $conditional_logic = array();
                foreach ($this->conditional_rules as $rule) {
                    $conditional_logic[0][] = [
                        'field' => $namespace . '_' . $rule['field'],
                        'operator' => $rule['operator'] . ($rule['value'] == 'empty' ? $rule['value'] : ''),
                        'value' => $rule['value']
                    ];
                }
            } else {
                $conditional_logic = null;
            }

            return array_merge([
                'key' => $namespace . '_' . $this->get_attribute(),
                'label' => $this->get_name(),
                'name' => $this->get_attribute(),
                'instructions' => $this->get_description(),
                'default_value' => $this->get_default(),
                'required' => $this->get_required(),
                'conditional_logic' => $conditional_logic
            ], $this->acf_config());
        }

        return null;
    }

    /**
     * Returns the field's attribute name.
     *
     * @return string
     */
    public function get_attribute(): ?string
    {
        return $this->attribute;
    }

    /**
     * Returns the field's name.
     *
     * @return string
     */
    public function get_name(): ?string
    {
        return $this->name;
    }

    /**
     * Returns the field's description.
     *
     * @return string
     */
    public function get_description(): ?string
    {
        return $this->description;
    }

    /**
     * Returns the field's default value.
     *
     * @return mixed
     */
    public function get_default()
    {
        return $this->default;
    }

    /**
     * Returns the field's required value.
     *
     * @return bool
     */
    public function get_required(): ?bool
    {
        return $this->required;
    }

    /**
     * Set the field's default value.
     *
     * @param string $value
     * @return self
     */
    public function default_value($value): self
    {
        $this->default = $value;

        return $this;
    }

    /**
     * Set the field's description.
     *
     * @param string $value
     * @return self
     */
    public function description($value): self
    {
        $this->description = $value;

        return $this;
    }

    /**
     * Add conditional logic to field.
     *
     * @param string $field
     * @param string $operator
     * @param string $value
     * @return self
     */
    public function conditional_rule($field, $operator, $value): self
    {
        $this->conditional_rules[] = [
            'field' => $field,
            'operator' => $operator,
            'value' => $value
        ];

        return $this;
    }

    /**
     * Get the field's value.
     *
     * @param string $type
     * @param array $data
     * @return mixed
     */
    public function get_value(string $type, array $data = [])
    {
        switch ($type) {
            case 'acf':
                $value = get_field($this->get_attribute());

                if (count($data) && isset($data[$this->get_attribute()])) {
                    $value = $data[$this->get_attribute()];
                }
                break;
            case 'shortcode':
                $value = $data[$this->get_attribute()] ?? null;

                if (!is_null(json_decode($value, true))) {
                    $value = json_decode($value, true);
                }
                break;
        }

        return $this->format_value(
            is_null($value) || is_string($value) && (empty($value) || $value == 'default') ? $this->get_default() : $value
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
     * Returns the field's subfields.
     *
     * @return array
     */
    public function get_subfields(): array
    {
        return $this->subfields;
    }
}
