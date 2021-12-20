module.exports = {
    pageTemplate: null,

    /* Init
    /* ----------------------------------------------- */
    init: function () {
        this.pageTemplateSwitcher();
    },

    /* Template Switcher
    /* ----------------------------------------------- */
    pageTemplateSwitcher: function () {
        wp.data.subscribe(() => {
            let template = wp.data.select('core/editor').getEditedPostAttribute('template');

            if (template !== undefined && template) {
                template = "page-template-" + template.replace(".php", "");
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

module.exports.init();
