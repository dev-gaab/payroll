// Dependencies
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';

import { login, logout } from '../store/actions';
// Components
import Routes from './Routes';

class App extends Component {

    render() {
        return (
            <div>
                <Routes isLoggedIn={this.props.isLoggedIn}/>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        isLoggedIn: state.isLoggedIn
    }
}

const mapDispatchToProps = (dispatch) => {
    return {
        logIn: () => {
            dispatch(login())
        },
        logOut: () => {
            dispatch(logout())
        }
    }
}
export default withRouter(connect(mapStateToProps, mapDispatchToProps)(App));
