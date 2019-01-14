import React, { Component } from 'react'
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import axios from 'axios'
import $ from 'jquery'

import { login} from '../../store/actions';

import loo from '../../assets/Loo.png'
import Alert from '../Alert/Alert';

const screenHeight = {
  height: screen.height
};

/**
 * TODO:
 * Invocar las alertas
 * Realizar las funciones de logueo
 * Dejar de utilizar redux y usar sessionStorage
 * Proteger las rutas
 * Realizar el modulo de empresas
 */


class Login extends Component {

  constructor(props) {
    super(props);
    this.state = {
      isLoggedIn: false,
      username: '',
      password: '',
      isSuccess: true,
      alertMessage: ''
    };

  }

  onChangerUser (event) {
    this.setState({username: event.target.value});
  }

  onChangerPassword (event) {
    this.setState({password: event.target.value});
  }
/**
 * 
 *
 * @param {*} event
 * @memberof Login
 */
  handleSubmit(event) {
    event.preventDefault();

    let data = {
      username: this.state.username,
      password: this.state.password
    };

    axios.post('http://payroll.com.local/api/auth/login', data)
        .then((res) => {

            this.setState({
                isLoggedIn: true,
                isSuccess: true,
                alertMessage: 'Logueado con exito'
            });

            $('.toast').toast({delay: 2000});
            $('.toast').toast('show');
            setTimeout(() => {
                this.props.logIn(res.data.isAdmin, res.data.user);
            }, 2000);

        })
        .catch((error) => {
          this.setState({
              isSuccess: false,
              alertMessage: 'Usuario o Contraseña incorrecta.'
          });

          $('.toast').toast({delay: 2000});
          $('.toast').toast('show');
        });
  }

  render() {
    return (
      <div className="container" style={screenHeight}>

        <Alert isSuccess={this.state.isSuccess} message={this.state.alertMessage}/>

        <div className="d-flex justify-content-center align-items-center" style={{height:'80%', width:'100%'}}>

            <div className="card border-light" style={{width:'400px'}}>

              <img src={loo} className="img-fluid card-img-top" alt="LOGO PAYROLL"/>

              <div className="card-body text-center">

                <form onSubmit={() => this.handleSubmit(event)}>

                  <div className="form-group">
                    <label htmlFor="username"><strong>Usuario</strong></label>
                    <input 
                      type="text" 
                      className="form-control" 
                      id="username" 
                      autoComplete="off" 
                      value={this.state.username} 
                      onChange={() => this.onChangerUser(event)}/>
                  </div>

                  <div className="form-group">
                    <label htmlFor="password"><strong>Contraseña</strong></label>
                    <input 
                      type="password" 
                      className="form-control" 
                      id="password" 
                      autoComplete="off" 
                      value={this.state.password} 
                      onChange={() => this.onChangerPassword (event)}/>
                  </div>

                  <button 
                    className="btn btn-success float-right" 
                    type="submit"
                    disabled={this.state.isLoggedIn}>
                      Login
                  </button>
                </form>
              </div>
            </div>
        </div>
      </div>
    )
  }
}

const mapStateToProps = (state) => {
    return {
        isLoggedIn: state.isLoggedIn,
        isAdmin: state.isAdmin,
        user: state.user
    }
};

const mapDispatchToProps = (dispatch) => {
    return {
        logIn: (isAdmin, user) => {
            dispatch(login(isAdmin, user))
        }
    }
};
export default withRouter(connect(mapStateToProps, mapDispatchToProps)(Login));
