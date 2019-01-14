require('bootstrap');
// Dependencies
import React from 'react';
import { render } from 'react-dom';
import { BrowserRouter as Router } from 'react-router-dom';
import { createStore } from 'redux';
import { Provider   } from 'react-redux';
import appReducers from './store/reducers';
// components
import App from './components/App';

const store = createStore(appReducers);
render(
    <Provider store={store}>
        <Router>
            <App/>
        </Router>
    </Provider>,
    document.getElementById('app')
);