const { InnerBlocks, RichText } = wp.blockEditor

const classNames = require( 'classnames' )

const Save = ( { className, attributes: { heading, url, icon, content, isVertical, target, type, image } } = props ) => {

    const headerClasses = classNames( 'block-header', { isVertical : isVertical } )

    const gridImage = ( type == 'image' && image ) ? image : __wp__.assets + '/images/placeholder.jpg'

    const headerContent = <>
        { icon.iconName && <span className={ `${icon.prefix} fa-${icon.iconName} mr-3`} />}
        <span className="block-heading">{ heading }</span>
    </>

    const blockHeading = () => {
        if( !url ) return <div className={ headerClasses }>{ headerContent }</div>
        return target ?
            <a href={ url } className={ headerClasses } target="_blank" rel="noreferrer noopener">{ headerContent }</a> :
            <a href={ url } className={ headerClasses }>{headerContent}</a>
    }

    return <div className={ classNames( className, 'col-md-4', 'col-sm-6' ) }>
        <div className="block-inner">
            { type == 'image' &&
                <figure>
                    <img src={ gridImage } alt="" />
                </figure>
            }
            { blockHeading() }
            { type == 'list' && <InnerBlocks.Content /> }
            { type == 'content' && <RichText.Content
                tagName="p"
                className="block-content"
                value={ content }
            />}
        </div>
    </div>
}

export default Save
