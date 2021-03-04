<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Text;
use Sitepilot\Blocks\Fields\Editor;
use Sitepilot\Blocks\Fields\Repeater;

class Accordion extends Block
{
    /**
     * Construct the block.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-block-accordion',
            'name' => __('Accordion', 'sitepilot'),
            'fields' => [
                Repeater::make(__('Items', 'sitepilot'), 'items')
                    ->fields([
                        Text::make(__('Title', 'sitepilot'), 'title'),

                        Editor::make(__('Content', 'sitepilot'), 'content')
                    ])
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-expand" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 8zM7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10z"/>
            </svg>'
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
        return [];
    }

    /**
     * Enqueue block assets.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        parent::enqueue_assets();

        /* Scripts */
        wp_enqueue_script('font-awesome-5');
    }
}

Accordion::make();
