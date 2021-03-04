<?php

namespace Sitepilot\Extension;

use Sitepilot\Module;

class BeaverBuilder extends Module
{
    /**
     * The builder admin capability.
     *
     * @var string
     */
    public $admin_settings_cap = 'sp_builder_admin_settings';

    /**
     * Construct the Beaver Builder extension.
     * 
     * @return void
     */
    public function init(): void
    {
        add_action('after_setup_theme', function () {
            if (!$this->is_active()) {
                return;
            }

            if (apply_filters('sp_beaver_builder_branding', false)) {
                require_once(SITEPILOT_DIR . '/includes/FLBuilderWhiteLabel.php');

                add_filter('all_plugins', [$this, 'filter_plugins']);
                add_filter('sp_log_replace_names', function ($replace) {
                    return array_merge($replace, [
                        'bb-plugin/fl-builder.php' => $this->get_branding_name(),
                        'bb-theme-builder/bb-theme-builder.php' => $this->get_branding_name() . ' - Themer Add-on'
                    ]);
                });
            }

            if (apply_filters('sp_beaver_builder_remove_default_templates', false)) {
                add_filter('fl_builder_get_templates', [$this, 'filter_builder_templates'], 99, 2);
            }

            if (apply_filters('sp_beaver_builder_remove_default_modules', false)) {
                add_filter('fl_builder_register_module', [$this, 'filter_builder_modules'], 99, 2);
            }

            if (apply_filters('sp_beaver_builder_filter_admin_settings_cap', false)) {
                get_role('administrator')->add_cap($this->admin_settings_cap);
                add_filter('fl_builder_admin_settings_capability', function () {
                    return $this->admin_settings_cap;
                });
            }
        });
    }

    /**
     * Check if Beaver Builder plugin is active.
     *
     * @return bool
     */
    public function is_active(): bool
    {
        return defined("FL_BUILDER_VERSION");
    }

    /**
     * Returns the branding name.
     * 
     * @return string
     */
    public function get_branding_name(): string
    {
        return apply_filters('sp_beaver_builder_branding_name', sprintf(__('%s Builder', 'sitepilot'), sitepilot()->branding->get_name()));
    }

    /**
     * Returns the branding description.
     * 
     * @return string
     */
    public function get_branding_description(): string
    {
        return apply_filters('sp_beaver_builder_branding_description', __('A drag and drop frontend page builder plugin that works with almost any theme.', 'sitepilot'));
    }

    /**
     * Filter builder branding in plugins list.
     *
     * @param array $plugins
     * @return array
     */
    public function filter_plugins(array $plugins): array
    {
        $namespace = 'bb-plugin/fl-builder.php';

        if (isset($plugins[$namespace])) {
            $plugins[$namespace]['Name'] = $this->get_branding_name();
            $plugins[$namespace]['Description'] = $this->get_branding_description();
            $plugins[$namespace]['PluginURI'] = sitepilot()->branding->get_website();
            $plugins[$namespace]['Author'] = sitepilot()->branding->get_name();
            $plugins[$namespace]['AuthorURI'] = sitepilot()->branding->get_website();
            $plugins[$namespace]['Title'] = $this->get_branding_name();
            $plugins[$namespace]['AuthorName'] = sitepilot()->branding->get_name();
        }

        return $plugins;
    }

    /**
     * Remove default modules.
     *
     * @param bool $enabled
     * @param object $instance
     * @return bool
     */
    public function filter_builder_modules($enabled, $instance): bool
    {
        $class = get_class($instance);
        $prefix = substr($class, 0, 2);

        if ($prefix == 'FL') {
            return false;
        }

        return $enabled;
    }

    /**
     * Remove default templates.
     *
     * @param array $data
     * @return array
     */
    public function filter_builder_templates($data): array
    {
        $return = [];

        foreach ($data as $item) {
            if (isset($item->image) && strpos($item->image, "demos.wpbeaverbuilder.com") === false) {
                $return[] = $item;
            }
        }

        return $return;
    }
}
