const { Component } = wp.element

const Edit = class extends Component {

    constructor() {
        super( ...arguments )
        this.state = {
        }
    }

    /**
     * Fires before the initial rendering of this block
     */
    componentDidMount = ( { attributes } = this.props ) => {
    
    }

    render = ( 
        {
            setAttributes,
            className,
            attributes
        } = this.props 
    ) => {
        return <div>Content to be displayed in the editor.</div>
    }
}

export default Edit