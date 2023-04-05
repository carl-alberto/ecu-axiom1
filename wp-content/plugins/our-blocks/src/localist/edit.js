/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 * @see https://developer.wordpress.org/block-editor/packages/packages-editor/
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
const { Component } = wp.element
const { InspectorControls } = wp.blockEditor
const { 
    SelectControl, 
    TextControl, 
    PanelBody, 
    RangeControl, 
    Spinner, 
    ToggleControl, 
    Button, 
    Placeholder 
} = wp.components

/**
 * @see https://lodash.com/docs/4.17.15
 */
import _ from 'lodash'

/**
 * @see https://github.com/axios/axios
 */
const axios = require('axios').default

const Edit = class extends Component {

    /**
     * Initialize state
     */
    constructor() {
        super( ...arguments )
        this.state = {
            loading: true,
            initialLoad: true,
            audienceOptions: [],
            departmentOptions: [],
            eventOptions: [],
            groupOptions: [],
            venueOptions: [],
            posts: []
        }
    }

    /**
     * Fetch all form options
     */
    componentDidMount = () => {
        axios.get( __wp__.ajax, { params: { action: 'block_localist_options' } } )
        .then( ( { data } = res ) => {
            if( data.success ){
                this.setState({
                    loading: false,
                    audienceOptions: data.data.audienceOptions,
                    departmentOptions: data.data.departmentOptions,
                    eventOptions: data.data.eventOptions,
                    groupOptions: data.data.groupOptions,
                    venueOptions: data.data.venueOptions
                })
            } else {
                console.log( data.data )
            }
        } )
    }
    
    render = ( 
        { 
            attributes: {
                group_id,
                venue_id,
                departments,
                events,
                audiences,
                max_events,
                distinct,
                days,
                keyword,
                columns,
                title,
                hideDescriptions
            },
            clientId,
            className, 
            setAttributes 
        } = this.props 
    ) => {
        const { 
            loading,
            initialLoad,
            posts,
            audienceOptions, 
            departmentOptions, 
            eventOptions, 
            groupOptions, 
            venueOptions
        } = this.state

        // Fetch events from API
        const getEvents = () => {
            axios.get( __wp__.ajax, { params: { 
                action: 'block_localist_get_events',
                events: _.isUndefined( events ) ? '' : _.join( events, '+' ),
                audiences: _.isUndefined( audiences ) ? '' : _.join( audiences, '+' ),
                venue_id: _.isUndefined( venue_id ) ? '' : _.join( venue_id, '+' ),
                departments: _.isUndefined( departments ) ? '' : _.join( departments, '+' ),
                keyword: _.isUndefined( keyword ) ? '' : encodeURIComponent( keyword ),
                group_id: _.isUndefined( group_id ) ? '' : group_id,
                max_events,
                days,
                distinct
            } } )
            .then( ( { data } = res ) => {
                if( data.success ){
                    this.setState( {
                        posts: data.data,
                        initialLoad: false
                    } )
                } else {
                    console.log( data.data )
                }
            } )
        }


        // Determine column size based on number of columns
        const getColumnSize = () => {
            const col = [
                'col-12',
                'col-md-6',
                'col-md-4',
                'col-md-3'
            ]
            return col[columns - 1]
        }

        // Fetch events only when block is added to editor
        if( initialLoad && ( group_id || events || audiences || venue_id || departments ) ) getEvents()

        // Used to break posts into columns and also serves to determine if posts are loaded
        const rows = posts.length > 0 ? _.chunk( posts, columns ) : false
        
        // Block editor markup 
        const Output = rows ? <div className={`${className} admin`}>
            { title && <h2>{ title }</h2>}
            { rows.map( row => 
                <div className="row">
                    { row.map( post => 
                        <div className={ getColumnSize() } key={ post.id }>
                            <div className="event">
                                <div className="date">
                                    <div className="datewrap">
                                        <abbr className="month" title={ post.month }>
                                            { post.monthabbr }
                                        </abbr>
                                        <span className="day">
                                            { post.day }
                                        </span>
                                    </div>
                                </div>
                                <div className="details">
                                    <h3>
                                        <a href={ post.url } target="_blank">{ post.title }</a>
                                    </h3>
                                    { post.location && <span className="location">{ post.location }</span> }
                                    <span className="time">
                                        <time datetime={ post.hours.start.time }>
                                            { post.hours.start.label }
                                        </time>
                                        { post.hours.end && <time datetime={ post.hours.end.time }>{ post.hours.end.label }</time> }
                                    </span>
                                    { post.description && !hideDescriptions && <p>{ post.description }</p> }
                                </div>
                            </div>
                        </div> 
                    ) }
                </div>
            ) }
        </div> :
        <Placeholder />

        // Block inspector controls
        const Inspector = <InspectorControls>
            <PanelBody title="Display Settings" initialOpen={ true }>
                <TextControl
                    label="Title"
                    value={ title }
                    onChange={ title => setAttributes( { title } ) }
                />
                <RangeControl
                    label="Columns"
                    min={ 1 } 
                    max={ 4 } 
                    step={ 1 }
                    value={ columns }
                    onChange={ columns => setAttributes( { columns } ) }
                />
                <ToggleControl
                    label="Hide Descriptions"
                    checked={ hideDescriptions }
                    onChange={ hideDescriptions => setAttributes( { hideDescriptions } ) }
                />
            </PanelBody>
            <PanelBody title="Event Settings" initialOpen={ true }>
                { loading && <p><Spinner />Loading Settings</p> }
                <Button 
                    isPrimary
                    onClick={ getEvents }
                    style={ { display: 'block', width: '100%', textAlign: 'center', marginBottom: '15px' } }
                >
                    Load Events
                </Button>
                <hr />
                <SelectControl
                    label="Groups"
                    value={ group_id }
                    options={ groupOptions }
                    onChange={ group_id => setAttributes( { group_id } ) }
                />
                <SelectControl
                    label="Places"
                    value={ venue_id }
                    options={ venueOptions }
                    multiple={ true }
                    onChange={ venue_id => setAttributes( { venue_id } ) }
                />
                <SelectControl
                    label="Departments"
                    value={ departments }
                    options={ departmentOptions }
                    multiple={ true }
                    onChange={ departments => setAttributes( { departments } ) }
                />
                <SelectControl
                    label="Event Types"
                    value={ events }
                    options={ eventOptions }
                    multiple={ true }
                    onChange={ events => setAttributes( { events } ) }
                />
                <SelectControl
                    label="Target Audiences"
                    value={ audiences }
                    options={ audienceOptions }
                    multiple={ true }
                    onChange={ audiences => setAttributes( { audiences } ) }
                />
                <RangeControl
                    label="Number of events"
                    min={ 1 } 
                    max={ 50 } 
                    step={ 1 }
                    value={ max_events }
                    onChange={ max_events => setAttributes( { max_events } ) }
                />
                <RangeControl
                    label="Days Ahead"
                    min={ 1 } 
                    max={ 365 } 
                    step={ 1 }
                    value={ days }
                    onChange={ days => setAttributes( { days } ) }
                />
                <ToggleControl
                    label='Include all matching instances'
                    checked={ distinct }
                    onChange={ distinct => setAttributes( { distinct } ) }
                    help='Displays all instances of recurring events'
                />
                <TextControl
                    label="Keywords"
                    value={ keyword }
                    onChange={ keyword => setAttributes( { keyword } ) }
                    help="Seperate keyword with commas"
                />
            </PanelBody>
        </InspectorControls>

        return [ Inspector, Output ]
    }
}

export default Edit