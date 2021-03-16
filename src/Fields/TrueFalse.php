<?php

namespace Sitepilot\Fields;

class TrueFalse extends Field
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$args)
    {
        $this->type = 'true_false';

        parent::__construct(...$args);
    }

    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function acf_config(string $namespace): array
    {
        return [
            'type' => 'true_false',
            'ui' => 1,
            'ui_on_text' => __('Yes', 'sitepilot'),
            'ui_off_text' => __('No', 'sitepilot')
        ];
    }
}
