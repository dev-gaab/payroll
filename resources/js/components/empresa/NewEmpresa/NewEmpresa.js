import React, { Component } from 'react'
import MaterialTable from 'material-table'
import icons from 'material-design-icons-iconfont'
import axios from 'axios'
import {withRouter} from "react-router-dom";
import connect from "react-redux/es/connect/connect";
import $ from 'jquery'

import Header from '../../Layout/Header';
import FormEmpresa from '../FormEmpresa/FormEmpresa';
import Alert from '../../Alert/Alert';

class NewEmpresa extends Component {
	constructor(props) {
		super(props);

		this.state = {
			empresas: [],
			newEmpresa: {},
			alert: {
				isSuccess: false,
				message: ''
			}
		}

		this.saveEmpresa = this.saveEmpresa.bind(this);
	}

	saveEmpresa(empresa) {

		axios.post(
			'http://payroll.com.local/api/empresas',
			empresa,
			{headers: {'Authorization': `Bearer ${this.props.user.token}`} })
			.then((res) => {

				let {empresas} = this.state;
				empresas.push({rif: empresa.rif, razon_social: empresa.razon_social});
				console.log(empresas);

				this.setState({
					empresas: empresas,
					alert: {
						isSuccess: true,
						message: 'Empresa registrada'
					}
				});

				$('.toast').toast({delay: 2000});
				$('.toast').toast('show');

			}).catch((error) => {
				this.setState({
					empresas: empresas,
					alert: {
						isSuccess: false,
						message: 'Error en el registro'
					}
				});

				$('.toast').toast({delay: 2000});
				$('.toast').toast('show');
			});
	}

  render() {
    return (
      <div>
        <Header/>
        <div className="container-fluid mt-4">

	        <Alert isSuccess={this.state.alert.isSuccess} message={this.state.alert.message}/>

          <div className="row mb-4">
	          {/*Formulario para Agregar nuevas empresas*/}
            <div className="col-sm-8">
	            <div className="card">
		            <h3 className="card-header text-light" style={{backgroundColor: '#0392A2'}}>Nueva Empresa</h3>
		            <div className="card-body">
			            <FormEmpresa save={this.saveEmpresa}/>
		            </div>
	            </div>
            </div>
	          {/*Tabla mostrando las empresas agregadas en la operacion*/}
	          <div className="col-sm-4">
		          <MaterialTable
			          columns={[
				          {title: 'Rif', field: 'rif'},
				          {title: 'Razón Social', field: 'razon_social'},
			          ]}
			          data={this.state.empresas}
			          title="Empresas"
		          />
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

export default withRouter(connect(mapStateToProps)(NewEmpresa));
