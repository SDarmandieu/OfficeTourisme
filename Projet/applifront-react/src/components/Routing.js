import React, {Component} from 'react';
import {Route, Switch} from "react-router-dom";
import Home from "../webpages/Home";
import {checkUser} from '../database/user'


export default class Routing extends Component {
    constructor(props) {
        super(props)
        this.state = {user: undefined}
    }

    async componentWillMount() {
        let user = await checkUser()
        this.setState({user: user})
    }

    render() {
        let {user} = this.state
        return (
            <Switch>
                <Route exact path='/' render={(props) => <Home {...props} user={user}/>}/>
                <Route path='/account' render={() => <div>Account</div>}/>
                <Route path='/contacts' render={() => <div>Contacts</div>}/>
                <Route path='/tutorial' render={() => <div>Tutoriel</div>}/>
            </Switch>
        )
    }
}