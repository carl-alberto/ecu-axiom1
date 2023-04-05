const { Component } = wp.element
const { RichText, BlockControls, InspectorControls } = wp.blockEditor
const { Button, SelectControl, ToggleControl, TextControl, DropdownMenu, PanelBody, RangeControl } = wp.components
const { MediaUpload }  = wp.editor

const axios = require('axios').default

const classNames = require( 'classnames' )

const Edit = class extends Component {

    constructor() {
        super( ...arguments )
        this.state = {
            pages: []
        }
    }

    componentDidMount = () => {
        axios.get( __wp__.ajax, { params: { action: 'wp_blocks_get_posts' } } )
        .then( ( { data } = res ) => this.setState( { pages: data.data } ) )
    }
    
    render = ( { setAttributes, attributes: { heading, image, url, internal, invert, target, height, width } } = this.props ) => {

        const classes = classNames( 'wp-block-wp-blocks-grid-item', { invert: invert } )

        const CustomControls = <BlockControls> 
            <MediaUpload
                onSelect={ image => setAttributes( { image: image.url } ) }
                type="image"
                value={ image }
                render={ ( { open } ) => 
                    <Button onClick={ open } ><i class="fas fa-camera fa-lg fa-fw"></i></Button>
                }
            />
            <DropdownMenu 
                icon="admin-links"
                label="Select URL"
                popoverProps={ { position: "bottom left"} }
            >
                { ( { onClose } ) => (
                    <div class="p-2">
                        { !internal && <TextControl
                            label="Enter URL"
                            value={ url }
                            onChange={ url => setAttributes( { url } ) }
                        /> }
                        { internal && <SelectControl
                            label="Select a post or page"
                            value={ url }
                            options={ this.state.pages }
                            onChange={ url => setAttributes( { url } ) }
                        /> }
                        <ToggleControl
                            label="Page / Post"
                            checked={ internal }
                            onChange={ internal => setAttributes( { internal } ) }
                        />
                        <ToggleControl
                            label="Open in new tab"
                            checked={ target }
                            onChange={ target => setAttributes( { target } ) }
                        />
                        <p className="text-right" style={ { margin: 0 } }>
                            <Button isSecondary isSmall onClick={ onClose } label="Apply"><i class="fas fa-check"></i></Button>
                        </p>
                    </div>
                ) }
            </DropdownMenu>
        </BlockControls>

        // Used for troubleshooting height and width
        const Inspector = <InspectorControls>
            <PanelBody title="Settings" initialOpen={ true }>
                <SelectControl
                    label="Width"
                    value={ width }
                    options={ [
                        { label: 'One', value: 'col-12' },
                        { label: 'Two', value: 'col-md-6' },
                        { label: 'Three', value: 'col-md-4' },
                        { label: 'Four', value: 'col-md-3' }
                    ] }
                    onChange={ width => setAttributes( { width } ) }
                />
                <RangeControl
                    label="Height"
                    min={ 200 } 
                    max={ 500 } 
                    step={ 100 }
                    value={ height }
                    onChange={ height => setAttributes( { height } ) }
                />
            </PanelBody>
        </InspectorControls>

        const Output = <div className={ classes }>
            <div className="bg-wrap" style={ { backgroundImage: `url('${ image ? image : __wp__.assets + '/images/placeholder.jpg' }')` } }>
                <a href="#">
                    <RichText
                        value={ heading }
                        onChange={ heading  => setAttributes( { heading } ) }
                        placeholder="Click to add heading"
                        className="h5"
                        keepPlaceholderOnFocus={ true }
                        preserveWhiteSpace={ true }
                        tagName="p"
                        allowedFormats={ [] }
                    />
                </a>
            </div>
        </div>
        
        return [ CustomControls, Output ]
    }
}

export default Edit