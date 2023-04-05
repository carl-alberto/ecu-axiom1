/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-blocks/
 */
const { registerBlockType } = wp.blocks

import Edit from './edit';
import Save from './save';

registerBlockType('wp-blocks/iframe', {
    title: 'iFrame',
    description: 'Allows you to embed a url from another site onto your page.',
    icon: 'format-aside',
    category: 'widgets',
    attributes: {

        // Source url for iframe
        url: {
            type: 'string',
            default: '',
            selector: 'iframe',
            attribute: 'src'
        },

        // Remove side scrollbars
        disableScroll: {
            type: 'boolean',
            default: false
        },

        // Height of iframe
        height: {
            type: 'number',
            default: 750,
            selector: 'iframe',
            attribute: 'height'
        },

        // Width of iframe
        width: {
            type: 'number',
            default: 100,
            selector: 'iframe',
            attribute: 'width'
        }

    },
    edit: Edit,
    // save: Save
    save: () => {
        return null
    }
});