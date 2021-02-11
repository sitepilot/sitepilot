<?php

namespace Sitepilot\Blocks;

use Sitepilot\Blocks\Fields\Textarea;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\AspectRatio;
use Sitepilot\Blocks\Fields\Style\BorderColor;
use Sitepilot\Blocks\Fields\Style\BorderStyle;
use Sitepilot\Blocks\Fields\Style\BorderWidth;
use Sitepilot\Blocks\Fields\Preset\BorderGroup;
use Sitepilot\Blocks\Fields\Style\BorderRadius;
use Sitepilot\Blocks\Fields\Preset\SpacingGroup;

class GoogleMaps extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-google-maps',
            'name' => __('Google Maps', 'sitepilot'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="sp-brand-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98l4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
            </svg>'
        ]);
    }

    public function fields(): array
    {
        return [
            Textarea::make(__('Address', 'sitepilot'), 'address'),

            AspectRatio::make('aspect_ratio')
                ->default_value(['ratio' => ['mobile' => '16x9']]),

            BorderGroup::make('border_group')
                ->fields([
                    BorderColor::make('border_color'),

                    BorderStyle::make('border_style'),

                    BorderWidth::make('border_width'),

                    BorderRadius::make(__('Border Radius', 'sitepilot'), 'border_radius')
                ]),

            SpacingGroup::make('spacing_group')
                ->fields([
                    Margin::make('margin')->default_value(
                        sitepilot()->model->get_block_margin()
                    )
                ]),
        ];
    }

    protected function view_data(array $data): array
    {
        $params = [
            'q'   => empty($data['address']) ? 'Netherlands' : urlencode($data['address']),
            'key' => 'AIzaSyD09zQ9PNDNNy9TadMuzRV_UsPUoWKntt8',
        ];

        $url = add_query_arg($params, 'https://www.google.com/maps/embed/v1/place');

        return [
            'url' => $url,
            'classes' => $this->get_classes([
                'field:aspect_ratio',
                'field:margin',
                !empty($data['aspect_ratio']) ? 'relative' : '',
            ]),
            'iframe_classes' => $this->get_classes([
                'field:border_color',
                'field:border_style',
                'field:border_width',
                'field:border_radius',
                !empty($data['aspect_ratio']) ? 'absolute' : '',
                !empty($data['aspect_ratio']) ? 'h-full' : '',
                !empty($data['aspect_ratio']) ? 'w-full' : ''
            ])
        ];
    }
}

GoogleMaps::make();
