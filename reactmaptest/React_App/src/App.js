import React, { Component } from 'react'
import './App.css'
import { Map, TileLayer , Marker , Circle} from "react-leaflet"
import ModalCustom from './ModalCustom.js'
import ReactInterval from 'react-interval'

//import MapCustom from './MapCustom.js'

/**
*
* dexie.js
*
**/
import db from './db';


class App extends Component {
  constructor(props){
    super(props)
    this.modalNull = this.modalNull.bind(this)
    this.state = {
      modal:null, // définit le modal à afficher
      center: [43.107973199999996, 0.7253157], // centre de la map au chargement
      zoom: 17,
      userLocation:null,
      markers: [], // les pois
      enabled: true,
      timeout: 5000,

      markersDexie: [] // dexie.js
  }
}
  
  /**
  *
  * fonction passée au customModal enfant en prop , pour le force à rendre null le state , afin que 
  * le modal ne se réouvre pas à chaque update de component
  *
  **/
  modalNull()  { this.setState({ modal: null}) }


  componentDidMount() {

    // premier appel à la fonction qui donne les coords de user (à supprimer ?) 
    //this.getUserLoc()

    /**
    *
    * transformation de la DB sql sur l'API Laravel en IndexedDB avec Dexie
    *
    **/
    fetch('http://192.168.1.118:8000/api/dexie')
      .then(response => response.json())
      .then(data => {
        console.log("data",data)
        Object.entries(data).forEach(([tableName,tableDatas]) => {
          tableDatas.map(current=> db.table(tableName)
            .add(current)
            .then(id => {
              const newpoi = [...this.state.markersDexie, Object.assign({}, current, { id })]
              this.setState({ markersDexie: newpoi })
              console.log("marker Dexie",this.state.markersDexie)
            }))
          })
      })

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
    this.setState({modal:e})
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

  /**
  *
  * fonction appelée dans le react-interval pour mettre à jour la position de user
  *
  **/
  getUserLoc = () => window.navigator.geolocation.getCurrentPosition(this.geolocSuccess, this.geolocError, this.geolocOpts)


  render() {
    console.log("markers",this.state.markers)

    const {timeout, enabled, userLocation , markers , modal , zoom , center , markersDexie} = this.state

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

          {/* refresh de la position du user toutes les ${timeout} secondes */}
          <ReactInterval {...{timeout, enabled}}
          callback={this.getUserLoc} />

          {/* création du marker position du user */ console.log("test",userLocation)}
          {userLocation && <Marker 
            onClick={()=>alert("c'est moi")} 
            position={userLocation}>
            <Circle 
                  center={{lat:userLocation[0], lng: userLocation[1]}}
                  fillColor="blue" 
                  radius={20}/>
            </Marker> }

          {/* iteration sur les POIs pour les afficher sur la carte */}
          {markers.map(x=>
            <Marker onClick={()=>this.handleMarkerClick(x)} key={x.id} position={[+x.lon,+x.lat]}/>)}

          {/* même chose mais sur le(s) marqueur(s) Dexie */}
          {markersDexie.map(x=>
            <Marker onClick={()=>alert("DEXIE M A TUER")} key={x.id} position={[x.lon,x.lat]}/>)}

          {/* conditional rendering pour afficher ou non un modal (si on a cliqué ou non sur un POI)
           l'objet entier est passé en prop*/}
          {modal && <ModalCustom marker={modal} modalNull = {this.modalNull} />}
        </Map>


      </div>
    );
  }
}

export default App;