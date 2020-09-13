import React from 'react'
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import { store } from './store'
import { MainRouter } from './router'
// import App from './router/router'

ReactDOM.render(
    <Provider store = { store }>
        {/* <App/> */}
        <MainRouter/>
    </Provider>,

document.getElementById( 'root' )
)
