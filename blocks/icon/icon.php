<?php

namespace Sitepilot\Blocks;

use Sitepilot\Blocks\Fields\Text;
use Sitepilot\Blocks\Fields\Style\Color;
use Sitepilot\Blocks\Fields\Style\Margin;
use Sitepilot\Blocks\Fields\Style\FontSize;
use Sitepilot\Blocks\Fields\Style\TextAlign;
use Sitepilot\Blocks\Fields\Preset\StyleGroup;
use Sitepilot\Blocks\Fields\Preset\SpacingGroup;

class Icon extends Block
{
    public function __construct()
    {
        parent::__construct([
            'slug' => 'sp-icon',
            'name' => __('Icon', 'sitepilot'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="sp-brand-fill" viewBox="0 0 16 16">
                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
            </svg>'
        ]);
    }

    public function fields(): array
    {
        return [
            Text::make(__('Icon Class', 'sitepilot'), 'icon')
                ->description(sprintf(__('You can search for icons %shere%s. Copy and paste the class names between the \'class\' tag.', 'sitepilot'), '<a href="https://fontawesome.com/icons?m=free" target="_blank">', '</a>'))
                ->default_value('far fa-star'),

            StyleGroup::make('style_group')
                ->fields([
                    Color::make('color'),

                    FontSize::make(__('Size', 'sitepilot'), 'font_size'),

                    TextAlign::make(__('Align', 'sitepilot'), 'text_align')
                ]),

            SpacingGroup::make('spacing_group')
                ->fields([
                    Margin::make('margin')->default_value(
                        sitepilot()->model->get_block_margin()
                    )
                ]),
        ];
    }

    protected function view_data(array $data): array
    {
        return [
            'classes' => $this->get_classes([
                'field:color',
                'field:font_size',
                'field:text_align',
                'field:margin'
            ])
        ];
    }

    /**
     * Enqueue block styles and scripts.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        parent::enqueue_assets();

        /* Enqueue Scripts */
        wp_enqueue_script('font-awesome-5');
    }
}

Icon::make();
