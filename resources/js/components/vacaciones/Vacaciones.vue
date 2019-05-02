<template>
  <v-layout row wrap>
    <!-- Tabla de datos -->
    <v-flex xs12>
      <v-alert v-model="alert" dismissible type="success">{{alertMsg}}</v-alert>
      <v-card>
        <v-card-title>
          <h3>Vacaciones</h3>
          <v-spacer></v-spacer>
          <v-text-field
            color="teal darken-4"
            v-model="search"
            append-icon="fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-btn icon color="teal darken-4" dark @click="printAll">
            <v-icon>print</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="vacaciones" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre1 }} {{props.item.apellido1 }}</td>
            <td>{{ props.item.a_servicio }}</td>
            <td>{{ props.item.dias_disfrute }}</td>
            <td>{{ props.item.dias_feriados }}</td>
            <td>{{ props.item.fecha_inicial | dateFormat }}</td>
            <td>{{ props.item.fecha_final | dateFormat }}</td>
            <td>{{ props.item.montos.total_pagar | numberFormat }}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn v-if="comprobarFechaInicio(props.item.fecha_final)" @click="editVacaciones(props.item.id)" icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn icon small color="error" @click="deleteVacaciones(props.item.id)">
                <v-icon small>fa-trash</v-icon>
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
    <!-- componente modal para editar empresa -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogUpd" persistent max-width="500">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Modificar Vacaciones</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogUpd = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <v-alert v-model="alertUpd" dismissible type="error">{{alertUpdMsg}}</v-alert>
          <!-- formulario -->
          <form @submit.prevent="save">
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      v-if="comprobarFechaInicio(vacacion.fecha_inicial)"
                      :color="errors.has('fecha_inicial') ? 'error' : 'teal darken-1'"
                      v-model="vacacion.fecha_inicial"
                      name="fecha_inicio"
                      label="Fecha Inicial"
                      id="fecha_inicio"
                      type="date"
                      v-validate="'required'"
                    ></v-text-field>

                    <v-alert
                      v-show="errors.has('fecha_inicio')"
                      type="error"
                    >{{errors.first('fecha_inicio')}}</v-alert>
                  </v-flex>

                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('dias_feriados') ? 'error' : 'teal darken-1'"
                      v-model="vacacion.dias_feriados"
                      name="dias_feriados"
                      label="Razon Social"
                      id="dias_feriados"
                      v-validate="'required|numeric'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('dias_feriados')"
                      type="error"
                    >{{errors.first('dias_feriados')}}</v-alert>
                  </v-flex>
                </v-layout>
              </v-container>

            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="dialogUpd = false">Cerrar</v-btn>
              <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
            </v-card-actions>
          </form>
          <!-- Fin form-->
        </v-card>
      </v-dialog>
    </v-layout>
  </v-layout>
</template>

<script>
import axios from "axios";
const moment = require("moment");

export default {
  name: "Vacaciones",
  data() {
    return {
      search: "",
      headers: [
        { text: "Cedula", value: "cedula" },
        { text: "Nombre", value: "nombre" },
        { text: "Años de servicio", value: "a_servicio" },
        { text: "Días disfrute", value: "dias_disfrute" },
        { text: "Días feriados", value: "dias_feriados" },
        { text: "Fecha Inicial", value: "fecha_inicio" },
        { text: "Fecha Final", value: "fecha_final" },
        { text: "Monto a pagar", value: "monto" },
        {
          text: "Acciones",
          value: "acciones",
          align: "center",
          sortable: false
        }
      ],
      vacaciones: [],
      idE: this.$store.state.empresa.id,
      alert: false,
      alertType: "success",
      alertMsg: "",
      vacacion: {
        fecha_inicio: "2019-01-01",
        dias_feriados: 0
      },
      dialogUpd: false,
      alertUpd: false,
      alertUpdMsg: ""
    };
  },
  created() {
    this.allVacaciones();
  },
  methods: {
    allVacaciones() {
      const vm = this;

      axios
        .get(`http://payroll.com.local/api/vacaciones/all/${vm.idE}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.vacaciones = res.data;
        })
        .catch(err => console.log(err));
    },
    agregarDiasFeriados(id) {
      const vm = this;
      axios
        .put(`http://payroll.com.local/api/vacaciones/dias_feriados/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          //desplegar alerta;
          vm.alert = true;
          vm.alertType = "success";
          vm.alertMsg = "Dias feriados agregados";
        })
        .catch(err => console.log(err));
    },
    editVacaciones(id) {
      const vm = this;
      axios
        .get(`http://payroll.com.local/api/vacaciones/ver/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.vacaciones = res.data;
          vm.formVacaciones = {
            fecha_inicio: res.data.fecha_inicio,
            dias_feriados: 0
          };
          vm.dialogUpd = true;
        })
        .catch(err => console.log(err));
    },
    comprobarFechaInicio(fecha) {
      let now = moment().format('YYYY-MM-DD');
      let a = moment(now).isSameOrBefore(fecha);

      return a;
    },

    printAll(){

    },
    save() {

    }

  },
  filters: {
     dateFormat(value) {
      if (!value) return "";

      value = moment(value, "YYYY-MM-DD").format("DD/MM/YYYY");
      return value;
    },
     numberFormat(value) {
      value = new Intl.NumberFormat("es-VE", {
        style: "currency",
        currency: "VES"
      }).format(value);
      return value;
    }
  }
};
</script>
