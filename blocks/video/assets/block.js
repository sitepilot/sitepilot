module.exports = {
    init: function () {
        if (window.spBlocks) {
            window.spBlocks.addInitAction('sp-video', this.initBlock);
        }
    },

    initBlock: function (block) {
        new Plyr(block.find('.sp-video__player'));
    }
}
