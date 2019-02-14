import React, {Component} from 'react';
import Routing from './components/Routing'
// import Footer from './components/Footer'
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

    inGame = () => window.location.pathname.split`/`[1] === 'game'

    render() {
        return (
            <div className="App">
                <div style={{paddingTop:!this.inGame()*50}}><Routing inGame={this.inGame}/></div>
                {/*{!inGame && <Footer/>}*/}
            </div>
        );
    }
}

export default App;
