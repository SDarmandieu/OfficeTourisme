import React, {Component} from 'react';
import {pointIndex} from '../database/pointController'
import {questionShow} from '../database/questionController'
import {answerIndex} from '../database/answerController'
import {userGameProgress, checkUser} from "../database/userController";
import {fileIndex} from "../database/fileController";
import QuestionModal from '../components/QuestionModal'
import ResultModal from '../components/ResultModal'
import L from "leaflet";
import 'leaflet.locatecontrol';
import 'leaflet-easybutton'
import isEqual from "lodash/isEqual";

export default class Game extends Component {
    constructor(props) {
        super(props)
        this.state = {
            questionModal: null,
            validAnswer: null,
            gameOver: false,
            userLocation: null
        }
    }

    async componentDidMount() {
        let {city, game} = this.props.location.state
        let files = await fileIndex(game.id)
        console.log('files', files)

        let points = await pointIndex(game)
        let map = this.generateMap([city.lat, city.lon], points, 16)
        this.addMarkers(map, points, [city.lat, city.lon])
    }

    /**
     * if user has changed , call update from parent
     * @param prevProps
     * @param prevState
     * @param snapshot
     * @returns {Promise<void>}
     */
    async componentDidUpdate(prevProps, prevState, snapshot) {
        let user = await checkUser()
        if (!isEqual(prevProps.user, user[0])) {
            await this.props.updateUser()
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
                L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png'),
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

        let {done, total} = userGameProgress(this.props)
        if (done === total) this.setState({gameOver: true})

        let progressBadge = L.Control.extend({
            'onAdd': () => {
                let container = L.DomUtil.create('div', 'search-container');
                let badge = L.DomUtil.create('span', 'badge badge-inverse', container);
                badge.innerHTML = `Avancement : ${done}/${total}`
                return container
            }
        })

        L.easyButton('fa-home fa-3x',
            () => this.props.history.push('/', {backButton: 'au jeu'}), {position: 'bottomleft'}
        ).addTo(map)

        L.easyButton('fa-user fa-3x',
            () => this.props.history.push('/account', {backButton: 'au jeu'}), {position: 'bottomright'}
        ).addTo(map)


        map.addControl(new progressBadge());

        map.on('locationfound', async e => await this.setState({userLocation: e.latlng}))


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
            iconUrl: process.env.PUBLIC_URL + '/images/marker-icon-green.png',
            shadowUrl: process.env.PUBLIC_URL + '/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.marker(center, {icon: infoIcon}).addTo(map).bindPopup(`Office de Tourisme`)

        let notDoneGroup = L.featureGroup().addTo(map).on("click", this.showQuestionModal)
        let doneGroup = L.featureGroup().addTo(map)

        let doneIcon = new L.Icon({
            iconUrl: process.env.PUBLIC_URL + '/images/marker-icon-black.png',
            shadowUrl: process.env.PUBLIC_URL + '/images/marker-shadow.png',
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
                // let {userLocation} = this.state

                let question = await questionShow(check)
                if (this.props.user.questions_done.includes(question.id)) {
                    marker = L.marker([point.lat, point.lon], {icon: doneIcon}).bindPopup('Tu as déjà répondu à cette question').addTo(doneGroup)
                }
                // else if (userLocation && (userLocation.distanceTo([point.lat, point.long]) < 50000000)) {
                   else marker = L.marker([point.lat, point.lon]).addTo(notDoneGroup)
                // } else marker = L.marker([point.lat, point.lon]).bindPopup('Tu es trop éloigné de ce point').addTo(map)

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
    hideResultModal = () => this.setState({validAnswer: null, gameOver: false})

    render() {
        let {questionModal, validAnswer, gameOver} = this.state

        return (
            <>
                <div id="map"></div>
                {questionModal && <QuestionModal {...this.props}
                                                 data={questionModal}
                                                 showResultModal={this.showResultModal}
                                                 hideQuestionModal={this.hideQuestionModal}/>}
                {validAnswer && !gameOver && <ResultModal validAnswer={validAnswer}
                                                          hideResultModal={this.hideResultModal}/>
                }
                {gameOver && <ResultModal {...this.props}
                                          validAnswer="gameOver"
                                          hideResultModal={this.hideResultModal}/>}
            </>
        )
    }
}
