<?php

namespace Sitepilot\Blocks;

use Sitepilot\Fields\Group;
use Sitepilot\Fields\Image;
use Sitepilot\Fields\TrueFalse;
use Sitepilot\Modules\Blocks\Block;
use Sitepilot\Fields\FieldConditional;
use Sitepilot\Fields\Preset\ImageSize;

class Section extends Block
{
    /**
     * Create a new block instance.
     * 
     * @return void
     */
    public function __construct($config = array())
    {
        parent::__construct('sp-block-section', array_merge([
            'name' => __('Section', 'sitepilot'),
            'align' => 'full',
            'supports' => [
                'align' => ['full', 'wide'],
                'color' => [
                    'text' => false,
                    'background' => true
                ],
                'inner_blocks' => true
            ],
            'dir' => SITEPILOT_DIR . '/blocks/section',
            'url' => SITEPILOT_URL . '/blocks/section',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 1.5a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 0-1h-13a.5.5 0 0 0-.5.5zm0 13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 0-1h-13a.5.5 0 0 0-.5.5z"/>
                <path d="M2 7a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7z"/>
            </svg>'
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
            Group::make('background_image', [
                'name' => __('Background Image', 'sitepilot'),
                'fields' => [
                    Image::make('bg_image', [
                        'name' => __('Image', 'sitepilot')
                    ]),

                    TrueFalse::make('bg_image_featured', [
                        'name' => __('Use Featured Image', 'sitepilot'),
                        'description' => __("Use the post's featured image as background image when available.", 'sitepilot'),
                        'conditionals' => [
                            new FieldConditional('bg_image', [
                                'operator' => '!=',
                                'value' => 'empty'
                            ])
                        ]
                    ]),

                    ImageSize::make('bg_image_size', [
                        'name' => __('Background Image', 'sitepilot'),
                        'conditionals' => [
                            new FieldConditional('bg_image', [
                                'operator' => '!=',
                                'value' => 'empty'
                            ])
                        ]
                    ])
                ]
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
        wp_enqueue_style('sp-frontend');
    }

    /**
     * Returns the block's view data.
     *
     * @param arrray $data
     * @return array
     */
    protected function view_data(array $data): array
    {
        if ($data['bg_image_featured'] && $featured_image_id = get_post_thumbnail_id()) {
            $bg_image = (Image::make('', ''))->format_value($featured_image_id);
        } else {
            $bg_image = $data['bg_image'];
        }

        return [
            'template' => wp_json_encode([
                ['core/columns']
            ]),
            'allowed_blocks' => wp_json_encode([
                'core/columns'
            ]),
            'bg_image' => $bg_image,
        ];
    }
}
