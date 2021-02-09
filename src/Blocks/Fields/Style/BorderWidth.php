<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BorderWidth extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Border Width', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $choices = [
            'default' => '',
            '0' => '0',
            '1' => '1',
            '2' => '2',
            '4' => '4',
            '8' => '8'
        ];

        $this->fields([
            'top' => [
                'label' => __('Top', 'sitepilot'),
                'choices' => $choices
            ],

            'bottom' => [
                'label' => __('Bottom', 'sitepilot'),
                'choices' => $choices
            ],

            'left' => [
                'label' => __('Left', 'sitepilot'),
                'choices' => $choices
            ],

            'right' => [
                'label' => __('Right', 'sitepilot'),
                'choices' => $choices
            ],
        ]);
    }

    protected function format_value($value)
    {
        $top = [
            'mobile-0' => 'border-t-0',
            'mobile-1' => 'border-t',
            'mobile-2' => 'border-t-2',
            'mobile-4' => 'border-t-4',
            'mobile-8' => 'border-t-8',
            'tablet-0' => 'md:border-t-0',
            'tablet-1' => 'md:border-t-1',
            'tablet-2' => 'md:border-t-2',
            'tablet-4' => 'md:border-t-4',
            'tablet-8' => 'md:border-t-8',
            'desktop-0' => 'lg:border-t-0',
            'desktop-1' => 'lg:border-t-1',
            'desktop-2' => 'lg:border-t-2',
            'desktop-4' => 'lg:border-t-4',
            'desktop-8' => 'lg:border-t-8'
        ];

        $bottom = [
            'mobile-0' => 'border-b-0',
            'mobile-1' => 'border-b',
            'mobile-2' => 'border-b-2',
            'mobile-4' => 'border-b-4',
            'mobile-8' => 'border-b-8',
            'tablet-0' => 'md:border-b-0',
            'tablet-1' => 'md:border-b-1',
            'tablet-2' => 'md:border-b-2',
            'tablet-4' => 'md:border-b-4',
            'tablet-8' => 'md:border-b-8',
            'desktop-0' => 'lg:border-b-0',
            'desktop-1' => 'lg:border-b-1',
            'desktop-2' => 'lg:border-b-2',
            'desktop-4' => 'lg:border-b-4',
            'desktop-8' => 'lg:border-b-8'
        ];

        $left = [
            'mobile-0' => 'border-l-0',
            'mobile-1' => 'border-l',
            'mobile-2' => 'border-l-2',
            'mobile-4' => 'border-l-4',
            'mobile-8' => 'border-l-8',
            'tablet-0' => 'md:border-l-0',
            'tablet-1' => 'md:border-l-1',
            'tablet-2' => 'md:border-l-2',
            'tablet-4' => 'md:border-l-4',
            'tablet-8' => 'md:border-l-8',
            'desktop-0' => 'lg:border-l-0',
            'desktop-1' => 'lg:border-l-1',
            'desktop-2' => 'lg:border-l-2',
            'desktop-4' => 'lg:border-l-4',
            'desktop-8' => 'lg:border-l-8'
        ];

        $right = [
            'mobile-0' => 'border-r-0',
            'mobile-1' => 'border-r',
            'mobile-2' => 'border-r-2',
            'mobile-4' => 'border-r-4',
            'mobile-8' => 'border-r-8',
            'tablet-0' => 'md:border-r-0',
            'tablet-1' => 'md:border-r-1',
            'tablet-2' => 'md:border-r-2',
            'tablet-4' => 'md:border-r-4',
            'tablet-8' => 'md:border-r-8',
            'desktop-0' => 'lg:border-r-0',
            'desktop-1' => 'lg:border-r-1',
            'desktop-2' => 'lg:border-r-2',
            'desktop-4' => 'lg:border-r-4',
            'desktop-8' => 'lg:border-r-8'
        ];

        $classes = [
            $this->get_class('top', 'mobile', $top, $value),
            $this->get_class('top', 'tablet', $top, $value),
            $this->get_class('top', 'desktop', $top, $value),

            $this->get_class('bottom', 'mobile', $bottom, $value),
            $this->get_class('bottom', 'tablet', $bottom, $value),
            $this->get_class('bottom', 'desktop', $bottom, $value),

            $this->get_class('left', 'mobile', $left, $value),
            $this->get_class('left', 'tablet', $left, $value),
            $this->get_class('left', 'desktop', $left, $value),

            $this->get_class('right', 'mobile', $right, $value),
            $this->get_class('right', 'tablet', $right, $value),
            $this->get_class('right', 'desktop', $right, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
