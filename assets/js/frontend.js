/* Blocks
/* ----------------------------------------------- */
let videoBlock = require("../../blocks/video/block");
let accordionBlock = require("../../blocks/accordion/block");
let iconSliderBlock = require("../../blocks/icon-slider/block");
let imageCompareBlock = require("../../blocks/image-compare/block");

jQuery(function () {
    videoBlock.init();
    accordionBlock.init();
    iconSliderBlock.init();
    imageCompareBlock.init();
});
