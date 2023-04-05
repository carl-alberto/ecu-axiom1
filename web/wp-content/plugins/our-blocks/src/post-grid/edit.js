const { Component } = wp.element
const { SelectControl, PanelBody, RangeControl, TextControl, Button, Spinner } = wp.components
const { InspectorControls } = wp.blockEditor

/**
 * @see https://lodash.com/docs/4.17.15
 */
import _ from 'lodash'

import ECUPlaceholder from "../components/ecu-placeholder"

const Edit = class extends Component {

    constructor() {
        super( ...arguments )
        this.state = {
            allCategories: [],
            posts: [],
            newCategory: '',
            newCategoryLabel: '',
            active: '',
            loading: true
        }
    }
    
    componentDidMount = async ( { attributes: { categories } } = this.props ) => {
        const apiData = await fetch( __wp__.api + '/categories?per_page=100' ).then( res => res.json() )
        const cats = categories.map( cat => cat.value )
        const allCategories = _.compact( apiData.map( cat => { 
            if( !cats.includes( cat.slug ) ) return { label: cat.name, value: cat.slug } 
        }) )
        
        var state = { 
            allCategories, 
            newCategory: allCategories[0].label, 
            loading: false 
        }

        if( !_.isEmpty( categories ) ) {
            const posts = await fetch( __wp__.ajax + '?action=block_post_grid_posts&category=' + categories[0].value ).then( res => res.json() )
            state.posts = [ { category: categories[0].value, posts: posts.data } ]
            state.active = categories[0].value
        }
        this.setState( state )
    }

    render = ( 
        {
            setAttributes,
            className,
            attributes: {
                height,
                width,
                categories,
                total
            }
        } = this.props 
    ) => {
        const { allCategories, posts, newCategory, newCategoryLabel, active, loading } = this.state
        
        const currentPosts = posts.map( p => { if ( p.category === active) return p.posts } )

        const currentDisplay = _( currentPosts[0] ).slice( 0 ).take( total ).value()


        const handleNewCategory = async () => {
            const newCategories = [ ...categories, { label: newCategoryLabel, value: newCategory } ] 
            let tempState = {
                allCategories: allCategories.filter( c => c.value !== newCategory ),
                active: newCategory,
                loading: false
            }

            tempState.newCategory = tempState.allCategories.length > 0 ? tempState.allCategories[0].value : '';
            tempState.newCategoryLabel = tempState.allCategories.length > 0 ? tempState.allCategories[0].slug : '';

            if(  _.isEmpty( posts.filter( p => p.category === newCategory ) ) ){
                console.log(  __wp__.ajax + '?action=block_post_grid&category=' + newCategory )
                const newPosts = await fetch( __wp__.ajax + '?action=block_post_grid_posts&category=' + newCategory ).then( res => res.json() )

                tempState.posts = [ 
                    ...posts, 
                    { 
                        category: newCategory, 
                        posts: newPosts.data, 
                        newCategoryLabel: '' 
                    } 
                ]
            }
            this.setState( tempState )
            setAttributes( { categories: newCategories } )
        }

        const handleUpdateLabel = ( category, newLabel ) => {
            const updated = [ ...categories ]
            const index = updated.indexOf( category )
            updated[ index ] = { ...category }
            updated[ index ].label = newLabel
            setAttributes( { categories: updated } )
        }

        const handleRemoveCategory = category => {
            const updated = categories.filter( c => c.value !== category.value )
            const newActive = updated.length > 0 ? updated[0].value : ""

            setAttributes( { categories: updated } )

            this.setState( { active: newActive } )
        }

        const handleUpdateActive = async newActive => {
            let tempState = { active: newActive }
            if(  _.isEmpty( posts.filter( p => p.category === newActive ) ) ){
                const newPosts = await fetch( __wp__.ajax + '?action=block_post_grid&category=' + newCategory ).then( res => res.json() )
                tempState.posts = [ ...posts, { category: newCategory, posts: newPosts.data } ]
            }
            this.setState( tempState )
        }

        const handleMoveUp = i => {
            if( !i ) return

            const updated = [ ...categories ]
            const temp = updated[ i - 1 ]
            updated[ i - 1 ] = updated[i]
            updated[i] = temp
            setAttributes( { categories: updated } )
        }

        const handleMoveDown = i => {
            if( i >= categories.length - 1 ) return

            const updated = [ ...categories ]
            const temp = updated[ i + 1 ]
            updated[ i + 1 ] = updated[i]
            updated[i] = temp
            setAttributes( { categories: updated } )
        }

        const cols = {
            1: 'col-12',
            2: 'col-md-6',
            3: 'col-md-4',
            4: 'col-md-3'
        }
        
        const Markup = <>
            <ul>
                { !_.isEmpty( categories) && categories.map( category =>
                    <li key={ category.value } >
                        <a 
                            className={ category.value == active && 'active' }
                            href="#"
                            onClick={ () => handleUpdateActive( category.value ) }
                        >
                            { category.label ? category.label : category.value }
                        </a>
                    </li>
                )}
            </ul>
            { loading && <div style={ { textAlign: 'center' } }><Spinner /></div>}

            { _.isEmpty( currentDisplay ) && !loading && <p>No posts found for this category. Please make sure your posts have featured images.</p> }
            { !_.isEmpty( currentDisplay ) && <div className="row">
                { currentDisplay.map( post => <div key={ post.id } className={ `wp-block-wp-blocks-grid-item ${ cols[width] }`}>
                    <div className="bg-wrap" style={ { 
                            backgroundImage: `url('${ post.image ? post.image : __wp__.assets + '/images/placeholder.jpg' }')`,
                            height: height
                        } }>
                        <a href="#">
                            <p>{ post.post_title }</p>
                        </a>
                    </div>
                </div>
                ) } 
            </div>}
            
        </>

        const Output = <div className={className} >
            { !_.isEmpty( categories ) ? Markup : <ECUPlaceholder style="offset-3 col-6" ><p className="text-center">Please add a category to begin <i class="ml-2 fas fa-arrow-right"></i></p></ECUPlaceholder> }
        </div>

        const Inspector = <InspectorControls>
            <PanelBody title="Add New Category" initialOpen={ true } >
                <SelectControl
                    label="Select Category"
                    value={ newCategory }
                    options={ allCategories }
                    onChange={ newCategory => this.setState( { newCategory } ) }
                />
                <TextControl
                    label="Label"
                    value={ newCategoryLabel }
                    onChange={ label => this.setState( { newCategoryLabel: label } ) }
                />
                <Button 
                    isSecondary
                    className="btn-block"
                    onClick={ () => { 
                        this.setState( { loading: true } )
                        handleNewCategory()
                    } }
                    disabled={ allCategories.length < 1 }
                >
                    <i className="fas fa-plus mr-2"></i> Add Category
                </Button>
            </PanelBody>
            <PanelBody title="Settings" initialOpen={ true } >
                <RangeControl
                    label="Maximum posts per category to display"
                    min={ 1 } 
                    max={ 12 } 
                    step={ 1 }
                    value={ total }
                    onChange={ total => setAttributes( { total } ) }
                    help="*Post must have featured image"
                />
                <RangeControl
                    label="Posts per row"
                    min={ 1 } 
                    max={ 4 } 
                    step={ 1 }
                    value={ width }
                    onChange={ width => setAttributes( { width } ) }
                />
                <RangeControl
                    label="Post height (px)"
                    min={ 200 } 
                    max={ 500 } 
                    step={ 100 }
                    value={ height }
                    onChange={ height => setAttributes( { height } ) }
                />
            </PanelBody>
            { !_.isEmpty( categories) && <PanelBody title="Manage Categories" initialOpen={ true } >
                { categories.map( (category, i ) =>
                    <>
                        <TextControl
                            label={ <>
                                <Button
                                    isSecondary
                                    isSmall
                                    onClick={ () => handleMoveUp( i )}
                                    disabled={ !i }
                                >
                                    <i className="fas fa-arrow-up"></i>
                                </Button>
                                <Button
                                    isSecondary
                                    isSmall
                                    onClick={ () => handleMoveDown( i )}
                                    disabled={ i == categories.length - 1}
                                >
                                    <i className="fas fa-arrow-down"></i>
                                </Button>
                                <Button
                                    isSecondary
                                    isSmall
                                    onClick={ () => handleRemoveCategory( category )}
                                    className="mr-2"
                                >
                                    <i className="fas fa-times"></i>
                                </Button>
                                { category.value }
                            </>
                            }
                            value={ category.label }
                            placeholder="Enter new label"
                            onChange={ label => handleUpdateLabel( category, label ) }
                        />
                        
                    </>
                ) }
            </PanelBody>
            }
            
        </InspectorControls>

        return [ Output, Inspector ]
    }
}

export default Edit