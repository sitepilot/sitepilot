<?php

namespace Sitepilot\Modules;

use Sitepilot\Module;

class PrimaryKeyFixer extends Module
{
    /**
     * Initialize the log module.
     * 
     * @return void
     */
    public function init(): void
    {
        if (!$this->get_setting('enabled')) {
            return;
        }

        /* Actions */
        add_filter('query', [$this, 'filter_query']);
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return apply_filters('sp_primary_key_fixer_settings', [
            'enabled' => sitepilot()->model()->is_sitepilot_platform()
        ]);
    }

    /**
     * Add primary key (if not exist) when creating new tables.
     * 
     * @return void
     */
    public function filter_query($query)
    {
        $pattern = '/(?>UNIQUE\\s+KEY)\\s+(?>[\\w]+)\\s+\\((?<field>[\\w]+)\\)/im';
        $replacement = 'PRIMARY KEY ($1), \\0';
        $search = strtolower($query);

        if (
            strpos($search, "create table") !== false &&
            strpos($search, "primary key") === false &&
            strpos($search, "unique key")  !== false
        ) {
            $query = preg_replace($pattern, $replacement, $query, 1);
            error_log("FIXED PRIMARY KEY IN QUERY\n$query");
        }

        return $query;
    }
}
