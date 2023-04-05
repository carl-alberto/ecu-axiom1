/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-data/
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 * @see https://developer.wordpress.org/block-editor/packages/packages-editor/
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */

const { Component } = wp.element
const { InnerBlocks, InspectorControls, BlockControls } = wp.blockEditor
const { TextControl, PanelBody, ToggleControl, Button, DropdownMenu } = wp.components

const classNames = require( 'classnames' )

const Edit = class extends Component {
    constructor() {
        super( ...arguments )
        this.state = {
            content: [],
            callout: []
        }
    }
    /**
     * Renders block on gutenberg editor
     * @param {*} props 
     */
    render = ( 
        { 
            attributes: { title, isFull, isLeft }, 
            setAttributes,
            className,
        } = this.props 
    ) => {

        const Options = <>
            <TextControl
                label="Enter ribbon title:"
                value={ title }
                onChange={ ( title ) => setAttributes( { title } ) }
            />
            { !isFull && <ToggleControl
                label="Left Callout"
                checked={ isLeft }
                onChange={ isLeft => setAttributes( { isLeft } ) }
            /> }
            <ToggleControl
                label="No Callout"
                checked={ isFull }
                onChange={ isFull => setAttributes( { isFull } ) }
            />
            
        </>

        const CustomControls = <BlockControls> 
            <DropdownMenu 
                icon="admin-settings"
                label="Settings"
                popoverProps={ { position: "bottom left"} }
            >
                { ( { onClose } ) => (
                    <div className="p-2">
                        { Options }
                    </div>
                ) }
            </DropdownMenu>
        </BlockControls>

        const Output = <div className={ classNames( className, 'alignfull', { 'full-width': isFull }, { 'is-left': isLeft } ) }>
            <InnerBlocks 
                template = { [
                    [ 'wp-blocks/ribbon-content', {} ],
                    [ 'wp-blocks/ribbon-callout', {} ]
                ] }
                allowedBlocks={ [
                    'ecu-blocks/ribbon-callout', 
                    'ecu-blocks/ribbon-content'
                ] }	
                templateLock='all'
                templateInsertUpdatesSelection={ false }
            />
        </div>

        const Inspector = <InspectorControls>
            <PanelBody title="Ribbon Settings" initialOpen={ true }>
                { Options }
            </PanelBody>
        </InspectorControls>

        return [ Inspector, Output, CustomControls ]
    }
}

export default Edit