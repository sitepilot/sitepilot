<?php

namespace Sitepilot\Blocks\Fields;

class Editor extends Field
{
    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function get_acf_config(string $namespace): array
    {
        return [
            'type' => 'wysiwyg',
            'media_upload' => 0,
            'toolbar' => 'basic'
        ];
    }
}
