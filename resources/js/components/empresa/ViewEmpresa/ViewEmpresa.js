import React, {Component} from 'react';
import FormEmpresa from '../FormEmpresa/FormEmpresa';

class ViewEmpresa extends Component {

	constructor(props) {
		super(props);
	}

	render() {
		return (
			<div>
				<div id="viewEmpresa" className="modal fade" tabIndex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
				     aria-hidden="true">
					<div className="modal-dialog modal-lg">
						<div className="modal-content">
							<div className="modal-header">
								<h5 className="modal-title" id="exampleModalCenterTitle">Empresa - {this.props.empresa.razon_social}</h5>
								<button type="button" className="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>

							<div className="modal-body">
								<form>
									<div className="form-group row">
										<label htmlFor="rif" className="col-sm-3 col-form-label">Rif</label>
										<div className="col-sm-9">
											<input
												type="text" className="form-control"
												id="rif" placeholder="Rif de la empresa"
												defaultValue={this.props.empresa.rif}
												readOnly={true}
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
												defaultValue={this.props.empresa.razon_social}
												readOnly={true}
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
									      value={this.props.empresa.direccion}
									      readOnly={true}
								      />
										</div>
									</div>

									<div className="form-group row">
										<label htmlFor="riesgo_ivss" className="col-sm-3 col-form-label">Riesgo IVSS</label>
										<div className="col-sm-9">
											<input
												type="text"
												className="form-control"
												id="riesgo_ivss"
												placeholder=""
												defaultValue={this.props.empresa.riesgo_ivss}
												readOnly={true}
											/>
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
												defaultValue={this.props.empresa.num_afiliacion_ivss}
												readOnly={true}
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
												defaultValue={this.props.empresa.fecha_inscripcion_ivss}
												readOnly={true}
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
												defaultValue={this.props.empresa.num_afiliacion_faov}
												readOnly={true}
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
												defaultValue={this.props.empresa.num_afiliacion_inces}
												readOnly={true}
											/>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}

export default ViewEmpresa;