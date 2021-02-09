<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class Opacity extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Opacity', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'opacity' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    '0' => '0%',
                    5 => '5%',
                    10 => '10%',
                    20 => '20%',
                    25 => '25%',
                    30 => '30%',
                    40 => '40%',
                    50 => '50%',
                    60 => '60%',
                    70 => '70%',
                    75 => '75%',
                    80 => '80%',
                    90 => '90%',
                    95 => '95%',
                    100 => '100%'
                ]
            ],
        ]);
    }

    protected function format_value($value)
    {
        $opacity = [
            'mobile-0' => 'opacity-0',
            'mobile-5' => 'opacity-5',
            'mobile-10' => 'opacity-10',
            'mobile-20' => 'opacity-20',
            'mobile-25' => 'opacity-25',
            'mobile-30' => 'opacity-30',
            'mobile-40' => 'opacity-40',
            'mobile-50' => 'opacity-50',
            'mobile-60' => 'opacity-60',
            'mobile-70' => 'opacity-70',
            'mobile-80' => 'opacity-80',
            'mobile-90' => 'opacity-90',
            'mobile-100' => 'opacity-100',

            'tablet-0' => 'md:opacity-0',
            'tablet-5' => 'md:opacity-5',
            'tablet-10' => 'md:opacity-10',
            'tablet-20' => 'md:opacity-20',
            'tablet-25' => 'md:opacity-25',
            'tablet-30' => 'md:opacity-30',
            'tablet-40' => 'md:opacity-40',
            'tablet-50' => 'md:opacity-50',
            'tablet-60' => 'md:opacity-60',
            'tablet-70' => 'md:opacity-70',
            'tablet-80' => 'md:opacity-80',
            'tablet-90' => 'md:opacity-90',
            'tablet-100' => 'md:opacity-100',

            'desktop-0' => 'lg:opacity-0',
            'desktop-5' => 'lg:opacity-5',
            'desktop-10' => 'lg:opacity-10',
            'desktop-20' => 'lg:opacity-20',
            'desktop-25' => 'lg:opacity-25',
            'desktop-30' => 'lg:opacity-30',
            'desktop-40' => 'lg:opacity-40',
            'desktop-50' => 'lg:opacity-50',
            'desktop-60' => 'lg:opacity-60',
            'desktop-70' => 'lg:opacity-70',
            'desktop-80' => 'lg:opacity-80',
            'desktop-90' => 'lg:opacity-90',
            'desktop-100' => 'lg:opacity-100'
        ];

        $classes = [
            $this->get_class('opacity', 'mobile', $opacity, $value),
            $this->get_class('opacity', 'tablet', $opacity, $value),
            $this->get_class('opacity', 'desktop', $opacity, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
