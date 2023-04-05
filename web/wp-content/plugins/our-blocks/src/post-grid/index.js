import './style.scss';

import Edit from './edit';
import Save from './save';

const { registerBlockType } = wp.blocks;

registerBlockType( 'wp-blocks/post-grid', {
    title: 'Post Grid',
    icon: 'admin-page',
    description: 'A tabbed feed of blog posts grouped by category',
    category: 'ecu',
    attributes: {
        categories: {
            type: 'array',
            default: []
        },
        total: {
            type: 'number',
            default: 6
        },
        width: {
            type: 'number',
            default: 3
		},
		height: {
            type: 'number',
            default: 300
		}
	},
    edit: Edit,
    save: Save
});