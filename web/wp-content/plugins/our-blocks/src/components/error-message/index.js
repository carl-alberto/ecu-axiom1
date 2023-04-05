import { styled, css } from 'astroturf';

const ErrorMessage = ( { children } = props ) => {
    return <div className="error-message">{ children }</div>
}

export default ErrorMessage