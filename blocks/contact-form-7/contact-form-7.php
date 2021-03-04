<?php

namespace Sitepilot\Theme\Blocks;

use Sitepilot\Blocks\Block;
use Sitepilot\Blocks\Fields\Post;
use Sitepilot\Blocks\Fields\Select;

class ContactForm7 extends Block
{
    /**
     * Construct the block.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-block-cf7',
            'name' => __('Contact Form 7', 'sitepilot'),
            'fields' => [
                Post::make(__('Contact Form', 'sitepilot'), 'contact_form')
                    ->post_types([
                        'wpcf7_contact_form'
                    ]),

                Select::make(__('Button Alignment', 'sitepilot'), 'btn_align')
                    ->options([
                        'default' => '',
                        'btn-left' => __('Left', 'sitepilot'),
                        'btn-center' => __('Center', 'sitepilot'),
                        'btn-right' => __('Right', 'sitepilot'),
                        'btn-full-width' => __('Full Width', 'sitepilot')
                    ]),
            ],
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
            </svg>',
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
        if (is_admin()) {
            $post = get_post($data['contact_form']);

            if ($post) {
                $placeholder = 'Contact Form 7<br /><i>' . $post->post_title . '</i>';
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
                'sp-block-cf7__wrap',
                'field:btn_align'
            ])
        ];
    }

    /**
     * Wether the block should be active or not.
     *
     * @return bool
     */
    public function enabled(): bool
    {
        return defined('WPCF7_VERSION');
    }
}

ContactForm7::make();
