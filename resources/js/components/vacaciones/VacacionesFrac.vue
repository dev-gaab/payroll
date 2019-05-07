<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-alert v-model="alert" dismissible type="success">{{alertMsg}}</v-alert>
      <v-card>
        <v-card-title>
          <h3>Vacaciones Fraccionadas - Trabajadores disponibles</h3>
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
              <v-btn @click="calcular(props.item.id)" icon small color="success" title="Calcular Vacaciones">
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
const moment = require('moment');

export default {
  name: "VacacionesFrac",
  data() {
    return {
      headers: [
        { text: "Cedula", value: "cedula" },
        { text: "Nombre", value: "nombre" },
        { text: "Fecha Ingreso", value: "fecha_ingreso" },
        { text: "Años de Servcio", value: "a_servicio" },
        {
          text: "Acciones",
          value: "acciones",
          align: "center",
          sortable: false
        }
      ],
      trabajadores: [],
      search: "",
      trabajador: {},
      empresa_id: this.$store.state.empresa.id,
      dialogUpd: false,
      alertUpd: false,
      alertUpdMsg: "",
      alert: false,
      alertMsg: ""
    };
  },
  created() {
    this.allVacacionesFraccionadas();
  },
  methods: {
    allVacacionesFraccionadas() {
      const vm = this;

      axios.get(
        `http://payroll.com.local/api/vacaciones/fraccionadas/disponibles/${
          vm.empresa_id
        }`,
        {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        }
      )
      .then(res => {
        if(!res.data.error) {
          vm.trabajadores = res.data;
        }
      })
      .catch(err => console.log(err));
    },
    calcular(id) {
      const vm = this;

      const data = {
        id,
        isFraccionada: true,
        dias_feriados: 0,
        fecha_inicio: moment().format('YYYY-MM-DD'),
        tipo: 'fraccionada'
      };

      axios.post(`http://payroll.com.local/api/vacaciones/calcular`,
        data,
        {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        }
      )
      .then(res => {
        if(!res.data.error) {
          vm.alertMsg = "Vacaciones Calculadas";
          vm.allVacacionesFraccionadas();
          vm.alert = true;
        }
      })
      .catch(err => console.log(err));
    }
  },
   filters: {
    dateFormat(value) {
      if (!value) return "";

      value = moment(value, "YYYY-MM-DD").format("DD/MM/YYYY");
      return value;
    }
  }
};
</script>
