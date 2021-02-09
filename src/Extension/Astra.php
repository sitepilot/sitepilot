<?php

namespace Sitepilot\Extension;

use Sitepilot\Module;

class Astra extends Module
{
    /**
     * Construct the Astra extension.
     * 
     * @return void
     */
    public function init(): void
    {
        add_action('after_setup_theme', function () {
            if (!$this->is_active()) {
                return;
            }

            if (apply_filters('sp_astra_branding', false)) {
                add_filter('astra_addon_get_white_labels', [$this, 'filter_branding_options'], 99);
                add_filter('sp_log_replace_names', function ($replace) {
                    return array_merge($replace, [
                        'astra' => $this->get_branding_name(),
                    ]);
                });
            }
        });
    }

    /**
     * Check if Astra theme is active.
     *
     * @return bool
     */
    public function is_active(): bool
    {
        return defined("ASTRA_THEME_VERSION");
    }

    /**
     * Returns the branding name.
     * 
     * @return string
     */
    public function get_branding_name(): string
    {
        return apply_filters('sp_astra_branding_name', sprintf(__('%s Theme', 'sitepilot'), $this->plugin->branding->get_name()));
    }

    /**
     * Returns the branding description.
     * 
     * @return string
     */
    public function get_branding_description(): string
    {
        return apply_filters('sp_astra_branding_description', 'Base theme used for website development.');
    }

    /**
     * Filter branding options.
     *
     * @param array $branding
     * @return array $branding
     */
    public function filter_branding_options(array $branding): array
    {
        if (isset($branding['astra-agency'])) {
            $branding['astra-agency']['author'] = $this->plugin->branding->get_name();
            $branding['astra-agency']['author_url'] = $this->plugin->branding->get_website();
            $branding['astra-agency']['hide_branding'] = true;
        }

        if (isset($branding['astra'])) {
            $branding['astra']['name'] = $this->get_branding_name();
            $branding['astra']['description'] = $this->get_branding_description();
            $branding['astra']['screenshot'] = $this->plugin->branding->get_screenshot();
        }

        return $branding;
    }
}
