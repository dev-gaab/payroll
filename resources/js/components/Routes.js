// Dependencies
import React, { Component } from 'react';
import { Route, Switch, Redirect } from 'react-router-dom';
// Components
import Home from './Home';
import AllEmpresas from './Empresa/AllEmpresas';
import Login from './Login/Login';
import NewEmpresa from './Empresa/NewEmpresa/NewEmpresa';

export default class Routes extends Component {
  constructor(props) {
    super(props)
  }
  render() {

    return (
      <Switch>
        <PrivateRoute exact path="/empresas" component={ AllEmpresas } isLoggedIn={this.props.isLoggedIn}/>
        <LoginRoute exact path="/login" component={ Login } isLoggedIn={this.props.isLoggedIn} />
        <PrivateRoute exact path="/" component={Home} isLoggedIn={this.props.isLoggedIn}/>
      
        <Route exact path="/empresas-new" component={NewEmpresa}/>
      </Switch>
    )
  }
}

function PrivateRoute({ component: Component, ...rest }) {
  return (

    <Route
      {...rest}
      render={() =>
        rest.isLoggedIn ? (
            <Component {...rest} />
        ) : (
          <Redirect
            to="/login"
          />
        )
      }
    />
    
  );
}

function LoginRoute({ component: Component, ...rest }) {
    return (

        <Route
            {...rest}
            render={() =>
                rest.isLoggedIn ? (
                    <Redirect
                        to="/"
                    />
                ) : (
                    <Component {...rest} />
                )
            }
        />

    );
}