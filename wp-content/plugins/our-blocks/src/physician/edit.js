/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 * @see https://developer.wordpress.org/block-editor/packages/packages-editor/
 */
const { Component, Fragment } = wp.element
const { SelectControl, Button, PanelBody, ToggleControl, Spinner } = wp.components
const { InspectorControls } = wp.blockEditor

const axios = require('axios').default

/**
 * @see https://lodash.com/docs/4.17.15
 */
import _ from 'lodash'

import ECUPlaceholder from "../components/ecu-placeholder"

const Edit = class extends Component {

    /**
     * Initializes state
     */
    constructor() {
        super( ...arguments )
        this.state = {
            physicians: [],
            meta: {}
        }
    }

    /**
     * Auto populates physicians state
     */
    componentDidMount = () => {
        // TODO: Change __wp__.ajax to hardcoded url for physician cpt site
        axios.get( __wp__.ajax, { params: { action: 'block_physician_get_options' } } )
        .then( ( { data } = res ) => {
            if( data.success ){
                this.setState( { physicians: data.data } )
            } else {
                console.log( data.data )
            }
        } )
    }

    render = ( { 
        attributes: { 
            physicianId, 
            hideImage,
            hideName,
            hideEcuTitle,
            hideClinicalTitle,
            hideLocation
        }, 
        className, 
        setAttributes 
    } = this.props ) => {

        const { physicians, meta } = this.state

        const loaded = physicians.length > 0 ? true : false

        // Populates physician meta state
        const handlePhysicianSubmit = () => {

            // this gets rewritten inside axios response
            // copy to self
            const self = this

            axios.get( __wp__.ajax, { 
                params: { 
                    action: 'block_physician_get_meta', 
                    physician_id: physicianId 
                } 
            } )
            .then( function ( { data } = res ) { 
                self.setState( { meta: data.data } )
            } )
            .catch( function( e ) {
                console.log( e )
            } )
        }
        
        if( physicianId && _.isEmpty( this.state.meta ) ) handlePhysicianSubmit()

        // Block editor markup 
        const Output = <div className={`${className} admin`}>
            { _.isEmpty( this.state.meta ) ? 
                <ECUPlaceholder>
                    { !loaded && <p><Spinner /> Loading Physicians</p> }
                    { loaded && <div className="inline-control">
                        <SelectControl
                            label="Select a Physician:"
                            value={ physicianId }
                            options={ [ 
                                { 
                                    value: false, 
                                    label: ""
                                }, 
                                ...physicians
                            ] }
                            onChange={ physicianId => setAttributes( { physicianId } ) }
                        />
                        <Button
                            isPrimary
                            onClick={ handlePhysicianSubmit  }
                            disabled={ physicianId ? false : true }
                        >
                            <span class="dashicons dashicons-search"></span>
                        </Button>
                    </div> }
                </ECUPlaceholder>
            :
                <div className="physician">
                    { meta.image && !hideImage && <img src={ meta.image } className="image mr-3" />}
                    <div className="content">
                        { !hideName && <h4 className="physician_name">
                            { meta.name  }
                            { meta.accreditation && 
                                <Fragment>,&nbsp;
                                    <span>{ meta.accreditation }</span>
                                </Fragment> 
                            }
                        </h4> }
                        { meta.ecu_title && !hideEcuTitle && <p>{ meta.ecu_title }</p> }
                        { meta.clinical_title && !hideClinicalTitle && <p>{ meta.clinical_title }</p> }
                        { meta.location && !hideLocation && <p>{ meta.location }</p> }
                    </div>
                    <div className="clearfix"></div>
                </div>
            }
        </div>

        // Block inspector controls
        const Inspector = <InspectorControls>
            <PanelBody title="Settings" initialOpen={ true } >
                <ToggleControl
                    label="Hide Image"
                    checked={ hideImage }
                    onChange={ hideImage => setAttributes( { hideImage } ) }
                />
                <ToggleControl
                    label="Hide Name"
                    checked={ hideName }
                    onChange={ hideName => setAttributes( { hideName } ) }
                />
                <ToggleControl
                    label="Hide ECU Title"
                    checked={ hideEcuTitle }
                    onChange={ hideEcuTitle => setAttributes( { hideEcuTitle } ) }
                />
                <ToggleControl
                    label="Hide Clinical Title"
                    checked={ hideClinicalTitle }
                    onChange={ hideClinicalTitle => setAttributes( { hideClinicalTitle } ) }
                />
                <ToggleControl
                    label="Hide Location"
                    checked={ hideLocation }
                    onChange={ hideLocation => setAttributes( { hideLocation } ) }
                />
            </PanelBody>
        </InspectorControls>

        return[ Inspector, Output ]
    }
}

export default Edit