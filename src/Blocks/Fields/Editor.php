<?php

namespace Sitepilot\Blocks\Fields;

class Editor extends Field
{
    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
    {
        return [
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => false
        ];
    }
}
