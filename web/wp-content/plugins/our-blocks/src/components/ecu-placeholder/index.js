const { Placeholder} = wp.components

import './style.scss';

const ECUPlaceholder = ( { children, style } = props ) => {
    const row = style ? style : 'offset-4 col-4'
    return <Placeholder className="form">
        <div className="container">
            <div className="row">
                <div className={ row }>
                    { children }
                </div>
            </div>
        </div>
    </Placeholder>
}

export default ECUPlaceholder