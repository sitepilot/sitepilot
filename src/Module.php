<?php

namespace Sitepilot;

abstract class Module
{
    /**
     * The plugin instance.
     */
    protected Plugin $plugin;

    /**
     * Construct the module.
     * 
     * @return void
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;

        add_action('sitepilot_init', [$this, 'init']);
    }

    abstract public function init(): void;
}
