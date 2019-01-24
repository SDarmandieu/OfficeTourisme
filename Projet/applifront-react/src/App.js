import React, {Component} from 'react';
import Navigation from './components/Navigation'
import Routing from './components/Routing'
import './App.css';

import {populate} from './database/populateDB'

class App extends Component {
    constructor(props) {
        super(props)
        this.state = {}
    }

    componentDidMount() {
        populate()
    }

    render() {
        return (
            <div className="App">
                <Navigation/>
                <Routing/>
            </div>
        );
    }
}

export default App;
