/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-element/
 */
const { Component, Fragment } = wp.element
const { select } = wp.data

import ECUPlaceholder from "../components/ecu-placeholder"

const Edit = class extends Component {

    /**
     * Initialize state
     */
    constructor() {
        super( ...arguments )
        this.state = {
            error: '',
            tempUrl: ''
        }
    }


    /**
     * Set tempUrl to url if attribute set
     */
    componentDidMount = ( { attributes } = this.props ) => {
        if( attributes.url ) this.setState( { tempUrl: attributes.url } )
    }

    render = ( { clientId, attributes: { url, height, width, disableScroll }, setAttributes, className } = this.props ) => {

        const { error, tempUrl } = this.state

        const { TextControl, FocusableIframe, PanelBody, RangeControl, ToggleControl, Button } = wp.components
        const { InspectorControls } = wp.blockEditor

        /**
         * Whitelisted domains
         */
        const allowedDomains = [
            'navigator.ecu.edu',
            'public.tableau.com',
            'app.powerbi.com',
            'mediasite.ecu.edu',
            'cdn.knightlab.com',
            'www.google.com',
            'www.imleagues.com',
            'www.youvisit.com',
            'www.nextbus.com',
            'ecu-svs.librarika.com',
            'ecugrad.radiusbycampusmgmt.com',
            'anchor.fm'
        ]

        const whiteList = __wp__.hosts.length > 0 ? __wp__.hosts : allowedDomains

        /**
         * Ensures supplied URL is whitelisted
         */
        const handleUrlChange = () => {
            try {
                const input = new URL( tempUrl )
                console.log( input )
                whiteList.includes( input.host ) ? setAttributes( { url: tempUrl } ) : this.setState( { error: 'Invalid URL entered'})
            } catch( e ) {
                this.setState( { error: 'Invalid URL' } )
            }
        }

        const Form = <div className="inline-wp-form">
            { error && <div className="error">{ error }</div>}
            <div className="inline-control d-flex align-items-end">
                <TextControl
                    value={ tempUrl }
                    onChange={ tempUrl => this.setState( { tempUrl } ) }
                    placeholder="Please enter URL"
                />
                <Button
                    isPrimary
                    onClick={ handleUrlChange }
                    disabled={ tempUrl ? false : true }
                >
                    <span class="dashicons dashicons-search"></span>
                </Button>
            </div>
        </div>

        if( !url ) {
            const root = select( 'core/block-editor' ).getBlockRootClientId( clientId )
            return root ? Form : <ECUPlaceholder>{ Form }</ECUPlaceholder>
        }

        /*
         * Editor Output
         */

        const Output = () => {
            let markup
            if( !url ){
                const root = select( 'core/block-editor' ).getBlockRootClientId( clientId )
                markup = root ? Form : <ECUPlaceholder>{ Form }</ECUPlaceholder>
            } else {
                const parseUrl = new URL('', url);
                if ( 'public.tableau.com' === parseUrl.hostname) {
                    url = url.concat('?:embed=y&:showVizHome=no&:host_url=https&#37;3A&#37;2F&#37;2Fpublic.tableau.com&#37;2F&:embed_code_version=2&:tabs=yes&:toolbar=yes&:animate_transition=yes&:display_static_image=no&:display_spinner=no&:display_overlay=yes&:display_count=yes&:loadOrderID=0')
                console.log('here', url);
                }
                markup = <FocusableIframe
                src={ url }
                height={ height }
                width={ `${width}%` }
                scrolling={ disableScroll ? 'no' : 'yes'}
            />
            }
            return <div className={ className }>{ markup}</div>
        }

        /*
         * Inspector Controls
         */
        const Inspector = <InspectorControls>
            <PanelBody title="Allowed Domains">
                <ul>
                    { whiteList.map(
                        domain => <li key={ domain }>{ domain }</li>
                    ) }
                </ul>
            </PanelBody>
            { url && <Fragment>
                { Form }
                <PanelBody title="Attributes">
                    <RangeControl
                        label="Height"
                        min={ 300 }
                        max={ 1000 }
                        step={ 1 }
                        value={ height }
                        initialPosition={ 750 }
                        onChange={ height => setAttributes( { height } ) }
                        help="Sets iframe height in pixels (px)."
                    />
                    <RangeControl
                        label="Width"
                        min={ 0 }
                        max={ 100 }
                        step={ 1 }
                        value={ width }
                        initialPosition={ 100 }
                        onChange={ width => setAttributes( { width } ) }
                        help="Sets iframe width in percent (%)."
                    />
                    <ToggleControl
                        label={ disableScroll ? 'Enable scrolling' : 'Disable scrolling' }
                        checked={ disableScroll }
                        onChange={ disableScroll => setAttributes( { disableScroll } ) }
                    />
                </PanelBody>
            </Fragment> }

        </InspectorControls>

        return [ Inspector, Output() ]
    }
}

export default Edit