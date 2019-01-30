import React, {Component} from "react";
import {Modal, FormGroup, Radio, Button} from 'react-bootstrap';

export default class QuestionModal extends Component {

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
    handleChangeInput = e => this.setState({
        validAnswer: {
            valid: e.target.value,
            expe: this.props.data.question.expe
        }
    })


    handleSubmit = async e => {
        e.preventDefault()
        this.handleClose()
        let {validAnswer} = this.state
        this.showResultModal(validAnswer)
    }

    render() {
        let {point, answers, question} = this.props.data
        let {show} = this.state
        return (
            <>
                <Modal bsSize="large" show={show} onHide={this.handleClose}>
                    <Modal.Header closeButton>{point.desc}</Modal.Header>
                    <Modal.Body>
                        {question.content}
                        <FormGroup>
                            {answers.map(answer => <Radio
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