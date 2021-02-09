<?php

namespace Sitepilot\Blocks\Fields\Preset;

use Sitepilot\Blocks\Fields\Group;

class BorderGroup extends Group
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, '<i class="fas fa-border-all"></i> ' . __('Border', 'sitepilot'));
        } else {
            $arguments[0] = '<i class="fas fa-border-all"></i> ' . $arguments[0];
        }

        parent::__construct(...$arguments);
    }
}
