<?php

namespace Sitepilot\Blocks\Fields\Style;

use Sitepilot\Blocks\Fields\ResponsiveSelect;

class BackgroundImageAttachment extends ResponsiveSelect
{
    public function __construct(...$arguments)
    {
        if (count($arguments) == 1) {
            array_unshift($arguments, __('Attachment', 'sitepilot'));
        }

        parent::__construct(...$arguments);

        $this->fields([
            'attachment' => [
                'label' => $this->name,
                'choices' => [
                    'default' => '',
                    'fixed' => __('Fixed', 'sitepilot'),
                    'scroll' => __('Scroll', 'sitepilot'),
                ],
                'width' => '50%'
            ]
        ]);
    }

    protected function format_value($value)
    {
        $attachment = [
            'mobile-fixed' => 'bg-fixed',
            'mobile-scroll' => 'bg-scroll',
            'tablet-fixed' => 'md:bg-fixed',
            'tablet-scroll' => 'md:bg-scroll',
            'desktop-fixed' => 'lg:bg-fixed',
            'desktop-scroll' => 'lg:bg-scroll',
        ];

        $classes = [
            $this->get_class('attachment', 'mobile', $attachment, $value),
            $this->get_class('attachment', 'tablet', $attachment, $value),
            $this->get_class('attachment', 'desktop', $attachment, $value)
        ];

        return implode(" ", array_filter($classes));
    }
}
