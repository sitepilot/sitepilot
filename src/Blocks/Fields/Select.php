<?php

namespace Sitepilot\Blocks\Fields;

class Select extends Field
{
    /**
     * Holds the field's options.
     *
     * @var array
     */
    public array $options = [];

    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function get_acf_config(string $namespace): array
    {
        return [
            'type' => 'select',
            'choices' => $this->options
        ];
    }

    /**
     * Set the field's options.
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
