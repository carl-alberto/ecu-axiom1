const { registerPlugin } = wp.plugins
const { TextControl, ToggleControl, SelectControl, Button, RangeControl, TextareaControl } = wp.components
const { PluginDocumentSettingPanel } = wp.editPost
const { select, subscribe } = wp.data
const { MediaUpload } = wp.blockEditor
const { __ } = wp.i18n;

const axios = require('axios').default

import './style.scss'

const SLIDESHOW_LIMIT = 5

registerPlugin('ecu-meta-sidebar', {
	render: class PostPlugin extends React.Component{
        state = {
            post: {},
            meta: {},
            sidebars: [],
            categories: [],
            posts: [],
            banner: {}
        }

        componentDidMount = () => {

            this.initSubscriptions()

            const currentPost = select( 'core/editor' ).getCurrentPost()

            axios.get( _wp_.ajax, { params: { action: 'ecu_meta_sidebar' } } )
            .then( ( { data } = res ) => {
                if( data.success ){
                    //console.log( data.data )
                    this.setState( {
                        post: currentPost,
                        meta: currentPost.meta,
                        banner: JSON.parse( currentPost.meta.ecu_banner ),
                        posts: data.data.posts,
                        sidebars: data.data.sidebars,
                        categories: data.data.categories,
                    } )
                } else {
                    console.log( data.data )
                }
            } )
        }

        initSubscriptions = () => {
            subscribe( () => {
                if( select('core/editor').isSavingPost() ) {
                    this.updateMeta()
                }
            })
        }

        updateOption = ( value, name ) => {
            const updated = { ...this.state.meta }
            updated[name] = value
            this.setState( { meta: updated } )
        }

        updateMeta = () => {
            const form = new FormData
            form.append( 'action', 'ecu_meta_sidebar_update' )
            form.append( 'post', JSON.stringify( this.state.post ) )
            form.append( 'meta', JSON.stringify( this.state.meta ) )
            axios.post( _wp_.ajax, form )
            .then( ( { data: res } ) => console.log( res ) )
        }

        updateBanner = ( value, name ) => {
            const updatedBanner = { ...this.state.banner }
            updatedBanner[name] = value

            const updatedMeta = { ...this.state.meta }
            updatedMeta.ecu_banner = JSON.stringify( updatedBanner )

            this.setState( { banner: updatedBanner, meta: updatedMeta } )
        }

            handleUpdateSlide = ( value, i, field ) => {
                const updated = { ...this.state.banner }
                updated.slides[i][field] = value
                this.updateBanner( updated.slides, 'slides' )
            }

            handleNewSlide = () => {
                const updated = { ...this.state.banner }

                if( updated.hasOwnProperty('slides') ){
                    updated.slides.push( { type: 'image' } )
                } else {
                    updated.slides = [ { type: 'image' } ]
                }
                this.updateBanner( updated.slides, 'slides' )
            }

            handleRemoveSlide = i => {
                let updated = { ...this.state.banner },
                slides = updated.slides.filter( ( slide, n ) => n !== i )

                updated.slides = slides

                this.updateBanner( updated.slides, 'slides' )
            }

            handleMoveSlide = ( i, direction ) => {
                let updated = { ...this.state.banner },
                slides = updated.slides,
                temp

                if( direction === 'up' ){
                    temp = slides[ i - 1 ] // move up
                    slides[ i - 1 ] = slides[ i ]
                } else {
                    temp = slides[ i + 1 ]
                    slides[ i + 1 ] = slides[i]
                }
                slides[i] = temp

                updated.slides = slides

                this.updateBanner( updated.slides, 'slides' )
            }

        render = () => {
            const { post, meta, sidebars, banner, categories } = this.state,
            isPage = post.type === 'page',
            isPost = post.type === 'post'

            return(
                <>
                    <PluginDocumentSettingPanel name="general" title={ __( 'General', 'txtdomain') } icon="nothing" initialOpen="true">
                            <TextControl
                                label='Display Title'
                                value={ meta.ecu_alt_title }
                                onChange={ ecu_alt_title => this.updateOption( ecu_alt_title, 'ecu_alt_title' ) }
                                help='Changes the title on the page'
                            />
                            <br />
                            { isPost && <>
                                <TextControl
                                    label='Subtitle'
                                    value={ meta.ecu_subtitle }
                                    onChange={ ecu_subtitle => this.updateOption( ecu_subtitle, 'ecu_subtitle' ) }
                                    help='Adds a subtitle under the page title'
                                />
                                <br />
                            </>}
                            { isPost && <>
                                <TextControl
                                    label='Spark URL'
                                    value={ meta.ecu_spark }
                                    onChange={ ecu_spark => this.updateOption( ecu_spark, 'ecu_spark' ) }
                                    help='Links to external posts in archive pages'
                                />
                                <br />
                            </>}
                            <ToggleControl
                                    label='Hide Title'
                                    checked={ meta.ecu_hide_h1 }
                                    onChange={ ecu_hide_h1 => this.updateOption( ecu_hide_h1, 'ecu_hide_h1' ) }
                                    help='Hides page title from view'
                            />
                            <ToggleControl
                                label='Enable back to top'
                                checked={ meta.ecu_to_top }
                                onChange={ ecu_to_top => this.updateOption( ecu_to_top, 'ecu_to_top' ) }
                                help='Displays back to top button'
                            />
                        </PluginDocumentSettingPanel>
                        <PluginDocumentSettingPanel name='banner' title={ __( 'Banner', 'txtdomain') } icon="nothing" initialOpen="true">
                            <SelectControl
                                label='Select banner type'
                                value={ banner.type }
                                options={ [
                                    { label: 'None', value: 'none' },
                                    { label: 'Slideshow', value: 'slideshow' },
                                    { label: 'Image', value: 'image' },
                                    { label: 'Posts', value: 'posts' },
                                    { label: 'Video', value: 'video' }
                                ] }
                                onChange={ type => this.updateBanner( type, 'type') }
                            />
                            <br />
                            { banner.type && banner.type !== 'none' && <>
                            <ToggleControl
                                label='Full width banner'
                                checked={ meta.ecu_banner_full }
                                onChange={ ecu_banner_full => this.updateOption( ecu_banner_full, 'ecu_banner_full' ) }
                                help='100% width page banner'
                            /><hr /></>}

                            { banner.type === 'slideshow' && <>
                                <Button isPrimary onClick={ () => this.handleNewSlide() } disabled={ banner.slides && banner.slides.length === 5 } >Add Slide</Button>
                                <Button isDestructive onClick={ () => this.updateBanner( [], 'slides' ) }>Reset</Button>
                                <hr />
                                {
                                    banner.slides &&
                                    banner.slides.length > 0 &&
                                    banner.slides.map( ( slide, n ) =>
                                        <div className="slide-wrap" key={ n }>
                                            <div className="slide-header">
                                                <h3>Slide {n + 1}</h3>
                                                <div class="slide-controls">
                                                    { banner.slides.length > 1 && <>
                                                        <Button
                                                            isTertiary
                                                            onClick={ () => this.handleMoveSlide( n, 'up' ) }
                                                            disabled={ n + 1 === 1 }
                                                        >
                                                            <i class="fas fa-chevron-up"></i>
                                                        </Button>
                                                        <Button
                                                            isTertiary
                                                            onClick={ () => this.handleMoveSlide( n, 'down' ) }
                                                            disabled={ n + 1 === banner.slides.length }
                                                        >
                                                            <i class="fas fa-chevron-down"></i>
                                                        </Button>
                                                    </>}
                                                    <Button isDestructive onClick={ () => this.handleRemoveSlide( n ) }><i class="fas fa-times"></i></Button>
                                                </div>
                                            </div>
                                            <div className="slide-body">
                                                <SelectControl
                                                    label='Slide type'
                                                    value={ slide.type }
                                                    options={ [
                                                        { label: 'Image', value: 'image' },
                                                        { label: 'Post', value: 'post' },
                                                    ] }
                                                    onChange={ type => this.handleUpdateSlide( type, n, 'type' ) }
                                                />
                                                { slide.type === 'image' && <>
                                                    { slide.image && <img src={ slide.image } /> }
                                                    <MediaUpload
                                                        onSelect={ image => this.handleUpdateSlide( image.url, n, 'image' ) }
                                                        allowedTypes={ ['image'] }
                                                        value={ slide.image || false }
                                                        render={ ( { open } ) =>
                                                            <Button isPrimary onClick={ open } >{ slide.image ? <i class="fas fa-edit"></i> : <i class="fas fa-image"></i> }</Button>
                                                        }
                                                    />
                                                    <hr />
                                                    <TextControl
                                                        label='Caption heading'
                                                        value={ slide.heading }
                                                        onChange={ heading => this.handleUpdateSlide( heading, n, 'heading' ) }
                                                    />
                                                    <br />
                                                    <SelectControl
                                                        label='Caption location'
                                                        value={ slide.caption_position }
                                                        options={ [
                                                            { label: 'Random', value: 'random' },
                                                            { label: 'Top Left', value: 'top-left' },
                                                            { label: 'Top Right', value: 'top-right' },
                                                            { label: 'Bottom Left', value: 'bottom-left' },
                                                            { label: 'Bottom Right', value: 'bottom-right' },
                                                        ] }
                                                        onChange={ caption_position => this.handleUpdateSlide( caption_position, n, 'caption_position' ) }
                                                    />
                                                    <br />
                                                    <TextareaControl
                                                        label='Caption'
                                                        value={ slide.caption }
                                                        onChange={ caption => this.handleUpdateSlide( caption, n, 'caption' ) }
                                                    />
                                                </> }
                                                { slide.type === 'post' && <>
                                                    <SelectControl
                                                        label='Select post'
                                                        value={ slide.post }
                                                        options={ this.state.posts }
                                                        onChange={ post => this.handleUpdateSlide( post, n, 'post' ) }
                                                    />
                                                    <SelectControl
                                                        label='Caption location'
                                                        value={ slide.caption_position }
                                                        options={ [
                                                            { label: 'Random', value: 'random' },
                                                            { label: 'Top Left', value: 'top-left' },
                                                            { label: 'Top Right', value: 'top-right' },
                                                            { label: 'Bottom Left', value: 'bottom-left' },
                                                            { label: 'Bottom Right', value: 'bottom-right' },
                                                        ] }
                                                        onChange={ caption_position => this.handleUpdateSlide( caption_position, n, 'caption_position') }
                                                    />
                                                </> }
                                            </div>
                                        </div>
                                    )
                                }
                            </> }

                            { banner.type === 'image' && <>
                                { banner.image && <img src={ banner.image } /> }
                                <MediaUpload
                                    onSelect={ image => this.updateBanner( image.url , 'image' ) }
                                    allowedTypes={ ['image'] }
                                    value={ banner.image || false }
                                    render={ ( { open } ) =>
                                        <Button isPrimary onClick={ open } >{ banner.image ? <i class="fas fa-edit"></i> : <i class="fas fa-image"></i> }</Button>
                                    }
                                />
                            </> }

                            { banner.type === 'posts' && <>
                                <SelectControl
                                    label='Select a category'
                                    value={ banner.category }
                                    options={ categories }
                                    onChange={ category => this.updateBanner( category, 'category') }
                                    help='Category that posts are loaded from'
                                />
                                <br />
                                <SelectControl
                                    label='Caption location'
                                    value={ banner.caption_position }
                                    options={ [
                                        { label: 'Random', value: 'random' },
                                        { label: 'Top Left', value: 'top-left' },
                                        { label: 'Top Right', value: 'top-right' },
                                        { label: 'Bottom Left', value: 'bottom-left' },
                                        { label: 'Bottom Right', value: 'bottom-right' },
                                    ] }
                                    onChange={ caption_position => this.updateBanner( caption_position, 'caption_position') }
                                />
                                <br />
                                <RangeControl
                                    label='Number of posts'
                                    min={ 1 }
                                    max={ 6 }
                                    step={ 1 }
                                    value={ parseInt( banner.number_of_posts ) || 3 }
                                    onChange={ number_of_posts => this.updateBanner( number_of_posts, 'number_of_posts') }
                                    help='Number of posts with featured / banner image to display'
                                />
                            </> }

                            { banner.type === 'video' && <>
                                { banner.video && <video controls>
                                    <source src={ banner.video} type="video/mp4" />
                                    </video>}
                                <MediaUpload
                                    onSelect={ video => this.updateBanner( video.url , 'video' ) }
                                    allowedTypes={ ['video'] }
                                    value={ banner.video || false }
                                    render={ ( { open } ) =>
                                        <Button isPrimary onClick={ open } >{ banner.video ? <i class="fas fa-edit"></i> : <i class="fas fa-video"></i> }</Button>
                                    }
                                />
                            </> }
                        </PluginDocumentSettingPanel>

                        <PluginDocumentSettingPanel name='sidebar' title={ __( 'Sidebars', 'txtdomain') } icon="nothing" initialOpen="true">
                            { sidebars.length === 0 && <p>No sidebars available. <a href={ _wp_.admin + 'widgets.php' }>Click here</a> to create one.</p>}
                            { sidebars.length > 0 && <>
                                <SelectControl
                                    label='Select a sidebar'
                                    name='sidebar'
                                    value={ meta.ecu_sidebar }
                                    options={ sidebars }
                                    onChange={ ecu_sidebar => this.updateOption( ecu_sidebar, 'ecu_sidebar' ) }
                                    help='Displays selected sidebar on the page if the sidebar template is selected in Page Attributes.'
                                />
                                <br />
                                <ToggleControl
                                    label={ meta.ecu_sidebar_position ? 'Right' : 'Left' }
                                    checked={ meta.ecu_sidebar_position }
                                    onChange={ ecu_sidebar_position => this.updateOption( ecu_sidebar_position, 'ecu_sidebar_position' ) }
                                    help='Switches the position of the sidebar'
                                />
                            </>}
                    </PluginDocumentSettingPanel>
                </>
            )
        }
    }
})
