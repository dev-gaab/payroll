<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-alert v-model="alert" dismissible type="success">{{alertMsg}}</v-alert>
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
              <v-btn @click="modalCalcularVacaciones(props.item)" icon small color="success">
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

    <v-layout row justify-center>
      <v-dialog v-model="dialogUpd" persistent max-width="500">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>{{ trabajador.cedula }} - {{ trabajador.nombre1 }} {{ trabajador.apellido1 }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogUpd = false; trabajador = {}" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <v-alert v-model="alertUpd" dismissible type="error">{{alertUpdMsg}}</v-alert>
          <!-- formulario -->
          <form @submit.prevent="calcularVacaciones">
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('fecha_inicial') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.fecha_inicio"
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
                      v-model="trabajador.dias_feriados"
                      name="dias_feriados"
                      label="Dias Feriados"
                      id="dias_feriados"
                      v-validate="'required|numeric|min_value:0'"
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
              <v-btn
                color="blue darken-1"
                flat
                @click.native="dialogUpd = false; trabajador = {}"
              >Cerrar</v-btn>
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
  name: "NewVacaciones",
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
    this.trabajadoresDisponibles();

    const dict = {
      custom: {
        fecha_inicio: {
          required: "No debe ser vacio"
        },
        dias_feriados: {
          required: "No debe ser vacio",
          numeric: "Solo se permite el ingreso de números",
          min_value: "Solo se aceptan números positivos"
        }
      }
    };

    this.$validator.localize("es", dict);
  },
  mounted() {
    let minDate = moment()
      .subtract(7, "d")
      .format("YYYY-MM-DD");
    let maxDate = moment()
      .add(7, "d")
      .format("YYYY-MM-DD");

    document.getElementById("fecha_inicio").min = minDate;
    document.getElementById("fecha_inicio").max = maxDate;
  },
  methods: {
    calcularVacaciones() {
      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }

        const vm = this;

        axios
          .post(
            `http://payroll.com.local/api/vacaciones/calcular`,
            vm.trabajador,
            {
              headers: {
                Authorization: `Bearer ${vm.$store.state.currentUser.token}`
              }
            }
          )
          .then(res => {
            if (!res.data.error) {
              vm.alert = true;
              vm.alertMsg = "Vacaciones calculadas.";
              vm.dialogUpd = false;
              vm.trabajadoresDisponibles();
            }
          })
          .catch(err => console.log(err));
      });
    },
    trabajadoresDisponibles() {
      const vm = this;

      axios
        .get(
          `http://payroll.com.local/api/vacaciones/disponibles/${
            vm.empresa_id
          }`,
          {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          }
        )
        .then(res => {
          vm.trabajadores = res.data;
        })
        .catch(err => console.log(err));
    },
    modalCalcularVacaciones(trabajador) {
      this.trabajador = trabajador;
      this.trabajador.dias_feriados = 0;
      this.trabajador.tipo = "anual";
      this.dialogUpd = true;
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
