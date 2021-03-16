<?php

namespace Sitepilot\Blocks;

use Sitepilot\Fields\Image;
use Sitepilot\Modules\Blocks\Block;
use Sitepilot\Fields\Preset\ImageSize;

class ImageCompare extends Block
{
    /**
     * Create a new block instance.
     * 
     * @return void
     */
    public function __construct($config = array())
    {
        parent::__construct('sp-block-image-compare', array_merge([
            'name' => __('Image Compare', 'sitepilot'),
            'dir' => SITEPILOT_DIR . '/blocks/image-compare',
            'url' => SITEPILOT_URL . '/blocks/image-compare',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
            </svg>',
        ], $config));
    }

    /**
     * Enqueues the block's assets.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        /* Styles */
        wp_enqueue_style('twenty-twenty');

        /* Scripts */
        wp_enqueue_script('sp-frontend');
        wp_enqueue_script('twenty-twenty');
    }

    /**
     * Returns the block's fields.
     *
     * @return array
     */
    protected function fields(): array
    {
        return [
            Image::make('img_1', [
                'name' => __('Image 1', 'sitepilot'),
                'default' => 'https://picsum.photos/1024/600'
            ]),

            ImageSize::make('img_1_size', [
                'name' => __('Image 1 Size', 'sitepilot'),
            ]),

            Image::make('img_2', [
                'name' => __('Image 2', 'sitepilot'),
                'default' => 'https://picsum.photos/1024/600'
            ]),

            ImageSize::make('img_2_size', [
                'name' => __('Image 2 Size', 'sitepilot')
            ]),
        ];
    }
}
