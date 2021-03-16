<?php

namespace Sitepilot;

abstract class Module
{
    /**
     * The module's init priority.
     *
     * @var int
     */
    protected $priority = 10;

    /**
     * The module's settings cache.
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Create a new module instance.
     * 
     * @return void
     */
    public function __construct()
    {
        add_action('sitepilot_init', [$this, 'init'], $this->priority);
    }

    /**
     * Initialize the module.
     *
     * @return void
     */
    abstract public function init(): void;

    /**
     * Returns a module setting by key.
     *
     * @return mixed
     */
    public function get_setting($key, $default = null)
    {
        if (!$this->settings) {
            $this->settings = $this->settings();
        }

        return $this->settings[$key] ?? $default;
    }

    /**
     * Returns the module's settings.
     *
     * @return array
     */
    protected function settings(): array
    {
        return [];
    }
}
