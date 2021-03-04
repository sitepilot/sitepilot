<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Image;
use Sitepilot\Blocks\Fields\Accordion;
use Sitepilot\Blocks\Fields\TrueFalse;
use Sitepilot\Blocks\Fields\Preset\ImageSize;

class Section extends Block
{
    /**
     * Construct the block.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-block-section',
            'name' => __('Section', 'sitepilot'),
            'align' => 'wide',
            'supports' => [
                'align' => [
                    'full',
                    'wide'
                ],
                'color' => [
                    'text' => false,
                    'background' => true
                ],
                'inner_blocks' => true,
            ],
            'fields' => [
                Accordion::make(__('Background Image', 'sitepilot'), 'background_image')
                    ->fields([
                        Image::make(__('Image', 'sitepilot'), 'bg_image'),

                        TrueFalse::make(__('Use Featured Image', 'sitepilot'), 'bg_image_featured')
                            ->conditional_rule('bg_image', '!=', 'empty')
                            ->description(__("Use the post's featured image as background image when available.", 'sitepilot')),

                        ImageSize::make('bg_image_size')
                            ->conditional_rule('bg_image', '!=', 'empty')
                    ])
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 1.5a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 0-1h-13a.5.5 0 0 0-.5.5zm0 13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 0-1h-13a.5.5 0 0 0-.5.5z"/>
                <path d="M2 7a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7z"/>
            </svg>',
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
        if ($data['bg_image_featured'] && $featured_image_id = get_post_thumbnail_id()) {
            $image_field = Image::make('', '');
            $bg_image = $image_field->format_value($featured_image_id);
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

Section::make();
