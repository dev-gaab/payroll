<template>
 <v-flex xs12>
   <v-card>
      <v-card-title>
        <h3>Trabajadores disponibles</h3>
        <v-spacer></v-spacer>
        <v-text-field
          color="teal darken-4"
          v-model="search"
          append-icon="fa-search"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table :headers="headers" :items="trabajadores" :search="search">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.cedula }}</td>
          <td>{{ props.item.nombre }} {{ props.item.apellido }}</td>
          <!-- Acciones -->
          <td class="justify-center layout px-0">
            <v-btn
              @click="validarVacaciones(props.item.id)"
              icon
              small
              color="success"
            >
              <v-icon small>fa-check</v-icon>
            </v-btn>
          </td>
        </template>

        <v-alert
          slot="no-results"
          :value="true"
          color
          icon="fa-exclamation-triangle"
        >Tu busqueda "{{ search }}" no encontro resultados.</v-alert>
      </v-data-table>
    </v-card>
  </v-flex>
</template>

<script>

import axios from "axios";

export default {
  name: "NewVacaciones",
  data(){
  	return {
  		headers: [
	  		{text: "Cedula", value: "cedula"},
	  		{text: "Nombre", value: "nombre"},
	  		{text: "Acciones", value: "acciones", align: "center", sortable: false}
  		],
  		trabajadores: [],
  		search: "",
  		trabajador: {}
  	}
  },
  methods: {
  	calcularVacaciones() {
  		const vm = this;
  		axios
        .post(`http://payroll.com.local/api/vacaciones/calcular/${id}`, vm.trabajador,{
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {})
        .catch(err => console.log(err));
  	},
  	validarVacaciones(id) {
  		const vm = this;

  		axios
        .get(`http://payroll.com.local/api/vacaciones/validar/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
        	if(!res.data.res) {
        		vm.trabajador = {
        			id,
        			is_fraccionado: true,
        			meses: res.data.meses
        		}
        		vm.dialog = true;

        	} else {
        		v.trabajador = {
        			id,
        			is_fraccionado: false
        		}
        		vm.calcularVacaciones();
        	}
        })
        .catch(err => console.log(err));
  	}
  }
}
</script>
