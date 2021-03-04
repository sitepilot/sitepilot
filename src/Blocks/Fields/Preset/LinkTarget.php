<?php

namespace Sitepilot\Blocks\Fields\Preset;

use Sitepilot\Blocks\Fields\Select;

class LinkTarget extends Select
{
    /**
     * Construct the field.
     *
     * @param array ...$arguments
     */
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Link Target', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $options = [
            '_self' => __('This Window', 'sitepilot'),
            '_blank' => __('New Window', 'sitepilot')
        ];

        $this->default_value('_self');

        $this->options($options);
    }
}
