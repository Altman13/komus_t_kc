import React from 'react'
import { Snackbar } from '@material-ui/core'
import MuiAlert, { AlertProps } from '@material-ui/lab/Alert'

function Alert( props: AlertProps ) {
  return <MuiAlert elevation = { 6 } variant ='filled' { ...props } />
}
export default function DefaultNotice( props ) {
  const { err, err_text } = props
  const [open, setOpen] = React.useState( true )
  const handleClose = ( event?: React.SyntheticEvent, reason?: string ) => {
    if ( reason === 'clickaway' ) {
      return
    }
    setOpen( false )
  }
    return (
    <div>
      { err ? (
        <Snackbar
          open = { open }
          autoHideDuration = { 6000 }
          onClose = { handleClose }
          anchorOrigin = {{ vertical: 'top', horizontal: 'center' }}
          key = { 'vertical' + 'horizontal' }
        >
          <Alert onClose = { handleClose } severity = 'error'>
            { err_text } 
          </Alert>
        </Snackbar>
        
      ) : (
        <Snackbar
          open = { open }
          autoHideDuration = { 6000 }
          onClose = { handleClose }
          anchorOrigin = {{ vertical: 'top', horizontal: 'center' }}
          key = { 'vertical' + 'horizontal' }
        >
          <Alert onClose = { handleClose } severity = 'success'>
            Действие выполнено успешно!
          </Alert>
        </Snackbar>
      )}
    </div>
  )
}
