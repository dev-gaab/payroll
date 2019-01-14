import React, { Component } from 'react'
import axios from 'axios'
import {withRouter, Link} from "react-router-dom";
import connect from "react-redux/es/connect/connect";
import Paper from '@material-ui/core/Paper';
import $ from 'jquery';
import {
	SortingState,
	SearchState,
	IntegratedFiltering,
	IntegratedSorting,
	PagingState,
	IntegratedPaging,
} from '@devexpress/dx-react-grid';
import {
	Grid,
	Table,
	Toolbar,
	SearchPanel,
	TableHeaderRow,
	VirtualTable,
	PagingPanel,
} from '@devexpress/dx-react-grid-material-ui';

import Header from '../Layout/Header'
import MaterialTable from 'material-table'
import icons from 'material-design-icons-iconfont'
import ViewEmpresa from "./ViewEmpresa/ViewEmpresa";

class AllEmpresas extends Component {

	constructor(props) {
		super(props);
		this.state = {
			empresas: [],
			columns: [
				{name: 'rif', title: 'Rif'},
				{name: 'razon_social', title: 'Razón Social'},
				{name: 'estatus', title: 'Estatus'},
				{name: 'actions', title: 'Acciones'},
			],
			tableColumnExtensions: [
				{ columnName: 'actions', align: 'right' },
			],
			viewEmpresa: {}
		};

	}

	componentDidMount() {
		this.getAllData();
	}

	getAllData() {
		axios.get('http://payroll.com.local/api/empresas',
			{headers: {'Authorization': `Bearer ${this.props.user.token}`}})
			.then((res) => {
				let data = res.data.empresas.map((item) => {
					item.actions =
						<div className="btn-group mr-4" role="group">
							<button
								className="btn btn-sm btn-outline-secondary"
								onClick={() => this.viewEmpresa(item)}>
								<i className="material-icons">visibility</i>
							</button>
							<button className="btn btn-sm btn-outline-secondary"><i className="material-icons">edit</i></button>
							<button className="btn btn-sm btn-outline-secondary"><i className="material-icons">lock</i></button>
						</div>
					;
					return item;
				});
				this.setState({
					empresas: data
				});
			})
			.catch((error) => {
				console.log(error);
			});
	}

	newEmpresa() {

	}

	async viewEmpresa(empresa) {
		await this.setState({
			viewEmpresa: empresa
		});
		$('#viewEmpresa').modal();
	}

	editEmpresa() {

	}

	disableEmpresa() {

	}

	render() {
		const {empresas, columns, tableColumnExtensions} = this.state;

		return (
			<div>
				<Header/>
				<ViewEmpresa empresa={this.state.viewEmpresa}/>
				<div className="container mt-4">
					<div className="row mb-4">
						<div className="col">
							<h2 className="float-left">Empresas</h2>
							<Link to="/empresas-new" style={{backgroundColor: '#0392A2'}} className="btn float-right text-light">
								<i className="material-icons d-inline-block align-top">add</i>
								Nueva
							</Link>
						</div>
					</div>
					<div className="row">
						<div className="col-sm-12">
							<Paper>
								<Grid
									rows={empresas}
									columns={columns}
								>
									<PagingState
										defaultCurrentPage={0}
										pageSize={20}
									/>
									<IntegratedPaging />
									<SortingState/>
									<IntegratedSorting />
									<SearchState />
									<IntegratedFiltering/>
									<VirtualTable columnExtensions={tableColumnExtensions}/>
									<TableHeaderRow showSortingControls/>
									<Toolbar />
									<SearchPanel />
									<PagingPanel />
								</Grid>
							</Paper>
						</div>
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = (state) => {
	return {
		isAdmin: state.isAdmin,
		user: state.user
	}
};

const mapDispatchToProps = (dispatch) => {
	return {
		activeEmpresa: (empresa) => {
			dispatch(activeEmpresa(empresa))
		}
	}
};
export default withRouter(connect(mapStateToProps, mapDispatchToProps)(AllEmpresas));
