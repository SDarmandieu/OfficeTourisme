import React, {Component} from 'react';
import {Navbar, NavItem, Nav} from "react-bootstrap";
import {LinkContainer} from "react-router-bootstrap";

export default class Navigation extends Component {
    constructor(props) {
        super(props)
        this.state = {}
    }

    render() {
        return (
            <Navbar inverse collapseOnSelect fixedTop>
                <Navbar.Header>
                    <Navbar.Brand>
                        <a href="#brand">Jeux de piste 5C</a>
                    </Navbar.Brand>
                    <Navbar.Toggle/>
                </Navbar.Header>
                <Navbar.Collapse>
                    <Nav>
                        <LinkContainer to="/">
                            <NavItem eventKey={1}>
                                Accueil
                            </NavItem>
                        </LinkContainer>
                        <LinkContainer to="/account">
                            <NavItem eventKey={2}>
                                Mon compte
                            </NavItem>
                        </LinkContainer>
                        <LinkContainer to="/contacts">
                            <NavItem eventKey={3}>
                                Contacts
                            </NavItem>
                        </LinkContainer>
                        <LinkContainer to="/tutorial">
                            <NavItem eventKey={4}>
                                Tutoriel
                            </NavItem>
                        </LinkContainer>
                    </Nav>
                </Navbar.Collapse>
            </Navbar>
        )
    }
}