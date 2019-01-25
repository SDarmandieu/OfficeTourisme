import React, {Component} from 'react';
import { Map, TileLayer , Marker , Circle} from "react-leaflet"
import ReactInterval from 'react-interval'


export default class Game extends Component {
    constructor(props) {
        super(props)
        this.state = {
            game: {},
            points: {},
            modal: {},
            center:[43.107973199999996, 0.7253157],
            zoom:17,
            userLocation:null,
            markers:[],
            enabled:true,
            timeout:5000
        }
    }

    async componentWillMount() {
    }

    render(){
        const {timeout, enabled, userLocation , markers , modal , zoom , center , markersDexie} = this.state

        return (
            <Map
                center={center}
                // onClick={(e)=>this.handleMapClick(e)}
                zoom={zoom}>

                {/* layer openstreetmap */}
                <TileLayer
                    attribution='&amp;copy <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                    url="https://{s}.tile.osm.org/{z}/{x}/{y}.png" />

                {/* refresh de la position du user toutes les ${timeout} secondes */}
                {/*<ReactInterval {...{timeout, enabled}}*/}
                               {/*callback={this.getUserLoc} />*/}

                {/* création du marker position du user */ }
                {/*{userLocation && <Marker*/}
                    {/*onClick={()=>alert("c'est moi")}*/}
                    {/*position={userLocation}>*/}
                    {/*<Circle*/}
                        {/*center={{lat:userLocation[0], lng: userLocation[1]}}*/}
                        {/*fillColor="blue"*/}
                        {/*radius={20}/>*/}
                {/*</Marker> }*/}

                // {/* iteration sur les POIs pour les afficher sur la carte */}
                // {/*markers.map(x=>
          //   <Marker onClick={()=>this.handleMarkerClick(x)} key={x.id} position={[+x.lon,+x.lat]}/>)*/}

                {/* même chose mais sur le(s) marqueur(s) Dexie */}
                {/*{markersDexie.map(x=>*/}
                    {/*<Marker onClick={()=>this.handleMarkerClick(x)} key={x.id} position={[x.lon,x.lat]}/>)}*/}

                {/* conditional rendering pour afficher ou non un modal (si on a cliqué ou non sur un POI)
           l'objet entier est passé en prop*/}
                {/*{modal && <ModalCustom marker={modal} modalNull = {this.modalNull} />}*/}
            </Map>
        )
    }
}