<?php

namespace Sitepilot\Blocks\Fields\Preset;

use Sitepilot\Blocks\Fields\Group;

class StyleGroup extends Group
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, '<i class="fas fa-paint-brush"></i> ' . __('Style', 'sitepilot'));
        } else {
            $arguments[0] = '<i class="fas fa-paint-brush"></i> ' . $arguments[0];
        }

        parent::__construct(...$arguments);
    }
}
