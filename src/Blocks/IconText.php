<?php

namespace Sitepilot\Blocks;

use Sitepilot\Modules\Blocks\Block;

class IconText extends Block
{
    /**
     * Create a new block instance.
     * 
     * @return void
     */
    public function __construct($config = array())
    {
        parent::__construct('sp-block-icon-text', array_merge([
            'name' => __('Icon Text', 'sitepilot'),
            'supports' => [
                'inner_blocks' => true
            ],
            'dir' => SITEPILOT_DIR . '/blocks/icon-text',
            'url' => SITEPILOT_URL . '/blocks/icon-text',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-stars" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z"/>
                <path d="M2.242 2.194a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.256-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53zm0 4a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.255-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53zm0 4a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.255-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53z"/>
            </svg>'
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
        wp_enqueue_style('sp-frontend');

        /* Scripts */
        wp_enqueue_script('sp-frontend');
        wp_enqueue_script('font-awesome-5');
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
                ['core/paragraph']
            ])
        ];
    }
}
