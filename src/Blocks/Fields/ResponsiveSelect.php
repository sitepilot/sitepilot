<?php

namespace Sitepilot\Blocks\Fields;

class ResponsiveSelect extends Field
{
    private array $select_fields = [];

    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function get_acf_config(string $namespace): array
    {
        $variations = [
            'mobile' => [
                'name' => 'Default (Mobile)',
                'icon' => 'dashicons-smartphone'
            ],
            'tablet' => [
                'name' => 'Tablet',
                'icon' => 'dashicons-tablet'
            ],
            'desktop' => [
                'name' => 'Desktop',
                'icon' => 'dashicons-laptop'
            ]
        ];

        return [
            'type' => 'sp_responsive_select',
            'variations' => $variations,
            'fields' => $this->select_fields,
            'default_value' => null,
            'default_values' => $this->default
        ];
    }

    /**
     * Set the select fields.
     *
     * @param array $fields
     * @return self
     */
    public function select_fields(array $select_fields): self
    {
        if (!empty($select_fields['choices'])) {
            $this->select_fields = [$select_fields];
        } else {
            $this->select_fields = $select_fields;
        }

        return $this;
    }

    /**
     * Set the field's default value.
     *
     * @param mixed $value
     * @return self
     */
    public function default_value($value): self
    {
        if (!empty($value['mobile'])) {
            $this->default = array($value);
        } else {
            $this->default = $value;
        }

        return $this;
    }

    /**
     * Returns class string.
     *
     * @param string $field
     * @param string $breakpoint
     * @param array $classes
     * @param array $value
     * @return string|null
     */
    protected function get_class($breakpoint, $classes, $value, $field = 0): ?string
    {
        $prefix = $breakpoint . '-';

        if (isset($value[$field][$breakpoint]) && isset($classes[$prefix . $value[$field][$breakpoint]])) {
            return $classes[$prefix . $value[$field][$breakpoint]];
        } elseif (isset($this->default[$field][$breakpoint]) && isset($classes[$prefix . $this->default[$field][$breakpoint]])) {
            return $classes[$prefix . $this->default[$field][$breakpoint]];
        }

        return null;
    }
}
