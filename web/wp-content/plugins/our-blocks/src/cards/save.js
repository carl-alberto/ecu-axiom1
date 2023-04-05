/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 * @see https://developer.wordpress.org/block-editor/packages/packages-editor/
 */
const { Component } = wp.element
const { RichText } = wp.blockEditor

const Save = class extends Component {
    render = ( { attributes: { heading, content, size, hasShadow } } = this.props ) => {
        return <div className={ hasShadow && 'hasShadow' }>
            <div className="block-content">
                { heading && <RichText.Content tagName={`h${size}`} className="card-heading" value={ heading } /> }
                <RichText.Content tagName="div" className="card-content" value={ content } />
            </div>
        </div>
    }
}

export default Save