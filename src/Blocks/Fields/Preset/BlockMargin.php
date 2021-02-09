<?php

namespace Sitepilot\Blocks\Fields\Preset;

use Sitepilot\Blocks\Fields\Style\Margin;

class BlockMargin extends Margin
{
    public function __construct()
    {
        parent::__construct(__('Margin', 'sitepilot'), 'margin');

        $this->default_value([
            'top' => ['mobile' => 4],
            'bottom' => ['mobile' => 4]
        ]);
    }
}
