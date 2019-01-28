import React, {Component} from 'react';
import {addMarkers, generateMap} from '../map/utils'
import {pointIndex} from '../database/pointController'
import {questionShow} from '../database/questionController'
import QuestionModal from '../components/QuestionModal'
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

        return map
    }


    addMarkers = (map, points, center) => {
        L.marker(center).addTo(map).bindPopup(`Office de Tourisme`)

        let POIgroup = L.featureGroup().addTo(map).on("click", this.showQuestionModal)

        points.map(point => {
            let marker = L.marker([point.lat, point.lon])
                .addTo(POIgroup)
                .bindPopup(`<p>${point.desc}</p>`)
            marker.id = [point.id,this.state.game.id]
            return marker
        })
    }

    showQuestionModal = async e => {
        let question = await questionShow(e.layer.id)
        await this.setState({questionModal:question})
    }

    modalNull = () => this.setState({questionModal: null})

    render() {
        let {questionModal} = this.state
        return (
            <>
                <div id="map"></div>
                {questionModal && <QuestionModal question={questionModal} modalNull={this.modalNull}/>}
            </>
        )
    }
}