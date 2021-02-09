<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class Color extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Color', 'sitepilot'));
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
            'mobile-primary' => 'text-primary',
            'mobile-secondary' => 'text-secondary',
            'mobile-black' => 'text-black',
            'mobile-white' => 'text-white',

            'tablet-primary' => 'md:text-primary',
            'tablet-secondary' => 'md:text-secondary',
            'tablet-black' => 'md:text-black',
            'tablet-white' => 'md:text-white',

            'desktop-primary' => 'lg:text-primary',
            'desktop-secondary' => 'lg:text-secondary',
            'desktop-black' => 'lg:text-black',
            'desktop-white' => 'lg:text-white'
        ];

        $color_hover = [
            'mobile-primary' => 'hover:text-primary group-hover:text-primary',
            'mobile-secondary' => 'hover:text-secondary  group-hover:text-secondary',
            'mobile-black' => 'hover:text-black group-hover:text-black',
            'mobile-white' => 'hover:text-white group-hover:text-white',

            'tablet-primary' => 'md:hover:text-primary md:group-hover:text-primary',
            'tablet-secondary' => 'md:hover:text-secondary md:group-hover:text-secondary',
            'tablet-black' => 'md:hover:text-black md:group-hover:text-black',
            'tablet-white' => 'md:hover:text-white md:group-hover:text-white',

            'desktop-primary' => 'lg:hover:text-primary lg:group-hover:text-primary',
            'desktop-secondary' => 'lg:hover:text-secondary lg:group-hover:text-secondary',
            'desktop-black' => 'lg:hover:text-black lg:group-hover:text-black',
            'desktop-white' => 'lg:hover:text-white lg:group-hover:text-white'
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
