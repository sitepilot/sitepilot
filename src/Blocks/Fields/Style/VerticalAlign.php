<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class VerticalAlign extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Vertical Align', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'align' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    'start' => __('Top', 'sitepilot'),
                    'center' => __('Center', 'sitepilot'),
                    'end' => __('Bottom', 'sitepilot')
                ]
            ],
        ]);
    }

    protected function format_value($value)
    {
        $align_items = [
            'mobile-start' => 'flex items-start',
            'mobile-center' => 'flex items-center',
            'mobile-end' => 'flex items-end',
            'tablet-start' => 'md:flex md:items-start',
            'tablet-center' => 'md:flex md:items-center',
            'tablet-end' => 'md:flex md:items-end',
            'desktop-start' => 'lg:flex lg:items-start',
            'desktop-center' => 'lg:flex lg:items-center',
            'desktop-end' => 'lg:flex lg:items-end',
        ];

        $classes = [
            $this->get_class('align', 'mobile', $align_items, $value),
            $this->get_class('align', 'tablet', $align_items, $value),
            $this->get_class('align', 'desktop', $align_items, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
