<div id="sp-<?= $class::$module ?>-form" class="sp-settings-form">

    <form class="sp-settings-form-content" action="<?php Sitepilot\Settings::render_form_action($class::$module); ?>" method="post">

        <h2 class="sp-settings-form-header"><?= sprintf(__('%s Settings', 'sitepilot'), $class::$name) ?></h2>
        <p><?= $class::$description ?></p>

        <hr />

        <?php
        $enabled_settings = $class::get_enabled_settings();
        $checked = $class::get_checkbox_count() == count($enabled_settings) ? 'checked' : '';
        ?>

        <?php if ($class::get_checkbox_count() > 0) : ?>
            <p>
                <label>
                    <input class="sp-<?= $class::$module ?>-all-cb" type="checkbox" name="sp-<?= $class::$module ?>-enabled[]" value="all" <?php echo $checked; ?> />
                    <?php _ex('All', 'sitepilot'); ?>
                </label>
            </p>
        <?php endif; ?>

        <?php foreach ($class::get_fields() as $key => $setting) : ?>
            <?php if (!isset($setting['active']) || $setting['active']) : ?>
                <p>
                    <?php if ($setting['type'] == 'checkbox') : ?>
                        <label>
                            <?php if (has_filter('sp_' . $class::$module . '_enabled_setting_' . $key)) : ?>
                                <input class="sp-<?= $class::$module ?>-cb" type="checkbox" name="sp-<?= $class::$module ?>-fake[]" value="<?= $key ?>" <?php echo $class::is_setting_enabled($key) ? 'checked' : ''; ?> disabled />
                                <input type="hidden" name="sp-<?= $class::$module ?>-enabled[]" value="<?= $key ?>" />
                            <?php else : ?>
                                <input class="sp-<?= $class::$module ?>-cb" type="checkbox" name="sp-<?= $class::$module ?>-enabled[]" value="<?= $key ?>" <?php echo $class::is_setting_enabled($key) ? 'checked' : ''; ?> <?= has_filter('sp_' . $class::$module . '_setting_enabled_' . $key) ? 'disabled' : '' ?> />
                            <?php endif ?>
                            <?= $setting['label'] ?>
                            <?php if (isset($setting['help']) && !empty($setting['help'])) : ?> <i class="dashicons dashicons-editor-help" title="<?= $setting['help'] ?>"></i><?php endif; ?>
                        </label>
                    <?php elseif ($setting['type'] == 'text') : ?>
                        <p style="margin-bottom: 5px;"><?= $setting['label'] ?><?php if (isset($setting['help']) && !empty($setting['help'])) : ?> <i class="dashicons dashicons-editor-help" title="<?= $setting['help'] ?>"></i><?php endif; ?></p>
                        <input type="text" name="sp-<?= $class::$module ?>[<?= $key ?>]" value="<?= $class::get_setting($key, $setting['default']) ?>" class="regular-text" <?= has_filter('sp_' . $class::$module . '_setting_' . $key) ? 'readonly' : '' ?> />
                    <?php elseif ($setting['type'] == 'textarea') : ?>
                        <p style="margin-bottom: 5px;"><?= $setting['label'] ?><?php if (isset($setting['help']) && !empty($setting['help'])) : ?> <i class="dashicons dashicons-editor-help" title="<?= $setting['help'] ?>"></i><?php endif; ?></p>
                        <textarea name="sp-<?= $class::$module ?>[<?= $key ?>]" class="regular-text" rows="6" style="width: 100%;" <?= has_filter('sp_' . $class::$module . '_setting_' . $key) ? 'readonly' : '' ?>><?= $class::get_setting($key, $setting['default']) ?></textarea>
                    <?php elseif ($setting['type'] == 'separator') : ?>
                        <hr />
                    <?php elseif ($setting['type'] == 'category') : ?>
                        <h4 style="margin-top: 25px;"><?= $setting['label'] ?></h4>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        <?php endforeach; ?>

        <p class="submit">
            <input type="submit" name="update" class="button-primary" value="<?php esc_attr_e('Save Settings', 'sitepilot'); ?>" />
            <?php wp_nonce_field($class::$module, 'sp-' . $class::$module . '-nonce'); ?>
        </p>
    </form>

</div>