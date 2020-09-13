import React from 'react'
import { TextField } from '@material-ui/core'

export default function SearchComponent() {
    return (
        <TextField
            id = 'outlined-basic'
            label = 'Поиск'
            variant = 'outlined'
            style = {{ width: '100%', marginBottom: '15px' }}
        />
    )
}
