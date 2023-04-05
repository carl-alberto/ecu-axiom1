const { Component } = wp.element
const { createBlock } = wp.blocks
const { InnerBlocks } = wp.blockEditor
const { Button } = wp.components
const { dispatch } = wp.data

const Edit = class extends Component {

    render = ( { className, clientId } = this.props ) => {

        return <section className={ className }>
            <InnerBlocks 
                template={ [ [ 'wp-blocks/dash-item', {} ] ] }
                allowedBlocks={ [ 'wp-blocks/dash-item' ] }
                renderAppender={ () =>
                    <Button
                        isSecondary
                        onClick={ () => 
                            dispatch( 'core/block-editor' ).insertBlocks( createBlock( 'wp-blocks/dash-item', {} ), 9999, clientId ) }
                    >
                            <i class="fas fa-plus-circle"></i>
                    </Button>
                }    
            />
        </section>
    }
}

export default Edit