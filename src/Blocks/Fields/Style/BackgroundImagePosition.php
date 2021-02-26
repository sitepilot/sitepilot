<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BackgroundImagePosition extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Position', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => [
                'default' => '',
                'bottom' => __('Bottom', 'sitepilot'),
                'center' => __('Center', 'sitepilot'),
                'left' => __('Left', 'sitepilot'),
                'left-bottom' => __('Left Bottom', 'sitepilot'),
                'left-top' => __('Left Top', 'sitepilot'),
                'right' => __('Right', 'sitepilot'),
                'right-bottom' => __('Right Bottom', 'sitepilot'),
                'right-top' => __('Right Top', 'sitepilot'),
                'top' => __('Top', 'sitepilot')
            ]
        ]);
    }

    protected function format_value($value)
    {
        $position = [
            'mobile-bottom' => 'bg-bottom',
            'mobile-center' => 'bg-center',
            'mobile-left' => 'bg-left',
            'mobile-left-bottom' => 'bg-left-bottom',
            'mobile-left-top' => 'bg-left-top',
            'mobile-right' => 'bg-right',
            'mobile-right-bottom' => 'bg-right-bottom',
            'mobile-right-top' => 'bg-right-top',
            'mobile-top' => 'bg-top',
            'tablet-bottom' => 'md:bg-bottom',
            'tablet-center' => 'md:bg-center',
            'tablet-left' => 'md:bg-left',
            'tablet-left-bottom' => 'md:bg-left-bottom',
            'tablet-left-top' => 'md:bg-left-top',
            'tablet-right' => 'md:bg-right',
            'tablet-right-bottom' => 'md:bg-right-bottom',
            'tablet-right-top' => 'md:bg-right-top',
            'tablet-top' => 'md:bg-top',
            'desktop-bottom' => 'lg:bg-bottom',
            'desktop-center' => 'lg:bg-center',
            'desktop-left' => 'lg:bg-left',
            'desktop-left-bottom' => 'lg:bg-left-bottom',
            'desktop-left-top' => 'lg:bg-left-top',
            'desktop-right' => 'lg:bg-right',
            'desktop-right-bottom' => 'lg:bg-right-bottom',
            'desktop-right-top' => 'lg:bg-right-top',
            'desktop-top' => 'lg:bg-top',
        ];

        $classes = [
            $this->get_class('mobile', $position, $value),
            $this->get_class('tablet', $position, $value),
            $this->get_class('desktop', $position, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
