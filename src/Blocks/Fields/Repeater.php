<?php

namespace Sitepilot\Blocks\Fields;

class Repeater extends Field
{
    /**
     * Wether the field is a repeater field.
     *
     * @var boolean
     */
    public $repeater = true;

    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function get_acf_config(string $namespace): array
    {
        $subfields = array();
        foreach ($this->fields as $field) {
            if ($field instanceof Field) {
                $subfields[$field->get_attribute()] = $field->get_config('acf');
            }
        }

        return [
            'type' => 'repeater',
            'layout' => 'block',
            'button_label' => __('New item', 'sitepilot'),
            'sub_fields' => $subfields
        ];
    }
}
