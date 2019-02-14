import React, {Component} from "react";
import {Modal, FormGroup, Radio, Button} from 'react-bootstrap';
import {userQuestionDone} from "../database/userController"

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

    /**
     * show result modal of parent
     * @type {Game.showResultModal|*}
     */
    showResultModal = this.props.showResultModal

    /**
     * change answer state when radio is selected
     * @param e
     */
    handleChangeInput = e => this.setState({
        validAnswer: {
            valid: e.target.value,
            question: this.props.data.question
        }
    })

    /**
     * when submit an answer , defines what to do
     * @param e
     * @returns {Promise<void>}
     */
    handleSubmit = async e => {
        e.preventDefault()
        this.handleClose()
        let {validAnswer} = this.state
        this.showResultModal(validAnswer)
        if (validAnswer.valid === "true") {
            await userQuestionDone(validAnswer.question)
        }
    }

    render() {
        let {point, answers, question} = this.props.data
        console.log(question)
        let {show} = this.state
        return (
            <>
                <Modal bsSize="large" show={show} onHide={this.handleClose}>
                    <Modal.Header closeButton>{point.desc}</Modal.Header>
                    <Modal.Body>
                        {question.content}
                        {question.file !== null && <img className="img-responsive"
                                                           src={`http://192.168.43.44:8000/storage/${question.file.path}`}
                                                           alt={question.file.alt}/>}
                        <FormGroup>
                            {answers.map(answer => <Radio
                                onChange={this.handleChangeInput}
                                key={answer.id}
                                name="answer"
                                value={!!answer.valid}>
                                {answer.content}
                                {answer.file !== null && <img className="img-responsive"
                                                                   src={`http://192.168.43.44:8000/storage/${answer.file.path}`}
                                                                   alt={answer.file.alt}/>}
                                {answer.valid}
                            </Radio>)}
                            <Button type="submit" onClick={this.handleSubmit}>Choisir</Button>
                        </FormGroup>
                    </Modal.Body>
                </Modal>
            </>)
    }
}