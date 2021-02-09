<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BackgroundImageRepeat extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Repeat', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'repeat' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    'repeat' => __('Repeat', 'sitepilot'),
                    'no-repeat' => __('No Repeat', 'sitepilot'),
                    'repeat-x' => __('Repeat X', 'sitepilot'),
                    'repeat-y' => __('Repeat Y', 'sitepilot')
                ]
            ]
        ]);
    }

    protected function format_value($value)
    {
        $repeat = [
            'mobile-repeat' => 'bg-repeat',
            'mobile-no-repeat' => 'bg-no-repeat',
            'mobile-repeat-x' => 'bg-repeat-x',
            'mobile-repeat-y' => 'bg-repeat-y',
            'tablet-repeat' => 'md:bg-repeat',
            'tablet-no-repeat' => 'md:bg-no-repeat',
            'tablet-repeat-x' => 'md:bg-repeat-x',
            'tablet-repeat-y' => 'md:bg-repeat-y',
            'desktop-repeat' => 'lg:bg-repeat',
            'desktop-no-repeat' => 'lg:bg-no-repeat',
            'desktop-repeat-x' => 'lg:bg-repeat-x',
            'desktop-repeat-y' => 'lg:bg-repeat-y'
        ];

        $classes = [
            $this->get_class('repeat', 'mobile', $repeat, $value),
            $this->get_class('repeat', 'tablet', $repeat, $value),
            $this->get_class('repeat', 'desktop', $repeat, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
