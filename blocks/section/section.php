<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Image;
use Sitepilot\Blocks\Fields\Accordion;
use Sitepilot\Blocks\Fields\TrueFalse;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\Opacity;
use Sitepilot\Blocks\Fields\Style\Padding;
use Sitepilot\Blocks\Fields\Style\MaxWidth;
use Sitepilot\Blocks\Fields\Style\BoxShadow;
use Sitepilot\Blocks\Fields\Style\MinHeight;
use Sitepilot\Blocks\Fields\Preset\ImageSize;
use Sitepilot\Blocks\Fields\Style\BorderColor;
use Sitepilot\Blocks\Fields\Style\BorderStyle;
use Sitepilot\Blocks\Fields\Style\BorderWidth;
use Sitepilot\Blocks\Fields\Style\BorderRadius;
use Sitepilot\Blocks\Fields\Style\VerticalAlign;
use Sitepilot\Blocks\Fields\Style\BackgroundColor;
use Sitepilot\Blocks\Fields\Style\BackgroundImageScale;
use Sitepilot\Blocks\Fields\Style\BackgroundImageRepeat;
use Sitepilot\Blocks\Fields\Style\BackgroundImagePosition;
use Sitepilot\Blocks\Fields\Style\BackgroundImageAttachment;

class Section extends Block
{
    /**
     * Construct the block.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-section',
            'name' => __('Section', 'sitepilot'),
            'supports' => [
                'inner_blocks' => true,
                'full_width' => true,
                'wide_width' => true
            ],
            'default' => [
                'width' => 'full'
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="sp-brand-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 1.5a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 0-1h-13a.5.5 0 0 0-.5.5zm0 13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 0-1h-13a.5.5 0 0 0-.5.5z"/>
                <path d="M2 7a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7z"/>
            </svg>',
            'fields' => [
                Accordion::make('<i class="fas fa-fill-drip"></i> ' . __('Background', 'sitepilot'), 'background')
                    ->fields([
                        BackgroundColor::make('bg_color'),

                        BoxShadow::make('bg_shadow')
                    ]),

                Accordion::make('<i class="fas fa-image"></i>  ' . __('Background Image', 'sitepilot'), 'background_image')
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
                            ->conditional_rule('bg_image', '!=', 'empty'),
                    ]),

                Accordion::make('<i class="fas fa-border-all"></i>  ' . __('Border', 'sitepilot'), 'border')
                    ->fields([
                        BorderColor::make('border_color'),

                        BorderStyle::make('border_style'),

                        BorderWidth::make('border_width'),

                        BorderRadius::make(__('Border Radius', 'sitepilot'), 'border_radius')
                    ]),

                Accordion::make('<i class="fas fa-arrows-alt"></i> ' . __('Spacing', 'sitepilot'), 'spacing')
                    ->fields([
                        MaxWidth::make(__('Content Width', 'sitepilot'), 'content_width')
                            ->default_value(['mobile' => 'container']),

                        MinHeight::make('min_height'),

                        VerticalAlign::make('vertical_align'),

                        Padding::make('padding')
                            ->default_value([
                                'top' => ['mobile' => 8],
                                'bottom' => ['mobile' => 8],
                                'left' => ['mobile' => 8],
                                'right' => ['mobile' => 8]
                            ]),

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
        if ($data['bg_image_featured'] && $featured_image_id = get_post_thumbnail_id()) {
            $image_field = Image::make('', '');
            $bg_image = $image_field->format_value($featured_image_id);
        } else {
            $bg_image = $data['bg_image'];
        }

        return [
            'template' => wp_json_encode([
                ['core/columns']
            ]),
            'allowed_blocks' => wp_json_encode([
                'core/columns'
            ]),
            'classes' => $this->get_classes([
                'relative',
                'field:bg_color',
                'field:bg_shadow',
                'field:border_width',
                'field:border_radius',
                'field:border_color',
                'field:border_style',
                'field:min_height',
                'field:vertical_align',
                'field:padding',
                'field:margin'
            ]),
            'content_classes' => $this->get_classes([
                'relative',
                'flex-grow',
                'inner-blocks',
                'field:content_width'
            ]),
            'bg_image_classes' => $this->get_classes([
                'inset-0',
                'absolute',
                'field:bg_image_scale',
                'field:bg_image_position',
                'field:bg_image_attachment',
                'field:bg_image_repeat',
                'field:bg_image_opacity'
            ]),
            'bg_image' => $bg_image,
        ];
    }
}

Section::make();
