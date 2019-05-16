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
        </v-card-title>
        <v-data-table :headers="headers" :items="vacaciones" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre1 }} {{props.item.apellido1 }}</td>
            <td>{{ props.item.tipo | capitalize }}</td>
            <td>{{ props.item.a_servicio }}</td>
            <td>{{ props.item.dias_disfrute }}</td>
            <td>{{ props.item.dias_feriados }}</td>
            <td>{{ props.item.dias_descanso }}</td>
            <td>{{ props.item.fecha_inicial | dateFormat }}</td>
            <td>{{ props.item.fecha_final | dateFormat }}</td>
            <td>{{ props.item.montos.total_pagar | numberFormat }}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn
                v-if="comprobarFecha(props.item.fecha_final) && props.item.tipo == 'anual'"
                @click="editVacaciones(props.item.id)"
                icon
                small
                color="warning"
                title="Editar vacaciones"
              >
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn
                v-if="comprobarFecha(props.item.fecha_final)"
                icon
                small
                color="error"
                @click="deleteVacaciones(props.item.id)"
                title="Eliminar Vacaciones"
              >
                <v-icon small>fa-trash</v-icon>
              </v-btn>
              <v-btn
                icon
                small
                color="primary"
                @click="print(props.item.id)"
                title="Eliminar Vacaciones"
              >
                <v-icon small>print</v-icon>
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
            <v-toolbar-title>Modificar | {{ formVacaciones.cedula }} {{ formVacaciones.nombre1 }} {{formVacaciones.apellido1 }}</v-toolbar-title>
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
                  <v-flex xs6 v-if="comprobarFecha(formVacaciones.fecha_inicial)">
                    <v-text-field
                      v-if="comprobarFecha(formVacaciones.fecha_inicial)"
                      :color="errors.has('fecha_inicio') ? 'error' : 'teal darken-1'"
                      v-model="formVacaciones.fecha_inicial"
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

                  <v-flex xs6 v-if="comprobarFecha(formVacaciones.fecha_final
                  )">
                    <v-text-field
                      v-if="comprobarFecha(formVacaciones.fecha_final
                      )"
                      :color="errors.has('dias_feriados') ? 'error' : 'teal darken-1'"
                      v-model="formVacaciones.dias_feriados"
                      name="dias_feriados"
                      label="Dias Feriados"
                      id="dias_feriados"
                      v-validate="`required|numeric|min_value:${formVacaciones.dias_feriados}`"
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
import { historial } from "../users/historial";
import { numeroALetras } from "./numLetras";

const moment = require("moment");

export default {
  name: "Vacaciones",
  data() {
    return {
      search: "",
      headers: [
        { text: "Cedula", value: "cedula" },
        { text: "Nombre", value: "nombre" },
        { text: "Tipo", value: "tipo" },
        { text: "Años de servicio", value: "a_servicio" },
        { text: "Días disfrute", value: "dias_disfrute" },
        { text: "Días feriados", value: "dias_feriados" },
        { text: "Días descanso", value: "dias_descanso" },
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
      dialogUpd: false,
      alertUpd: false,
      alertUpdMsg: "",
      formVacaciones: {}
    };
  },
  created() {
    this.allVacaciones();

    const dict = {
      custom: {
        fecha_inicio: {
          required: "No debe ser vacio"
        },
        dias_feriados: {
          required: "No debe ser vacio",
          numeric: "Solo se permite el ingreso de números",
          min_value: "No puede ser menor a los días feriados anteriores"
        }
      }
    };

    this.$validator.localize("es", dict);
  },
  mounted() {
    let maxDate = moment()
      .add(7, "d")
      .format("YYYY-MM-DD");

    document.getElementById("fecha_inicio").max = maxDate;
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
          vm.formVacaciones = res.data;
          vm.dialogUpd = true;

          document.getElementById("fecha_inicio").min = res.data.fecha_inicial;
        })
        .catch(err => console.log(err));
    },
    comprobarFecha(fecha) {
      let now = moment().format("YYYY-MM-DD");
      let a = moment(now).isSameOrBefore(fecha);

      return a;
    },
    deleteVacaciones(id) {
      let confirm = window.confirm(
        "¿Seguro que quiere eliminar las Vacaciones?"
      );

      if (confirm) {
        const vm = this;

        axios
          .delete(`http://payroll.com.local/api/vacaciones/${id}`, {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          })
          .then(res => {
            vm.alert = true;
            vm.alertMsg = "Vacación eliminada";
            vm.allVacaciones();
          })
          .catch(err => console.log(err));

        this.dialogDis = false;
      }
    },
    printAll() {},
    save() {
      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }
        const vm = this;
        axios
          .put(
            `http://payroll.com.local/api/vacaciones/${vm.formVacaciones.id}`,
            vm.formVacaciones,
            {
              headers: {
                Authorization: `Bearer ${vm.$store.state.currentUser.token}`
              }
            }
          )
          .then(res => {
            if (!res.data.error) {
              vm.alert = true;
              vm.alertType = "success";
              vm.alertMsg = "Vacaciones modificadas";
              vm.allVacaciones();
              vm.dialogUpd = false;

              historial(
                vm.$store.state.currentUser.token,
                "Modificar vacaciones"
              );
            }
          })
          .catch(error => console.log(error));
      });
    },
    print(id) {
      const vm = this;
      axios
        .get(`http://payroll.com.local/api/reportes/vacaciones/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          const date = moment().format("DD-MM-YYYY, h:mm:ss a");
          const montoTotal = vm.nmbFormat(res.data.montos.total_pagar);
          const montoLet = numeroALetras(res.data.montos.total_pagar);
          const year = moment(res.data.fecha_inicial).format("YYYY");
          const ya = year - 1;

          let dd = {
            footer: {
              columns: [
                {
                  text: `${this.$store.state.empresa.direccion}`,
                  alignment: "center",
                  bold: true
                }
              ]
            },
            content: [
              {
                stack: [
                  `${vm.$store.state.empresa.nombre}`,
                  `RIF ${vm.$store.state.empresa.rif}`
                ],
                style: "header"
              },
              {
                text: "Recibo de vacaciones",
                bold: true,
                alignment: "center",
                fontSize: 16,
                margin: [0, 0, 0, 4]
              },
              {
                text: `Emitido el ${date}`,
                bold: true,
                alignment: "center",
                fontSize: 10,
                margin: [0, 0, 0, 4]
              },
              {
                text: `POR: ${montoTotal}`,
                bold: true,
                alignment: "center",
                margin: [0, 15, 0, 15]
              },
              {
                text: `He recibido de ${
                  vm.$store.state.empresa.nombre
                } la cantidad de ${montoLet} (${montoTotal}), por concepto de VACACIONES MAS LA LEY DE ALIMENTACION (CESTA TICKET), correspondientes al periodo AÑO ${ya} - ${year}.`,
                margin: [0, 0, 0, 15]
              },
              {
                text: `Queda entendido que ${
                  vm.$store.state.empresa.nombre
                } no me adeuda nada por este concepto por el periodo comprendido desde el año ${ya} - ${year}`,
                bold: true,
                margin: [0, 0, 0, 15]
              },
              {
                text: `Carúpano: ${moment().format("DD-MM-YYYY")}`,
                bold: true,
                margin: [0, 0, 0, 20]
              },
              {
                text: `Recibo Conforme`,
                bold: true,
                fontSize: 16,
                alignment: "center",
                margin: [0, 0, 0, 50]
              },
              {
                text: `${res.data.nombre1} ${res.data.apellido1}`,
                fontSize: 16,
                alignment: "center",
                bold: true,
                border: [false, true, false, false],
                margin: [2, 0, 0, 10]
              },
              {
                text: `C.I N° ${res.data.cedula}`,
                fontSize: 16,
                alignment: "center",
                bold: true,
                border: [false, true, false, false],
                margin: [2, 0, 0, 10]
              }
            ],
            styles: {
              header: {
                fontSize: 18,
                bold: true,
                alignment: "center",
                margin: [0, 0, 0, 20]
              }
            }
          };

          pdfMake.createPdf(dd).open();
          historial(
            vm.$store.state.currentUser.token,
            "Imprimir Nomina Detalle"
          );
        })
        .catch(error => console.log(error));
    },

    nmbFormat(value) {
      value = new Intl.NumberFormat("es-VE", {
        style: "currency",
        currency: "VES"
      }).format(value);
      return value;
    }
  },
  filters: {
    capitalize(value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
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
