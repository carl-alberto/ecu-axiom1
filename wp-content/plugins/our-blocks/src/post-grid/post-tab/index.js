// import './style.scss';

import Edit from './edit';
import Save from './save';

const { registerBlockType } = wp.blocks;

registerBlockType( 'wp-blocks/post-tab', {
    title: 'Post Tab',
    icon: 'admin-page',
    category: 'ecu',
    parent: ['wp-blocks/post-grid'],
    attributes: {
        category: {
            type: 'string'
        },
        categoryName: {
            type: 'string'
        },
        custom: {
            type: 'boolean',
            default: false
        },
        posts: {
            type: 'array',
            default: []
        }
    },
    edit: Edit,
    save: Save
});