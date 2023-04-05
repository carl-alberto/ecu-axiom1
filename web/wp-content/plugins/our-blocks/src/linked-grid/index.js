import './style.scss';

import Edit from './edit';
import Save from './save';

const { registerBlockType } = wp.blocks;

registerBlockType( 'wp-blocks/linked-grid', {
    title: 'Linked Grid',
    icon: 'admin-page',
    description: 'A grid of custom linked images',
    category: 'ecu',
    attributes: {
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