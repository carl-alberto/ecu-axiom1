const { registerBlockType } = wp.blocks;

import Edit from './edit';
import Save from './save';
import './style.scss';

registerBlockType(
    'ecu-blocks/[ Block Slug ]', {
        title: "[ Block Title ]",
        description: "[ Block Description ]",
        icon: "[ Block Icon ]",
        category: "ecu",
        Edit,
        Save
    }
);