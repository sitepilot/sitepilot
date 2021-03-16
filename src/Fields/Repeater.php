<?php

namespace Sitepilot\Fields;

class Repeater extends Field
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$args)
    {
        $this->type = 'repeater';
        $this->repeater = true;

        parent::__construct(...$args);
    }

    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function acf_config(string $namespace): array
    {
        $subfields = array();
        foreach ($this->fields as $field) {
            if ($field instanceof Field) {
                $subfields[$field->key] = $field->config('acf');
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
