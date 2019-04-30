<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-card>
         <v-card-title>
           <h3>Vacaciones - Trabajadores disponibles</h3>
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
             <td>{{ props.item.nombre1 }} {{ props.item.apellido1 }}</td>
             <td>{{ props.item.fecha_ingreso | dateFormat }}</td>
             <td>{{ props.item.a_servicio }}</td>
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
  </v-layout>
</template>

<script>

import axios from "axios";
const moment = require("moment");

export default {
  name: "NewVacaciones",
  data(){
  	return {
  		headers: [
	  		{text: "Cedula", value: "cedula"},
	  		{text: "Nombre", value: "nombre"},
        {text: "Fecha Ingreso", value: "fecha_ingreso"},
        {text: "Años de Servcio", value: "a_servicio"},
	  		{text: "Acciones", value: "acciones", align: "center", sortable: false}
  		],
  		trabajadores: [],
  		search: "",
  		trabajador: {},
      empresa_id: this.$store.state.empresa.id,
  	}
  },
  created() {
    this.trabajadoresDisponibles();
  },
  methods: {
  	calcularVacaciones() {
  		const vm = this;

  		axios
        .post(`http://payroll.com.local/api/vacaciones/calcular`, vm.trabajador,{
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
  	},
    trabajadoresDisponibles() {
      const vm = this;

      axios
        .get(`http://payroll.com.local/api/vacaciones/disponibles/${vm.empresa_id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.trabajadores = res.data;
        })
        .catch(err => console.log(err));
    }
  },
  filters: {
    dateFormat(value) {
      if (!value) return "";

      value = moment(value, "YYYY-MM-DD").format("DD/MM/YYYY");
      return value;
    },
  }
}
</script>
