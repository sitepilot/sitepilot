window.spBlocksEditor = {
    pageTemplate: null,
    activeDevice: 'mobile',

    /* Init
    /* ----------------------------------------------- */
    init: function () {
        this.pageTemplateSwitcher();

        if (window.acf) {
            window.acf.addAction('new_field/type=sp_responsive_select', this.initializeResponsiveSelect);
        }
    },

    initializeResponsiveSelect: function (field) {
        window.spBlocksEditor.activeDevice = 'mobile';

        field.$el.find('.sp-responsive-select__variation').each(function () {
            jQuery(this).click(window.spBlocksEditor.toggleResponsiveSelect);
            window.spBlocksEditor.updateResponsiveSelect();
        });
    },

    updateResponsiveSelect: function () {
        jQuery('.sp-responsive-select__variation').each(function () {
            if (jQuery(this).data('device') !== window.spBlocksEditor.activeDevice) {
                jQuery(this).hide();
            } else {
                jQuery(this).show();
            }
        });

        jQuery('.sp-responsive-select__field').each(function () {
            if (jQuery(this).data('device') !== window.spBlocksEditor.activeDevice) {
                jQuery(this).hide();
            } else {
                jQuery(this).show();
            }
        });
    },

    toggleResponsiveSelect: function () {
        if (window.spBlocksEditor.activeDevice == 'mobile') {
            window.spBlocksEditor.activeDevice = 'tablet';
        } else if (window.spBlocksEditor.activeDevice == 'tablet') {
            window.spBlocksEditor.activeDevice = 'desktop';
        } else {
            window.spBlocksEditor.activeDevice = 'mobile';
        }

        window.spBlocksEditor.updateResponsiveSelect();
    },

    /* Template Switcher
    /* ----------------------------------------------- */
    pageTemplateSwitcher: function () {
        wp.data.subscribe(() => {
            let template = wp.data.select('core/editor').getEditedPostAttribute('template');

            if (template !== undefined && template) {
                template = "sp-template-" + template.replace(".php", "");
            }

            if (this.pageTemplate === null) {
                this.pageTemplate = template;
            }

            if (template !== undefined && template !== this.pageTemplate) {
                jQuery(document.body).removeClass(this.pageTemplate);
                jQuery(document.body).addClass(template);

                this.pageTemplate = template;
            }
        });
    }
}

spBlocksEditor.init();