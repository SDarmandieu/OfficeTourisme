import React, {Component} from 'react';
import {ProgressBar, Tabs, Tab} from 'react-bootstrap'
import {gameSearch} from "../database/gameController";
import {Link} from "react-router-dom";
import {cityIndex} from "../database/cityController";

export default class Account extends Component {
    constructor(props) {
        super(props)
        this.state = {
            games_done: [],
            games_doing: [],
            cities: []
        }
    }

    async componentDidMount() {
        let {user} = this.props
        let cities = await cityIndex()
        let games_done = await gameSearch(user.games_done)
        let games_doing = await gameSearch(user.games_doing)
        await this.setState({
            games_done: games_done,
            games_doing: games_doing,
            cities: cities
        })
    }

    /**
     * returns an array of xp levels
     * @param n
     * @returns {*}
     */
    fibonacciLevel = n => {
        if (n === 2) return [150, 300]
        const arr = this.fibonacciLevel(n - 1)
        return [...arr, arr[arr.length - 1] + arr[arr.length - 2]]
    }

    /**
     * returns an object , with the level of user , and its progress (%) to reach next level
     * @param expe
     * @returns {{level: *, percent: number}}
     */
    getLevel = expe => {
        let array = this.fibonacciLevel(50)
        let level = array.findIndex(val => val >= expe) + 1
        let percent = Math.round(level === 1 ? 100 * expe / array[0] : (expe - array[level - 2]) / (array[level - 1] - array[level - 2]) * 100)
        return {
            level: level,
            percent: percent
        }
    }

    render() {
        let {user} = this.props
        let {games_done, cities, games_doing} = this.state
        let {level, percent} = this.getLevel(user.expe)
        return (
            <main style={{marginTop: 15, marginBottom: 15}}>

                <section className="text-center">
                    <h3>{user.name}</h3>
                    <p>Niveau {level}</p>
                    <p>{user.expe} points d'expérience</p>
                </section>

                <hr/>

                <section className="container">
                    <h3>Progression</h3>
                    <div>Vers le niveau {level + 1}</div>
                    <ProgressBar>
                        <ProgressBar now={percent} label={`${percent}%`} style={{minWidth: '15%'}}/>
                    </ProgressBar>
                </section>

                <hr/>

                <section className="container">
                    <h3>Mes jeux</h3>
                    <Tabs defaultActiveKey="doing" id="my-games">

                        <Tab eventKey="doing" title="En cours">
                            {games_doing.map(game => {
                                let city = cities.find(c => c.id === game.city_id)
                                return <Link key={game.id} to={{
                                    pathname: `/game/${game.id}`,
                                    state: {
                                        game: game,
                                        city: city
                                    }
                                }}>
                                    <div style={link} className={'list-group-item center-block'}>
                                        <h4 className="list-group-item-heading">{game.desc} ({city.name})</h4>
                                        <p className="list-group-item-text">Âge conseillé : {game.age}</p>
                                    </div>
                                </Link>
                            })}
                        </Tab>

                        <Tab eventKey="done" title="Terminé">
                            {games_done.map(game => {
                                let city = cities.find(c => c.id === game.city_id)
                                return <Link key={game.id} to={{
                                    pathname: `/game/${game.id}`,
                                    state: {
                                        game: game,
                                        city: city
                                    }
                                }}>
                                    <div style={link} className={'list-group-item center-block'}>
                                        <h4 className="list-group-item-heading">{game.desc} ({city.name})</h4>
                                        <p className="list-group-item-text">Âge conseillé : {game.age}</p>
                                    </div>
                                </Link>
                            })}
                        </Tab>

                    </Tabs>
                </section>
            </main>
        )
    }
}

const link = {
    color: 'black',
    borderRadius: 5,
    width: '95%',
    marginTop: '10px'
}