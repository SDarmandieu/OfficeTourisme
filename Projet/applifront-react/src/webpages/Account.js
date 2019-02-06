import React, {Component} from 'react';
import {ProgressBar, Tabs, Tab, FormGroup, ControlLabel, FormControl, HelpBlock, Button} from 'react-bootstrap'
import {gameSearch} from "../database/gameController";
import {Link} from "react-router-dom";
import {cityIndex} from "../database/cityController";
import FontAwesome from 'react-fontawesome'
import {checkUser, userNameUpdate} from "../database/userController";


export default class Account extends Component {
    constructor(props) {
        super(props)
        this.state = {
            games_done: [],
            games_doing: [],
            cities: [],
            value: '',
            showForm: false
        }
    }

    async componentDidMount() {
        let user = await checkUser()
        let cities = await cityIndex()
        let games_done = await gameSearch(user[0].games_done)
        let games_doing = await gameSearch(user[0].games_doing)
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

    /**
     * call user store when form submit
     **/
    handleSubmit = () => {
        let {value} = this.state
        if (/^[a-zA-Z]{3,10}$/.test(value))
            userNameUpdate(this.state.value)
    }

    /**
     * change value state on input change
     **/
    handleChange = (event) => {
        this.setState({value: event.target.value})
    }

    /**
     * check if input match regex
     **/
    getValidationState() {
        let {value} = this.state
        return value.length === 0 ? null : /^[a-zA-Z]{3,10}$/.test(value) ? 'success' : 'error'
    }

    render() {
        let {user, location} = this.props
        let {games_done, cities, games_doing, showForm} = this.state
        let {level, percent} = this.getLevel(user.expe)
        let {backButton} = location.state || false
        return (
            <main style={{marginTop: 15, marginBottom: 15}}>

                <section className="text-center container">
                    {!showForm ?
                        <h3>{user.name} <FontAwesome onClick={() => this.setState({showForm: true})} name="edit"/></h3>
                        : <form onSubmit={this.handleSubmit}>
                            <FormGroup
                                controlId="updateUser"
                                validationState={this.getValidationState()}
                            >
                                <ControlLabel>Modifie ton nom de joueur</ControlLabel>
                                <FormControl
                                    type="text"
                                    value={this.state.value}
                                    placeholder="Saisis ton nouveau pseudo"
                                    onChange={this.handleChange}
                                    required
                                    pattern={"^[a-zA-Z]{3,10}$"}
                                />
                                <FormControl.Feedback/>
                                <HelpBlock>Entre 3 et 10 lettres</HelpBlock>
                            </FormGroup>
                            <Button type="submit">Ok</Button>
                            <hr/>
                        </form>
                    }
                    <p>Niveau {level}</p>
                    <p>{user.expe} {user.expe === 0 ? "point d'expérience" : "points d'expérience"} </p>
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

                <section className="container" style={{marginBottom: 50}}>
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
                                    <div style={styles.link} className={'list-group-item center-block'}>
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
                                    <div style={styles.link} className={'list-group-item center-block'}>
                                        <h4 className="list-group-item-heading">{game.desc} ({city.name})</h4>
                                        <p className="list-group-item-text">Âge conseillé : {game.age}</p>
                                    </div>
                                </Link>
                            })}
                        </Tab>

                    </Tabs>
                </section>
                {backButton !== undefined ?
                    <Button onClick={this.props.history.goBack} variant="success" size="lg"
                            style={styles.button}>
                        Retour {backButton}
                    </Button> :
                    <Button onClick={() => this.props.history.push('/')} variant="success" size="lg"
                            style={styles.button}>
                        Retour à l'accueil
                    </Button>

                }
            </main>
        )
    }
}

const styles = {
    link: {
        color: 'black',
        borderRadius: 5,
        width: '95%',
        marginTop: '10px',
    },
    button: {
        width: '90%',
        position: 'fixed',
        bottom: 10,
        right: '5%',
        color: 'white',
        backgroundColor: '#428BCA',
        borderColor: '#428BCA'
    }
}