<?php

namespace Sitepilot\Modules\BeaverBuilder;

use Sitepilot\Module;

/**
 * @property \Sitepilot\Modules\BeaverBuilder\PowerPack $power_pack
 * @property \Sitepilot\Modules\BeaverBuilder\UltimateAddons $ultimate_addons
 */
class BeaverBuilder extends Module
{
    /**
     * The builder admin capability.
     *
     * @var string
     */
    public $admin_settings_cap = 'sp_builder_admin_settings';

    /**
     * Construct the module.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $modules = [
            'power_pack' => \Sitepilot\Modules\BeaverBuilder\PowerPack::class,
            'ultimate_addons' => \Sitepilot\Modules\BeaverBuilder\UltimateAddons::class
        ];

        foreach ($modules as $key => $class) {
            $this->$key = new $class;
        }
    }

    /**
     * Initialize the module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->get_setting('enabled')) {
            return;
        }

        if ($this->get_setting('branding_enabled')) {
            require_once(SITEPILOT_DIR . '/includes/beaver-builder/FLBuilderWhiteLabel.php');

            add_filter('all_plugins', [$this, 'filter_plugins']);
            add_filter('sp_logs_replace_names', function ($replace) {
                return array_merge($replace, [
                    'bb-plugin/fl-builder.php' => $this->get_setting('branding_name'),
                    'bb-theme-builder/bb-theme-builder.php' => $this->get_setting('branding_name') . ' - Themer Add-on'
                ]);
            });
        }

        if ($this->get_setting('remove_default_templates')) {
            add_filter('fl_builder_get_templates', [$this, 'filter_builder_templates'], 99, 2);
        }

        if ($this->get_setting('remove_default_modules')) {
            add_filter('fl_builder_register_module', [$this, 'filter_builder_modules'], 99, 2);
        }

        if ($this->get_setting('filter_admin_settings_cap')) {
            get_role('administrator')->add_cap($this->admin_settings_cap);
            add_filter('fl_builder_admin_settings_capability', function () {
                return $this->admin_settings_cap;
            });
        }
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_beaver_builder_settings', [
            'enabled' => defined("FL_BUILDER_VERSION"),
            'branding_enabled' => apply_filters('sp_beaver_builder_branding_enabled', false),
            'remove_default_templates' => apply_filters('sp_beaver_builder_remove_default_templates', false),
            'remove_default_modules' => apply_filters('sp_beaver_builder_remove_default_modules', false),
            'filter_admin_settings_cap' => apply_filters('sp_beaver_builder_filter_admin_settings_cap', false),
            'branding_name' => apply_filters('sp_beaver_builder_branding_name', sprintf(__('%s Builder', 'sitepilot'), sitepilot()->branding->get_name())),
            'branding_description' => apply_filters('sp_beaver_builder_branding_description', __('A drag and drop frontend page builder plugin that works with almost any theme.', 'sitepilot'))
        ]);
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
            $plugins[$namespace]['Name'] = $this->get_setting('branding_name');
            $plugins[$namespace]['Description'] = $this->get_setting('branding_description');
            $plugins[$namespace]['PluginURI'] = sitepilot()->branding->get_website();
            $plugins[$namespace]['Author'] = sitepilot()->branding->get_name();
            $plugins[$namespace]['AuthorURI'] = sitepilot()->branding->get_website();
            $plugins[$namespace]['Title'] = $this->get_setting('branding_name');
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
