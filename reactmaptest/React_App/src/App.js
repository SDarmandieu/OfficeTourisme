import React, { Component } from 'react'
import './App.css'
import { Map, TileLayer , Marker} from "react-leaflet"
import ModalCustom from './ModalCustom.js'
import ReactInterval from 'react-interval';


class App extends Component {
  state = {
    modal:null, // définit le modal à afficher
    center: [43.107973199999996, 0.7253157], // centre de la map au chargement
    zoom: 17,
    userLocation:null,
    markers: [], // les pois
    enabled: true,
    timeout: 5000
  }

  componentDidMount() {
    this.getUserLoc()
    /**
    *
    * fetch tous les pois et les stocks dans le state markers
    *
    **/
    return fetch('http://192.168.1.118:8000/api/pois')
      .then(response => response.json())
      .then(data => this.setState({markers:data.pois}))
  }

  /**
  *
  * au clic sur un marqueur de POI , stocke le POI dans le state modal
  *
  **/
  handleMarkerClick = (e) => {
    this.setState({modal:e});
    console.log("state",this.state.modal.id)
  }

  /**
  *
  * au clic sur la map , console.log les lat et lon de ce point => DEV , en vue de l'interface admin
  *
  **/
  handleMapClick = (e) => console.log(`mapclic : latitude: ${e.latlng.lat} , longitude: ${e.latlng.lng}`)

  /**
  *
  * tools pour localiser le user
  *
  **/
  geolocOpts = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
  }

  geolocSuccess = pos => { 
    const crd = pos.coords
    this.setState({userLocation:[crd.latitude,crd.longitude]})
    console.log("mylocation",this.state.userLocation) }

  geolocError = err => console.warn(`ERREUR (${err.code}): ${err.message}`)

  getUserLoc = () => window.navigator.geolocation.getCurrentPosition(this.geolocSuccess, this.geolocError, this.geolocOpts)




  render() {
    console.log("markers",this.state.markers)

    const {timeout, enabled, userLocation , markers , modal , zoom , center} = this.state

    return (
      <div className="App">

        {/* div qui contient la map */}
        <Map
          center={center}
          onClick={(e)=>this.handleMapClick(e)} 
          zoom={zoom}>

          {/* layer openstreetmap */}
          <TileLayer
            attribution='&amp;copy <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            url="https://{s}.tile.osm.org/{z}/{x}/{y}.png" />

          <ReactInterval {...{timeout, enabled}}
          callback={this.getUserLoc} />

          {/* création du marker position du user */ console.log("test",userLocation)}
          {userLocation && <Marker position={userLocation}/> }

          {/* iteration sur les POIs pour les afficher sur la carte */}
          {markers.map(x=>
            <Marker onClick={()=>this.handleMarkerClick(x)} key={x.id} position={[+x.lon,+x.lat]}/>)}

          {/* conditional rendering pour afficher ou non un modal (si on a cliqué ou non sur un POI)
           l'objet entier est passé en prop*/}
          {modal && <ModalCustom marker={modal} />}
        </Map>

      </div>
    );
  }
}

export default App;
