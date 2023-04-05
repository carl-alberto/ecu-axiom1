const { Component } = wp.element
const { createBlock } = wp.blocks
const { InnerBlocks, InspectorControls } = wp.blockEditor
const { Button, PanelBody, RangeControl } = wp.components
const { select, dispatch } = wp.data

const classNames = require( 'classnames' )

const Edit = class extends Component {

    render = ( { className, clientId, setAttributes, attributes: { width, height } } = this.props ) => {

        const widthClass = [ 'one-item', 'two-item', 'three-item', 'four-item' ]

        const classes = classNames( className, widthClass[ width - 1 ], 'height-' + height)

        const cols = {
            1: 'col-12',
            2: 'col-md-6',
            3: 'col-md-4',
            4: 'col-md-3'
        }
        
        const handleWidthChange = width => {

            const self = select( 'core/block-editor' ).getBlocksByClientId( clientId )[ 0 ]

            self.innerBlocks.map( block => {
                block.attributes.width = cols[width]
                dispatch( 'core/block-editor' ).updateBlockAttributes( block.clientId, block.attributes )
            } )
            setAttributes( { width } )
        }

        const handleHeightChange = height => {
            const self = select( 'core/block-editor' ).getBlocksByClientId( clientId )[ 0 ]

            self.innerBlocks.map( block => {
                block.attributes.height = height
                dispatch( 'core/block-editor' ).updateBlockAttributes( block.clientId, block.attributes )
            } )

            setAttributes( { height } )
        }

        const Output = <section className={ classes }>
            <InnerBlocks 
                template={ [ [ 'wp-blocks/grid-item', { height: height, width: cols[width] } ] ] }
                allowedBlocks={ [ 'wp-blocks/grid-item' ] }
                renderAppender={ () =>
                    <Button
                        isSecondary
                        onClick={ () => 
                            dispatch( 'core/block-editor' ).insertBlocks( createBlock( 'wp-blocks/grid-item', { height: height, width: cols[width] } ), 9999, clientId ) }
                    >
                            <i class="fas fa-plus-circle"></i>
                    </Button>
                }    
            />
        </section>

        const Inspector = <InspectorControls>
            <PanelBody title="Settings" initialOpen={ true }>
                <RangeControl
                    label="Items per row"
                    min={ 1 } 
                    max={ 4 } 
                    step={ 1 }
                    value={ width }
                    onChange={ width => handleWidthChange( width ) }
                />
                <RangeControl
                    label="Item height (px)"
                    min={ 200 } 
                    max={ 500 } 
                    step={ 100 }
                    value={ height }
                    onChange={ height => handleHeightChange( height ) }
                />
            </PanelBody>
        </InspectorControls>
        
        return [ Inspector, Output ]
    }
}

export default Edit