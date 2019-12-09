<?php

use Sitepilot\Model;

if (!class_exists('FLBuilderWhiteLabel')) {

    /**
     * Whitelabel class for the builder.
     *
     * @since 1.0.13
     */
    class FLBuilderWhiteLabel
    {
        /**
         * Disable the help button in the builder.
         *
         * @since 1.0.13
         * @return array $help_button
         */
        public static function get_help_button_settings()
        {
            $help_button['enabled'] = false;
            return $help_button;
        }

        /**
         * Return the name of the builder.
         *
         * @since 1.0.13
         * @return string $name
         */
        public static function get_branding()
        {
            return Model::get_branding_name() . " Builder";
        }

        /**
         * Return the branding icon url.
         *
         * @since 1.0.13
         * @return string $icon_url
         */
        public static function get_branding_icon()
        {
            return Model::get_branding_icon();
        }

        /**
         * Returns that the builder is white labeled.
         *
         * @since 2.0.8
         * @return bool
         */
        public static function is_white_labeled()
        {
            return true;
        }
    }
}
