import { getSize } from '../util'

const Save = ( { attributes: { heading, isVertical, size, sites } } = props ) => {
    const layoutClass = isVertical ? 'vertical' : 'horizontal'
    return (
        <div className={ layoutClass }>
            { heading && <h2 className="social-media-heading">{heading}</h2>}
            <ul>
                { sites && sites.map( site => 
                    <li key={ site.name } className="social-media-item">
                        <a href={site.url} aria-label={ site.title } className={ getSize( size ) }>
                            <span className={ site.icon }></span>
                        </a>
                    </li>
                ) }
            </ul>
        </div>
    )
}

export default Save;