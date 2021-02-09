<?php

namespace Sitepilot\Blocks;

use Sitepilot\Blocks\Fields\Post;
use Sitepilot\Blocks\Fields\Select;
use Sitepilot\Blocks\Fields\Style\Color;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\FontSize;
use Sitepilot\Blocks\Fields\Style\TextAlign;
use Sitepilot\Blocks\Fields\Style\FontWeight;
use Sitepilot\Blocks\Fields\Preset\StyleGroup;
use Sitepilot\Blocks\Fields\Preset\SpacingGroup;
use Sitepilot\Blocks\Fields\Style\TextTransform;
use Sitepilot\Blocks\Fields\Preset\TypographyGroup;

class ContactForm7 extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-contact-form-7',
            'name' => __('Contact Form 7', 'sitepilot'),
            'supports' => [
                'inner_blocks' => true,
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="fill: #1062fe" class="bi bi-envelope" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
            </svg>'
        ]);
    }

    public function fields(): array
    {
        return [
            Post::make(__('Contact Form', 'sitepilot'), 'contact_form')
                ->post_types([
                    'wpcf7_contact_form'
                ]),

            StyleGroup::make('style_group')->fields([
                Select::make(__('Button Alignment', 'sitepilot'), 'btn_align')
                    ->options([
                        'default' => '',
                        'btn-left' => __('Left', 'sitepilot'),
                        'btn-center' => __('Center', 'sitepilot'),
                        'btn-right' => __('Right', 'sitepilot'),
                        'btn-full-width' => __('Full Width', 'sitepilot')
                    ]),
            ]),

            TypographyGroup::make('text_group')
                ->fields([
                    Color::make('color'),

                    FontSize::make('font_size'),

                    FontWeight::make('font_weight'),

                    TextAlign::make('text_align'),

                    TextTransform::make('text_transform')
                ]),

            SpacingGroup::make('spacing_group')
                ->fields([
                    Margin::make('margin')->default_value(
                        $this->plugin->model->get_block_margin()
                    )
                ]),
        ];
    }

    protected function view_data(array $data): array
    {
        if (is_admin()) {
            $post = get_post($data['contact_form']);

            if ($post) {
                $placeholder = 'Contact Form<br /><i>' . $post->post_title . '</i>';
            } else {
                $placeholder = 'Contact Form 7';
            }

            $form = '<div class="py-48 border border-grey-100 border-dashed text-center">' . $placeholder . '</div>';
        } else {
            $form = do_shortcode('[contact-form-7 id="' . $data['contact_form'] . '"]');
        }

        return [
            'form' => $form,
            'classes' => $this->get_classes([
                'field:color',
                'field:font_size',
                'field:font_weight',
                'field:text_align',
                'field:text_transform',
                'field:margin',
                'field:btn_align',
                !empty($data['color']) ? 'color-inherit' : '',
                !empty($data['font_size']) ? 'font-size-inherit' : '',
                !empty($data['font_weight']) ? 'font-weight-inherit' : '',
                !empty($data['text_align']) ? 'text-align-inherit' : '',
                !empty($data['text_transform']) ? 'text-transform-inherit' : ''
            ])
        ];
    }

    public function enabled(): bool
    {
        return defined('WPCF7_VERSION');
    }
}
