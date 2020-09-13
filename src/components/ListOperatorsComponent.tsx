import React from "react"
import Autocomplete from "@material-ui/lab/Autocomplete"
import * as core from "@material-ui/core"

import SpinnerComponent from './SpinnerComponent'
import NoticeModal from './NoticeComponent'

import { ajaxAction } from '../services'

export default function ListOperators( data : any ) {
  
  const { users } = data

  const [oper, setOperator] = React.useState( "" )
  const [spinner, setSpinnerVisible] = React.useState( false )
  const [error , setError] = React.useState( false )
  const [noticeModal, setVisibleNoticeModal] = React.useState( false )
  
  function ChooseStOperator( operator ){
    setOperator( operator )
  }

  async function setStOperator( ) {
    if ( oper ) {
      const url : string = 'user'
      const method : string = 'PATCH'
      setSpinnerVisible( true )
      const resp : any = await ajaxAction( url, method , oper )
      if( resp ) {
        setSpinnerVisible( false )
        setVisibleNoticeModal( true )
      //return resp
      }
    }
  }
  return (
    <div>
    { noticeModal ? <NoticeModal/>: null }
    { spinner ? <SpinnerComponent/> : 
      <core.Grid item xs = { 12 } lg = { 3 } sm = { 4 } md = { 4 }>
      <Autocomplete
        id="operators"
        freeSolo
        onChange = { ( event, value ) => ChooseStOperator( value ) }
        options={ users.map( ( option )  => option.operators ) }
        renderInput={ ( params ) => (
          <core.TextField {...params} label="Выбрать оператора" margin="normal" variant="outlined" />
        )}
      />
      <core.Button
        variant = "outlined"
        color = "primary"
        style = {{ width: '100%', margin: 'auto', height: 55, marginBottom: 20 }}
        onClick = { () => setStOperator( ) }
      >
        Назначить
      </core.Button>
      </core.Grid>
    }
    </div>  
  )
}
