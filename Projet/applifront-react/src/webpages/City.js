import React, {Component} from 'react';
import {gameIndex} from '../database/gameController'
import {ListGroup, ListGroupItem} from "react-bootstrap";
import {Link} from "react-router-dom";

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
                                                      header={game.name}>
                        <Link style={styles} to={{
                            pathname: `/game/${game.id}`,
                            state: {
                                game: game,
                                city: city
                            }
                        }}>
                            {game.desc}
                        </Link>
                    </ListGroupItem>)}
                </ListGroup>
            </>
        )
    }
}

const styles = {
    display: 'block',
    color: 'inherit'
}