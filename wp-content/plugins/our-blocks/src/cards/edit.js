/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 * @see https://developer.wordpress.org/block-editor/packages/packages-editor/
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
const { Component} = wp.element
const { SelectControl, PanelBody, ToggleControl } = wp.components
const { RichText } = wp.blockEditor
const { InspectorControls } = wp.blockEditor

const Edit = class extends Component {
    
    render = ( { className, setAttributes, isSelected, attributes: { heading, content, size, hasShadow } } = this.props ) => {

        const Output = <div className={`${ className } ${ hasShadow && 'hasShadow' }`}>
            <div className="block-content">
                { ( ( !heading && !content ) || heading || isSelected ) && <RichText
                    className="card-heading"
                    tagName={ `h${size}` }													
                    onChange={ heading => setAttributes( { heading } ) }
                    value={ heading }
                    placeholder="Enter heading"
                    keepPlaceholderOnFocus={ true }
                />	}
                <RichText
                    className="card-content"
                    tagName="div"
                    onChange={ content => setAttributes( { content } ) }
                    value={ content }
                    placeholder="Enter content"
                    keepPlaceholderOnFocus={ true }
                />
            </div>      
        </div>

        const Inspector = <InspectorControls>
            <PanelBody title="Settings" initialOpen={ true } >
                <SelectControl
                    label="Heading Size"
                    value={ size }
                    options={ [ 
                        { value: 2, label: "Largest" },
                        { value: 3, label: "Larger" },
                        { value: 4, label: "Large" }
                    ] }
                    onChange={ size => setAttributes( { size } ) }
                />
                <ToggleControl
                    label={ hasShadow ? 'Remove box shadow' : 'Add box shadow' }
                    checked={ hasShadow }
                    onChange={ hasShadow => setAttributes( { hasShadow } ) }
                />
            </PanelBody>
        </InspectorControls>

        return[ Inspector, Output ]
    }
}

export default Edit