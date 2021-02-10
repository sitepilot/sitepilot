<?php

namespace Sitepilot\Blocks;

use Sitepilot\Blocks\Fields\Image;
use Sitepilot\Blocks\Fields\TrueFalse;
use Sitepilot\Blocks\Fields\Style\Scale;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\Opacity;
use Sitepilot\Blocks\Fields\Style\Padding;
use Sitepilot\Blocks\Fields\Style\BoxShadow;
use Sitepilot\Blocks\Fields\Preset\ImageSize;
use Sitepilot\Blocks\Fields\Style\Transition;
use Sitepilot\Blocks\Fields\Style\BorderColor;
use Sitepilot\Blocks\Fields\Style\BorderStyle;
use Sitepilot\Blocks\Fields\Style\BorderWidth;
use Sitepilot\Blocks\Fields\Preset\BorderGroup;
use Sitepilot\Blocks\Fields\Style\BorderRadius;
use Sitepilot\Blocks\Fields\Preset\SpacingGroup;
use Sitepilot\Blocks\Fields\Style\BackgroundColor;
use Sitepilot\Blocks\Fields\Preset\BackgroundGroup;
use Sitepilot\Blocks\Fields\Preset\TransitionGroup;
use Sitepilot\Blocks\Fields\Style\TransitionDuration;
use Sitepilot\Blocks\Fields\Style\BackgroundImageScale;
use Sitepilot\Blocks\Fields\Preset\BackgroundImageGroup;
use Sitepilot\Blocks\Fields\Style\BackgroundImageRepeat;
use Sitepilot\Blocks\Fields\Style\BackgroundImagePosition;
use Sitepilot\Blocks\Fields\Style\BackgroundImageAttachment;

class Group extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-group',
            'name' => __('Group', 'sitepilot'),
            'supports' => [
                'inner_blocks' => true,
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-back" viewBox="0 0 16 16" style="fill: #1062fe">
                <path fill-rule="evenodd" d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z"/>
            </svg>'
        ]);
    }

    public function fields(): array
    {
        return [
            BackgroundGroup::make('bg_group')
                ->fields([
                    BackgroundColor::make('bg_color'),

                    BoxShadow::make('bg_shadow')
                ]),

            BackgroundImageGroup::make('bg_image_group')
                ->fields([
                    Image::make(__('Image', 'sitepilot'), 'bg_image'),

                    TrueFalse::make(__('Use Featured Image', 'sitepilot'), 'bg_image_featured')
                        ->conditional_rule('bg_image', '!=', 'empty')
                        ->description(__("Use the post's featured image as background image when available.", 'sitepilot')),

                    ImageSize::make('bg_image_size')
                        ->conditional_rule('bg_image', '!=', 'empty'),

                    BackgroundImageScale::make('bg_image_scale')
                        ->conditional_rule('bg_image', '!=', 'empty'),

                    BackgroundImagePosition::make('bg_image_position')
                        ->conditional_rule('bg_image', '!=', 'empty'),

                    BackgroundImageRepeat::make('bg_image_repeat')
                        ->conditional_rule('bg_image', '!=', 'empty'),

                    BackgroundImageAttachment::make('bg_image_attachment')
                        ->conditional_rule('bg_image', '!=', 'empty'),

                    Opacity::make('bg_image_opacity')
                        ->conditional_rule('bg_image', '!=', 'empty')
                ]),

            BorderGroup::make('border_group')
                ->fields([
                    BorderColor::make('border_color'),

                    BorderStyle::make('border_style'),

                    BorderWidth::make('border_width'),

                    BorderRadius::make(__('Border Radius', 'sitepilot'), 'border_radius')
                ]),

            TransitionGroup::make('transition_group')
                ->fields([
                    Transition::make('transition'),

                    TransitionDuration::make('transition_duration')
                ]),

            SpacingGroup::make('spacing_group')
                ->fields([
                    Scale::make('scale'),

                    Padding::make('padding'),

                    Margin::make('margin')
                ])
        ];
    }

    protected function view_data(array $data): array
    {
        if ($data['bg_image_featured'] && $featured_image_id = get_post_thumbnail_id()) {
            $image_field = Image::make('', '');
            $bg_image = $image_field->format_value($featured_image_id);
        } else {
            $bg_image = $data['bg_image'];
        }

        return [
            'bg_image' => $bg_image,
            'classes' => $this->get_classes([
                'group',
                'relative',
                'field:bg_color',
                'field:bg_shadow',
                'field:border_color',
                'field:border_style',
                'field:border_width',
                'field:border_radius',
                'field:transition',
                'field:transition_duration',
                'field:scale',
                !empty($data['scale']) ? 'transform' : '',
                'field:padding',
                'field:margin'
            ]),
            'content_classes' => $this->get_classes([
                'relative',
                'flex-grow',
                'inner-blocks'
            ]),
            'bg_image_classes' => $this->get_classes([
                'inset-0',
                'absolute',
                'field:bg_image_scale',
                'field:bg_image_position',
                'field:bg_image_repeat',
                'field:bg_image_attachment',
                'field:bg_image_opacity'
            ])
        ];
    }
}

Group::make();
