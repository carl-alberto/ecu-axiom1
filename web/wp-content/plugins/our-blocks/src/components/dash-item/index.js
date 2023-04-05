import './style.scss'

import Edit from './edit'
import Save from './save'

const { registerBlockType } = wp.blocks

registerBlockType( 'wp-blocks/dash-item', {
    title: 'Dash Item',
    icon: 'admin-page',
    description: 'Dash item used in the dashboard.',
    category: 'ecu',
    parent: [ 'wp-blocks/dashboard' ],
    attributes: {
        image: {
            type: 'string',
            source: 'attribute',
            selector: 'figure img',
            attribute: 'src',
            default: ''
        },
        url: {
            type: 'string',
            default: ''
        },
        icon: {
            type: 'object',
            default: {}
        },
        heading: {
            type: 'string',
            source: 'html',
            selector: '.block-heading',
            default: ''
        },
        content: {
            type: 'string',
            source: 'html',
            selector: '.block-content',
            default: ''
        },
        internal: {
            type: 'boolean',
            default: false
        },
        target: {
            type: 'boolean',
            default: false
        },
        isVertical: {
            type: 'boolean',
            default: false
        },
        type: {
            type: 'string',
            default: ''
        }
    },
    styles: [
        { name: 'default', label: 'Default', isDefault: true },
        { name: 'gold', label: 'Gold' },
        { name: 'lavender', label: 'Lavender' },
        { name: 'dark-purple', label: 'Dark Purple' },
        { name: 'grey', label: 'Grey' }
    ],
    edit: Edit,
    save: Save
})