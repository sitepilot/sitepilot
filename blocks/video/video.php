<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Text;
use Sitepilot\Blocks\Fields\Image;
use Sitepilot\Blocks\Fields\Select;
use Sitepilot\Blocks\Fields\Accordion;
use Sitepilot\Blocks\Fields\TrueFalse;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Preset\ImageSize;
use Sitepilot\Blocks\Fields\Style\BorderColor;
use Sitepilot\Blocks\Fields\Style\BorderStyle;
use Sitepilot\Blocks\Fields\Style\BorderWidth;

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
            'slug' => 'sp-video',
            'name' => __('Video', 'sitepilot'),
            'supports' => [
                'full_width' => true,
                'width_width' => true
            ],
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

                Accordion::make('<i class="fas fa-border-all"></i>  ' . __('Border', 'sitepilot'), 'border')
                    ->fields([
                        BorderColor::make('border_color'),

                        BorderStyle::make('border_style'),

                        BorderWidth::make('border_width')
                    ]),

                Accordion::make('<i class="fas fa-arrows-alt"></i> ' . __('Spacing', 'sitepilot'), 'spacing')
                    ->fields([
                        Margin::make('margin')->default_value([
                            'bottom' => ['mobile' => 4]
                        ]),
                    ])
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
                'autoplay' => $data['autoplay'],
                'muted' => $data['muted']
            ]),
            'classes' => $this->get_classes([
                'relative',
                'field:border_width',
                'field:border_color',
                'field:border_style',
                'field:margin'
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
