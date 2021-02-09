<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class MinHeight extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Minimal Height', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'height' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    'screen' => __('Full Screen', 'sitepilot')
                ]
            ],
        ]);
    }

    protected function format_value($value)
    {
        $height = [
            'mobile-screen' => 'min-h-screen',
            'tablet-screen' => 'md:min-h-screen',
            'desktop-screen' => 'lg:min-h-screen'
        ];

        $classes = [
            $this->get_class('height', 'mobile', $height, $value),
            $this->get_class('height', 'tablet', $height, $value),
            $this->get_class('height', 'desktop', $height, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
