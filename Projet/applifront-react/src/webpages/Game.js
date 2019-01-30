import React, {Component} from 'react';
import {pointIndex} from '../database/pointController'
import {questionShow} from '../database/questionController'
import {answerIndex} from '../database/answerController'
import QuestionModal from '../components/QuestionModal'
import ResultModal from '../components/ResultModal'
import L from "leaflet";
import 'leaflet.locatecontrol';

export default class Game extends Component {
    constructor(props) {
        super(props)
        this.state = {
            game: {},
            points: [],
            city: {},
            questionModal: null,
            validAnswer: null,
            center: [0, 0],
            zoom: 16
        }
    }

    async componentDidMount() {
        let {city, game} = this.props.location.state
        let points = await pointIndex(game)
        await this.setState({
            points: points,
            city: city,
            center: [city.lat, city.lon],
            game: game
        })
        let {center, zoom} = this.state
        let map = this.generateMap(center, points, zoom)
        this.addMarkers(map, points, center)
    }

    /**
     * generate map and start user control
     * @param center
     * @param points
     * @param zoom
     */
    generateMap = (center, points, zoom) => {
        let map = L.map('map', {
            attributionControl: false,
            center: center,
            zoom: zoom,
            layers: [
                L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png'),
            ]
        })
        L.control.locate({
            watch: true,
            drawMarker: true,
            strings: {
                popup: "C'est moi !"
            }
        }).addTo(map)

        map.setView(center)

        return map
    }

    /**
     * add markers and bind to them the method which opens question modal
     * @param map
     * @param points
     * @param center
     */
    addMarkers = (map, points, center) => {
        L.marker(center).addTo(map).bindPopup(`Office de Tourisme`)

        let POIgroup = L.featureGroup().addTo(map).on("click", this.showQuestionModal)

        points.map(point => {
            let marker = L.marker([point.lat, point.lon])
                .addTo(POIgroup)
            marker.point = point
            marker.game = this.state.game
            return marker
        })
    }

    /**
     * set modal state to open it
     * @param e
     * @returns {Promise<void>}
     */
    showQuestionModal = async e => {
        let question = await questionShow(e.layer)
        let answers = await answerIndex(question.id)
        this.setState({
            questionModal: {
                question: question,
                answers: answers,
                point: e.layer.point
            }
        })
    }

    /**
     * set modal to null to close it
     */
    hideQuestionModal = () => this.setState({questionModal: null})

    /**
     * show result modal, with data : wrong or right answer
     * @param bool
     */
    showResultModal = bool => this.setState({validAnswer: bool})

    /**
     * close result modal by setting state to null
     */
    hideResultModal = () => this.setState({validAnswer: null})

    render() {
        let {questionModal, validAnswer} = this.state
        return (
            <>
                <div id="map"></div>
                {questionModal && <QuestionModal data={questionModal}
                                                 showResultModal={this.showResultModal}
                                                 hideQuestionModal={this.hideQuestionModal}/>}
                {validAnswer && <ResultModal validAnswer={validAnswer}
                                             hideResultModal={this.hideResultModal}/>
                }
            </>
        )
    }
}