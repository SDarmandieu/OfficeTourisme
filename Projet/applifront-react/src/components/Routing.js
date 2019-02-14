import React, {Component} from 'react';
import {Route, Switch, Redirect} from "react-router-dom";
import Home from "../webpages/Home";
import City from "../webpages/City";
import Game from "../webpages/Game";
import Account from "../webpages/Account"
import {checkUser} from '../database/userController'
import Navigation from "./Navigation";

export default class Routing extends Component {
    constructor(props) {
        super(props)
        this.state = {
            user: '',
            mapKey: 1
        }
    }

    async componentDidMount() {
        this.updateUser()
    }

    /**
     * update user and generate new map
     * @returns {Promise<void>}
     */
    updateUser = async () => {
        let user = await checkUser()
        await this.setState({user: user[0], mapKey: this.state.mapKey + 1})
    }

    render() {
        let {user} = this.state
        let {inGame} = this.props
        return (
            <>
                {!inGame() && <Navigation user={user}/>}
                <Switch>
                    <Route exact path='/' render={(props) => <Home {...props} user={user}/>}/>
                    <Route path='/contacts' render={() => <div>Contacts</div>}/>
                    <Route path='/tutorial' render={() => <div>Tutoriel</div>}/>
                    <Route path='/account' render={(props) => user !== undefined ?
                        <Account {...props} user={user}/> :
                        <Redirect to="/"/>}/>
                    <Route path='/city/:id' render={(props) => user !== undefined ?
                        <City {...props} user={user}/> :
                        <Redirect to="/"/>}/>
                    <Route path='/game/:id'
                           render={(props) => user !== undefined ?
                               <Game key={this.state.mapKey} {...props}
                                     user={user}
                                     updateUser={this.updateUser}/> :
                               <Redirect to="/"/>}/>
                </Switch>
            </>
        )
    }
}