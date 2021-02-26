<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BackgroundImageScale extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Scale', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->select_fields([
            'choices' => [
                'default' => '',
                'cover' => __('Cover', 'sitepilot'),
                'contain' => __('Contain', 'sitepilot')
            ]
        ]);
    }

    protected function format_value($value)
    {
        $scale = [
            'mobile-cover' => 'bg-cover',
            'mobile-contain' => 'bg-contain',
            'tablet-cover' => 'md:bg-cover',
            'tablet-contain' => 'md:bg-contain',
            'desktop-cover' => 'lg:bg-cover',
            'desktop-contain' => 'lg:bg-contain'
        ];

        $classes = [
            $this->get_class('mobile', $scale, $value),
            $this->get_class('tablet', $scale, $value),
            $this->get_class('desktop', $scale, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
