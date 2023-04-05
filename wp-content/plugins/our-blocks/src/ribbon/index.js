const { registerBlockType } = wp.blocks;

import './style.scss';
import './innerblocks/Callout';
import './innerblocks/Content';

import Edit from './edit';
import Save from './save';

registerBlockType('wp-blocks/ribbon', {
    title: 'Ribbon',
    icon: 'align-left',
    category: 'layout',
    attributes: {

        // Aria title for ribbon
        title: { type: 'string', source: 'attribute', selector: 'section', attribute: 'aria-label' },

        isFull: { type: 'boolean', default: false },

        isLeft: { type: 'boolean', default: false },
    },
    styles: [
        { name: 'default', label: 'Default', isDefault: true },
        { name: 'purple', label: 'Dark Purple / Plum' },
        { name: 'grey', label: 'Grey / Dark Grey' },
        { name: 'lavender', label: 'Lavender / ECU Purple' },
        { name: 'teal', label: 'Teal / Dark Teal' }
    ],

    edit: Edit,
    save: Save
});
