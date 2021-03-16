<?php

namespace Sitepilot\Fields\Preset;

use Sitepilot\Fields\Text;
use Sitepilot\Fields\Group;
use Sitepilot\Fields\Number;
use Sitepilot\Fields\Select;

class Query extends Group
{
    /**
     * Construct the field.
     *
     * @param array ...$arguments
     */
    public function __construct(...$arguments)
    {
        parent::__construct(...$arguments);

        $this->fields([
            Select::make([
                'name' => __('Source', 'sitepilot'),
                'options' => [
                    'main_query' => __('Main Query', 'sitepilot'),
                    'custom_query' => __('Custom Query', 'sitepilot')
                ],
                'default' => 'main_query'
            ]),

            Select::make(__('Post Type', 'sitepilot'), 'query_post_type')
                ->options([
                    'post' => __('Post', 'sitepilot')
                ])
                ->default_value('post')
                ->conditional_rule('query_source', '==', 'custom_query'),

            Select::make(__('Order By', 'sitepilot'), 'query_order_by')
                ->options([
                    'date' => __('Date', 'sitepilot'),
                    'modified' => __('Date Last Modified', 'sitepilot'),
                    'title' => __('Title', 'sitepilot'),
                    'name' => __('Post Slug', 'sitepilot'),
                    'menu_order' => __('Menu Order', 'sitepilot'),
                    'rand' => __('Random', 'sitepilot'),
                    'ID' => __('ID', 'sitepilot'),
                    'comment_count' => __('Comment Count', 'sitepilot'),
                    'none' => __('None', 'sitepilot'),
                ])
                ->default_value('date')
                ->conditional_rule('query_source', '==', 'custom_query'),

            Select::make(__('Order Direction', 'sitepilot'), 'query_order')
                ->options([
                    'desc' => __('Descending', 'sitepilot'),
                    'asc' => __('Ascending', 'sitepilot')
                ])
                ->default_value('desc')
                ->conditional_rule('query_source', '==', 'custom_query'),

            Number::make(__('Posts Per Page', 'sitepilot'), 'query_posts_per_page')
                ->conditional_rule('query_source', '==', 'custom_query'),

            Number::make(__('Offset', 'sitepilot'), 'query_offset')
                ->default_value(0)
                ->conditional_rule('query_source', '==', 'custom_query'),

            Select::make(__('Exclude Current Post', 'sitepilot'), 'query_exclude_self')
                ->options([
                    'yes' => __('Yes', 'sitepilot'),
                    'no'  => __('No', 'sitepilot'),
                ])
                ->default_value('no')
                ->conditional_rule('query_source', '==', 'custom_query'),

            Text::make(__('Keyword', 'sitepilot'), 'query_keyword')
                ->conditional_rule('query_source', '==', 'custom_query')
        ]);
    }

    /**
     * Set the query post types.
     *
     * @param array $post_types
     * @return self
     */
    public function post_types(array $post_types): self
    {
        for ($i = 0; $i < count($this->fields); $i++) {
            if ('query_post_type' == $this->fields[$i]->attribute) {
                $this->fields[$i]->options($post_types);
            }
        }

        return $this;
    }
}
