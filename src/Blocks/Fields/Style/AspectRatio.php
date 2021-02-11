<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class AspectRatio extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Aspect Ratio', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'ratio' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    '4x3' => '4x3',
                    '16x9' => '16x9',
                    '21x9' => '21x9'
                ]
            ]
        ]);
    }

    protected function format_value($value)
    {
        $ratio = [
            'mobile-4x3' => 'pb-3/4',
            'mobile-16x9' => 'pb-9/16',
            'mobile-21x9' => 'pb-9/21',

            'tablet-4x3' => 'md:pb-3/4',
            'tablet-16x9' => 'md:pb-9/16',
            'tablet-21x9' => 'md:pb-9/21',

            'desktop-4x3' => 'lg:pb-3/4',
            'desktop-16x9' => 'lg:pb-9/16',
            'desktop-21x9' => 'lg:pb-9/21'
        ];

        $classes = [
            $this->get_class('ratio', 'mobile', $ratio, $value),
            $this->get_class('ratio', 'tablet', $ratio, $value),
            $this->get_class('ratio', 'desktop', $ratio, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}