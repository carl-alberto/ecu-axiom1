import React from 'react'
import Snackbar from '@material-ui/core/Snackbar'
import MuiAlert from '@material-ui/lab/Alert'

const Toast = props => {
    const [ open, setOpen ] = React.useState( true )

    const handleClose = () => {
        setOpen( false )
        props.onClose()
    }

    return <Snackbar
        anchorOrigin={ { vertical: 'bottom', horizontal: 'center' } }
        open={ open } 
        autoHideDuration={ 5000 } 
        onClose={ handleClose }>
        <Alert severity={ props.type } onClose={ handleClose }>{ props.data }</Alert>
    </Snackbar>
}

const Alert = props => {
    return <MuiAlert elevation={ 6 } variant="filled" {...props} />
}

export default Toast

