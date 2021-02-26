<?php

namespace Sitepilot\Blocks\Fields\Preset;

use Sitepilot\Blocks\Fields\Select;

class ImageSize extends Select
{
    /**
     * Construct the field.
     *
     * @param array ...$arguments
     */
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Image Size', 'sitepilot'));
        }
        
        parent::__construct(...$arguments);

        $options = [
            'full' => 'Full'
        ];

        foreach (get_intermediate_image_sizes() as $size) {
            $options[$size] = ucfirst($size);
        }

        $this->default_value('full');

        $this->options($options);
    }
}
