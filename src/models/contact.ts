export interface Contact {
  id: number
  name: string
  fio: string
  phone: number
  email?: string
  comment?: string
  send_mail_kp: boolean
}

export interface Contacts {
  id: number
  contacts: any
  report?: Report[]
  filter?: Filter
}
export interface Login {
  password: string
  login: string
}
export interface Call {
  status_call: string
  date_call?: number
  count_calls?: number
  operator?: string
}

export interface Report extends Contact, Call {
}
export enum Filter {
  SHOW_ALL = 'all',
  SHOW_RECALL = 'recall',
  SHOW_NORECALL = 'norecall',
  SHOW_FINISHCALL = 'finishcall',
}
