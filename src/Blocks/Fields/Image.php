<?php

namespace Sitepilot\Blocks\Fields;

class Image extends Field
{
    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
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
            $image['full'] = wp_get_attachment_url($value);

            foreach (get_intermediate_image_sizes() as $size) {
                $image[$size] = wp_get_attachment_image_url($value, $size);
            }

            return $image;
        }

        return null;
    }
}
