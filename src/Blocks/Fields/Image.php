<?php

namespace Sitepilot\Blocks\Fields;

class Image extends Field
{
    /**
     * Returns the field's ACF config.
     *
     * @return array
     */
    protected function get_acf_config(string $namespace): array
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
        if ($value && $image['full'] = wp_get_attachment_url($value)) {
            foreach (get_intermediate_image_sizes() as $size) {
                $image[$size] = wp_get_attachment_image_url($value, $size);
            }

            $image['html'] = '<img src="' . $image['full'] . '" srcset="' . wp_get_attachment_image_srcset($value) . '" />';

            return $image;
        } elseif ($this->default) {
            $image['full'] = $this->default;

            foreach (get_intermediate_image_sizes() as $size) {
                $image[$size] = $image['full'];
            }

            $image['html'] = '<img src="' . $image['full'] . '" />';

            return $image;
        }

        return null;
    }
}
