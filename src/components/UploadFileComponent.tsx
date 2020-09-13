import React from 'react'

import { Button } from '@material-ui/core'

import SpinnerComponent from './SpinnerComponent'
import NoticeModal from './NoticeComponent'

import { ajaxAction } from '../services'

export default function UploadFileComponent( props ) {
  const [data, setFormData] = React.useState<FormData | null>()
  const [spinner, setSpinnerVisible] = React.useState( false )
  const [error , setError] = React.useState( false )
  const [noticeModal, setVisibleNoticeModal] = React.useState( false )

  function setFileToUpload(event: React.ChangeEvent<HTMLInputElement>) {
    event.persist()
    if ( event.target.files ) {
      const file : File = event.target.files[0]
      const formData = new FormData()
      let fn : string = 'upload_file'
      formData.append( fn, file )
      setFormData( formData )
    }
  }

  async function uploadFile() {
    const { url } = props
    const method : string = 'POST'
    const check : boolean = checkFileIsReadyForLoad()
    if ( check ) {
      setSpinnerVisible( true )
      const ret : any = await ajaxAction( url, method, data )
      if ( ret ) {
        setSpinnerVisible( false )
        setVisibleNoticeModal( true )
        setTimeout( () => {
          setVisibleNoticeModal( false )
        }, 6000 )
        return ret
      }
    }
  }
  function checkFileIsReadyForLoad() {
    let ret
    if( data == null ){
      setError( true )
      setTimeout( () => {
        setError( false )
      }, 2000 )
      ret = false
    }
    else {
      ret = true
    }
    return ret
  }

  return (
    <div>
      { noticeModal ? <NoticeModal /> : null }
      { error ? <NoticeModal err = { error } err_text = 'Выберете файл'/> : null }
      { spinner ? (
        <SpinnerComponent />
      ) : (
        <div>
          <input
            accept = '.xls,.xlsx'
            style = {{ display: 'none' }}
            id = 'file'
            multiple = { true }
            type = 'file'
            onChange = { setFileToUpload }
          />
          <label htmlFor = 'file'>
            <Button
              variant = 'outlined'
              color = 'primary'
              aria-label = 'upload file'
              component = 'span'
              style = {{
                width: '100%',
                margin: 'auto',
                height: 55,
                marginBottom: '5',
              }}
            >
              Выбрать файл
            </Button>
          </label>

          <Button
            variant = 'outlined'
            color = 'primary'
            style = {{ width: '100%', margin: 'auto', height: 55, marginTop: 5 }}
            onClick = { uploadFile }
          >
            Загрузить
          </Button>
        </div>
      )}
    </div>
  )
}
