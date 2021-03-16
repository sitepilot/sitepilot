<?php

global $template_name;

get_header();

if (function_exists('sitepilot')) {
    echo sitepilot()->templates->render($template_name);
}

get_footer();
