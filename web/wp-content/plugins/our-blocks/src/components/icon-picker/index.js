import React from 'react'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import * as Icons from '@fortawesome/free-solid-svg-icons'
const { TextControl, Button, Popover } = wp.components

import './style.scss'

class IconPicker extends React.Component {
    state = {
        open: false,
        filter: '',
        selected: {},
        icons: []
    }

    componentDidMount = () => {
        if( this.props.icon.iconName ) this.setState( { selected: this.props.icon } )
    }

    handleLoadIcons = () => {
        const exclude = ['bacteria','bacterium','bahai','balance-scale-left','balance-scale-right','biking','border-all','border-none','border-style','box-tissue','caravan','compress-alt','disease','expand-alt','fan','faucet','font-awesome-logo-full','hand-holding-medical','hand-holding-water','hand-sparkles','hands-wash','handshake-alt-slash','handshake-slash','hat-cowboy','hat-cowboy-side','head-side-cough','head-side-cough-slash','head-side-mask','head-side-virus','hospital-user','house-user','icons','laptop-house','lungs','lungs-virus','mouse','people-arrows','phone-alt','phone-square-alt','photo-video','plane-slash','pump-medical','pump-soap','record-vinyl','remove-format','shield-virus','sink','soap','sort-alpha-down-alt','sort-alpha-up-alt','sort-amount-down-alt','sort-amount-up-alt','sort-numeric-down-alt','sort-numeric-up-alt','spell-check','stopwatch-20','store-alt-slash','store-slash','toilet-paper-slash','trailer','users-slash','virus','virus-slash','viruses','voicemail']
        const iconList = Object
        .keys( Icons )
        .filter( key => key !== 'fas' && key !== 'prefix' )
        const icons = iconList.map( icon => Icons[ icon ] ).filter( icon => !exclude.includes( icon.iconName ) && icon )
        this.setState( { icons, open: true } )
    }
    
    render = props => {
        const { open, icons: allIcons, filter, selected } = this.state

        const icons = !filter ? allIcons : allIcons.filter( icon => icon.iconName.includes( filter ) )

        return <div className="ecu-iconpicker">
            { selected && selected.prefix && <div className="ecu-iconpicker-icon">
                <span className={`${selected.prefix} fa-${selected.iconName} fa-4x fa-fw `}></span>
            </div>}
            { !open ? 
                <Button isPrimary onClick={ () => this.handleLoadIcons() }>{ selected && selected.iconName ? 'Edit Icon' : 'Select Icon' }</Button> : 
                <Button isSecondary onClick={ () => this.setState({ icons: [], open: false }) }>Close</Button> }
                { open && <Popover 
                    className="ecu-iconpicker-picker"
                    onFocusOutside={ () => this.setState( { icons: [], open: false } ) }
                >
                    <TextControl placeholder="Search Icons" value={ filter } onChange={ filter => this.setState( { filter } ) } />
                    <div className="iconpicker-icon-list">
                        { icons.map( icon => 
                            <span
                                key={ icon.iconName }
                                className="icon-option"
                                onClick={ () => {
                                    this.props.onClick( { prefix: icon.prefix, iconName: icon.iconName } )
                                    this.setState( { selected: { prefix: icon.prefix, iconName: icon.iconName }, open: false } )
                                } }
                            >
                                <span className={`${icon.prefix} fa-${icon.iconName} fa-lg fa-fw fa-icon`}></span>{ icon.iconName.replace(/-/g, ' ' ) }
                            </span>
                        ) }
                        { icons.length === 0 && <p>No icons found.</p>}
                    </div>
                </Popover> }
            
            { selected && selected.prefix && <Button isDestructive onClick={ () => this.setState( { selected: {} } ) }>Remove Icon</Button>}
            
        </div>
    }
}
 
export default IconPicker