import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from 'react-router-dom';
import '../css/app.css';
import Home from './components/Home';
import { transitions, positions, Provider as AlertProvider } from 'react-alert'
import AlertTemplate from 'react-alert-template-basic'

// ReactDOM.render(<Router><Home /></Router>, document.getElementById('root'));
const options = {
    timeout: 5000,
    position: positions.BOTTOM_CENTER
};

const Root = () => (
    <AlertProvider template={AlertTemplate} {...options}>
        <Router><Home /></Router>
    </AlertProvider>
)

ReactDOM.render(<Root />, document.getElementById('root'))