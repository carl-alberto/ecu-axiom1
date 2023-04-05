import './style.scss';

import Edit from './edit';
import Save from './save';

const { registerBlockType } = wp.blocks;

registerBlockType('wp-blocks/card', {
    title: 'Card',
    icon: 'admin-page',
    description: 'Colored card to emphasize content.',
    category: 'ecu',
    attributes: {

        // Heading of the card
        heading: {
            type: 'string',
            source: 'html',
            selector: '.card-heading'
        },

        // Card content 
        content: {
            type: 'string',
            source: 'html',
            selector: '.card-content'
        },

        // Heading size (h2)
        size: {
            type: 'string',
            default: 2
        },

        // Whether or not card has shadow applied
        hasShadow: {
            type: 'boolean'
        }
        
    },
    styles: [
        { name: 'default', label: 'Default', isDefault: true },
        { name: 'hotpink', label: 'Hot Pink' },
        { name: 'darkpurple', label: 'Dark Purple' },
        { name: 'ecupurple', label: 'ECU Purple' },
        { name: 'goldgrey', label: 'Gold Grey' },
        { name: 'darkteal', label: 'Dark Teal' },
        { name: 'darkergrey', label: 'Darker Grey' },
        { name: 'teal', label: 'Teal' },
        { name: 'lavender', label: 'Lavender' }
    ],
    edit: Edit,
    save: Save
});