<?php

namespace Sitepilot\Blocks\Fields;

class ResponsiveSelect extends Field
{
    private array $fields = [];

    private string $type = 'responsive';

    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
    {
        if ($this->type == 'responsive') {
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
        } elseif ($this->type == 'single') {
            $variations = [
                'mobile' => [
                    'name' => 'Default (Mobile)',
                    'icon' => ''
                ],
            ];
        }

        return [
            'type' => 'sp_responsive_select',
            'variations' => $variations,
            'fields' => $this->fields,
            'default_value' => null,
            'defaults' => $this->default
        ];
    }

    /**
     * Set the select fields.
     *
     * @param array $fields
     * @return self
     */
    public function fields(array $fields): self
    {
        $this->fields = $fields;

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
    protected function get_class($field, $breakpoint, $classes, $value): ?string
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
