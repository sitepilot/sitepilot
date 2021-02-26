<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class MaxWidth extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Max Width', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => [
                'default' => '',
                'container' => __('Container', 'sitepilot'),
                'xs' => __('Extra Small', 'sitepilot'),
                'sm' => __('Small', 'sitepilot'),
                'md' => __('Medium', 'sitepilot'),
                'lg' => __('Large', 'sitepilot'),
                'xl' => __('Extra Large', 'sitepilot'),
                '2xl' => __('Extra Large x2', 'sitepilot'),
                '3xl' => __('Extra Large x3', 'sitepilot'),
                '4xl' => __('Extra Large x4', 'sitepilot'),
                '5xl' => __('Extra Large x5', 'sitepilot'),
                '6xl' => __('Extra Large x6', 'sitepilot'),
                '7xl' => __('Extra Large x7', 'sitepilot'),
                'full' => __('Full Width', 'sitepilot')
            ]
        ]);
    }

    protected function format_value($value)
    {
        $max_width = [
            'mobile-container' => 'mx-auto container',
            'mobile-xs' => 'mx-auto max-w-xs',
            'mobile-sm' => 'mx-auto max-w-sm',
            'mobile-md' => 'mx-auto max-w-md',
            'mobile-lg' => 'mx-auto max-w-lg',
            'mobile-xl' => 'mx-auto max-w-xl',
            'mobile-2xl' => 'mx-auto max-w-2xl',
            'mobile-3xl' => 'mx-auto max-w-3xl',
            'mobile-4xl' => 'mx-auto max-w-4xl',
            'mobile-5xl' => 'mx-auto max-w-5xl',
            'mobile-6xl' => 'mx-auto max-w-6xl',
            'mobile-7xl' => 'mx-auto max-w-7xl',
            'mobile-full' => 'max-w-none'
        ];

        $classes = [
            $this->get_class('mobile', $max_width, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
