const classNames = require( 'classnames' )

const Save = ( { className, attributes: { heading, image, url, width, height, target, invert } } = props ) => {
    console.log( height )
    const classes = classNames( className, width, { invert: invert } )

    const placeholder = image ? image : __wp__.assets + '/images/placeholder.jpg'

    return <div className={ classes }>
        <div className="bg-wrap" style={ { backgroundImage: `url('${placeholder}')`, height: `${height}px` } }>
            { !target && <a href={ url }><p>{ heading }</p></a> }
            
            { // Links need to have noreferrer and noopener attributes if target is _blank
            target && <a href={ url } target="_blank" rel="noreferrer noopener"><p>{ heading }</p></a> }
        </div>
    </div>
}

export default Save

