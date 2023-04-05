const { registerBlockType } = wp.blocks

import './style.scss';
import Edit from './edit';
import Save from './save';

registerBlockType('wp-blocks/social-media', {
    title: 'Social Media',
    description: 'Show an ECU organization\'s social media accounts registered with the university',
    icon: 'share',
    category: 'widgets',
    attributes: {

        // Heading for social media section
        heading: {
            type: 'string',
            source: 'html',
            selector: 'h2.social-media-heading',
            default: 'Follow us on social media!'
        },

        // Size of icons
        size: {
            type: 'number',
            default: 4
        },

        // Whether or not the list is vertical
        isVertical: {
            type: 'boolean',
            default: false
        },

        // Array of social media sites
        sites: {
            type: 'array',
            selector: 'li.social-media-item',
            default: [],
            query: {

                // Url of social media site
                url: {
                    type: 'string',
                    selector: 'a',
                    attribute: 'href'
                },

                // Aria label of social media site
                title: {
                    type: 'string',
                    selector: 'a',
                    attribute: 'aria-label'
                }, 

                // Social media icon
                icon: {
                    type: 'string',
                    selector: 'span',
                    attribute: 'class'
                }
            }
        }
    },
    supports: {
        align: [ 'left', 'right', 'full' ]
    },
    edit: Edit,
    save: Save
});