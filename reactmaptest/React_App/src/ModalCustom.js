import React, { Component } from "react";
import { Modal } from 'react-bootstrap';
import axios from 'axios';


class ModalCustom extends Component {

  constructor(props) {
    super(props);
    this.handleClose = this.handleClose.bind(this)
    this.state = {
      show:false,
      answer:''
    }
  }

  handleClose() {
    //this.setState({show:false})
    this.props.modalNull()
  }

  componentWillMount() {
    this.setState({show:true})
  }

  /**
  *
  * pour gérer l'ouverture d'un modal après la fermeture d'un autre
  *
  **/
  componentWillReceiveProps() {
    this.setState({show:true})
  }

  /**
  *
  * modifie le state answer au changement de réponse
  *
  **/
  handleChangeInput = e => { this.setState({ answer: e.target.value }) }

  /**
  *
  * envoi de la réponse au back , et console.log de ce que renvoie l'API 
  *
  **/
  handleSubmit = async e => {
    e.preventDefault();

    const answer = {
      answer: this.state.answer
    }

    const res = await axios.post(`http://192.168.0.106:8000/api/answer`, { answer })

    console.log(res)
    console.log(res.data)
  }

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

  render()  {
    return (
      <div>
      <Modal bsSize="large" show={this.state.show} onHide={this.handleClose}>
        <Modal.Header closeButton>{this.props.marker.desc}</Modal.Header>
        <Modal.Body>
          <p>marker id : {this.props.marker.id} , latitude : {this.props.marker.lat} , longitude : {this.props.marker.lon}</p>
          <form onSubmit={this.handleSubmit}>
            <label>
              Réponse : 
              <input type="text" name="answer" onChange={this.handleChangeInput} />
            </label>
            <button type="submit">Répondre</button>
          </form>
        </Modal.Body>
      </Modal>
    </div>)}
  }

export default ModalCustom;