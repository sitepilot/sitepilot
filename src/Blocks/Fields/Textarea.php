<?php

namespace Sitepilot\Blocks\Fields;

class Textarea extends Field
{
    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
    {
        return [
            'type' => 'textarea'
        ];
    }
}
