<?php

namespace Sitepilot\Fields;

class Group extends Field
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$args)
    {
        $this->type = 'group';

        parent::__construct(...$args);
    }

    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function acf_config(string $namespace): array
    {
        $append_fields = array();
        foreach ($this->fields as $field) {
            if ($field instanceof Field) {
                $append_fields[$field->key] = $field->config('acf', $namespace);
            }
        }

        return [
            'type' => 'accordion',
            'append_fields' => $append_fields
        ];
    }
}
