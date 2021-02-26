<?php

namespace Sitepilot\Extension\Acf\Fields;

class ResponsiveSelect extends \acf_field
{
    /**
     * Construct field.
     *
     * @param array $settings
     * @return void
     */
    function __construct(array $settings)
    {
        $this->name = 'sp_responsive_select';
        $this->label = __('Responsive Select', 'sitepilot');
        $this->category = 'basic';
        $this->settings = $settings;

        $this->defaults = array(
			'default_values' => []
		);
     
        parent::__construct();
    }

    /**
     * Render field html.
     *
     * @param array $field
     * @return void
     */
    function render_field(array $field): void
    {
        $field = array_merge([
            'fields' => [],
            'variations' => []
        ], $field);

        $blade = sitepilot()->blade();

        echo $blade->make('acf/responsive-select', $field)->render();
    }
}
