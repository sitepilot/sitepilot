<?php

namespace Sitepilot\Blocks\Fields\Preset;

use Sitepilot\Blocks\Fields\Group;

class TransitionGroup extends Group
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, '<i class="fas fa-mouse"></i> ' . __('Transition', 'sitepilot'));
        } else {
            $arguments[0] = '<i class="fas fa-mouse"></i> ' . $arguments[0];
        }

        parent::__construct(...$arguments);
    }
}
