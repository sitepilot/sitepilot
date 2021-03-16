<?php

namespace Sitepilot\Fields;

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
        if ($value) {
            if ($image['full'] = wp_get_attachment_url($value)) {
                foreach (get_intermediate_image_sizes() as $size) {
                    $image[$size] = wp_get_attachment_image_url($value, $size);
                }

                return $image;
            }
        } elseif ($this->default) {
            $image['full'] = $this->default;

            foreach (get_intermediate_image_sizes() as $size) {
                $image[$size] = $image['full'];
            }

            return $image;
        }

        return null;
    }
}
