<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class Scale extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Scale', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $choices = [
            'default' => '',
            '0' => '0%',
            50 => '50%',
            75 => '75%',
            90 => '90%',
            95 => '95%',
            100 => '100%',
            105 => '105%',
            110 => '110%',
            125 => '125%',
            150 => '150%'
        ];

        $this->fields([
            'scale' => [
                'label' => __('Default', 'sitepilot'),
                'choices' => $choices
            ],

            'scale_hover' => [
                'label' => __('Hover', 'sitepilot'),
                'choices' => $choices
            ]
        ]);
    }

    protected function format_value($value)
    {
        $scale = [
            'mobile-0' => 'scale-0',
            'mobile-50' => 'scale-50',
            'mobile-75' => 'scale-75',
            'mobile-90' => 'scale-90',
            'mobile-95' => 'scale-95',
            'mobile-100' => 'scale-100',
            'mobile-105' => 'scale-105',
            'mobile-110' => 'scale-110',
            'mobile-125' => 'scale-125',
            'mobile-150' => 'scale-150',
            'tablet-0' => 'md:scale-0',
            'tablet-50' => 'md:scale-50',
            'tablet-75' => 'md:scale-75',
            'tablet-90' => 'md:scale-90',
            'tablet-95' => 'md:scale-95',
            'tablet-100' => 'md:scale-100',
            'tablet-105' => 'md:scale-105',
            'tablet-110' => 'md:scale-110',
            'tablet-125' => 'md:scale-125',
            'tablet-150' => 'md:scale-150',
            'desktop-0' => 'lg:scale-0',
            'desktop-50' => 'lg:scale-50',
            'desktop-75' => 'lg:scale-75',
            'desktop-90' => 'lg:scale-90',
            'desktop-95' => 'lg:scale-95',
            'desktop-100' => 'lg:scale-100',
            'desktop-105' => 'lg:scale-105',
            'desktop-110' => 'lg:scale-110',
            'desktop-125' => 'lg:scale-125',
            'desktop-150' => 'lg:scale-150',
        ];

        $scale_hover = [
            'mobile-0' => 'hover:scale-0',
            'mobile-50' => 'hover:scale-50',
            'mobile-75' => 'hover:scale-75',
            'mobile-90' => 'hover:scale-90',
            'mobile-95' => 'hover:scale-95',
            'mobile-100' => 'hover:scale-100',
            'mobile-105' => 'hover:scale-105',
            'mobile-110' => 'hover:scale-110',
            'mobile-125' => 'hover:scale-125',
            'mobile-150' => 'hover:scale-150',
            'tablet-0' => 'md:hover:scale-0',
            'tablet-50' => 'md:hover:scale-50',
            'tablet-75' => 'md:hover:scale-75',
            'tablet-90' => 'md:hover:scale-90',
            'tablet-95' => 'md:hover:scale-95',
            'tablet-100' => 'md:hover:scale-100',
            'tablet-105' => 'md:hover:scale-105',
            'tablet-110' => 'md:hover:scale-110',
            'tablet-125' => 'md:hover:scale-125',
            'tablet-150' => 'md:hover:scale-150',
            'desktop-0' => 'lg:hover:scale-0',
            'desktop-50' => 'lg:hover:scale-50',
            'desktop-75' => 'lg:hover:scale-75',
            'desktop-90' => 'lg:hover:scale-90',
            'desktop-95' => 'lg:hover:scale-95',
            'desktop-100' => 'lg:hover:scale-100',
            'desktop-105' => 'lg:hover:scale-105',
            'desktop-110' => 'lg:hover:scale-110',
            'desktop-125' => 'lg:hover:scale-125',
            'desktop-150' => 'lg:hover:scale-150',
        ];

        $classes = [
            $this->get_class('scale', 'mobile', $scale, $value),
            $this->get_class('scale', 'tablet', $scale, $value),
            $this->get_class('scale', 'desktop', $scale, $value),
            $this->get_class('scale_hover', 'mobile', $scale_hover, $value),
            $this->get_class('scale_hover', 'tablet', $scale_hover, $value),
            $this->get_class('scale_hover', 'desktop', $scale_hover, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
