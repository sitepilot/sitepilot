<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Accordion;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\FontSize;
use Sitepilot\Blocks\Fields\Style\TextAlign;
use Sitepilot\Blocks\Fields\Style\FontWeight;
use Sitepilot\Blocks\Fields\Style\TextTransform;

class Heading extends Block
{
    /**
     * Construct the block.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-heading',
            'name' => __('Advanced Heading', 'sitepilot'),
            'supports' => [
                'inner_blocks' => true
            ],
            'fields' => [
                Accordion::make('<i class="fas fa-text-height"></i> ' . __('Typography', 'sitepilot'), 'typography')
                    ->fields([
                        FontSize::make('font_size'),

                        FontWeight::make('font_weight'),

                        TextAlign::make('text_align'),

                        TextTransform::make('text_transform')
                    ]),

                Accordion::make('<i class="fas fa-arrows-alt"></i> ' . __('Spacing', 'sitepilot'), 'spacing')
                    ->fields([
                        Margin::make('margin'),
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
            'template' => wp_json_encode([
                ['core/heading']
            ]),
            'classes' => $this->get_classes([
                'field:font_size',
                'field:font_weight',
                'field:text_align',
                'field:text_transform',
                !empty($data['font_size']) ? 'font-size-inherit' : '',
                !empty($data['font_weight']) ? 'font-weight-inherit' : '',
                !empty($data['text_align']) ? 'text-align-inherit' : '',
                !empty($data['text_transform']) ? 'text-transform-inherit' : '',
                'field:margin'
            ])
        ];
    }
}

Heading::make();
