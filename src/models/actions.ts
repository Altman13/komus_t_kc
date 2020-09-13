import { Contact, Contacts, Call } from './contact'

export const MAKE_CALL = 'MAKE_CALL'
export const RECEIVE_CALL = 'RECEIVE_CALL'
export const GET_CONTACTS = 'GET_CONTACTS'
export const UNLOCK_CONTACTS = 'UNLOCK_CONTACTS'
export const SET_FILTER_ON_CONTACTS = 'SET_FILTER_ON_CONTACTS'
export const SPINNER_ACTION = 'SPINNER_ACTION'

export interface MakeCallAction {
  type: typeof MAKE_CALL
  call : Call
  id : number
}

export interface ReceiveCallAction {
  type: typeof RECEIVE_CALL;
  contact: Contact;
}
export interface GetContactsAction {
  type: typeof GET_CONTACTS
  data: Contacts
}
export interface UnlockContactsAction {
  type: typeof UNLOCK_CONTACTS
  data: Contacts
}
export interface SetFilterOnContactsAction {
  type: typeof SET_FILTER_ON_CONTACTS
  contact: Contact
}

export interface SpinnerAction {
  type: typeof SPINNER_ACTION
  is_visible : boolean
}

export type CallActionTypes =
  | ReceiveCallAction
  | MakeCallAction
  | GetContactsAction
  | UnlockContactsAction
  | SetFilterOnContactsAction
  | SpinnerAction

  export type AppActions = CallActionTypes
