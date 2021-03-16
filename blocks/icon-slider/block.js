module.exports = {
    init: function () {
        jQuery('.sp-block-icon-slider').each(function () {
            module.exports.initBlock(jQuery(this));
        });

        if (window.acf) {
            window.acf.addAction('render_block_preview/type=sp-block-icon-slider', this.initBlock)
        }
    },

    initBlock: function (block) {
        let carousel = block.find('.owl-carousel');
        let items = carousel.data('items');

        carousel.owlCarousel({
            responsive: {
                0: {
                    items: 1,
                    loop: true,
                    dots: false,
                    autoplay: true
                },

                768: {
                    items: items < 4 ? items : 4,
                    loop: items < 4 ? false : true,
                    autoplay: items < 4 ? false : true,
                    dots: false
                }
            }
        });
    }
}
