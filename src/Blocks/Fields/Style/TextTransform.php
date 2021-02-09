<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class TextTransform extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Text Transform', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'transform' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    'uppercase' => __('Uppercase', 'sitepilot'),
                    'lowercase' => __('Lowercase', 'sitepilot'),
                    'capitalize' => __('Capitalize', 'sitepilot'),
                    'normal-case' => __('Normal', 'sitepilot')
                ]
            ]
        ]);
    }

    protected function format_value($value)
    {
        $size = [
            'mobile-uppercase' => 'uppercase',
            'mobile-lowercase' => 'lowercase',
            'mobile-capitalize' => 'capitalize',
            'mobile-normal-case' => 'normal-case',

            'tablet-uppercase' => 'md:uppercase',
            'tablet-lowercase' => 'md:lowercase',
            'tablet-capitalize' => 'md:capitalize',
            'tablet-normal-case' => 'md:normal-case',

            'desktop-uppercase' => 'lg:uppercase',
            'desktop-lowercase' => 'lg:lowercase',
            'desktop-capitalize' => 'lg:capitalize',
            'desktop-normal-case' => 'lg:normal-case',
        ];

        $classes = [
            $this->get_class('transform', 'mobile', $size, $value),
            $this->get_class('transform', 'tablet', $size, $value),
            $this->get_class('transform', 'desktop', $size, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
