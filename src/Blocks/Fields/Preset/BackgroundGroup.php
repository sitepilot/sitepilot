<?php

namespace Sitepilot\Blocks\Fields\Preset;

use Sitepilot\Blocks\Fields\Group;

class BackgroundGroup extends Group
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, '<i class="fas fa-fill-drip"></i> ' . __('Background', 'sitepilot'));
        } else {
            $arguments[0] = '<i class="fas fa-fill-drip"></i> ' . $arguments[0];
        }

        parent::__construct(...$arguments);
    }
}
