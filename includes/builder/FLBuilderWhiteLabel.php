<?php

use Sitepilot\Modules\Branding;

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
         * @return string $name
         */
        public static function get_branding()
        {
            return Branding::get_name() . " Builder";
        }

        /**
         * Return the branding icon url.
         *
         * @return string $icon_url
         */
        public static function get_branding_icon()
        {
            return Branding::get_icon();
        }

        /**
         * Returns that the builder is white labeled.
         *
         * @return bool
         */
        public static function is_white_labeled()
        {
            return true;
        }
    }
}
