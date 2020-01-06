<?php

namespace Sitepilot;

use Sitepilot\Model;

final class Shortcodes
{
    /**
     * Initialize shortcodes.
     *
     * @return void
     */
    static public function init()
    {
        /* Shortcodes */
        add_shortcode('sp_domain', __CLASS__ . '::domain');
        add_shortcode('sp_branding_name', __CLASS__ . '::branding_name');
        add_shortcode('sp_branding_logo', __CLASS__ . '::branding_logo');
    }

    /**
     * Domain shortcode.
     * 
     * @return string
     */
    static public function domain()
    {
        return get_bloginfo('url');
    }

    /**
     * Branding name shortcode.
     *
     * @return string
     */
    static public function branding_name()
    {
        return Model::get_branding_name();
    }

    /**
     * Branding logo shortcode.
     *
     * @return string
     */
    static public function branding_logo()
    {
        return Model::get_branding_logo();
    }
}
