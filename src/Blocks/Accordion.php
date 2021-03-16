<?php

namespace Sitepilot\Blocks;

use Sitepilot\Fields\Text;
use Sitepilot\Fields\Editor;
use Sitepilot\Fields\Repeater;
use Sitepilot\Modules\Blocks\Block;

class Accordion extends Block
{
    /**
     * Create a new block instance.
     * 
     * @return void
     */
    public function __construct($config = array())
    {
        parent::__construct('sp-block-accordion', array_merge([
            'name' => __('Accordion', 'sitepilot'),
            'dir' => SITEPILOT_DIR . '/blocks/accordion',
            'url' => SITEPILOT_URL . '/blocks/accordion',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-expand" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 8zM7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10z"/>
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
            Repeater::make('items', [
                'name' => __('Items', 'sitepilot'),
                'fields' => [
                    Text::make('title', [
                        'name' => __('Title', 'sitepilot')
                    ]),

                    Editor::make('content', [
                        'name' => __('Content', 'sitepilot')
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

        /* Scripts */
        wp_enqueue_script('sp-frontend');
        wp_enqueue_script('font-awesome-5');
    }
}
