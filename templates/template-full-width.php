<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wp_query;
global $blocks_template_query;

get_header();

if ($blocks_template_query) $wp_query = $blocks_template_query;

?>

<div class="sp-template-full-width__content">
    <?php
    do_action('sp_blocks_template_before_content');

    while (have_posts()) : the_post();
        the_content();
    endwhile;

    do_action('sp_blocks_template_after_content');
    ?>
</div>

<?php

wp_reset_query();

get_footer();
