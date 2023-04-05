/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-blocks/
 */
const { registerBlockType } = wp.blocks

import './style.scss';
import Edit from './edit';

registerBlockType('wp-blocks/degrees', {
    title: 'Degrees',
    description: 'Displays brief information about a degree',
    icon: 'media-document',
    category: 'widgets',
    attributes: {

        // ID of selected string 
        degree: { type: 'string' }
    },
    edit: Edit,
    save: () => {
        return null
    }
});