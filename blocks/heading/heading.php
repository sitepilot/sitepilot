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

class Heading extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-heading',
            'name' => __('Heading', 'sitepilot'),
            'supports' => [
                'inner_blocks' => true,
            ],
            'icon' => '<svg width="24" height="24" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="sp-brand-fill" role="img" aria-hidden="true" focusable="false"><path d="M9 5h2v10H9v-4H5v4H3V5h2v4h4V5zm6.6 0c-.6.9-1.5 1.7-2.6 2v1h2v7h2V5h-1.4z"></path></svg>'
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
                        sitepilot()->model->get_block_margin()
                    )
                ]),
        ];
    }

    protected function view_data(array $data): array
    {
        $template = array(
            array('sitepilot/heading')
        );

        $allowed_blocks = array('sitepilot/heading');

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

Heading::make();
