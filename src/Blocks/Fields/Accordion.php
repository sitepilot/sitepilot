<?php

namespace Sitepilot\Blocks\Fields;

class Accordion extends Field
{
    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function get_acf_config(string $namespace): array
    {
        $append_fields = array();
        foreach ($this->fields as $field) {
            if ($field instanceof Field) {
                $append_fields[$field->get_attribute()] = $field->get_config('acf', $namespace);
            }
        }

        return [
            'type' => 'accordion',
            'append_fields' => $append_fields
        ];
    }
}
