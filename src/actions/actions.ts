import actionCreatorFactory from 'typescript-fsa'
import { asyncFactory } from 'typescript-fsa-redux-thunk'
import { AppState } from '../store'
import { MAKE_CALL, RECEIVE_CALL, 
          GET_CONTACTS, UNLOCK_CONTACTS,
  SPINNER_ACTION } from './../models/actions'
import { ajaxAction } from './../services'

const create = actionCreatorFactory()
const createAsync = asyncFactory<AppState>( create )

export const getContacts = createAsync<any, any>(
  "GET_CONTACTS",
  async ( params, dispatch ) => {
    const url: string = 'calls'
    const method : string = 'GET'
    const resp : any = await ajaxAction( url, method )
    const { data } = resp.data
    return dispatch({ type: GET_CONTACTS, data })
  }
)

export const unlockContacts = createAsync<any, any>(
  "UNLOCK_CONTACTS",
  async ( contact, dispatch ) => {
    const url: string = 'contact'
    const method : string = 'PATCH'
    const resp : any = await ajaxAction( url, method, contact )
    const { data } = resp
    console.log( data )
    return dispatch({ type: UNLOCK_CONTACTS, data })
  }
)

export const receiveCalls = createAsync<any, any>(
  "RECEIVE_CALL",
  async ( params, dispatch ) => {
    const url: string = 'contact'
    const method : string = 'POST'
    const resp = await ajaxAction( url, method )
    return dispatch({ type: RECEIVE_CALL, contacts: resp })
  }
)

export const makeCalls = createAsync<any, any>(
  "MAKE_CALL",
  async ( contact, dispatch ) => {
    const url : string = 'contact'
    const method : string = 'POST' 
    const { id } = contact
    await ajaxAction( url, method, contact )
    return dispatch({ type: MAKE_CALL, id })
  }
)

export const switchSpinnerVisible = createAsync<any, any>(
  "SPINNER_ACTION", 
  async(params, dispatch) => {
    const is_visible: boolean = true
    return dispatch({ type : SPINNER_ACTION, is_visible })
  }
)

export async function sendMails ( url: string, method: string, data: any ) {
    const resp = await ajaxAction( url, method, data )
    return resp
}