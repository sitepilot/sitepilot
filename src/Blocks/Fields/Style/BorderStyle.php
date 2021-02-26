<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BorderStyle extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Border Style', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => [
                'default' => '',
                'solid' => __('Solid', 'sitepilot'),
                'dashed' => __('Dashed', 'sitepilot'),
                'dotted' => __('Dotted', 'sitepilot'),
                'double' => __('Double', 'sitepilot')
            ]
        ]);
    }

    protected function format_value($value)
    {
        $style = [
            'mobile-solid' => 'border-solid',
            'mobile-dashed' => 'border-dashed',
            'mobile-dotted' => 'border-dotted',
            'mobile-double' => 'border-double',

            'tablet-solid' => 'md:border-solid',
            'tablet-dashed' => 'md:border-dashed',
            'tablet-dotted' => 'md:border-dotted',
            'tablet-double' => 'md:border-double',

            'desktop-solid' => 'lg:border-solid',
            'desktop-dashed' => 'lg:border-dashed',
            'desktop-dotted' => 'lg:border-dotted',
            'desktop-double' => 'lg:border-double'
        ];

        $classes = [
            $this->get_class('mobile', $style, $value),
            $this->get_class('tablet', $style, $value),
            $this->get_class('desktop', $style, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
