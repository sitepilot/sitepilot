<?php

namespace Sitepilot\Blocks;

use Sitepilot\Blocks\Fields\Text;
use Sitepilot\Blocks\Fields\TrueFalse;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\AspectRatio;
use Sitepilot\Blocks\Fields\Style\BorderColor;
use Sitepilot\Blocks\Fields\Style\BorderStyle;
use Sitepilot\Blocks\Fields\Style\BorderWidth;
use Sitepilot\Blocks\Fields\Preset\BorderGroup;
use Sitepilot\Blocks\Fields\Style\BorderRadius;
use Sitepilot\Blocks\Fields\Preset\SpacingGroup;

class Youtube extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-youtube',
            'name' => __('Youtube', 'sitepilot'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="fill: #1062fe" class="bi bi-youtube" viewBox="0 0 16 16">
                <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.122C.002 7.343.01 6.6.064 5.78l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
            </svg>'
        ]);
    }

    public function fields(): array
    {
        return [
            Text::make(__('Youtube ID', 'sitepilot'), 'youtube_id'),

            TrueFalse::make(__('Autoplay', 'sitepilot'), 'autoplay'),

            TrueFalse::make(__('Muted', 'sitepilot'), 'muted'),

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
                        $this->plugin->model->get_block_margin()
                    )
                ]),
        ];
    }

    protected function view_data(array $data): array
    {
        $params = [
            'rel' => '0',
            'muted' => $data['muted'] ? '1' : '0',
            'autoplay' => $data['autoplay'] ? '1' : '0'
        ];

        $video = empty($data['youtube_id']) ? 'K1QICrgxTjA' : urlencode($data['youtube_id']);

        $url = add_query_arg($params, 'https://youtube.com/embed/' . $video);

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
