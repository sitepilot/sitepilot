<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Text;
use Sitepilot\Blocks\Fields\Repeater;

class IconSlider extends Block
{
    /**
     * Construct the block.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-block-icon-slider',
            'name' => __('Icon Slider', 'sitepilot'),
            'fields' => [
                Repeater::make(__('Items', 'sp-theme'), 'items')
                    ->fields([
                        Text::make(__('Text', 'sp-theme'), 'text')
                    ])
            ]
        ]);
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
        wp_enqueue_style('owl-carousel-2');

        /* Scripts */
        wp_enqueue_script('font-awesome-5');
        wp_enqueue_script('owl-carousel-2');
    }
}

IconSlider::make();
