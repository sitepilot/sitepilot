<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BorderColor extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Border Color', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => array_merge(['default' => ''], sitepilot()->model->get_color_options())
        ]);
    }

    protected function format_value($value)
    {
        $color = [
            'mobile-primary' => 'border-primary',
            'mobile-secondary' => 'border-secondary',
            'mobile-third' => 'border-third',
            'mobile-fourth' => 'border-fourth',
            'mobile-black' => 'border-black',
            'mobile-white' => 'border-white',

            'tablet-primary' => 'md:border-primary',
            'tablet-secondary' => 'md:border-secondary',
            'tablet-third' => 'md:border-third',
            'tablet-fourth' => 'md:border-fourth',
            'tablet-black' => 'md:border-black',
            'tablet-white' => 'md:border-white',

            'desktop-primary' => 'lg:border-primary',
            'desktop-secondary' => 'lg:border-secondary',
            'desktop-third' => 'lg:border-third',
            'desktop-fourth' => 'lg:border-fourth',
            'desktop-black' => 'lg:border-black',
            'desktop-white' => 'lg:border-white'
        ];

        $classes = [
            $this->get_class('mobile', $color, $value),
            $this->get_class('tablet', $color, $value),
            $this->get_class('desktop', $color, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
