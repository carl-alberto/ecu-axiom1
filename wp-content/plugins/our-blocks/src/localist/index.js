/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-blocks/
 */
const { registerBlockType } = wp.blocks;

import './style.scss';

import Edit from './edit';

registerBlockType( 'wp-blocks/localist', {
    title: 'University Calendar',
    icon: 'calendar',
    category: 'ecu',
    attributes: {

        // Selected group
        group_id: { type: 'string' },

        // Array of selected venues
        venue_id: { type: 'array' },

        // Array of selected departments
        departments: { type: 'array' },

        // Array of selected events
        events: { type: 'array' },

        // Array of selected events
        audiences: { type: 'array' },

        // Maximum days forward to pull events from
        days: { type: 'number', default: 31 },

        // Amount of columns to display events in
        columns: { type: 'number', default: 3 },

        // Maximum amount of events to load
        max_events: { type: 'number', default: 5 },

        // Hides re-occuring events
        distinct: { type: 'boolean' },

        // String of search keywords 
        keyword: { type: 'string' },

        // Hides event descriptions
        hideDescriptions: { type: 'boolean' },

        // Block heading (h2)
        title: { type: 'string', selector: 'h2' }

    },
    edit: Edit,
    save: () => {
        return null
    }
});
