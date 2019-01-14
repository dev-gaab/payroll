import React, {Component} from 'react';
import icons from 'material-design-icons-iconfont'


class FormEmpresa extends Component {
	constructor(props) {
		super(props);

		this.state = {
			rif: '',
			razon_social: '',
			direccion: '',
			riesgo_ivss: 'minimo',
			fecha_inscripcion_ivss: '',
			num_afiliacion_ivss: '',
			num_afiliacion_faov: '',
			num_afiliacion_inces: ''
		};

	}

	componentDidMount() {
		if (this.props.empresa != null) {
			this.setState({
				rif: this.props.empresa.rif,
				razon_social: this.props.empresa.razon_social,
				direccion: this.props.empresa.direccion,
				riesgo_ivss: this.props.empresa.riesgo_ivss,
				fecha_inscripcion_ivss: this.props.empresa.fecha_inscripcion_ivss,
				num_afiliacion_ivss: this.props.empresa.num_afiliacion_ivss,
				num_afiliacion_faov: this.props.empresa.num_afiliacion_faov,
				num_afiliacion_inces: this.props.empresa.num_afiliacion_inces
			})
		}
	}

	save(event) {
		event.preventDefault();

		const empresa = {
			rif: this.state.rif,
			razon_social: this.state.razon_social,
			direccion: this.state.direccion,
			riesgo_ivss: this.state.riesgo_ivss,
			fecha_inscripcion_ivss: this.state.fecha_inscripcion_ivss,
			num_afiliacion_ivss: this.state.num_afiliacion_ivss,
			num_afiliacion_faov: this.state.num_afiliacion_faov,
			num_afiliacion_inces: this.state.num_afiliacion_inces
		};

		this.props.save(empresa);
	}

	render() {
    return (
      <form onSubmit={(event) => this.save(event)}>
        <div className="form-group row">
	        <label htmlFor="rif" className="col-sm-3 col-form-label">Rif</label>
          <div className="col-sm-9">
	          <input
		          type="text" className="form-control"
		          id="rif" placeholder="Rif de la empresa"
		          value={this.state.rif}
		          onChange={(event) => this.setState({rif: event.target.value})}
	          />
          </div>
        </div>

        <div className="form-group row">
	        <label htmlFor="razon_social" className="col-sm-3 col-form-label">Razón Social</label>
	        <div className="col-sm-9">
		        <input
			        type="text"
			        className="form-control"
			        id="razon_social"
			        placeholder="Nombre de la empresa"
			        value={this.state.razon_social}
			        onChange={(event) => this.setState({razon_social: event.target.value})}
		        />
	        </div>
        </div>

	      <div className="form-group row">
		      <label htmlFor="direccion" className="col-sm-3 col-form-label">Dirección</label>
		      <div className="col-sm-9">
			      <textarea
				      className="form-control"
				      id="direccion"
				      placeholder="Dirección de la empresa"
				      value={this.state.direccion != '' ? this.state.direccion : ''}
				      onChange={(event) => this.setState({direccion: event.target.value})}
			      />
		      </div>
	      </div>

	      <div className="form-group row">
		      <label htmlFor="riesgo_ivss" className="col-sm-3 col-form-label">Riesgo IVSS</label>
		      <div className="col-sm-9">
			      <select
				      defaultValue={this.state.riesgo_ivss != '' ? this.state.riesgo_ivss : 'minimo'}
				      name="riesgo_ivss" id="riesgo_ivss"
				      className="form-control"
				      onChange={(event) => this.setState({riesgo_ivss: event.target.value})}
			      >
				      <option value="minimo">Mínimo</option>
				      <option value="medio">Medio</option>
				      <option value="maximo">Máximo</option>
			      </select>
		      </div>
	      </div>

	      <div className="form-group row">
		      <label htmlFor="num_afiliacion_ivss" className="col-sm-3 col-form-label">Núm. Afiliación IVSS</label>
		      <div className="col-sm-9">
			      <input
				      type="text"
				      className="form-control"
				      id="num_afiliacion_ivss"
				      placeholder=""
				      value={this.state.num_afiliacion_ivss != '' ? this.state.num_afiliacion_ivss : ''}
				      onChange={(event) => this.setState({num_afiliacion_ivss: event.target.value})}
			      />
		      </div>
	      </div>

	      <div className="form-group row">
		      <label htmlFor="fecha_inscripcion_ivss" className="col-sm-3 col-form-label">Fecha de Inscripción IVSS</label>
		      <div className="col-sm-9">
			      <input
				      type="date"
				      className="form-control"
				      id="fecha_inscripcion_ivss" placeholder=""
				      value={this.state.fecha_inscripcion_ivss != '' ? this.state.fecha_inscripcion_ivss : ''}
				      onChange={(event) => this.setState({fecha_inscripcion_ivss: event.target.value})}
			      />
		      </div>
	      </div>

	      <div className="form-group row">
		      <label htmlFor="num_afiliacion_faov" className="col-sm-3 col-form-label">Núm. Afiliación FAOV</label>
		      <div className="col-sm-9">
			      <input
				      type="text"
				      className="form-control"
				      id="num_afiliacion_faov"
				      placeholder=""
				      value={this.state.num_afiliacion_faov != '' ? this.state.num_afiliacion_faov : ''}
				      onChange={(event) => this.setState({num_afiliacion_faov: event.target.value})}
			      />
		      </div>
	      </div>

	      <div className="form-group row">
		      <label htmlFor="num_afiliacion_inces" className="col-sm-3 col-form-label">Núm. Afiliación INCES</label>
		      <div className="col-sm-9">
			      <input
				      type="text"
				      className="form-control"
				      id="num_afiliacion_inces"
				      placeholder=""
				      value={this.state.num_afiliacion_inces != '' ? this.state.num_afiliacion_inces : ''}
				      onChange={(event) => this.setState({num_afiliacion_inces: event.target.value})}
			      />
		      </div>
	      </div>

	      <button type="submit" style={{backgroundColor: '#0392A2'}} className="btn btn-lg float-right text-light"><i className="material-icons d-inline-block align-top">save</i> Guardar</button>
      </form>
  );
  }
}

export default FormEmpresa;