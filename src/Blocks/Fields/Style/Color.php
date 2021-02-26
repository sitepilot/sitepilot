<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class Color extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Color', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $choices = array_merge(['default' => ''], sitepilot()->model->get_color_options());

        $this->select_fields([
            'choices' => $choices
        ]);
    }

    protected function format_value($value)
    {
        $color = [
            'mobile-primary' => 'text-primary',
            'mobile-secondary' => 'text-secondary',
            'mobile-third' => 'text-third',
            'mobile-fourth' => 'text-fourth',
            'mobile-black' => 'text-black',
            'mobile-white' => 'text-white',

            'tablet-primary' => 'md:text-primary',
            'tablet-secondary' => 'md:text-secondary',
            'tablet-third' => 'md:text-third',
            'tablet-fourth' => 'md:text-fourth',
            'tablet-black' => 'md:text-black',
            'tablet-white' => 'md:text-white',

            'desktop-primary' => 'lg:text-primary',
            'desktop-secondary' => 'lg:text-secondary',
            'desktop-third' => 'lg:text-third',
            'desktop-fourth' => 'lg:text-fourth',
            'desktop-black' => 'lg:text-black',
            'desktop-white' => 'lg:text-white'
        ];

        $classes = [
            $this->get_class('mobile', $color, $value),
            $this->get_class('tablet', $color, $value),
            $this->get_class('desktop', $color, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
