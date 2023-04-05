/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-blocks/
 */
const { registerBlockType } = wp.blocks;

import './style.scss';

import Edit from './edit';

registerBlockType('wp-blocks/news-feed', {
    title: 'News Feed',
    icon: 'admin-site',
    category: 'common',
    attributes: {

        // News feed title
        title: { type: 'string' },

        // ID of selected feed
        feed: { type: 'string' },

        // Max amount of posts to load (3)
        limit: { type: 'number', default: 3 },

        // Whether or not to display media (images, videos)
        media: { type: 'boolean', default: false },

        // Center text 
        center: { type: 'boolean' }
        
    },
    styles: [
        { name: 'default', label: 'Horizontal list', isDefault: true },
        { name: 'vertical', label: 'Vertical list' }
    ],
    edit: Edit,
    save: () => {
        return null
    }
});
