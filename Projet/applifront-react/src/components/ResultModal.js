import React, {Component} from "react";
import {Modal} from 'react-bootstrap';
import FontAwesome from 'react-fontawesome'
import {userGameOver} from "../database/userController";

export default class ResultModal extends Component {

    constructor(props) {
        super(props);
        this.state = {
            show: false,
        }
    }

    componentWillMount() {
        this.setState({show: true})
    }

    async componentDidMount() {
        if (this.props.validAnswer === 'gameOver') {
            let game_id = this.props.location.state.game.id
            if (!this.props.user.games_done.includes(game_id)) {
                await userGameOver(game_id)
            }
        }
    }

    /**
     * handle opening a modal after closing another
     */
    componentWillReceiveProps() {
        this.setState({show: true})
    }

    handleClose = this.props.hideResultModal

    render() {
        let {valid, question} = this.props.validAnswer
        let {show} = this.state
        let rand = Math.floor(Math.random() * 6)
        let message = {
            right: ['Bravo !', 'Félicitations !', 'Bonne réponse !', 'C\'est bien ça !', 'Bien joué !', 'Correct !'],
            wrong: ['Oups...', 'Mauvaise réponse...', 'Ce n\'est pas ça...', 'Incorrect...', 'Aïe aïe...', 'Outch...']
        }
        return (<>
                {
                    valid === "true" ?
                        <Modal bsSize="large" show={show} onHide={this.handleClose}>
                            <Modal.Header closeButton><h1>{message.right[rand]}</h1></Modal.Header>
                            <Modal.Body style={styles.body}>
                                <FontAwesome name="check" size="4x"
                                             style={{color: 'green'}}/>
                                <h3 style={styles.advice}>+{question.expe} points d'expérience</h3>
                            </Modal.Body>
                        </Modal>
                        : valid === "false" ?
                        <Modal bsSize="large" show={show} onHide={this.handleClose}>
                            <Modal.Header closeButton><h1>{message.wrong[rand]}</h1></Modal.Header>
                            <Modal.Body style={{display: 'flex', alignItems: 'center'}}>
                                <FontAwesome name="times" size="4x"
                                             style={{color: 'red'}}/>
                                <h3 style={styles.advice}>Essaie encore !</h3>
                            </Modal.Body>
                        </Modal>
                        : <Modal bsSize="large" show={show} onHide={this.handleClose}>
                            <Modal.Header closeButton><h1>{message.right[rand]}</h1></Modal.Header>
                            <Modal.Body style={{display: 'flex', alignItems: 'center'}}>
                                <FontAwesome name="trophy" size="4x"
                                             style={{color: 'gold'}}/>
                                <h3 style={styles.advice}>Tu as terminé ce jeu !</h3>
                            </Modal.Body>
                        </Modal>
                }</>
        )
    }
}

const styles = {
    body: {
        display: 'flex', alignItems: 'center'
    },
    advice: {
        marginLeft: 'auto'
    }
}