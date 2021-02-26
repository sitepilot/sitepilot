<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Text;

class PostTitle extends Block
{
    /**
     * Construct the block.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-post-title',
            'name' => __('Post Title', 'sitepilot'),
            'post_types' => [
                'sp-template'
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="sp-brand-fill" viewBox="0 0 16 16">
                <path d="M1.5 2.5A1.5 1.5 0 0 1 3 1h10a1.5 1.5 0 0 1 1.5 1.5v3.563a2 2 0 0 1 0 3.874V13.5A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5V9.937a2 2 0 0 1 0-3.874V2.5zm1 3.563a2 2 0 0 1 0 3.874V13.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V9.937a2 2 0 0 1 0-3.874V2.5A.5.5 0 0 0 13 2H3a.5.5 0 0 0-.5.5v3.563zM2 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                <path d="M11.434 4H4.566L4.5 5.994h.386c.21-1.252.612-1.446 2.173-1.495l.343-.011v6.343c0 .537-.116.665-1.049.748V12h3.294v-.421c-.938-.083-1.054-.21-1.054-.748V4.488l.348.01c1.56.05 1.963.244 2.173 1.496h.386L11.434 4z"/>
            </svg>',
            'fields' => [
                Text::make(__('Webshop Title', 'sitepilot'), 'shop_title'),

                Text::make(__('404 Title', 'sitepilot'), '404_title')
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
        $object = get_queried_object();

        $title = '';
        if ($object instanceof \WP_Term) {
            $title =  $object->name;
        } elseif (is_search()) {
            $title = sprintf(__('Search results for: %s', 'sitepilot'), get_search_query());
        } elseif (!empty($data['404_title']) && function_exists('is_404') && is_404()) {
            $title = $data['404_title'];
        } elseif(!empty($data['shop_title']) && function_exists('is_shop') && is_shop()) {
            $title = $data['shop_title'];
        } else {
            $title = get_the_title();
        }

        return [
            'title' => $title
        ];
    }
}

PostTitle::make();
