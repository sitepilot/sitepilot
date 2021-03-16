module.exports = {
    init: function () {
        jQuery('.sp-block-image-compare').each(function () {
            module.exports.initBlock(jQuery(this));
        });

        if (window.acf) {
            window.acf.addAction('render_block_preview/type=sp-block-image-compare', this.initBlock)
        }
    },

    initBlock: function (block) {
        setTimeout(function () {
            let wrap = block.find('.sp-block-image-compare__wrap');

            wrap.twentytwenty({
                after_label: 'Na',
                before_label: 'Voor'
            });
        }, 250);
    }
}
