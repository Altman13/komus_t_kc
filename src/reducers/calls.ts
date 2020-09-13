import { Contact } from './../models/contact'
import { CallActionTypes } from './../models/actions'

const callsReducerDefaultState = { Contact: [] }
export const CallReducer = (
  state = callsReducerDefaultState,
  action: CallActionTypes
) => {
  switch ( action.type ) {
    case 'GET_CONTACTS':
    return  { ...state, Contact: action.data }
    case 'MAKE_CALL':
    return { Contact : state.Contact.filter(({ id }) => id !== action.id )}
    case 'RECEIVE_CALL':
      return [...state.Contact, action.contact]
    case 'SPINNER_ACTION':
        return { ...state, spinner_visible: action.is_visible }
    default:
      return state
  }
}
