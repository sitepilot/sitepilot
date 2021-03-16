module.exports = {
    init: function () {
        jQuery('.sp-block-video').each(function () {
            module.exports.initBlock(jQuery(this));
        });

        if (window.acf) {
            window.acf.addAction('render_block_preview/type=sp-block-video', this.initBlock)
        }
    },

    initBlock: function (block) {
        new Plyr(block.find('.sp-block-video__player'));
    }
}
