<?php

namespace Sitepilot\Blocks\Fields;

class Post extends Field
{
    /**
     * The selectable post types.
     *
     * @var array
     */
    protected $post_types = [];

    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
    {
        return [
            'type' => 'post_object',
            'post_type' => $this->post_types,
            'return_format' => 'id'
        ];
    }

    /**
     * Set the selectable post types.
     *
     * @param array $post_types
     * @return self
     */
    public function post_types(array $post_types): self
    {
        $this->post_types = $post_types;

        return $this;
    }
}
