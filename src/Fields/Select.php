<?php

namespace Sitepilot\Fields;

/**
 * @property array $options
 */
class Select extends Field
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$arguments)
    {
        $this->type = 'select';
        $this->fillable = array_merge($this->fillable, ['options']);
        $this->attributes = array_merge($this->attributes, ['options' => []]);

        parent::__construct(...$arguments);
    }

    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function acf_config(string $namespace): array
    {
        return [
            'type' => 'select',
            'choices' => $this->options
        ];
    }
}
