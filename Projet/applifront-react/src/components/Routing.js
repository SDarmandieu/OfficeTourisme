import React, {Component} from 'react';
import {Route, Switch} from "react-router-dom";
import Home from "../webpages/Home";
import City from "../webpages/City";
import Game from "../webpages/Game";
import {checkUser} from '../database/userController'


export default class Routing extends Component {
    constructor(props) {
        super(props)
        this.state = {
            user: '',
        }
    }

    async componentDidMount() {
        this.updateUser()
    }

    updateUser = async () => {
        let user = await checkUser()
        await this.setState({user: user[0]})
    }

    render() {
        let {user} = this.state
        return (
            <Switch>
                <Route exact path='/' render={(props) => <Home {...props} user={user}/>}/>
                <Route path='/account' render={() => <div>Account</div>}/>
                <Route path='/contacts' render={() => <div>Contacts</div>}/>
                <Route path='/tutorial' render={() => <div>Tutoriel</div>}/>
                <Route path='/city/:id' render={(props) => <City {...props} user={user}/>}/>
                <Route path='/game/:id' render={(props) => <Game {...props} user={user}/>}/>
            </Switch>
        )
    }
}