module.exports = {
    init: function () {
        if (window.spBlocks) {
            window.spBlocks.addInitAction('sp-block-video', this.initBlock);
        }
    },

    initBlock: function (block) {
        new Plyr(block.find('.sp-block-video__player'));
    }
}
