import React, {Component} from 'react';
import {Grid, Col, Image, Row} from "react-bootstrap";

export default class Footer extends Component {
    constructor(props) {
        super(props)
        this.state = {}
    }

    render() {
        return (
            <Grid style={grid}>
                <Row>
                    <Col xs={4} style={col}>
                        <Image src="/images/5c-logo.png" rounded responsive style={img}/>
                    </Col>
                    <Col xs={4}>
                        <Image src="/images/5c-logo.png" rounded responsive style={img}/>
                    </Col>
                    <Col xs={4}>
                        <Image src="/images/5c-logo.png" rounded responsive style={img}/>
                    </Col>
                </Row>
            </Grid>
        )
    }
}

const col = {
    height: '90%',
}

const grid = {
    height: '14vh',
    width: '100%',
    top: '86vh',
    backgroundColor: '#222',
    borderColor: '#080808'
}

const img = {
    margin : '1vh 1vh 0 0',
    height:'12vh'
}