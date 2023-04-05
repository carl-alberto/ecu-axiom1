const Save = ( { attributes: { url, height, width, disableScroll }, className } = props ) => {
    return (
        <figure className={ className }>
            { url ? 
                <iframe 
                    src={ url } 
                    height={ height } 
                    width={ `${width}%` }
                    frameborder={ 0 }
                    scrolling={ disableScroll ? 'no' : 'yes'}
                /> :
                <p>No valid iframe URL provided</p>
            }
        </figure>
    )
}

export default Save;