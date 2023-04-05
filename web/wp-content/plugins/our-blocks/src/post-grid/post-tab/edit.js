const { Component } = wp.element
const { SelectControl } = wp.components;

const Edit = class extends Component {

    constructor() {
        super( ...arguments )
        this.state = {
            categories: []
        }
    }

    /**
     * Fires before the initial rendering of this block
     */
    componentDidMount = async () => {
        // const api = await fetch( __wp__.api + '/categories?per_page=100' ).then( res => res.json() )
        // const categories = api.reduce( ( obj, item ) => {
        //     obj.push( { id: item.id, name: item.name } )
        //     return obj
        // }, [] )
        // this.setState( { categories } )
    }

    render = ( 
        {
            setAttributes,
            className,
            attributes
        } = this.props 
    ) => {
        console.log( attributes )
        const Output = <div className={ className } >
           TAB CONTENT
        </div>

        return Output
    }
}

export default Edit