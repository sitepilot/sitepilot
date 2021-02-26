<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BackgroundColor extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Background Color', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => array_merge(['default' => ''], sitepilot()->model->get_color_options())
        ]);
    }

    protected function format_value($value)
    {
        $color = [
            'mobile-primary' => 'bg-primary',
            'mobile-secondary' => 'bg-secondary',
            'mobile-third' => 'bg-third',
            'mobile-fourth' => 'bg-fourth',
            'mobile-black' => 'bg-black',
            'mobile-white' => 'bg-white',

            'tablet-primary' => 'md:bg-primary',
            'tablet-secondary' => 'md:bg-secondary',
            'tablet-third' => 'md:bg-third',
            'tablet-fourth' => 'md:bg-fourth',
            'tablet-black' => 'md:bg-black',
            'tablet-white' => 'md:bg-white',

            'desktop-primary' => 'lg:bg-primary',
            'desktop-secondary' => 'lg:bg-secondary',
            'desktop-third' => 'lg:bg-third',
            'desktop-fourth' => 'lg:bg-fourth',
            'desktop-black' => 'lg:bg-black',
            'desktop-white' => 'lg:bg-white'
        ];

        $classes = [
            $this->get_class('mobile', $color, $value),
            $this->get_class('tablet', $color, $value),
            $this->get_class('desktop', $color, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
