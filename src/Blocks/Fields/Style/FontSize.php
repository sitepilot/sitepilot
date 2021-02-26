<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class FontSize extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Font Size', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => [
                'default' => '',
                'xs' => __('Extra Small', 'sitepilot'),
                'sm' => __('Small', 'sitepilot'),
                'md' => __('Medium', 'sitepilot'),
                'xl' => __('Extra Large', 'sitepilot'),
                '2xl' => __('Extra Large X2', 'sitepilot'),
                '3xl' => __('Extra Large X3', 'sitepilot'),
                '4xl' => __('Extra Large X4', 'sitepilot'),
                '5xl' => __('Extra Large X5', 'sitepilot'),
                '6xl' => __('Extra Large X6', 'sitepilot'),
                '7xl' => __('Extra Large X7', 'sitepilot'),
                '8xl' => __('Extra Large X8', 'sitepilot'),
                '9xl' => __('Extra Large X9', 'sitepilot'),
            ]
        ]);
    }

    protected function format_value($value)
    {
        $size = [
            'mobile-xs' => 'text-xs',
            'mobile-sm' => 'text-sm',
            'mobile-md' => 'text-md',
            'mobile-lg' => 'text-lg',
            'mobile-xl' => 'text-xl',
            'mobile-2xl' => 'text-2xl',
            'mobile-3xl' => 'text-3xl',
            'mobile-4xl' => 'text-4xl',
            'mobile-5xl' => 'text-5xl',
            'mobile-6xl' => 'text-6xl',
            'mobile-7xl' => 'text-7xl',
            'mobile-8xl' => 'text-8xl',
            'mobile-9xl' => 'text-9xl',

            'tablet-xs' => 'md:text-xs',
            'tablet-sm' => 'md:text-sm',
            'tablet-md' => 'md:text-md',
            'tablet-lg' => 'md:text-lg',
            'tablet-xl' => 'md:text-xl',
            'tablet-2xl' => 'md:text-2xl',
            'tablet-3xl' => 'md:text-3xl',
            'tablet-4xl' => 'md:text-4xl',
            'tablet-5xl' => 'md:text-5xl',
            'tablet-6xl' => 'md:text-6xl',
            'tablet-7xl' => 'md:text-7xl',
            'tablet-8xl' => 'md:text-8xl',
            'tablet-9xl' => 'md:text-9xl',

            'desktop-xs' => 'lg:text-xs',
            'desktop-sm' => 'lg:text-sm',
            'desktop-md' => 'lg:text-md',
            'desktop-lg' => 'lg:text-lg',
            'desktop-xl' => 'lg:text-xl',
            'desktop-2xl' => 'lg:text-2xl',
            'desktop-3xl' => 'lg:text-3xl',
            'desktop-4xl' => 'lg:text-4xl',
            'desktop-5xl' => 'lg:text-5xl',
            'desktop-6xl' => 'lg:text-6xl',
            'desktop-7xl' => 'lg:text-7xl',
            'desktop-8xl' => 'lg:text-8xl',
            'desktop-9xl' => 'lg:text-9xl',
        ];

        $classes = [
            $this->get_class('mobile', $size, $value),
            $this->get_class('tablet', $size, $value),
            $this->get_class('desktop', $size, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
