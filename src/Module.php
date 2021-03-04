<?php

namespace Sitepilot;

abstract class Module
{
    /**
     * The module init priority.
     *
     * @var int
     */
    protected $priority = 10;

    /**
     * Construct the module.
     * 
     * @return void
     */
    public function __construct()
    {
        add_action('sitepilot_init', [$this, 'init'], $this->priority);
    }

    abstract public function init(): void;
}
