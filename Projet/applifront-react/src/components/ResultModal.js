import React, {Component} from "react";
import {Modal} from 'react-bootstrap';

class ResultModal extends Component {

    constructor(props) {
        super(props);
        this.state = {
            show: false,
        }
    }

    componentWillMount() {
        this.setState({show: true})
    }

    /**
     * handle opening a modal after closing another
     */
    componentWillReceiveProps() {
        this.setState({show: true})
    }

    handleClose = this.props.hideResultModal

    render() {
        let {validAnswer} = this.props
        console.log(validAnswer)
        let {show} = this.state
        return (<>
            {
                validAnswer==="true"?
                    <Modal bsSize="large" show={show} onHide={this.handleClose}>
                        <Modal.Header closeButton>Bravo !</Modal.Header>
                        <Modal.Body>
                            Bonne réponse
                        </Modal.Body>
                    </Modal> :
                    <Modal bsSize="large" show={show} onHide={this.handleClose}>
                        <Modal.Header closeButton>Oups !</Modal.Header>
                        <Modal.Body>
                            Essaie encore !
                        </Modal.Body>
                    </Modal>
            }</>
        )
    }
}

export default ResultModal;