import React, {Component} from 'react';
import {gameIndex} from '../database/gameController'
import {cityShow} from '../database/cityController'
import {ListGroup, ListGroupItem} from "react-bootstrap";

export default class City extends Component {
    constructor(props) {
        super(props)
        this.state = {
            games: [],
            city: {}
        }
    }

    async componentDidMount() {
        let games = await gameIndex(this.props.match.params.id)
        await this.setState({
            games: games, city: this.props.location.state.city
        })
        console.log('city', this.state.city)
    }

    render() {
        let {games, city} = this.state
        return (
            <>
                <h3>Liste des jeux de {city.name}</h3>
                <ListGroup>
                    {games.map(game => <ListGroupItem key={game.id}
                                                      header={game.name}>{game.desc}
                    </ListGroupItem>)}
                </ListGroup>
            </>
        )
    }
}