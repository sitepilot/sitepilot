<?php

namespace Sitepilot\Blocks;

use Sitepilot\Fields\Text;
use Sitepilot\Fields\Image;
use Sitepilot\Fields\Select;
use Sitepilot\Fields\TrueFalse;
use Sitepilot\Modules\Blocks\Block;
use Sitepilot\Fields\FieldConditional;
use Sitepilot\Fields\Preset\ImageSize;

class Video extends Block
{
    /**
     * Create a new block instance.
     * 
     * @return void
     */
    public function __construct($config = array())
    {
        parent::__construct('sp-block-video', array_merge([
            'name' => __('Video', 'sitepilot'),
            'dir' => SITEPILOT_DIR . '/blocks/video',
            'url' => SITEPILOT_URL . '/blocks/video',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
            </svg>',
        ], $config));
    }

    /**
     * Returns the block's fields.
     *
     * @return array
     */
    protected function fields(): array
    {
        return [
            Select::make('provider', [
                'name' => __('Provider', 'sitepilot'),
                'options' => [
                    'youtube' => __('Youtube', 'sitepilot'),
                    'vimeo' => __('Vimeo', 'sitepilot')
                ],
                'default' => 'youtube'
            ]),

            Text::make('youtube_id', [
                'name' => __('Youtube ID', 'sitepilot'),
                'conditionals' => [
                    new FieldConditional('provider', [
                        'operator' => '==',
                        'value' => 'youtube'
                    ])
                ],
                'default' => 'bTqVqk7FSmY'
            ]),

            Text::make('vimeo_id', [
                'name' => __('Vimeo ID', 'sitepilot'),
                'conditionals' => [
                    new FieldConditional('provider', [
                        'operator' => '==',
                        'value' => 'youtube'
                    ])
                ],
                'default' => '76979871'
            ]),

            TrueFalse::make('autoplay', [
                'name' => __('Autoplay', 'sitepilot')
            ]),

            TrueFalse::make('muted', [
                'name' => __('Muted', 'sitepilot')
            ]),

            Image::make('image', [
                'name' => __('Image', 'sitepilot')
            ]),

            ImageSize::make('image_size', [
                'name' => __('Image Size', 'sitepilot'),
                'conditionals' => [
                    new FieldConditional('image', [
                        'operator' => '!=',
                        'value' => 'empty'
                    ])
                ],
            ])
        ];
    }

    /**
     * Enqueues the block's assets.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        /* Styles */
        wp_enqueue_style('plyr-3');

        /* Scripts */
        wp_enqueue_script('plyr-3');
    }

    /**
     * Returns the block's view data.
     *
     * @param arrray $data
     * @return array
     */
    protected function view_data(array $data): array
    {
        return [
            'player_config' => wp_json_encode([
                'muted' => $data['muted'],
                'autoplay' => $data['autoplay']
            ])
        ];
    }
}
