<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BorderColor extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Border Color', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'color' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    'primary' => __('Primary', 'sitepilot'),
                    'secondary' => __('Secondary', 'sitepilot'),
                    'black' => __('Black', 'sitepilot'),
                    'white' => __('White', 'sitepilot')
                ]
            ]
        ]);
    }

    protected function format_value($value)
    {
        $color = [
            'mobile-primary' => 'border-primary',
            'mobile-secondary' => 'border-secondary',
            'mobile-black' => 'border-black',
            'mobile-white' => 'border-white',

            'tablet-primary' => 'md:border-primary',
            'tablet-secondary' => 'md:border-secondary',
            'tablet-black' => 'md:border-black',
            'tablet-white' => 'md:border-white',

            'desktop-primary' => 'lg:border-primary',
            'desktop-secondary' => 'lg:border-secondary',
            'desktop-black' => 'lg:border-black',
            'desktop-white' => 'lg:border-white'
        ];

        $classes = [
            $this->get_class('color', 'mobile', $color, $value),
            $this->get_class('color', 'tablet', $color, $value),
            $this->get_class('color', 'desktop', $color, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
