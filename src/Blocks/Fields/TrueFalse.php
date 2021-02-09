<?php

namespace Sitepilot\Blocks\Fields;

class TrueFalse extends Field
{
    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
    {
        return [
            'type' => 'true_false',
            'ui' => 1,
            'ui_on_text' => __('Yes', 'sitepilot'),
            'ui_off_text' => __('No', 'sitepilot')
        ];
    }
}
