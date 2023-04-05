import React from "react"
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Button from '@material-ui/core/Button'
import ButtonGroup from '@material-ui/core/ButtonGroup'
import CircularProgress from '@material-ui/core/CircularProgress'
import { 
    SelectControl,
    TextControl,
    ToggleControl
} from '@wordpress/components'
import _ from 'lodash'

import '@wordpress/components/build-style/style.css'
import { getStates } from './util'
import Toast from './components/Toast'
// const { getBlockTypes, createBlock, serialize, rawHandler } = wp.blocks

const axios = require( 'axios' )

class App extends React.Component {

    state = {
        currentPage: 'general',
        loading: true,
        saving: false,
        toast: false,
        options: {},
        pages: [],
        sidebars: []
    }

    componentDidMount = () => {
        axios.get( _wp_.ajax, { params: { action: 'ecu_theme_options' } } )
        .then( ( { data } = res ) => {
            if( data.success ){
                this.setState( { 
                    options: data.data.options,
                    pages: data.data.posts.filter( post => post.post_type === 'page' ),
                    sidebars: data.data.posts.filter( post => post.post_type === 'sidebar' ),
                    loading: false
                } )
            } else {
                console.log( data.data )
            }
        } )
    }

    updateOption = ( option, value, object = false ) => {
        const updatedOptions = { ...this.state.options }
        if( object ){
            updatedOptions[ object ][ option] = value
        } else {
            updatedOptions[ option ] = value
        }
       
        this.setState( { options: updatedOptions } )
    }

    saveOptions = () => {
        const form = new FormData
        form.append( 'action', 'ecu_theme_options' )
        form.append( 'save', true )
        form.append( 'options', JSON.stringify( this.state.options ) )

        axios.post( _wp_.ajax, form )
        .then( ( { data: res } ) => {
            this.setState( { 
                saving: false,
                toast: {
                    type: 'success',
                    data: 'Theme settings successfully saved!'
                }
            } )
        } )
    }
    
    render = () => {
        const { 
            currentPage, 
            options: {
                ecu_address,
                ecu_author_sidebar,
                ecu_blog_sidebar,
                ecu_category_sidebar,
                ecu_phone,
                ecu_contact,
                ecu_date_sidebar,
                ecu_expand_posts,
                ecu_hide_meta,
                ecu_page_template,
                ecu_post_template,
                ecu_second_level
            },
            pages,
            sidebars,
            loading,
            saving,
            toast
        } = this.state

        const pageOptions = [
            { label: 'Select a page', value: 0 },
            ...pages.map( page => {
                return { label: page.post_title, value: page.ID }
            } )
        ],
        sidebarOptions = [
            { label: 'Select a sidebar', value: 0 },
            ...sidebars.map( page => {
                return { label: page.post_title, value: page.ID }
            } )
        ]

        const nav = [
            { label: 'General', slug: 'general' },
            { label: 'Posts', slug: 'posts' },
            { label: 'Sidebars', slug: 'sidebars' }
        ]
        
        return <div id="theme_options wrap">
            <Container fluid>
                <h1>Theme Options</h1>
                <nav>
                    <ButtonGroup color="primary">
                        { nav.map( item => <Button
                            key={ item.slug } 
                            variant={ item.slug === currentPage && 'contained' }
                            onClick={ () => this.setState( { currentPage: item.slug } )}
                        >
                            { item.label }
                            </Button> 
                        ) }
                    </ButtonGroup>
                </nav>
                <Row>
                    <Col sm={ 8 } md={ 6 }>
                        <Choose>
                            <When condition={ loading }>
                                <CircularProgress />
                            </When>
                            <When condition={ currentPage === 'general' }>
                                <h2>General</h2>
                                <hr />
                                <Row>
                                    <Col md={ 12 }>
                                        <h3>Address</h3>
                                    </Col>
                                    <Col md={ 12 }>
                                        <TextControl
                                            label='Location'
                                            value={ ecu_address.location }
                                            onChange={ location => this.updateOption( 'location', location, 'ecu_address' ) }
                                            help='e.g. College of Engineering and Technology, Creative Services, etc.'
                                        />
                                    </Col>
                                    <Col md={ 12 }>
                                        <TextControl
                                            label='Address'
                                            value={ ecu_address.address }
                                            onChange={ address => this.updateOption( 'address', address, 'ecu_address' ) }
                                            help='Defaults to ECU main campus address if empty.'
                                        />
                                    </Col>
                                    <Col md={ 4 }>
                                        <TextControl
                                            label='City'
                                            value={ ecu_address.city }
                                            onChange={ city => this.updateOption( 'city', city, 'ecu_address' ) }
                                            help='Defaults to Greenville if empty.'
                                        />
                                    </Col>
                                    <Col md={ 4 }>
                                        <SelectControl
                                            label="State"
                                            value={ ecu_address.state }
                                            options={ getStates() }
                                            onChange={ state => this.updateOption( 'state', state, 'ecu_address' ) }
                                        />
                                    </Col>
                                    <Col md={ 4 }>
                                        <TextControl
                                            label='Zip'
                                            value={ ecu_address.zip }
                                            onChange={ zip => this.updateOption( 'zip', zip, 'ecu_address' ) }
                                            help='Defaults to 27858 if empty.'
                                        />
                                    </Col>
                                </Row>
                                <hr />
                                <h3>Contact</h3>
                                <Row>
                                    <Col md={ 6 }>
                                        <TextControl
                                            label='Phone'
                                            value={ ecu_phone }
                                            onChange={ ecu_phone => this.updateOption( 'ecu_phone', ecu_phone ) }
                                            help='Sample format: 2523286131'
                                        />
                                    </Col>
                                    <Col md={ 6 }>
                                        <SelectControl
                                            label="Contact Page"
                                            value={ ecu_contact }
                                            options={ pageOptions }
                                            onChange={ ecu_contact => this.updateOption( 'ecu_contact', ecu_contact ) }
                                            help='Displays contact link on page footer.'
                                        />
                                    </Col>
                                </Row>
                                <hr />
                                <h3>Default Templates</h3>
                                <Row>
                                    <Col md={ 6 }>
                                        <SelectControl
                                            label="Default Page Template"
                                            value={ ecu_page_template }
                                            options={ [
                                                { label: 'Full width', value: 'full' },
                                                { label: 'Sidebar (Left)', value: 'sidebar-left' },
                                                { label: 'Sidebar (Right)', value: 'sidebar-right' }
                                            ] }
                                            onChange={ ecu_page_template => this.updateOption( 'ecu_page_template', ecu_page_template ) }
                                            help='Sets the default template for pages. Can be individually changed per page.'
                                        />
                                    </Col>
                                    <Col md={ 6 }>
                                        <SelectControl
                                            label="Default Post Template"
                                            value={ ecu_post_template }
                                            options={ [
                                                { label: 'Full width', value: 'full' },
                                                { label: 'Sidebar (Left)', value: 'sidebar-left' },
                                                { label: 'Sidebar (Right)', value: 'sidebar-right' }
                                            ] }
                                            onChange={ ecu_post_template => this.updateOption( 'ecu_post_template', ecu_post_template ) }
                                            help='Sets the default template for posts. Can be individually changed per post.'
                                        />
                                    </Col>
                                </Row>
                                <If condition={ _wp_.user.blog_owner || _wp_.user.administrator }>
                                <hr />
                                    
                                    <h3>Misc</h3>
                                    <ToggleControl
                                        label={ ecu_second_level ? 'Disable top-level branding' : 'Enable top-level branding' }
                                        checked={ ecu_second_level }
                                        onChange={ ecu_second_level => this.updateOption( 'ecu_second_level', ecu_second_level ) }
                                        help='Displays top-level navigation and footer.'
                                    />
                                </If>
                            </When>
                            <When condition={ currentPage === 'posts' }>
                                <h2>Posts</h2>
                                <hr />
                                <ToggleControl
                                    label='Display full posts in archives'
                                    checked={ ecu_expand_posts }
                                    onChange={ ecu_expand_posts => this.updateOption( 'ecu_expand_posts', ecu_expand_posts ) }
                                    help='Allows archive pages to show the lates posts with next / previous navigation.'
                                />
                                <ToggleControl
                                    label='Hide post details'
                                    checked={ ecu_hide_meta }
                                    onChange={ ecu_hide_meta => this.updateOption( 'ecu_hide_meta', ecu_hide_meta ) }
                                    help='Hides post details such as categories, date, and authors.'
                                />
                            </When>
                            <When condition={ currentPage === 'sidebars' }>
                                <h2>Sidebars</h2>
                                <hr />
                                <SelectControl
                                    label='Blog Sidebar'
                                    value={ ecu_blog_sidebar }
                                    options={ sidebarOptions }
                                    onChange={ ecu_blog_sidebar => this.updateOption( 'ecu_blog_sidebar', ecu_blog_sidebar ) }
                                    help='Sidebar to display on posts page. Configure this page under Settings > Reading. ' 
                                />
                                <SelectControl
                                    label='Category Sidebar'
                                    value={ ecu_category_sidebar }
                                    options={ sidebarOptions }
                                    onChange={ ecu_category_sidebar => this.updateOption( 'ecu_category_sidebar', ecu_category_sidebar ) }
                                    help='Sidebar to display on category archives. e.g. site.ecu.edu/category/category-name'
                                />
                                <SelectControl
                                    label='Author Sidebar'
                                    value={ ecu_author_sidebar }
                                    options={ sidebarOptions }
                                    onChange={ ecu_author_sidebar => this.updateOption( 'ecu_author_sidebar', ecu_author_sidebar ) }
                                    help='Sidebar to display on author archives. e.g. site.ecu.edu/author/author-name'
                                />
                                <SelectControl
                                    label='Date Sidebar'
                                    value={ ecu_date_sidebar }
                                    options={ sidebarOptions }
                                    onChange={ ecu_date_sidebar => this.updateOption( 'ecu_date_sidebar', ecu_date_sidebar ) }
                                    help='Sidebar to display on date archives. e.g. site.ecu.edu/year/month'
                                />
                            </When>
                        </Choose>
                        <hr />
                        <Button
                            onClick={ () => this.setState( { saving: true }, () => this.saveOptions() ) }
                            variant="contained" 
                            color="primary"
                            disabled={ saving }
                        >
                            Save
                        </Button>
                    </Col>
                </Row>
            </Container>
            { toast && <Toast type={ toast.type } data={ toast.data } onClose={ () => this.setState( { toast: false } ) } /> }
        </div>
    }
}

export default App