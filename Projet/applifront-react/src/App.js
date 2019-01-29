import React, {Component} from 'react';
import Navigation from './components/Navigation'
import Routing from './components/Routing'
import Footer from './components/Footer'
import './App.css';

import {populate} from './database/populateDatabase'

class App extends Component {
    constructor(props) {
        super(props)
        this.state = {}
    }

    componentDidMount() {
        populate()
    }

    render() {
        let inGame = window.location.pathname.split`/`[1] === 'game'
        return (
            <div className="App">
                {!inGame && <Navigation/>}
                <div style={{marginBottom:'15vh'}}><Routing/></div>
                {!inGame && <Footer/>}
            </div>
        );
    }
}

export default App;
