import React from 'react'
import { BrowserRouter as Router,
        Switch, Route, Redirect
} from 'react-router-dom'

import  LoginComponent  from '../components/LoginComponent'
import FormComponent from '../components/FormComponent'
import  { DashBoardComponent }   from '../components/DashBoardComponent'

var auth = false
    const token = localStorage.getItem( 'token' )
    
    if( token ){
        auth = true
    }
export function MainRouter() {
    return (
            <Router>
                <Switch>
                    <Route exact path = '/' component = { PrivateRoute } />
                    <Route path = '/login' component = { LoginComponent } />
                    { auth ? (<Route path = '/main' component = { FormComponent } />) :
                    (<Route path = '/' component = { PrivateRoute } />)}
                    { auth ? (<Route path = '/dashboard' component = { DashBoardComponent } />) :
                    (<Route path = '/' component = { PrivateRoute } />)}
                    <Route path = '*' component = { PrivateRoute } />
                </Switch>
            </Router>
        )
}
function PrivateRoute() {

    return (
        <Route
            render = { () =>
                auth ? (
                    <Redirect to = '/main'/>
                ) : (
                    <Redirect to = '/login'/>
                )
            }
        />
    )
}