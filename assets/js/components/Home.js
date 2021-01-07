// ./assets/js/components/Home.js

import React, { Component } from 'react';
import { Route, Switch, Redirect, Link, withRouter } from 'react-router-dom';
import Users from './History';
import Map from '../components/Map';

class Home extends Component {

    render() {
        return (
            <div>
                <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                    <Link className={"navbar-brand"} to={"/"}> Map </Link>
                    <div className="collapse navbar-collapse" id="navbarText">
                        <ul className="navbar-nav mr-auto">
                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/users"}> History </Link>
                            </li>
                        </ul>
                    </div>
                </nav>
                <Switch>
                   <Redirect exact from="/" to="/map" />
                    <Route path="/map" component={Map} />
                    <Route path="/users" component={Users} />
                </Switch>
            </div>
        )
    }
}

export default Home;