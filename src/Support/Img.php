<?php

namespace Sitepilot\Support;

class Img
{
    /**
     * Returns image sizes by image ID.
     *
     * @param int $image_id
     * @return array|null
     */
    public static function sizes($image_id, $default = null): ?array
    {
        if ($image['full'] = wp_get_attachment_url($image_id)) {
            foreach (get_intermediate_image_sizes() as $size) {
                $image[$size] = wp_get_attachment_image_url($image_id, $size);
            }

            $image['html'] = '<img src="' . $image['full'] . '" srcset="' . wp_get_attachment_image_srcset($image_id) . '" />';

            return $image;
        } elseif (filter_var($default, FILTER_VALIDATE_URL)) {
            $image['full'] = $default;

            foreach (get_intermediate_image_sizes() as $size) {
                $image[$size] = $image['full'];
            }

            $image['html'] = '<img src="' . $image['full'] . '" />';

            return $image;
        }

        return null;
    }
}
