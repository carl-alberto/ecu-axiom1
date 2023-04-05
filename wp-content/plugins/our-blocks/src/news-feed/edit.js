/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 * @see https://developer.wordpress.org/block-editor/packages/packages-editor/
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
const { Component } = wp.element
const { InspectorControls } = wp.blockEditor
const { SelectControl, TextControl, PanelBody, RangeControl, ToggleControl, Spinner } = wp.components

/**
 * @see https://lodash.com/docs/4.17.15
 */
import _ from 'lodash'

import ECUPlaceholder from "../components/ecu-placeholder"

/**
 * @see https://github.com/axios/axios
 */
const axios = require('axios').default

const Edit = class extends Component {

    /**
     * Initializes state
     */
    constructor() {
        super( ...arguments )
        this.state = {
            feeds: [],
            posts: [],
            update: true,
            loading: true
        }
    }

    /**
     * Loads news feeds when component mounts
     */
    componentDidMount = () => {
        axios.get( __wp__.ajax, { params: { action: 'block_news_feed_feeds' } } )
        .then( ( { data } = res ) => {
            this.setState( { feeds: data.data, loading: false } )
        } )
    }
    
    render = ( { attributes: { feed, limit, title, media, center }, className, setAttributes } = this.props ) => {
        const { posts, feeds, update, loading } = this.state

        // Updates feed if feed, limit and update are true
        if( feed && limit && update ){
            if( !loading ) this.setState({ loading: true })
            axios.get( __wp__.ajax, { 
                params: { 
                    action: 'block_news_feed_posts',
                    feed: feed,
                    limit: limit
                } 
            } )
            .then( ( res ) => {
                if ( res.data.success ){
                    this.setState( { posts: res.data.data, update: false } )
                } else {
                    console.log( res.data.data )
                }
            } )
        }

        /*
        * Displays select organization form
        */
       const Form = <>
            { loading && <p><Spinner /> Loading News Feeds</p> }
            { !loading && <div className="inline-control">
                <SelectControl
                    label="News Feeds"
                    value={ feed }
                    options={ feeds }
                    onChange={ feed => {
                        this.setState( { update: true }, setAttributes( { feed } ) )
                    } }
                />
            </div> }
        </>


        // Chunks posts into columns
        const rows = posts.length > 0 ? _.chunk( posts, 3 ) : false

        // Block editor markup 
        const Output = rows ?
        <div className={ className }>
            { title && <h2>{ title }</h2> }
            <div className="news-feed">
                { rows.map( row =>
                    <div className="row">
                        { row.map( post =>
                            <div className={ center ? 'news-feed-item text-center' : 'news-feed-item'} key={ post.title }>
                                { !media && 
                                    <figure>
                                        <a href={ post.url } target="_blank">
                                            <img src={ post.image } />
                                        </a>
                                    </figure>
                                }
                                <div className="content">
                                    <h3><a href={ post.url } target="_blank">{ post.title }</a></h3>
                                    <p>{ post.excerpt }</p>
                                </div>
                            </div>
                        )}
                    </div>
                ) }
            </div>
            {/* { loading && <p><Spinner /> Loading News Feeds</p> } */}
        </div> :
        <ECUPlaceholder style="offset-3 col-6" > { Form } </ECUPlaceholder>

        // Block inspector controls
        const Inspector = <InspectorControls>
            { feed && <>
                <PanelBody title="Select a News Feed" initialOpen={ true }>
                <SelectControl
                    value={ feed }
                    options={ feeds }
                    onChange={ feed => {
                        this.setState( { update: true }, setAttributes( { feed } ) )
                    } }
                />
            </PanelBody>
            <PanelBody title="Attributes">
                <TextControl
                    label="Feed Title"
                    value={ title }
                    onChange={ title => setAttributes( { title } ) }
                    help="Leave empty for no title."
                />
                <RangeControl
                    label="Number of Posts"
                    min={ 3 } 
                    max={ 12 } 
                    step={ 1 }
                    value={ limit ? limit : 3 }
                    onChange={ limit => {
                        setAttributes( { limit } )
                        this.setState( { update: true } )
                    } }
                    help="Determines the amount of posts to display"
                />
                <ToggleControl
                    label={ media ? 'Display post image' : 'Hide post images' }
                    checked={ media }
                    onChange={ media => setAttributes( { media } ) }
                />
                <ToggleControl
                    label={ center ? 'Left align text' : 'Center text' }
                    checked={ center }
                    onChange={ center => setAttributes( { center } ) }
                />
            </PanelBody>
            </>}
            
        </InspectorControls>

        return [ Inspector, Output ]
    }
}

export default Edit