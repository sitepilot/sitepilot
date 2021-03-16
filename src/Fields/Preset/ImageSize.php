<?php

namespace Sitepilot\Fields\Preset;

use Sitepilot\Fields\Select;

class ImageSize extends Select
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$arguments)
    {
        parent::__construct(...$arguments);

        $options = [
            'full' => 'Full'
        ];

        foreach (get_intermediate_image_sizes() as $size) {
            $options[$size] = ucfirst($size);
        }

        $this->default = 'full';
        $this->options = $options;
    }
}
