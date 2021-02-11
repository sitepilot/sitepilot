<?php

namespace Sitepilot\Blocks;

use Sitepilot\Blocks\Fields\Style\Color;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\Padding;
use Sitepilot\Blocks\Fields\Style\FontSize;
use Sitepilot\Blocks\Fields\Style\BoxShadow;
use Sitepilot\Blocks\Fields\Style\TextAlign;
use Sitepilot\Blocks\Fields\Style\FontWeight;
use Sitepilot\Blocks\Fields\Style\BorderColor;
use Sitepilot\Blocks\Fields\Style\BorderStyle;
use Sitepilot\Blocks\Fields\Style\BorderWidth;
use Sitepilot\Blocks\Fields\Preset\BorderGroup;
use Sitepilot\Blocks\Fields\Style\BorderRadius;
use Sitepilot\Blocks\Fields\Preset\SpacingGroup;
use Sitepilot\Blocks\Fields\Style\TextTransform;
use Sitepilot\Blocks\Fields\Style\BackgroundColor;
use Sitepilot\Blocks\Fields\Preset\BackgroundGroup;
use Sitepilot\Blocks\Fields\Preset\TypographyGroup;

class PostContent extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-post-content',
            'name' => __('Post Content', 'sitepilot'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="sp-brand-fill" viewBox="0 0 16 16">
                <path d="M1.5 2.5A1.5 1.5 0 0 1 3 1h10a1.5 1.5 0 0 1 1.5 1.5v3.563a2 2 0 0 1 0 3.874V13.5A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5V9.937a2 2 0 0 1 0-3.874V2.5zm1 3.563a2 2 0 0 1 0 3.874V13.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V9.937a2 2 0 0 1 0-3.874V2.5A.5.5 0 0 0 13 2H3a.5.5 0 0 0-.5.5v3.563zM2 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                <path d="M11.434 4H4.566L4.5 5.994h.386c.21-1.252.612-1.446 2.173-1.495l.343-.011v6.343c0 .537-.116.665-1.049.748V12h3.294v-.421c-.938-.083-1.054-.21-1.054-.748V4.488l.348.01c1.56.05 1.963.244 2.173 1.496h.386L11.434 4z"/>
            </svg>',
            'post_types' => [
                'sp-template'
            ]
        ]);
    }

    public function fields(): array
    {
        return [
            TypographyGroup::make('text_group')
                ->fields([
                    Color::make('color'),

                    FontSize::make('font_size'),

                    FontWeight::make('font_weight'),

                    TextAlign::make('text_align'),

                    TextTransform::make('text_transform')
                ]),

            BackgroundGroup::make('bg_group')
                ->fields([
                    BackgroundColor::make('bg_color'),

                    BoxShadow::make('bg_shadow')
                ]),

            BorderGroup::make('border_group')
                ->fields([
                    BorderColor::make('border_color'),

                    BorderStyle::make('border_style'),

                    BorderWidth::make('border_width'),

                    BorderRadius::make(__('Border Radius', 'sitepilot'), 'border_radius')
                ]),

            SpacingGroup::make('spacing_group')
                ->fields([
                    Padding::make('padding'),

                    Margin::make('margin')->default_value(
                        sitepilot()->model->get_block_margin()
                    )
                ]),
        ];
    }

    protected function view_data(array $data): array
    {
        if (is_admin()) {
            $post_content = '<p>This is a preview. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla venenatis et libero ut tincidunt. Aliquam sit amet lorem porta, molestie nisl nec, sagittis eros. Sed ultricies rhoncus placerat. Fusce dignissim lorem lectus, sit amet lacinia ligula pellentesque in. Curabitur quis massa eget nibh volutpat ultrices at feugiat ipsum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Morbi imperdiet velit eu risus congue fermentum. Donec et ultricies neque. Pellentesque placerat, ante a dapibus tincidunt, elit est pretium tortor, et pulvinar erat nisi et tortor. Donec eget odio mollis, interdum sapien in, efficitur purus. Suspendisse aliquam ante metus, ac gravida lectus commodo ac. Nam ex quam, condimentum scelerisque nisi et, lobortis dapibus ex. In posuere tristique neque nec convallis. Maecenas vel magna condimentum, bibendum dui non, feugiat magna. Quisque imperdiet dapibus libero, quis auctor velit maximus vitae.</p>';
            $post_content .= '<p>Aenean leo ante, commodo ac urna non, tincidunt vestibulum enim. Ut enim ligula, porttitor eu magna sed, consequat posuere est. Nunc porta tincidunt tristique. Mauris hendrerit nec sapien id hendrerit. Nullam eu odio sit amet neque porta sodales. Duis vitae tellus a ipsum finibus pulvinar nec vel eros. Sed interdum urna id molestie pellentesque. Sed a commodo enim. Mauris dolor justo, tristique in tincidunt quis, facilisis at neque. Duis non pretium ipsum, in consequat eros. Sed cursus volutpat enim ut cursus. Aenean a mollis arcu. Maecenas eget tortor mattis, fringilla nisl sed, porta quam. Pellentesque felis urna, molestie ut diam eu, eleifend ultrices massa. Pellentesque nec quam quis metus sagittis sagittis aliquam et velit. Donec venenatis accumsan gravida.</p>';
        } else {
            $post_content = get_the_content();
        }

        return [
            'post_content' => $post_content,
            'classes' => $this->get_classes([
                'inner-blocks',
                'field:color',
                'field:font_size',
                'field:font_weight',
                'field:text_align',
                'field:text_transform',
                'field:bg_color',
                'field:bg_shadow',
                'field:border_color',
                'field:border_style',
                'field:border_width',
                'field:border_radius',
                'field:margin',
                'field:padding',
                !empty($data['color']) ? 'color-inherit' : '',
                !empty($data['font_size']) ? 'font-size-inherit' : '',
                !empty($data['font_weight']) ? 'font-weight-inherit' : '',
                !empty($data['text_align']) ? 'text-align-inherit' : '',
                !empty($data['text_transform']) ? 'text-transform-inherit' : ''
            ])
        ];
    }
}

PostContent::make();
