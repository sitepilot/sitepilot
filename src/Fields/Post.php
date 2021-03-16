<?php

namespace Sitepilot\Fields;

/**
 * @property array $post_types
 */
class Post extends Field
{
    /**
     * Create a new field instance.
     * 
     * @return void
     */
    public function __construct(...$args)
    {
        $this->type = 'post';

        $this->fillable = array_merge($this->fillable, ['post_types']);
        $this->attributes = array_merge($this->attributes, ['post_types' => []]);

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
            'type' => 'post_object',
            'post_type' => $this->post_types,
            'return_format' => 'id'
        ];
    }
}
