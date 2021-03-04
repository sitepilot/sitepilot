module.exports = {
    init: function () {
        if (window.spBlocks) {
            window.spBlocks.addInitAction('sp-block-section', this.initBlock);
        }
    },

    initBlock: function (block) {
        block.find('p:empty').remove();
    }
}
