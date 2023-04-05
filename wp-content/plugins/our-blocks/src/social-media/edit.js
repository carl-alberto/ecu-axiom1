/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 */
const { Component, Fragment } = wp.element

/**
 * @see https://github.com/axios/axios
 */
const axios = require('axios').default

/**
 * Custom components
 */
import ECUPlaceholder from "../components/ecu-placeholder"

/**
 * Custom utilities
 */
import { getSize } from '../util'

const Edit = class extends Component {
    constructor() {
        super( ...arguments )
        this.state = {
            id: false,
            orgs: []
        }
        this.handleDetails = this.handleOrgChange.bind( this )
    }

    // Loads social media organizations on component mount
    componentDidMount = () => {
        axios.get( __wp__.ajax, { params: { action: 'block_social_media_orgs' } } )
        .then( ( { data } = res ) => {
            this.setState( { orgs: data.data } )
        } )
    }

    // On organization changes, fetches registered sites from blogs
    handleOrgChange = () => {
        axios.get( __wp__.ajax, { params: { action: 'block_social_media_data', id: this.state.id } } )
        .then( ( { data } = res ) => {
            const sites = data.data.map( site => {
                const networks = {
                    facebook: 'fab fa-facebook-f',
                    instagram: 'fab fa-instagram',
                    linkedin: 'fab fa-linkedin-in',
                    pinterest: 'fab fa-pinterest-p',
                    twitter: 'fab fa-twitter',
                    youtube: 'fab fa-youtube'
                }

                site.icon = networks[ site.name.toLowerCase() ]
                delete site.name

                return site
            })
            
            this.props.setAttributes( { sites } );
        } )
    }

    render = ( { attributes, setAttributes, className } = this.props ) => {

        const { heading, size, isVertical, sites } = attributes

        const { id, orgs } = this.state
        const hasSites = sites.length > 0 ? true : false
        const loading = orgs.length > 0 ? true : false

        const { SelectControl, PanelBody, ToggleControl, RangeControl, Spinner, Button } = wp.components
        const { InspectorControls } = wp.blockEditor

        /*
        * Displays select organization form
        */
        const Form = <Fragment>
            { !loading && <p><Spinner /> Loading Organizations</p> }
            { loading && <div className="inline-control">
                <SelectControl
                    value={ id }
                    options={ orgs }
                    onChange={ id => this.setState( { id } ) }
                />
                <Button
                    isPrimary
                    onClick={ this.handleOrgChange  }
                    disabled={ id ? false : true }
                >
                    <span class="dashicons dashicons-search"></span>
                </Button>
            </div> }
        </Fragment>

        /*
        * Editor Output
        */
        const layoutClass = isVertical ? 'vertical' : 'horizontal'
        const Output = <div className={ `${className} ${layoutClass}` }>
            { !hasSites && <ECUPlaceholder style="offset-3 col-6" > { Form } </ECUPlaceholder> }
            { hasSites && <Fragment>
                { heading && <h2 className="social-media-heading">{ heading }</h2>}
                <ul>
                    { sites && sites.map( site => 
                        <li key={ site.name } className="social-media-item">
                            <a href={site.url} aria-label={ site.title } className={ getSize( size ) }>
                                <span className={ site.icon }></span>
                            </a>
                        </li>
                    ) }
                </ul>
            </Fragment> }
        </div>
    
        /*
        * Inspector Controls
        */
        const Inspector = <InspectorControls>
            { hasSites && <Fragment>
                <PanelBody title="Settings">
                    { Form }
                </PanelBody>
                <PanelBody title="Styling">
                    <RangeControl
                        label="Icon Size"
                        min={ 1 } 
                        max={ 8 } 
                        step={ 1 }
                        value={ size }
                        initialPosition={ 4 }
                        onChange={ size => setAttributes( { size } ) }
                    />
                    <ToggleControl
                        label={ isVertical ? 'Horizontal Layout' : 'Vertical Layout' }
                        checked={ isVertical }
                        onChange={ isVertical => setAttributes( { isVertical } ) }
                    />
                </PanelBody>
            </Fragment> }
        </InspectorControls>
    
        return [ Inspector, Output ]
    }

}

export default Edit