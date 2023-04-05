import './style.scss';

import Edit from './edit';
import Save from './save';

const { registerBlockType } = wp.blocks;

registerBlockType( 'wp-blocks/grid-item', {
    title: 'Grid Item',
    icon: 'admin-page',
    description: 'Linked image with heading',
    category: 'ecu',
    parent: [ 'wp-blocks/linked-grid' ],
    attributes: {

        heading: {
            type: 'string',
            source: 'html',
            selector: 'p',
            default: ''
        },

        url: {
            type: 'string',
            source: 'attribute',
            selector: 'a',
            attribute: 'href',
            default: ''
        },

        image: {
            type: 'string',
            default: ''
        },

        width: {
            type: 'string',
            default: 'col-md-4'
        },

        height: {
            type: 'number',
            default: 300
        },

        internal: {
            type: 'boolean',
            default: false
        },

        target: {
            type: 'boolean',
            default: true
        },

        invert: {
            type: 'boolean',
            default: false
        }

    },
    edit: Edit,
    save: Save
});