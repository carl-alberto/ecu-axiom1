import './style.scss';

import Edit from './edit';
import Save from './save';

const { registerBlockType } = wp.blocks;

registerBlockType( 'wp-blocks/dashboard', {
    title: 'Dashboard',
    icon: 'welcome-widgets-menus',
    description: 'Formerly Dashboard 2.0',
    category: 'ecu',
    edit: Edit,
    save: Save
});