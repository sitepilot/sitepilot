<?php

namespace Sitepilot\Blocks;

use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\BoxShadow;
use Sitepilot\Blocks\Fields\Preset\ImageSize;
use Sitepilot\Blocks\Fields\Style\AspectRatio;
use Sitepilot\Blocks\Fields\Style\BorderColor;
use Sitepilot\Blocks\Fields\Style\BorderStyle;
use Sitepilot\Blocks\Fields\Style\BorderWidth;
use Sitepilot\Blocks\Fields\Preset\BorderGroup;
use Sitepilot\Blocks\Fields\Style\BorderRadius;
use Sitepilot\Blocks\Fields\Image as ImageField;
use Sitepilot\Blocks\Fields\Preset\SpacingGroup;
use Sitepilot\Blocks\Fields\Style\BackgroundColor;
use Sitepilot\Blocks\Fields\Preset\BackgroundGroup;

class Image extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-image',
            'name' => __('Image', 'sitepilot'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="fill: #1062fe" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"/>
            </svg>'
        ]);
    }

    public function fields(): array
    {
        return [
            ImageField::make(__('Image', 'sitepilot'), 'image'),

            ImageSize::make('image_size')
                ->conditional_rule('image', '!=', 'empty'),

            AspectRatio::make('image_ratio')
                ->conditional_rule('image', '!=', 'empty'),

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
                ])
                ->conditional_rule('image', '!=', 'empty'),

            SpacingGroup::make('spacing_group')
                ->fields([
                    Margin::make('margin')->default_value(
                        $this->plugin->model->get_block_margin()
                    )
                ])
                ->conditional_rule('image', '!=', 'empty'),
        ];
    }

    protected function view_data(array $data): array
    {
        return [
            'classes' => $this->get_classes([
                'field:bg_color',
                'field:bg_shadow',
                'field:border_radius',
                'field:margin',
                'field:image_ratio',
                !empty($data['image_ratio']) ? 'relative' : '',
            ]),
            'image_classes' => $this->get_classes([
                'field:border_color',
                'field:border_style',
                'field:border_width',
                'field:border_radius',
                !empty($data['image_ratio']) ? 'absolute' : '',
                !empty($data['image_ratio']) ? 'h-full' : '',
                !empty($data['image_ratio']) ? 'w-full' : '',
                !empty($data['image_ratio']) ? 'object-cover' : '',
            ])
        ];
    }
}

Image::make();
