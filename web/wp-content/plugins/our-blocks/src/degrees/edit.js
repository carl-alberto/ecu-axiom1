/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 */
const { Component } = wp.element
import { Spinner } from '@wordpress/components'

/**
 * @see https://github.com/axios/axios
 */
const axios = require('axios').default

import ECUPlaceholder from "../components/ecu-placeholder"

const Edit = class extends Component {

    /**
     * Initialize state
     */
    constructor() {
        super( ...arguments )
        this.state = {
            degrees: [],
            content: '',
            tempDegree: false,
            loading: true
        }

        this.handleDegreeChange = this.handleDegreeChange.bind( this )
    }

    /**
     * Load degree options from db
     * 
     * @return null
     */
    componentDidMount = ( ) => {
        if( this.props.attributes.degree ) this.handleDegreeChange()

        axios.get( __wp__.ajax, { params: { action: 'block_degree_options' } } )
        .then( ( { data } = res ) => {
            this.setState( { degrees: data.data, loading: false } )
        } )
    }

     /**
     * Updates state and content attribute
     * @param {int} degree  ID of selected degree
     */
    handleDegreeChange = ( ) => {
        const { attributes , setAttributes } = this.props
        const id = this.state.tempDegree ? this.state.tempDegree : attributes.degree
        axios.get( __wp__.ajax, { params: { action: 'block_degree_info', degree: id } } )
        .then( res => {
            if( this.state.tempDegree ){
                setAttributes( { degree: this.state.tempDegree } )
            }
            
            this.setState( { content: res.data.data, loading: false } )
        } )
    }

    render = ( { attributes: { degree }, className } = this.props ) => {
    
        const { tempDegree, content, degrees, loading } = this.state

        /*
        * Editor Output
        */
        const { SelectControl, PanelBody, Button } = wp.components
        const { InspectorControls } = wp.blockEditor

        // Select a degree form
        const Form = <div className="inline-control">
            <SelectControl
                value={ tempDegree }
                options={ [ 
                    { 
                        value: false, 
                        label: "Select a degree"
                    }, 
                    ...degrees
                ] }
                onChange={ tempDegree => this.setState( { tempDegree } ) }
            />
            <Button
                isPrimary
                onClick={ () => this.handleDegreeChange()  }
                disabled={ tempDegree ? false : true }
            >
                <span class="dashicons dashicons-search"></span>
            </Button>
        </div>

        // Block editor output
        const Output = <div className={`${className} admin`}>
            { loading && <><Spinner /> Loading...</> }
            { !degree ? 
                <ECUPlaceholder style="offset-3 col-6" >
                    { degrees && Form }
                </ECUPlaceholder>
            :
                <div dangerouslySetInnerHTML={ { __html: content } }></div>
            }
        </div>

        const Inspector = <InspectorControls>
            { degree && <PanelBody title="Degrees">
               { Form }
            </PanelBody> }
        </InspectorControls>
    
        return [ Inspector, Output ]
    }

}

export default Edit