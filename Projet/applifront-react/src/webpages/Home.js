import React, {Component} from 'react';
import {userStore} from '../database/userController'
import {cityIndex} from '../database/cityController'
import {Link} from "react-router-dom";
import {FormGroup, FormControl, ControlLabel, Button, HelpBlock, ListGroup} from "react-bootstrap"

export default class Home extends Component {
    constructor(props) {
        super(props)
        this.state = {
            value: '',
            cities: [],
            clickCity: []
        }
    }

    async componentDidMount() {
        let cities = await cityIndex()
        await this.setState({
            cities: cities
        })
    }

    /**
     * call user store when form submit
     **/
    handleSubmit = () => {
        let {value} = this.state
        if (/^[a-zA-Z]{3,10}$/.test(value))
            userStore(this.state.value)
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
        let {cities} = this.state
        let {backButton} = location.state || false
        return (
            <>
                {user === undefined
                    ? <section className="container">
                        <h1>Bienvenue sur l'application de jeux de piste</h1>
                        <form onSubmit={this.handleSubmit} style={{marginTop: 30}}>
                            <FormGroup
                                controlId="addUser"
                                validationState={this.getValidationState()}
                            >
                                <ControlLabel>Choisis ton nom de joueur</ControlLabel>
                                <FormControl
                                    type="text"
                                    value={this.state.value}
                                    placeholder="Saisis ton pseudo"
                                    onChange={this.handleChange}
                                    required
                                    pattern={"^[a-zA-Z]{3,10}$"}
                                />
                                <FormControl.Feedback/>
                                <HelpBlock>Entre 3 et 10 lettres</HelpBlock>
                            </FormGroup>
                            <Button type="submit">Ok</Button>
                        </form>
                    </section>
                    : <section className="container" style={{marginBottom:50}}>
                        <h3>Bienvenue {user.name} sur l'application de jeux de piste</h3>
                        <ListGroup componentClass={'ul'}>
                            {cities.map(city =>
                                <Link
                                    key={city.id}
                                    to={{
                                        pathname: `/city/${city.id}`,
                                        state: {city: city}
                                    }}>
                                    <div style={styles.link} className={'list-group-item center-block'}>
                                        <h4 className="list-group-item-heading">{city.name}</h4>
                                        <p className="list-group-item-text">{city.games.length} jeux disponibles</p>
                                    </div>
                                </Link>)}
                        </ListGroup>
                    </section>}
                {backButton !== undefined && <Button onClick={this.props.history.goBack} variant="success" size="lg"
                                                     style={styles.button}>
                    Retour {backButton}
                </Button>}
            </>
        )
    }
}

const styles = {
    link : {
        color: 'black',
        borderRadius: 5,
        marginTop: '10px',
    },
    button : {
        width: '90%',
        position: 'fixed',
        bottom: 15,
        right: '5%',
        color:'white',
        backgroundColor:'#428BCA',
        borderColor:'#428BCA'
    }
}