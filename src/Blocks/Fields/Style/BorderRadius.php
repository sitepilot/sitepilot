<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BorderRadius extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Border Radius', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $choices = [
            'default' => '',
            'none' => __('None', 'sitepilot'),
            'xs' => __('Extra Small', 'sitepilot'),
            'sm' => __('Small', 'sitepilot'),
            'md' => __('Medium', 'sitepilot'),
            'lg' => __('Large', 'sitepilot'),
            'xl' => __('Extra Large', 'sitepilot')
        ];

        $this->select_fields([
            'top_left' => [
                'label' => __('Top Left', 'sitepilot'),
                'choices' => $choices,
                'width' => '50%'
            ],
            'top_right' => [
                'label' => __('Top Right', 'sitepilot'),
                'choices' => $choices,
                'width' => '50%'
            ],
            'bottom_left' => [
                'label' => __('Bottom Left', 'sitepilot'),
                'choices' => $choices,
                'width' => '50%'
            ],
            'bottom_right' => [
                'label' => __('Bottom Right', 'sitepilot'),
                'choices' => $choices,
                'width' => '50%'
            ],
        ]);
    }

    protected function format_value($value)
    {
        $top_left = [
            'mobile-none' => 'rounded-tl-none',
            'mobile-xs' => 'rounded-tl-xs',
            'mobile-sm' => 'rounded-tl-sm',
            'mobile-md' => 'rounded-tl-md',
            'mobile-lg' => 'rounded-tl-lg',
            'mobile-xl' => 'rounded-tl-xl',
            'tablet-none' => 'md:rounded-tl-none',
            'tablet-xs' => 'md:rounded-tl-xs',
            'tablet-sm' => 'md:rounded-tl-sm',
            'tablet-md' => 'md:rounded-tl-md',
            'tablet-lg' => 'md:rounded-tl-lg',
            'tablet-xl' => 'md:rounded-tl-xl',
            'desktop-none' => 'lg:rounded-tl-none',
            'desktop-xs' => 'lg:rounded-tl-xs',
            'desktop-sm' => 'lg:rounded-tl-sm',
            'desktop-md' => 'lg:rounded-tl-md',
            'desktop-lg' => 'lg:rounded-tl-lg',
            'desktop-xl' => 'lg:rounded-tl-xl',
        ];

        $top_right = [
            'mobile-none' => 'rounded-tr-none',
            'mobile-xs' => 'rounded-tr-xs',
            'mobile-sm' => 'rounded-tr-sm',
            'mobile-md' => 'rounded-tr-md',
            'mobile-lg' => 'rounded-tr-lg',
            'mobile-xl' => 'rounded-tr-xl',
            'tablet-none' => 'md:rounded-tr-none',
            'tablet-xs' => 'md:rounded-tr-xs',
            'tablet-sm' => 'md:rounded-tr-sm',
            'tablet-md' => 'md:rounded-tr-md',
            'tablet-lg' => 'md:rounded-tr-lg',
            'tablet-xl' => 'md:rounded-tr-xl',
            'desktop-none' => 'lg:rounded-tr-none',
            'desktop-xs' => 'lg:rounded-tr-xs',
            'desktop-sm' => 'lg:rounded-tr-sm',
            'desktop-md' => 'lg:rounded-tr-md',
            'desktop-lg' => 'lg:rounded-tr-lg',
            'desktop-xl' => 'lg:rounded-tr-xl',
        ];

        $bottom_left = [
            'mobile-none' => 'rounded-bl-none',
            'mobile-xs' => 'rounded-bl-xs',
            'mobile-sm' => 'rounded-bl-sm',
            'mobile-md' => 'rounded-bl-md',
            'mobile-lg' => 'rounded-bl-lg',
            'mobile-xl' => 'rounded-bl-xl',
            'tablet-none' => 'md:rounded-bl-none',
            'tablet-xs' => 'md:rounded-bl-xs',
            'tablet-sm' => 'md:rounded-bl-sm',
            'tablet-md' => 'md:rounded-bl-md',
            'tablet-lg' => 'md:rounded-bl-lg',
            'tablet-xl' => 'md:rounded-bl-xl',
            'desktop-none' => 'lg:rounded-bl-none',
            'desktop-xs' => 'lg:rounded-bl-xs',
            'desktop-sm' => 'lg:rounded-bl-sm',
            'desktop-md' => 'lg:rounded-bl-md',
            'desktop-lg' => 'lg:rounded-bl-lg',
            'desktop-xl' => 'lg:rounded-bl-xl',
        ];

        $bottom_right = [
            'mobile-none' => 'rounded-br-none',
            'mobile-xs' => 'rounded-br-xs',
            'mobile-sm' => 'rounded-br-sm',
            'mobile-md' => 'rounded-br-md',
            'mobile-lg' => 'rounded-br-lg',
            'mobile-xl' => 'rounded-br-xl',
            'tablet-none' => 'md:rounded-br-none',
            'tablet-xs' => 'md:rounded-br-xs',
            'tablet-sm' => 'md:rounded-br-sm',
            'tablet-md' => 'md:rounded-br-md',
            'tablet-lg' => 'md:rounded-br-lg',
            'tablet-xl' => 'md:rounded-br-xl',
            'desktop-none' => 'lg:rounded-br-none',
            'desktop-xs' => 'lg:rounded-br-xs',
            'desktop-sm' => 'lg:rounded-br-sm',
            'desktop-md' => 'lg:rounded-br-md',
            'desktop-lg' => 'lg:rounded-br-lg',
            'desktop-xl' => 'lg:rounded-br-xl',
        ];

        $classes = [
            $this->get_class('mobile', $top_left, $value, 'top_left'),
            $this->get_class('tablet', $top_left, $value, 'top_left'),
            $this->get_class('desktop', $top_left, $value, 'top_left'),

            $this->get_class('mobile', $top_right, $value, 'top_right'),
            $this->get_class('tablet', $top_right, $value, 'top_right'),
            $this->get_class('desktop', $top_right, $value, 'top_right'),

            $this->get_class('mobile', $bottom_left, $value, 'bottom_left'),
            $this->get_class('tablet', $bottom_left, $value, 'bottom_left'),
            $this->get_class('desktop', $bottom_left, $value, 'bottom_left'),

            $this->get_class('mobile', $bottom_right, $value, 'bottom_right'),
            $this->get_class('tablet', $bottom_right, $value, 'bottom_right'),
            $this->get_class('desktop', $bottom_right, $value, 'bottom_right')
        ];

        return implode(" ", array_filter($classes));
    }
}
