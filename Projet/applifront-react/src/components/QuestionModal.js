import React, {Component} from "react";
import {Modal} from 'react-bootstrap';

class QuestionModal extends Component {

    constructor(props) {
        super(props);
        this.state = {
            show: false,
            answer: ''
        }
    }

    handleClose = this.props.modalNull

    componentWillMount() {
        this.setState({show: true})
    }

    /**
     *
     * pour gérer l'ouverture d'un modal après la fermeture d'un autre
     *
     **/
    componentWillReceiveProps() {
        this.setState({show: true})
    }

    /**
     *
     * modifie le state answer au changement de réponse
     *
     **/
    handleChangeInput = e => {
        this.setState({answer: e.target.value})
    }

    /**
     *
     * envoi de la réponse au back , et console.log de ce que renvoie l'API
     *
     **/
    // handleSubmit = async e => {
    //     e.preventDefault();
    //
    //     const answer = {
    //         answer: this.state.answer
    //     }
    //
    //     const res = await axios.post(`http://192.168.43.44:8000/api/answer`, { answer })
    //
    //     console.log(res)
    //     console.log(res.data)
    // }

    /**
     *
     * version promise de la fonction précédente
     *
     **/

    /* handleSubmit = e => {
      e.preventDefault();

      const answer = {
        answer: this.state.answer
      }

      axios.post(`http://192.168.1.118:8000/api/answer`, { answer })
        .then(res => {
          console.log(res);
          console.log(res.data);
        })
    } */

    render() {
        let {question} = this.props
        console.log(question)
        return (
            <>
                <Modal bsSize="large" show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>{question.content}</Modal.Header>
                    <Modal.Body>
                        <form>
                            <label>
                                Réponse :
                                <input type="text" name="answer" onChange={this.handleChangeInput}/>
                            </label>
                            <button type="submit">Répondre</button>
                        </form>
                    </Modal.Body>
                </Modal>
            </>)
    }
}

export default QuestionModal;