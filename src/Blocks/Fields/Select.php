<?php

namespace Sitepilot\Blocks\Fields;

class Select extends Field
{
    /**
     * The field's options.
     *
     * @var array
     */
    private $options;

    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
    {
        return [
            'type' => 'select',
            'ui' => 0,
            'choices' => $this->options,
            'default_value' => null
        ];
    }

    /**
     * Set the selectable options.
     *
     * @param array $options
     * @return self
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
