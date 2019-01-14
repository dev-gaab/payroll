import React, { Component } from 'react'
import { connect } from 'react-redux';
import { withRouter, Link } from 'react-router-dom';

import logo from '../../assets/logo.png'
import {logout} from "../../store/actions";

class Header extends Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <div style={{backgroundColor:'#737577'}}>
      
        <nav className="navbar navbar-expand-lg navbar-dark">
          <div className="navbar-brand" style={{width: '250px', height:'70px'}}>
            <img src={logo} className="img-fluid" alt=""/>
          </div>

          <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
          </button>

          <div className="collapse navbar-collapse" id="navbarNav">
            <ul className="navbar-nav">
              <li className="nav-item">
                  <Link className="nav-link" to="/"> Home </Link>
              </li>
              <li className="nav-item">
                  <Link className="nav-link" to="/empresas"> Empresas </Link>
              </li>
              <li className="nav-item">
                <a className="nav-link" href="#">Pricing</a>
              </li>
              <li className="nav-item">
                <a className="nav-link disabled" href="#">Disabled</a>
              </li>

            </ul>
          </div>
            <div className="justify-content-end">
                <li className="nav-item dropdown" style={{listStyle: 'none', textDecoration: 'none'}}>
                    <a className="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {this.props.user.name}
                        <img width='30' height='30' className="ml-4 img-thunbaild rounded-circle" src="http://www.coordinadora.com/wp-content/uploads/sidebar_usuario-corporativo.png" alt="image usuario"/>
                    </a>
                    <div className="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a className="dropdown-item" href="#" onClick={() => this.props.logOut()}>Cerrar Sesión</a>
                    </div>

                </li>
            </div>
        </nav>

        <ul className="nav text-light">
          <h4>{this.props.empresa == '' ? `${this.props.empresa.rif} - ${this.props.empresa.razon_social}` : '' }</h4>
        </ul>

      </div>
    )
  }
}

const mapStateToProps = (state) => {
    return {
        user: state.user,
        empresa: state.empresa
    }
};

const mapDispatchToProps = (dispatch) => {
    return {
        logOut: () => {
            dispatch(logout())
        }
    }
};

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(Header));
