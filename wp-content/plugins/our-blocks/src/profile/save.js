const classNames = require( 'classnames' )

const Save = ( { className, attributes: {
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
} } = props ) => {
    if( !pirateID ) return <div></div>

    if( useImage !== true ) {

    return <div className={ classNames( className, { isVertical: isVertical }, { isCenter: isCenter } ) }>
        <div className="block-content">           
            <div class="profile-content">
                { name && <h2>{ name }</h2> }
                { title && <p className="profile-title">{ title }</p> }
                { department && <p className="profile-department">{ department }</p> }
                { office && <p className="profile-office">{ office }</p> }
                { mailstop && <p className="profile-mailstop">{ mailstop }</p> }
                { phone && <a className="profile-phone" href={ `tel:${phone}`}>{ phone }</a> }
                { email && <a className="profile-email" href={ `mailto:${email}`}>{ email }</a> }
                { details !== undefined && <p className="profile-details">{ details }</p> }
            </div>
        </div>
    </div>
    } else {
        return <div className={ classNames( className, { isVertical: isVertical }, { isCenter: isCenter } ) }>

        <div className="block-content">
        { image && 
                <figure>
                    <img className="profile-image" src={ image } alt={ `Image of ${name}` } />
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
                { details !== undefined && <p className="profile-details">{ details }</p> }
            </div>
        </div>
    </div>
    }
}

export default Save;