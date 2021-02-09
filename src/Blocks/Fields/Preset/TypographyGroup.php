<?php

namespace Sitepilot\Blocks\Fields\Preset;

use Sitepilot\Blocks\Fields\Group;

class TypographyGroup extends Group
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, '<i class="fas fa-text-height"></i> ' . __('Typography', 'sitepilot'));
        } else {
            $arguments[0] = '<i class="fas fa-text-height"></i> ' . $arguments[0];
        }

        parent::__construct(...$arguments);
    }
}
