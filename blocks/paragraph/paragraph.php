<?php

namespace Sitepilot\Blocks;

use Sitepilot\Blocks\Fields\Style\Color;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\Padding;
use Sitepilot\Blocks\Fields\Style\FontSize;
use Sitepilot\Blocks\Fields\Style\BoxShadow;
use Sitepilot\Blocks\Fields\Style\TextAlign;
use Sitepilot\Blocks\Fields\Style\FontWeight;
use Sitepilot\Blocks\Fields\Style\BorderColor;
use Sitepilot\Blocks\Fields\Style\BorderStyle;
use Sitepilot\Blocks\Fields\Style\BorderWidth;
use Sitepilot\Blocks\Fields\Preset\BorderGroup;
use Sitepilot\Blocks\Fields\Style\BorderRadius;
use Sitepilot\Blocks\Fields\Preset\SpacingGroup;
use Sitepilot\Blocks\Fields\Style\TextTransform;
use Sitepilot\Blocks\Fields\Style\BackgroundColor;
use Sitepilot\Blocks\Fields\Preset\BackgroundGroup;
use Sitepilot\Blocks\Fields\Preset\TypographyGroup;

class Paragraph extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-paragraph',
            'name' => __('Paragraph', 'sitepilot'),
            'supports' => [
                'inner_blocks' => true,
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="fill: #1062fe" class="bi bi-type" viewBox="0 0 16 16">
                <path d="M2.244 13.081l.943-2.803H6.66l.944 2.803H8.86L5.54 3.75H4.322L1 13.081h1.244zm2.7-7.923L6.34 9.314H3.51l1.4-4.156h.034zm9.146 7.027h.035v.896h1.128V8.125c0-1.51-1.114-2.345-2.646-2.345-1.736 0-2.59.916-2.666 2.174h1.108c.068-.718.595-1.19 1.517-1.19.971 0 1.518.52 1.518 1.464v.731H12.19c-1.647.007-2.522.8-2.522 2.058 0 1.319.957 2.18 2.345 2.18 1.06 0 1.716-.43 2.078-1.011zm-1.763.035c-.752 0-1.456-.397-1.456-1.244 0-.65.424-1.115 1.408-1.115h1.805v.834c0 .896-.752 1.525-1.757 1.525z"/>
            </svg>'
        ]);
    }

    public function fields(): array
    {
        return [
            TypographyGroup::make('text_group')
                ->fields([
                    Color::make('color'),

                    FontSize::make('font_size'),

                    FontWeight::make('font_weight'),

                    TextAlign::make('text_align'),

                    TextTransform::make('text_transform')
                ]),

            BackgroundGroup::make('bg_group')
                ->fields([
                    BackgroundColor::make('bg_color'),

                    BoxShadow::make('bg_shadow')
                ]),
                
            BorderGroup::make('border_group')
                ->fields([
                    BorderColor::make('border_color'),

                    BorderStyle::make('border_style'),

                    BorderWidth::make('border_width'),

                    BorderRadius::make(__('Border Radius', 'sitepilot'), 'border_radius')
                ]),

            SpacingGroup::make('spacing_group')
                ->fields([
                    Padding::make('padding'),

                    Margin::make('margin')->default_value(
                        $this->plugin->model->get_block_margin()
                    )
                ]),
        ];
    }

    protected function view_data(array $data): array
    {
        $template = array(
            array('sitepilot/paragraph')
        );

        $allowed_blocks = array('sitepilot/paragraph');

        return [
            'template' => wp_json_encode($template),
            'allowed_blocks' => wp_json_encode($allowed_blocks),
            'classes' => $this->get_classes([
                'inner-blocks',
                'field:color',
                'field:font_size',
                'field:font_weight',
                'field:text_align',
                'field:text_transform',
                'field:bg_color',
                'field:bg_shadow',
                'field:border_color',
                'field:border_style',
                'field:border_width',
                'field:border_radius',
                'field:margin',
                'field:padding',
                !empty($data['color']) ? 'color-inherit' : '',
                !empty($data['font_size']) ? 'font-size-inherit' : '',
                !empty($data['font_weight']) ? 'font-weight-inherit' : '',
                !empty($data['text_align']) ? 'text-align-inherit' : '',
                !empty($data['text_transform']) ? 'text-transform-inherit' : ''
            ])
        ];
    }
}
