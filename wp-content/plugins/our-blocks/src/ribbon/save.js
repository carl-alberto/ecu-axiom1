/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-editor/
 */
const { InnerBlocks } = wp.blockEditor

const classNames = require( 'classnames' )

const Save = ( { attributes, className } = props ) => {
    return (
        <section className={ classNames( 
            className, 
            'alignfull', 
            { 'full-width': attributes.isFull }, 
            { 'is-left': attributes.isLeft } ) }
            aria-title={ attributes.title }
        >
            <div className="row">
                <InnerBlocks.Content />
            </div>
        </section>
    )
}

export default Save