/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-blocks/
 */

const { registerBlockType } = wp.blocks;

import './style.scss';

import Edit from './edit';

registerBlockType('wp-blocks/physician', {
    title: 'Physician',
    icon: 'heart',
    description: 'Displays physician information from ECU physicians.',
    category: 'ecu',
    attributes: { 

        // ID of selected physician
        physicianId: { type: 'string' },

        // Hides physician image
        hideImage: { type: 'boolean' },

        // Hides physician name
        hideName: { type: 'boolean' },

        // Hides physician ecu title
        hideEcuTitle: { type: 'boolean' },

        // Hides physician clinical title
        hideClinicalTitle: { type: 'boolean' },

        // Hides physician location
        hideLocation: { type: 'boolean' }
        
    },
    edit: Edit,
    save: () => {
        return null
    }
});