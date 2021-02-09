<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BackgroundColor extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Background Color', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $choices = [
            'default' => '',
            'primary' => __('Primary', 'sitepilot'),
            'secondary' => __('Secondary', 'sitepilot'),
            'black' => __('Black', 'sitepilot'),
            'white' => __('White', 'sitepilot')
        ];

        $this->fields([
            'color' => [
                'label' => __('Default', 'sitepilot'),
                'choices' => $choices
            ],
            'color_hover' => [
                'label' => __('Hover', 'sitepilot'),
                'choices' => $choices
            ]
        ]);
    }

    protected function format_value($value)
    {
        $color = [
            'mobile-primary' => 'bg-primary',
            'mobile-secondary' => 'bg-secondary',
            'mobile-black' => 'bg-black',
            'mobile-white' => 'bg-white',
        ];

        $color_hover = [
            'mobile-primary' => 'hover:bg-primary group-hover:bg-primary',
            'mobile-secondary' => 'hover:bg-secondary group-hover:bg-secondary',
            'mobile-black' => 'hover:bg-black group-hover:bg-black',
            'mobile-white' => 'hover:bg-white group-hover:bg-white',
        ];

        $classes = [
            $this->get_class('color', 'mobile', $color, $value),
            $this->get_class('color', 'tablet', $color, $value),
            $this->get_class('color', 'desktop', $color, $value),

            $this->get_class('color_hover', 'mobile', $color_hover, $value),
            $this->get_class('color_hover', 'tablet', $color_hover, $value),
            $this->get_class('color_hover', 'desktop', $color_hover, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
