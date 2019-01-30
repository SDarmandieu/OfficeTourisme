import React, {Component} from 'react';
import {Grid, Col, Image, Row} from "react-bootstrap";
import {Link} from 'react-router-dom'
import FontAwesome from 'react-fontawesome'

export default class GameNavigation extends Component {
    constructor(props) {
        super(props)
        this.state = {}
    }

    render() {
        return (
            <Grid className="leaflet-bottom" style={grid}>
                <Row>
                    <Col xs={4}>
                        <Link style={link} to="/">
                            <FontAwesome
                                name='home'
                                size="4x"
                            />Accueil</Link>
                    </Col>
                    <Col xs={4}>
                        <Link style={link} to="/account">
                            <FontAwesome
                                name='userCog'
                                size="4x"
                            />Mon compte</Link>
                    </Col>
                    <Col xs={4}>
                        <Image src="/images/5c-logo.png" rounded responsive style={img}/>
                    </Col>
                </Row>
            </Grid>
        )
    }
}

const grid = {
    height: '13vh',
    width: '100%',
    position: 'fixed',
    top: '87vh',
    backgroundColor: 'transparent',
    borderColor: '#080808',
}

const img = {
    margin: '1vh 1vh 0 0',
    height: '12vh'
}

const link = {
    pointerEvents: 'auto',
    color:'black'
}
