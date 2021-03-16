<?php

global $template_name;

get_header();

?>

<div class="sp-template__full-width">
    <?php
    if (function_exists('sitepilot')) {
        the_content();
    }
    ?>
</div>

<?php
get_footer();
