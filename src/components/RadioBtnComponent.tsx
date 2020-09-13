import React from 'react'
import { Radio, FormControlLabel } from '@material-ui/core'

export default function RadioButtons() {
  const [selectedValue, setSelectedValue] = React.useState( 'a' )
  const handleChange = ( event ) => {
    setSelectedValue( event.target.value )
  }

  return (
    <div>
      <FormControlLabel
        value = 'start'
        control = {
          <Radio
            checked = { selectedValue === 'a' }
            onChange = { handleChange }
            value = 'a'
            name = 'radio-button-demo'
            inputProps = {{ 'aria-label': 'A' }}
            color = 'primary'
          />
        }
        label = 'Значение 1'
        labelPlacement ='start'
      />
      <Radio
        checked = { selectedValue === 'b' }
        onChange = { handleChange }
        value = 'b'
        name = 'radio-button-demo'
        inputProps = {{ 'aria-label': 'B' }}
      />
    </div>
  )
}
