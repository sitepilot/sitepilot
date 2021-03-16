<?php

namespace Sitepilot\Fields;

class Editor extends Field
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$args)
    {
        $this->type = 'editor';

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
            'type' => 'wysiwyg',
            'media_upload' => 0,
            'toolbar' => 'basic'
        ];
    }
}
