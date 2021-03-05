/**
 * WordPress dependencies
 */
import { addAction, doAction } from '@wordpress/hooks';

/**
 * Init blocks
 */
const initBlocks = () => {
    jQuery('div[data-init="true"]').each(function () {
        let initHook = 'sp_block_init_' + jQuery(this).data('block');

        if (wp.blockEditor) {
            console.log('Sitepilot - Running action: ' + initHook);
        }

        doAction(initHook, jQuery(this));

        jQuery(this).removeAttr("data-init");
    });
}

/**
 * Document ready
 */
jQuery(function () {
    initBlocks();

    if (window.acf) {
        window.acf.addAction('render_block_preview', initBlocks);
    }
});

/**
 * Blocks API
 */
window.spBlocks = {
    addInitAction: (block, callback) => {
        addAction('sp_block_init_' + block, 'sitepilot/block/init/' + block, callback);
    }
};

/**
 * Core blocks
 */
let videoBlock = require("../../blocks/video/assets/block");
let accordionBlock = require("../../blocks/accordion/assets/block");
let iconSliderBlock = require("../../blocks/icon-slider/assets/block");
let imageCompareBlock = require("../../blocks/image-compare/assets/block");

videoBlock.init();
accordionBlock.init();
iconSliderBlock.init();
imageCompareBlock.init();