<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Text;
use Sitepilot\Blocks\Fields\Image;
use Sitepilot\Blocks\Fields\Select;
use Sitepilot\Blocks\Fields\TrueFalse;
use Sitepilot\Blocks\Fields\Preset\ImageSize;

class Video extends Block
{
    /**
     * Construct the block.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-block-video',
            'name' => __('Video', 'sitepilot'),
            'supports' => [
                'align' => [
                    'full',
                    'wide'
                ]
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
            </svg>',
            'fields' => [
                Select::make(__('Provider', 'sitepilot'), 'provider')
                    ->options([
                        'youtube' => __('Youtube', 'sitepilot'),
                        'vimeo' => __('Vimeo', 'sitepilot')
                    ])
                    ->default_value('youtube'),

                Text::make(__('Youtube ID', 'sitepilot'), 'youtube_id')
                    ->default_value('bTqVqk7FSmY')
                    ->conditional_rule('provider', '==', 'youtube'),

                Text::make(__('Vimeo ID', 'sitepilot'), 'vimeo_id')
                    ->default_value('76979871')
                    ->conditional_rule('provider', '==', 'vimeo'),

                TrueFalse::make(__('Autoplay', 'sitepilot'), 'autoplay'),

                TrueFalse::make(__('Muted', 'sitepilot'), 'muted'),

                Image::make(__('Image', 'sitepilot'), 'image'),

                ImageSize::make(__('Image Size', 'sitepilot'), 'image_size')
                    ->conditional_rule('image', '!=', 'empty'),
            ]
        ]);
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

    /**
     * Enqueue block assets.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        parent::enqueue_assets();

        /* Styles */
        wp_enqueue_style('plyr-3');

        /* Scripts */
        wp_enqueue_script('plyr-3');
    }
}

Video::make();
