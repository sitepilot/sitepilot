<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class FontWeight extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Font Weight', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => [
                'default' => '',
                'thin' => __('Thin', 'sitepilot'),
                'extralight' => __('Extra Light', 'sitepilot'),
                'light' => __('Light', 'sitepilot'),
                'normal' => __('Normal', 'sitepilot'),
                'medium' => __('Medium', 'sitepilot'),
                'semibold' => __('Semi Bold', 'sitepilot'),
                'bold' => __('Bold', 'sitepilot'),
                'extrabold' => __('Extra Bold', 'sitepilot'),
                'black' => __('Black', 'sitepilot')
            ]
        ]);
    }

    protected function format_value($value)
    {
        $weight = [
            'mobile-thin' => 'font-thin',
            'mobile-extralight' => 'font-extralight',
            'mobile-light' => 'font-light',
            'mobile-normal' => 'font-normal',
            'mobile-medium' => 'font-medium',
            'mobile-semibold' => 'font-semibold',
            'mobile-bold' => 'font-bold',
            'mobile-extrabold' => 'font-extrabold',
            'tablet-thin' => 'md:font-thin',
            'tablet-extralight' => 'md:font-extralight',
            'tablet-light' => 'md:font-light',
            'tablet-normal' => 'md:font-normal',
            'tablet-medium' => 'md:font-medium',
            'tablet-semibold' => 'md:font-semibold',
            'tablet-bold' => 'md:font-bold',
            'tablet-extrabold' => 'md:font-extrabold',
            'desktop-thin' => 'lg:font-thin',
            'desktop-extralight' => 'lg:font-extralight',
            'desktop-light' => 'lg:font-light',
            'desktop-normal' => 'lg:font-normal',
            'desktop-medium' => 'lg:font-medium',
            'desktop-semibold' => 'lg:font-semibold',
            'desktop-bold' => 'lg:font-bold',
            'desktop-extrabold' => 'lg:font-extrabold',
        ];

        $classes = [
            $this->get_class('mobile', $weight, $value),
            $this->get_class('tablet', $weight, $value),
            $this->get_class('desktop', $weight, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
