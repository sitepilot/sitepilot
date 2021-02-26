<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class Transition extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Transition', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => [
                'default' => '',
                'all' => __('All', 'sitepilot'),
                'colors' => __('Colors', 'sitepilot'),
                'opacity' => __('Opacity', 'sitepilot'),
                'shadow' => __('Shadow', 'sitepilot'),
                'transform' => __('Transform', 'sitepilot')
            ]
        ]);
    }

    protected function format_value($value)
    {
        $transition = [
            'mobile-all' => 'transition-all',
            'mobile-colors' => 'transition-colors',
            'mobile-opacity' => 'transition-opacity',
            'mobile-shadow' => 'transition-shadow',
            'mobile-transform' => 'transition-transform',

            'tablet-all' => 'md:transition-all',
            'tablet-colors' => 'md:transition-colors',
            'tablet-opacity' => 'md:transition-opacity',
            'tablet-shadow' => 'md:transition-shadow',
            'tablet-transform' => 'md:transition-transform',

            'desktop-all' => 'lg:transition-all',
            'desktop-colors' => 'lg:transition-colors',
            'desktop-opacity' => 'lg:transition-opacity',
            'desktop-shadow' => 'lg:transition-shadow',
            'desktop-transform' => 'lg:transition-transform',
        ];

        $classes = [
            $this->get_class('mobile', $transition, $value),
            $this->get_class('tablet', $transition, $value),
            $this->get_class('desktop', $transition, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
