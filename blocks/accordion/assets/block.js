module.exports = {
    init: function () {
        if (window.spBlocks) {
            window.spBlocks.addInitAction('sp-block-accordion', this.initBlock)
        }
    },

    initBlock: function (block) {
        block.find('.sp-block-accordion__title-wrap').click(function () {
            let tab = jQuery(this).parent();

            if (tab.attr('data-state') == "open") {
                module.exports.closeTab(tab);
            } else {
                block.find('[data-state="open"]').each(function () {
                    module.exports.closeTab(jQuery(this));
                });

                module.exports.openTab(tab);
            }
        });
    },

    openTab: function (tab) {
        let icon = tab.find('.sp-block-accordion__icon i');
        let content = tab.find('.sp-block-accordion__content');

        content.slideDown();
        tab.attr('data-state', 'open');

        if (icon) {
            icon.addClass('fa-minus');
            icon.removeClass('fa-plus');
        }
    },

    closeTab: function (tab) {
        let icon = tab.find('.sp-block-accordion__icon i');
        let content = tab.find('.sp-block-accordion__content');

        content.slideUp();
        tab.attr('data-state', 'closed');

        if (icon) {
            icon.addClass('fa-plus');
            icon.removeClass('fa-minus');
        }
    }
}
