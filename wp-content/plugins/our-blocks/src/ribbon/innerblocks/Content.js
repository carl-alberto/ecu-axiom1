const { registerBlockType, createBlock } = wp.blocks
const { InnerBlocks, Inserter } = wp.blockEditor
const { Button } = wp.components
const { select, dispatch } = wp.data

registerBlockType('wp-blocks/ribbon-content', {
    title: 'Ribbon Content',
    icon: 'dashicons-menu',
    category: 'layout',
    parent: ['wp-blocks/ribbon'],
    edit: ( { className, clientId, isSelected } = props ) => {
        if( isSelected ) {
            const root = select( 'core/block-editor' ).getBlockRootClientId( clientId )
            dispatch( 'core/block-editor' ).selectBlock( root ) 
        }
        return <div className={ className }>
            <div className="content">
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
            <div className="order-2 order-md-1 col-sm-8 col-md-9" >
                <div className="content">
                    <InnerBlocks.Content />
                </div>
            </div>
        )
    }
})
