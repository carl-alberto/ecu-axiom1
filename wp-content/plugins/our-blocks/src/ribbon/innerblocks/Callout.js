const { registerBlockType, createBlock } = wp.blocks
const { InnerBlocks, Inserter } = wp.blockEditor
const { Button } = wp.components
const { select, dispatch } = wp.data

registerBlockType('wp-blocks/ribbon-callout', {
    title: 'Ribbon Callout',
    icon: 'dashicons-menu',
    category: 'layout',
    parent: ['wp-blocks/ribbon'],
    edit: ( { clientId, isSelected } = props ) => {
        if( isSelected ) {
            const root = select( 'core/block-editor' ).getBlockRootClientId( clientId )
            dispatch( 'core/block-editor' ).selectBlock( root ) 
        }
        return <div className="wp-block-wp-blocks-ribbon-callout">
            <div className="callout">
                <InnerBlocks 
                    templateLock={ false }
                    renderAppender={ () => <Inserter
                        rootClientId={ clientId }
                        renderToggle={ ( { onToggle, disabled } ) => (
                            <Button
                                isPressed
                                onClick={ onToggle }
                                disabled={ disabled }
                            >
                                <i class="fas fa-plus mr-2"></i> Add Block
                                </Button>
                        ) }
                        isAppender
                    />
                    }
                />
            </div>
        </div>
    },
    save: ( props ) => {
        return (
            <div className="order-1 order-md-2 col-sm-4 col-md-3">
                <div className="callout">
                    <InnerBlocks.Content />
                </div>
            </div>
        )
    }
})
