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

        $this->select_fields([
            'choices' => [
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

        $classes = [
            $this->get_class('mobile', $scale, $value),
            $this->get_class('tablet', $scale, $value),
            $this->get_class('desktop', $scale, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
