import React, {Component} from "react";
import {Modal, FormGroup, Radio, Button} from 'react-bootstrap';

class QuestionModal extends Component {

    constructor(props) {
        super(props);
        this.state = {
            show: false,
            validAnswer: ''
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

    /**
     * closing modal function of parent
     * @type {Game.modalNull}
     */
    handleClose = this.props.hideQuestionModal

    showResultModal = this.props.showResultModal


    /**
     * change answer state when radio is selected
     * @param e
     */
    handleChangeInput = e => this.setState({validAnswer: e.target.value})


    handleSubmit = async e => {
        e.preventDefault()
        this.handleClose()
        let {validAnswer} = this.state
        this.showResultModal(validAnswer)
    }

    render() {
        let {data} = this.props
        let {show} = this.state
        console.log(data)
        return (
            <>
                <Modal bsSize="large" show={show} onHide={this.handleClose}>
                    <Modal.Header closeButton>{data.question.content}</Modal.Header>
                    <Modal.Body>
                        <FormGroup>
                            {data.answers.map(answer => <Radio
                                onChange={this.handleChangeInput}
                                key={answer.id}
                                name="answer"
                                value={!!answer.valid}>
                                {answer.content}
                            </Radio>)}
                            <Button type="submit" onClick={this.handleSubmit}>Choisir</Button>
                        </FormGroup>
                    </Modal.Body>
                </Modal>
            </>)
    }
}

export default QuestionModal;