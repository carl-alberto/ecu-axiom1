/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 * @see https://developer.wordpress.org/block-editor/packages/packages-editor/
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
const { Component } = wp.element
const { TextControl, Button, DropdownMenu, ToggleControl, PanelBody, TextareaControl } = wp.components
const { RichText, BlockControls, InspectorControls, MediaUpload } = wp.blockEditor
const classNames = require( 'classnames' )
const { select } = wp.data

/**
 * @see https://github.com/axios/axios
 */
const axios = require('axios').default

import ECUPlaceholder from "../components/ecu-placeholder"

const Edit = class extends Component {

    constructor() {
        super( ...arguments )
        this.state = {
            error: '',
            tempId: ''
        }
    }

    /**
     * Set tempId to pirateID if attribute set
     */
    componentDidMount = ( { attributes } = this.props ) => {
        if( attributes.pirateID ) this.setState( { tempId: attributes.pirateID } )
    }

    render = ( 
        {
            setAttributes,
            className,
            clientId,
            attributes: {
                pirateID,
                useImage,
                image, 
                name, 
                title, 
                email, 
                department, 
                office, 
                mailstop, 
                phone,
                details,
                isVertical,
                isCenter
            } 
        } = this.props 
    ) => {

        const { error, tempId } = this.state

        /**
         * Executes HTTP get method to retrieve content for pirateID
         */
        const handlepirateIDSubmit = () => {

            if( !tempId ) this.setState( { error: 'Please enter a Pirate Id' } )

            // this gets rewritten inside axios response
            const self = this

            axios.get( __wp__.ajax, {
                params: {
                    action: 'block_profile',
                    pirateID: encodeURIComponent( tempId )
                }
            }).then( 
                function ( { data } = res ) {
                    if( !data.success ) { 
                        self.setState( { error: data.data, tempId: '' } ) 
                        return
                    }

                    const { 
                        data: { 
                            name, 
                            title, 
                            email, 
                            department, 
                            office, 
                            mailstop, 
                            phone 
                        } 
                    } = data
                    
                    setAttributes({
                        pirateID: tempId,
                        name,
                        title,
                        email: email ? email.toLowerCase() : '',
                        department,
                        office,
                        mailstop,
                        phone: phone ? phone.replace(/[^0-9\.]+/g, '').length == 7 ? '252-' + phone : phone : ''
                    })
                })
            .catch( function( e ) {
                console.log( e )
            })
        }

        /**
         * Displays the form to enter in pirateID
         */
        const Form = <div><div className={ error ? 'inline-control input-error' : 'inline-control' }>
            <TextControl
                value={ tempId }
                onChange={ tempId => this.setState( { tempId, error: '' } ) }
                placeholder='Enter Pirate ID'
                help={ error && error }
            />
            <Button
                isPrimary
                onClick={ handlepirateIDSubmit }
                disabled={ tempId ? false : true }
            >
                <i class="fas fa-check"></i>
            </Button>
            </div>
            <div className="prof-image-toggle">
            <ToggleControl
                        label={ "Profile Image" }
                        checked={ useImage }
                        onChange={ useImage => setAttributes( { useImage } ) }
                    />
            </div>        
        </div>

        if( !pirateID ) {
            const root = select( 'core/block-editor' ).getBlockRootClientId( clientId )
            return root ? Form : <ECUPlaceholder>{ Form }</ECUPlaceholder> 
        } 

        const CustomControls = <BlockControls> 
            <MediaUpload
                onSelect={ image => setAttributes( { image: image.url } ) }
                type="image"
                value={ image }
                render={ ( { open } ) => 
                    <Button  onClick={ open } ><i class="fas fa-camera fa-lg fa-fw"></i></Button>
                }
            />
            <DropdownMenu 
                icon="admin-settings"
                label="Settings"
                popoverProps={ { position: "bottom left"} }
            >
                { ( { onClose } ) => <>
                    { Form }
                    <ToggleControl
                        label={ "Vertical layout" }
                        checked={ isVertical }
                        onChange={ isVertical => setAttributes( { isVertical } ) }
                    />
                    <ToggleControl
                        label="Center content"
                        checked={ isCenter }
                        onChange={ isCenter => setAttributes( { isCenter } ) }
                    />
                    <div className="text-right">
                        <Button isSmall isSecondary onClick={ onClose } ><i class="fas fa-times"></i></Button>
                    </div>
                </> }
            </DropdownMenu>
        </BlockControls>


        /**
         * Displays block editor output
         */
        const Output = useImage === true ? <div className={ classNames( className, { isVertical: isVertical }, { isCenter: isCenter } ) }>
            <div className="block-content">
                { image && <figure>
                    <img src={ image } className="profile-image" />
                    <Button onClick={ () => setAttributes( { useImage: false } ) } isSecondary isSmall >
                        <i class="fas fa-times"></i>
                    </Button>
                </figure>
                }
                <div class="profile-content">
                    { name && <h2>{ name }</h2> }
                    { title && <p className="profile-title">{ title }</p> }
                    { department && <p className="profile-department">{ department }</p> }
                    { office && <p className="profile-office">{ office }</p> }
                    { mailstop && <p className="profile-mailstop">{ mailstop }</p> }
                    { phone && <a className="profile-phone" href={ `tel:${phone}`}>{ phone }</a> }
                    { email && <a className="profile-email" href={ `mailto:${email}`}>{ email }</a> }
                    { details && <p className="profile-details">{ details }</p> }
                </div>
            </div>
        </div> : <div className={ classNames( className, { isVertical: isVertical }, { isCenter: isCenter } ) }>            
            <div className="block-content">                
                <div class="profile-content">
                    { name && <h2>{ name }</h2> }
                    { title && <p className="profile-title">{ title }</p> }
                    { department && <p className="profile-department">{ department }</p> }
                    { office && <p className="profile-office">{ office }</p> }
                    { mailstop && <p className="profile-mailstop">{ mailstop }</p> }
                    { phone && <a className="profile-phone" href={ `tel:${phone}`}>{ phone }</a> }
                    { email && <a className="profile-email" href={ `mailto:${email}`}>{ email }</a> }
                    { details && <p className="profile-details">{ details }</p> }
                </div>
            </div>
        </div>

        const Inspector = <InspectorControls>
            <PanelBody title="Profile Settings" initialOpen={ true }>
                { Form }
                <hr />
                <TextControl
                    label="Name"
                    value={ name }
                    onChange={ name => setAttributes( { name } ) }
                />
                <TextControl
                    label="Title"
                    value={ title }
                    onChange={ title => setAttributes( { title } ) }
                />
                <TextControl
                    label="Department"
                    value={ department }
                    onChange={ department => setAttributes( { department } ) }
                />
                <TextControl
                    label="Office"
                    value={ office }
                    onChange={ office => setAttributes( { office } ) }
                />
                <TextControl
                    label="Mailstop"
                    value={ mailstop }
                    onChange={ mailstop => setAttributes( { mailstop } ) }
                />
                <TextControl
                    label="Phone"
                    value={ phone }
                    onChange={ phone => setAttributes( { phone } ) }
                />
                <TextControl
                    label="Email"
                    value={ email }
                    onChange={ email => setAttributes( { email } ) }
                />
                <TextareaControl
                    label="Details"
                    value={ details }
                    onChange={ ( details ) => setAttributes( { details } ) }
                />
                <hr />
                <ToggleControl
                    label={ "Vertical layout" }
                    checked={ isVertical }
                    onChange={ isVertical => setAttributes( { isVertical } ) }
                />
                <ToggleControl
                    label="Center content"
                    checked={ isCenter }
                    onChange={ isCenter => setAttributes( { isCenter } ) }
                />
                
            </PanelBody>
        </InspectorControls>
        
        return[ CustomControls, Output, Inspector ]
    }
}

export default Edit