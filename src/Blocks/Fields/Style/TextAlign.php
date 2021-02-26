<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class TextAlign extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Text Align', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => [
                'default' => '',
                'left' => __('Left', 'sitepilot'),
                'center' => __('Center', 'sitepilot'),
                'right' => __('Right', 'sitepilot'),
                'justify' => __('Justify', 'sitepilot')
            ]
        ]);
    }

    protected function format_value($value)
    {
        $align = [
            'mobile-left' => 'text-left',
            'mobile-right' => 'text-right',
            'mobile-center' => 'text-center',
            'mobile-justify' => 'text-justify',

            'tablet-left' => 'md:text-left',
            'tablet-right' => 'md:text-right',
            'tablet-center' => 'md:text-center',
            'tablet-justify' => 'md:text-justify',

            'desktop-left' => 'lg:text-left',
            'desktop-right' => 'lg:text-right',
            'desktop-center' => 'lg:text-center',
            'desktop-justify' => 'lg:text-justify'
        ];

        $classes = [
            $this->get_class('mobile', $align, $value),
            $this->get_class('tablet', $align, $value),
            $this->get_class('desktop', $align, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
