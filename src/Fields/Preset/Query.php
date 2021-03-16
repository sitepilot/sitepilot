<?php

namespace Sitepilot\Fields\Preset;

use Sitepilot\Fields\Text;
use Sitepilot\Fields\Group;
use Sitepilot\Fields\Number;
use Sitepilot\Fields\Select;
use Sitepilot\Fields\FieldConditional;

class Query extends Group
{
    /**
     * Construct the field.
     *
     * @param array ...$arguments
     */
    public function __construct(...$arguments)
    {
        $this->fillable = array_merge($this->fillable, ['post_types']);
        $this->attributes = array_merge($this->attributes, ['post_types' => ['post' => __('Post', 'sitepilot')]]);

        parent::__construct(...$arguments);
    }

    /**
     * Returns the field's fields.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('query_source', [
                'name' => __('Source', 'sitepilot'),
                'options' => [
                    'main_query' => __('Main Query', 'sitepilot'),
                    'custom_query' => __('Custom Query', 'sitepilot')
                ],
                'default' => 'main_query'
            ]),

            Select::make('query_post_type', [
                'name' => __('Post Type', 'sitepilot'),
                'options' => $this->post_types,
                'default' => 'post',
                'conditionals' => [
                    new FieldConditional('query_source', [
                        'operator' => '==',
                        'value' => 'custom_query'
                    ])
                ]
            ]),

            Select::make('query_order_by', [
                'name' => __('Order By', 'sitepilot'),
                'options' => [
                    'date' => __('Date', 'sitepilot'),
                    'modified' => __('Date Last Modified', 'sitepilot'),
                    'title' => __('Title', 'sitepilot'),
                    'name' => __('Post Slug', 'sitepilot'),
                    'menu_order' => __('Menu Order', 'sitepilot'),
                    'rand' => __('Random', 'sitepilot'),
                    'ID' => __('ID', 'sitepilot'),
                    'comment_count' => __('Comment Count', 'sitepilot'),
                    'none' => __('None', 'sitepilot'),
                ],
                'default' => 'date',
                'conditionals' => [
                    new FieldConditional('query_source', [
                        'operator' => '==',
                        'value' => 'custom_query'
                    ])
                ]
            ]),

            Select::make('query_order', [
                'name' => __('Order Direction', 'sitepilot'),
                'options' => [
                    'desc' => __('Descending', 'sitepilot'),
                    'asc' => __('Ascending', 'sitepilot')
                ],
                'default' => 'desc',
                'conditionals' => [
                    new FieldConditional('query_source', [
                        'operator' => '==',
                        'value' => 'custom_query'
                    ])
                ]
            ]),

            Number::make('query_posts_per_page', [
                'name' => __('Posts Per Page', 'sitepilot'),
                'default' => 0,
                'conditionals' => [
                    new FieldConditional('query_source', [
                        'operator' => '==',
                        'value' => 'custom_query'
                    ])
                ]
            ]),

            Number::make('query_offset', [
                'name' => __('Offset', 'sitepilot'),
                'default' => 0,
                'conditionals' => [
                    new FieldConditional('query_source', [
                        'operator' => '==',
                        'value' => 'custom_query'
                    ])
                ]
            ]),

            Select::make('query_exclude_self', [
                'name' => __('Exclude Current Post', 'sitepilot'),
                'options' => [
                    'yes' => __('Yes', 'sitepilot'),
                    'no'  => __('No', 'sitepilot'),
                ],
                'default' => 'no',
                'conditionals' => [
                    new FieldConditional('query_source', [
                        'operator' => '==',
                        'value' => 'custom_query'
                    ])
                ]
            ]),

            Text::make('query_keyword', [
                'name' => __('Keyword', 'sitepilot'),
                'conditionals' => [
                    new FieldConditional('query_source', [
                        'operator' => '==',
                        'value' => 'custom_query'
                    ])
                ]
            ]),
        ];
    }
}
