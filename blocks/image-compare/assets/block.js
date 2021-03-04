module.exports = {
    init: function () {
        if (window.spBlocks) {
            window.spBlocks.addInitAction('sp-block-image-compare', this.initBlock);
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
