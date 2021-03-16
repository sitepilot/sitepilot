<?php

namespace Sitepilot\Fields;

class Textarea extends Field
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$args)
    {
        $this->type = 'textarea';

        parent::__construct(...$args);
    }

    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function acf_config(string $namespace): array
    {
        return [
            'type' => 'textarea'
        ];
    }
}
