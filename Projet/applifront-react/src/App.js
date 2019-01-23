import React, {Component} from 'react';
import logo from './logo.svg';
import './App.css';

import db from './db';

class App extends Component {
    constructor(props) {
        super(props)
        this.state = {
            "city": null
        }
    }

    componentDidMount() {
        fetch('http://192.168.43.44:8000/api/database')
            .then(response => response.json())
            .then(data => {
                console.log("data", data)

                db.on("ready", function () {
                    Object.entries(data).forEach(([tableName, tableDatas]) => {
                        tableDatas.map(current => db.table(tableName).put(current))
                    })
                });
                db.open();

                // const getGame = async () => {
                //     let game = await db.games.where('id').equals(1).toArray();
                //     console.log(game)
                //
                //     let test = await db.points.where('id').anyOf(game[0]['points']).toArray();
                //     console.log(test)
                // }
                //
                // getGame()
                // getGame().then(data => getPoints(data))
                /*Object.entries(data).forEach(([tableName, tableDatas]) => {
                    tableDatas.map(current => db.table(tableName)
                        .add(current)
                        .then(id => {
                            const newpoi = [...this.state.markersDexie, Object.assign({}, current, {id})]
                            this.setState({markersDexie: newpoi})
                            console.log("marker Dexie", this.state.markersDexie)
                        }))
                })*/
            })
    }

    render() {
        return (
            <div className="App">
                <header className="App-header">
                    <img src={logo} className="App-logo" alt="logo"/>
                    <p>
                        Edit <code>src/App.js</code> and save to reload.
                    </p>
                    <a
                        className="App-link"
                        href="https://reactjs.org"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Learn React
                    </a>
                </header>
            </div>
        );
    }
}

export default App;
