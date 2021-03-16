<?php

namespace Sitepilot\Fields\Preset;

use Sitepilot\Fields\Select;

class LinkTarget extends Select
{
    /**
     * Construct the field.
     *
     * @param array ...$arguments
     */
    public function __construct(...$arguments)
    {
        parent::__construct(...$arguments);

        $options = [
            '_self' => __('This Window', 'sitepilot'),
            '_blank' => __('New Window', 'sitepilot')
        ];

        $this->type = 'select';
        $this->default = '_self';
        $this->options = $options;
    }
}
