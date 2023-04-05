import './style.scss';

import Edit from './edit';
import Save from './save';

const { registerBlockType } = wp.blocks;

registerBlockType('wp-blocks/profile', {
    title: 'Profile',
    icon: 'admin-users',
    description: 'Displays user information based on Pirate ID.',
    category: 'ecu',
    attributes: {
        pirateID: {
            type: 'string',
            default: ''
        },
        useImage: {
            type: 'boolean',
            default: true
        },
        image: {
            type: 'string',
            source: 'attribute',
            selector: '.profile-image',
            attribute: 'src',
            default: __wp__.assets + '/images/profile-placeholder-size.jpg'
        },
        name: {
            type: 'string',
            source: 'html',
            selector: 'h2',
            default: ''
        },
        title: {
            type: 'string',
            source: 'html',
            selector: '.profile-title',
            default: ''
        },
        department: {
            type: 'string',
            source: 'html',
            selector: '.profile-department',
            default: ''
        },
        office: {
            type: 'string',
            source: 'html',
            selector: '.profile-office',
            default: ''
        },
        mailstop: {
            type: 'string',
            source: 'html',
            selector: '.profile-mailstop',
            default: ''
        },
        email: {
            type: 'string',
            source: 'html',
            selector: '.profile-email',
            default: ''
        },
        phone: {
            type: 'string',
            source: 'html',
            selector: '.profile-phone',
            default: ''
        },
        details: {
            type: 'details',
            source: 'html',
            selector: '.profile-details',
            default: ''
        },
        isVertical: {
            type: 'boolean',
            default: true
        },
        isCenter: {
            type: 'boolean',
            default: false
        }
        
    },
    suports: {
        html: false
    },
    edit: Edit,
    save: Save
});