<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BoxShadow extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Shadow', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'shadow' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    'xs' => __('Extra Small', 'sitepilot'),
                    'sm' => __('Small', 'sitepilot'),
                    'md' => __('Medium', 'sitepilot'),
                    'lg' => __('Large', 'sitepilot'),
                    'xl' => __('Extra Large', 'sitepilot')
                ]
            ],
        ]);
    }

    protected function format_value($value)
    {
        $shadow = [
            'mobile-xs' => 'shadow-sm',
            'mobile-sm' => 'shadow',
            'mobile-md' => 'shadow-md',
            'mobile-lg' => 'shadow-lg',
            'mobile-xl' => 'shadow-xl',
            'tablet-xs' => 'md:shadow-sm',
            'tablet-sm' => 'md:shadow',
            'tablet-md' => 'md:shadow-md',
            'tablet-lg' => 'md:shadow-lg',
            'tablet-xl' => 'md:shadow-xl',
            'desktop-xs' => 'lg:shadow-sm',
            'desktop-sm' => 'lg:shadow',
            'desktop-md' => 'lg:shadow-md',
            'desktop-lg' => 'lg:shadow-lg',
            'desktop-xl' => 'lg:shadow-xl',
        ];

        $classes = [
            $this->get_class('shadow', 'mobile', $shadow, $value),
            $this->get_class('shadow', 'tablet', $shadow, $value),
            $this->get_class('shadow', 'desktop', $shadow, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
