<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class TransitionDuration extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Transition Duration', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'duration' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    75 => '75ms',
                    100 => '100ms',
                    200 => '200ms',
                    300 => '300ms',
                    500 => '500ms',
                    700 => '700ms',
                    1000 => '1000ms'
                ]
            ]
        ]);
    }

    protected function format_value($value)
    {
        $duration = [
            'mobile-75' => 'duration-75',
            'mobile-100' => 'duration-100',
            'mobile-150' => 'duration-150',
            'mobile-200' => 'duration-200',
            'mobile-300' => 'duration-300',
            'mobile-500' => 'duration-500',
            'mobile-700' => 'duration-700',
            'mobile-1000' => 'duration-1000',
            'tablet-75' => 'md:duration-75',
            'tablet-100' => 'md:duration-100',
            'tablet-150' => 'md:duration-150',
            'tablet-200' => 'md:duration-200',
            'tablet-300' => 'md:duration-300',
            'tablet-500' => 'md:duration-500',
            'tablet-700' => 'md:duration-700',
            'tablet-1000' => 'md:duration-1000',
            'desktop-75' => 'lg:duration-75',
            'desktop-100' => 'lg:duration-100',
            'desktop-150' => 'lg:duration-150',
            'desktop-200' => 'lg:duration-200',
            'desktop-300' => 'lg:duration-300',
            'desktop-500' => 'lg:duration-500',
            'desktop-700' => 'lg:duration-700',
            'desktop-1000' => 'lg:duration-1000',
        ];

        $classes = [
            $this->get_class('duration', 'mobile', $duration, $value),
            $this->get_class('duration', 'tablet', $duration, $value),
            $this->get_class('duration', 'desktop', $duration, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
