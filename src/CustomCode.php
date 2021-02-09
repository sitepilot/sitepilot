<?php

namespace Sitepilot;

class CustomCode extends Module
{
    /**
     * Construct the custom code module.
     * 
     * @return void
     */
    public function init(): void
    {
        /* Actions */
        if ($this->plugin->model->get_code_wp_head()) {
            add_action('wp_head', [$this, 'action_wp_head']);
        }

        if ($this->plugin->model->get_code_wp_body_open()) {
            add_action('wp_body_open', [$this, 'action_wp_body_open']);
        }

        if ($this->plugin->model->get_code_wp_footer()) {
            add_action('wp_footer', [$this, 'action_wp_footer']);
        }
    }

    /**
     * Echo custom head code.
     *
     * @return void
     */
    public function action_wp_head(): void
    {
        echo "<!-- Sitepilot: Head Code -->\n";
        echo $this->plugin->model->get_code_wp_head() . "\n";
        echo "<!-- Sitepilot: End Head Code -->\n";
    }

    /** 
     * Echo custom body open code.
     * 
     * @return void
     */
    public function action_wp_body_open(): void
    {
        echo "<!-- Sitepilot: Body Code -->\n";
        echo $this->plugin->model->get_code_wp_body_open() . "\n";
        echo "<!-- Sitepilot: End Body Code -->\n";
    }

    /**
     * Echo custom footer code.
     *
     * @return void
     */
    public function action_wp_footer(): void
    {
        echo "<!-- Sitepilot: Footer Code -->\n";
        echo $this->plugin->model->get_code_wp_footer() . "\n";
        echo "<!-- Sitepilot: End Footer Code -->\n";
    }
}
