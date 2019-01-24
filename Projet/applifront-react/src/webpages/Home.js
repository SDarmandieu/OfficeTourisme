import React, {Component} from 'react';
import {addUser} from '../database/user'
import {getCitiesWithGamesInfos} from '../database/fetchData'
import {FormGroup,FormControl,ControlLabel,Button,HelpBlock} from "react-bootstrap";

export default class Home extends Component {
    constructor(props) {
        super(props)
        this.state = {
            value: '',
            cities: []
        }
    }

    async componentWillMount() {
        let cities = await getCitiesWithGamesInfos()
        await this.setState({
            cities: cities
        })
        console.log(this.state.cities)
    }

    handleSubmit = () => {
        addUser(this.state.value)
    }

    handleChange = (event) => {
        this.setState({value: event.target.value})
        console.log(this.state.value)
    }

    getValidationState() {
        let {value} = this.state
        return value.length===0?null:/^[a-zA-Z]{3,10}$/.test(value) ?'success':'error'

        // if (length > 10) return 'success';
        // else if (length > 5) return 'warning';
        // else if (length > 0) return 'error';
        // return null;
    }


    render() {
        let {user} = this.props
        let {cities, value} = this.state
        return (
            <>
                {user === undefined
                    ? <>
                        <h1>Bienvenue sur l'application de jeux de piste</h1>
                        <form onSubmit={this.handleSubmit}>
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
                                />
                                <FormControl.Feedback />
                                <HelpBlock>Entre 3 et 10 lettres</HelpBlock>
                            </FormGroup>
                            <Button type="submit">Ok</Button>
                        </form>
                    </>
                    : <>
                        <h1>Bienvenue {user} sur l'application de jeux de piste</h1>
                        {
                            cities.map(city => <div key={city.id}>{city.name}</div>)
                        }
                    </>}
            </>
        )
    }
}