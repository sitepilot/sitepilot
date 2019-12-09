<div id="sp-modules-form" class="sp-settings-form">

	<h3 class="sp-settings-form-header"><?php _e('Modules', 'sitepilot'); ?></h3>

	<form id="modules-form" action="<?php Sitepilot\Settings::render_form_action('modules'); ?>" method="post">

		<?php if (Sitepilot\Model::is_multisite() && !is_network_admin()) : ?>
			<label>
				<input class="sp-override-ms-cb" type="checkbox" name="sp-override-ms" value="1" <?php echo (get_option('_sp_enabled_modules')) ? 'checked="checked"' : ''; ?> />
				<?php _e('Override network settings?', 'sitepilot'); ?>
			</label>
		<?php endif; ?>

		<div class="sp-settings-form-content">
			<p><?php _e('Check or uncheck modules below to enable or disable them.', 'sitepilot'); ?></p>

			<?php
			$enabled_modules = Sitepilot\Model::get_enabled_modules();
			$checked         = in_array('all', $enabled_modules) ? 'checked' : '';
			?>
			<p>
				<label>
					<input class="sp-module-all-cb" type="checkbox" name="sp-modules[]" value="all" <?php echo $checked; ?> />
					<?php _ex('All', 'Plugin setup page: Modules.', 'sitepilot'); ?>
				</label>
			</p>
			<p>
				<label>
					<input class="sp-module-cb" type="checkbox" name="sp-modules[]" value="branding" <?php echo in_array('branding', $enabled_modules) ? 'checked' : ''; ?> />
					<?php _e('Branding', 'sitepilot'); ?>
				</label>
			</p>
			<p>
				<label>
					<input class="sp-module-cb" type="checkbox" name="sp-modules[]" value="cleanup" <?php echo in_array('cleanup', $enabled_modules) ? 'checked' : ''; ?> />
					<?php _e('Cleanup', 'sitepilot'); ?>
				</label>
			</p>
			<p>
				<label>
					<input class="sp-module-cb" type="checkbox" name="sp-modules[]" value="client-role" <?php echo in_array('client-role', $enabled_modules) ? 'checked' : ''; ?> />
					<?php _e('Client Role', 'sitepilot'); ?>
				</label>
			</p>
			<p>
				<label>
					<input class="sp-module-cb" type="checkbox" name="sp-modules[]" value="menu" <?php echo in_array('menu', $enabled_modules) ? 'checked' : ''; ?> />
					<?php _e('Menu', 'sitepilot'); ?>
				</label>
			</p>
			<p>
				<label>
					<input class="sp-module-cb" type="checkbox" name="sp-modules[]" value="support" <?php echo in_array('support', $enabled_modules) ? 'checked' : ''; ?> />
					<?php _e('Support', 'sitepilot'); ?>
				</label>
			</p>

			<?php if (Sitepilot\Modules\ThemeBeaverBuilder::is_active()) : ?>
				<p>
					<label>
						<input class="sp-module-cb" type="checkbox" name="sp-modules[]" value="theme-beaver-builder" <?php echo in_array('theme-beaver-builder', $enabled_modules) ? 'checked' : ''; ?> />
						<?php _e('Beaver Builder Theme', 'sitepilot'); ?>
					</label>
				</p>
			<?php endif; ?>

			<?php if (Sitepilot\Modules\PluginBeaverBuilder::is_active()) : ?>
				<p>
					<label>
						<input class="sp-module-cb" type="checkbox" name="sp-modules[]" value="plugin-beaver-builder" <?php echo in_array('plugin-beaver-builder', $enabled_modules) ? 'checked' : ''; ?> />
						<?php _e('Beaver Builder Plugin', 'sitepilot'); ?>
					</label>
				</p>
			<?php endif; ?>

			<?php if (Sitepilot\Modules\ThemeAstra::is_active()) : ?>
				<p>
					<label>
						<input class="sp-module-cb" type="checkbox" name="sp-modules[]" value="theme-astra" <?php echo in_array('theme-astra', $enabled_modules) ? 'checked' : ''; ?> />
						<?php _e('Astra Theme', 'sitepilot'); ?>
					</label>
				</p>
			<?php endif; ?>

		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e('Save Modules', 'sitepilot'); ?>" />
			<?php wp_nonce_field('modules', 'sp-modules-nonce'); ?>
		</p>
	</form>
</div>