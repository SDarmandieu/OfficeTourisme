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
            questionModal: null,
            validAnswer: null
        }
    }

    async componentDidMount() {
        let {city, game} = this.props.location.state
        let points = await pointIndex(game)
        let map = this.generateMap([city.lat, city.lon], points, 16)
        this.addMarkers(map, points, [city.lat, city.lon])
        this.isGameOver()
    }

    isGameOver = () => {
        let questionsIdList = this.props.location.state.game.questions
        let {questions_done} = this.props.user
        let compare = questions_done.filter(q => questionsIdList.includes(q))
        if (compare.length === questionsIdList.length) {
            this.setState({validAnswer: 'gameOver'})
        }
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
        }).addTo(map).start()

        map.setView(center)

        let questionsIdList = this.props.location.state.game.questions
        let {questions_done} = this.props.user
        let compare = questions_done.filter(q => questionsIdList.includes(q))

        let filterButton = L.Control.extend({
            'onAdd': () => {
                let container = L.DomUtil.create('div', 'search-container');
                let badge = L.DomUtil.create('span', 'badge badge-inverse', container);
                console.log(badge)
                // badge.type = 'text';
                badge.innerHTML = `Avancement : ${compare.length}/${questionsIdList.length}`
                return container
            }
        })
        map.addControl(new filterButton);

        return map
    }

    /**
     * add markers and bind to them the method which opens question modal
     * @param map
     * @param points
     * @param center
     */
    addMarkers = (map, points, center) => {
        let infoIcon = new L.Icon({
            iconUrl: '/images/marker-icon-green.png',
            shadowUrl: '/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.marker(center, {icon: infoIcon}).addTo(map).bindPopup(`Office de Tourisme`)

        let notDoneGroup = L.featureGroup().addTo(map).on("click", this.showQuestionModal)
        let doneGroup = L.featureGroup().addTo(map)

        let doneIcon = new L.Icon({
            iconUrl: '/images/marker-icon-black.png',
            shadowUrl: '/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        points.map(async point => {
                let marker
                let check = {
                    point: point,
                    game: this.props.location.state.game
                }
                let question = await questionShow(check)
                if (this.props.user.questions_done.includes(question.id)) {
                    marker = L.marker([point.lat, point.lon], {icon: doneIcon}).bindPopup('Tu as déjà répondu à cette question').addTo(doneGroup)
                } else {
                    marker = L.marker([point.lat, point.lon]).addTo(notDoneGroup)
                }
                marker.point = point
                marker.game = this.props.location.state.game

                return marker
            }
        )
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
    hideResultModal = async () => {
        let bool = this.state.validAnswer === 'gameOver'
        await this.setState({
            validAnswer: null,
        })
        if (!bool) window.location.reload();
    }

    render() {
        let {questionModal, validAnswer} = this.state
        let height = Math.max(window.screen.height, window.screen.width)
        return (
            <>
                <div id="map" style={{height: height - 50}}></div>
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
