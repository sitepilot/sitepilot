<div class="wrap <?php Sitepilot\Settings::render_page_class(); ?>">

	<h1 class="sp-settings-heading">
		<?php Sitepilot\Settings::render_page_heading(); ?>
	</h1>

	<?php Sitepilot\Settings::render_update_message(); ?>

	<div class="sp-settings-nav">
		<ul>
			<?php Sitepilot\Settings::render_nav_items(); ?>
		</ul>
	</div>

	<div class="sp-settings-content">
		<?php Sitepilot\Settings::render_forms(); ?>
	</div>
</div>
