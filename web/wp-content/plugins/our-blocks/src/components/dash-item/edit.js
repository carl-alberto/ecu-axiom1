const { Component } = wp.element
const { RichText, InnerBlocks, InspectorControls } = wp.blockEditor
const { Button, SelectControl, ToggleControl, TextControl, DropdownMenu, PanelBody } = wp.components
const { MediaUpload }  = wp.editor
const { select } = wp.data

const classNames = require( 'classnames' )

const axios = require('axios').default

import IconPicker from "../icon-picker"

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

    render = ( {
        className,
        clientId,
        setAttributes,
        attributes: {
            heading,
            image,
            url,
            internal,
            icon,
            target,
            content,
            isVertical,
            type
        } } = this.props ) => {

        const root = select( 'core/block-editor' ).getBlock( clientId )
        console.log( root )

        const imgPlaceholder = __wp__.assets + '/images/placeholder.jpg'

        const Inspector = <InspectorControls>
            <PanelBody title="Settings" initialOpen={ true }>
                <SelectControl
                    label="Dash Item Type"
                    value={ type }
                    options={ [
                        { value: false, label: '-' },
                        { value: 'image', label: 'Image' },
                        { value: 'content', label: 'Content' },
                        { value: 'list', label: 'List' },
                    ] }
                    onChange={ type => setAttributes( { type } ) }
                />
                { type == 'image' && <>
                { image && <figure>
                    <img src={ image ? image : imgPlaceholder } />
                </figure>}
                <MediaUpload
                    onSelect={ image => setAttributes( { image: image.url } ) }
                    type="image"
                    value={ image }
                    render={ ( { open } ) => <>
                        <Button isPrimary className="mb-2" onClick={ open } >{ image && image !== imgPlaceholder ? "Change Image" : "Add Image" }</Button>
                        { image && image !== imgPlaceholder && <Button isDestructive className="mb-5" onClick={ () => setAttributes( { type: '' } ) } >Remove Image</Button>}
                    </>
                    }
                /></>}
                <br/><br/>
                <IconPicker icon={ icon } onClick={ icon => setAttributes( { icon } ) } />
                <ToggleControl
                    label="Stacked Header"
                    checked={ isVertical }
                    onChange={ isVertical => setAttributes( { isVertical } ) }
                />
            </PanelBody>
            <PanelBody title="Dash Item Link" initialOpen={ true }>
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
                    label="Select page from site"
                    checked={ internal }
                    onChange={ internal => setAttributes( { internal } ) }
                />
                <ToggleControl
                    label="Open in new tab"
                    checked={ target }
                    onChange={ target => setAttributes( { target } ) }
                />
            </PanelBody>
        </InspectorControls>

        const headerClasses = classNames( 'block-header', { isVertical : isVertical } ),
        headerContent = <>
            { icon && <i className={ `${icon.prefix} fa-${icon.iconName} mr-3`} />}
            <RichText
                value={ heading }
                onChange={ heading  => setAttributes( { heading } ) }
                placeholder="Click to add heading"
                className="block-heading"
                keepPlaceholderOnFocus={ true }
                preserveWhiteSpace={ true }
                tagName="span"
                allowedFormats={ [] }
            />
        </>
        const Output = <div className={ className }>
            <div className="block-inner">
                { type == 'image' && <figure>
                    <img src={ image ? image : imgPlaceholder } alt="" />
                </figure> }
                { url ? <a href="#" className={headerClasses}>{headerContent}</a> : <div className={ headerClasses }>{headerContent}</div>}

                { type == 'list' && <InnerBlocks
                    template ={ [ ['core/list' ] ] }
                    allowedBlocks={ ['core/list'] }
                    templateLock='all'
                /> }

                { type == 'content' && <RichText
                    value={ content }
                    onChange={ content  => setAttributes( { content } ) }
                    placeholder="Click to add content"
                    className="block-content"
                    keepPlaceholderOnFocus={ true }
                    preserveWhiteSpace={ true }
                    tagName="p"
                /> }

            </div>

        </div>

        return [ Output, Inspector ]
    }
}

export default Edit