<?php

namespace Sitepilot\Fields;

use Sitepilot\Support\Img;

class Image extends Field
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$args)
    {
        $this->type = 'image';

        parent::__construct(...$args);
    }

    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function acf_config(string $namespace): array
    {
        return [
            'type' => 'image',
            'return_format' => 'id'
        ];
    }

    /**
     * Format the field's value.
     *
     * @param mixed $value
     * @return mixed
     */
    public function format_value($value)
    {
        return Img::sizes($value, $this->default);
    }
}
