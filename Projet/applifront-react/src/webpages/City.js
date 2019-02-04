import React, {Component} from 'react';
import {gameCityIndex} from '../database/gameController'
import {ListGroup} from "react-bootstrap";
import {Link} from "react-router-dom";

export default class City extends Component {
    constructor(props) {
        super(props)
        this.state = {
            games: []
        }
    }

    async componentDidMount() {
        let games = await gameCityIndex(this.props.match.params.id)
        await this.setState({games: games})
    }

    render() {
        let {games} = this.state
        let {city} = this.props.location.state
        return (
            <>
                <h3>Liste des jeux de {city.name}</h3>
                <ListGroup componentClass="ul">
                    {games.map(game =>
                        <Link key={game.id} to={{
                            pathname: `/game/${game.id}`,
                            state: {
                                game: game,
                                city: city
                            }
                        }}>
                            <div style={link} className={'list-group-item center-block'}>
                                <h4 className="list-group-item-heading">{game.desc}</h4>
                                <p className="list-group-item-text">Âge conseillé : {game.age}</p>
                                <p className="list-group-item-text">{this.props.user.games_done.includes(game.id)?"Terminé" : "Non terminé"}</p>
                            </div>
                        </Link>)
                    }
                </ListGroup>
            </>
        )
    }
}

const link = {
    color: 'black',
    borderRadius: 5,
    width: '95%',
    marginTop: '10px'
}
