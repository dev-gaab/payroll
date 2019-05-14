<!-- Componente que permitira ver
    todas las empresas que tenga acceso el usuario -->
<template>
  <v-layout row wrap>
    <!-- Tabla de datos -->
    <v-flex xs12>
      <v-alert v-model="alertSuc" dismissible type="success">{{messageSuc}}</v-alert>
      <v-card>
        <v-card-title>
          <h3>Empresas</h3>
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
          <v-btn
            v-if="$store.state.currentUser.isAdmin"
            icon
            color="teal darken-4"
            dark
            @click="newEmpresa"
            title="Nueva Empresa"
          >
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="empresas" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.rif }}</td>
            <td>{{ props.item.razon_social }}</td>
            <td>{{ props.item.direccion }}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn
                @click="activarEmpresa(props.item.id, props.item.razon_social, props.item.direccion, props.item.rif)"
                v-if="props.item.id != empresaId"
                icon
                small
                color="success"
              >
                <v-icon small>fa-check</v-icon>
              </v-btn>
              <v-btn
                @click="verEmpresa(props.item.id)"
                icon
                small
                color="primary"
                title="Utilizar empresa"
              >
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn
                @click="editEmpresa(props.item.id)"
                icon
                small
                color="warning"
                v-if="$store.state.currentUser.isAdmin"
                title="Editar empresa"
              >
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn
                @click="disableEmpresa(props.item.id)"
                v-if="props.item.estatus == 'activa' && props.item.id != empresaId && $store.state.currentUser.isAdmin"
                icon
                small
                color="error"
                title="Inhabilitar Empresa"
              >
                <v-icon small>fa-lock</v-icon>
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
    <!-- Componente para ver modal de ver empresa -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogVer" persistent max-width="500">
        <v-card>
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>{{empresa.rif}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogVer = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Razon Social</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{empresa.razon_social}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>

              <v-layout wrap>
                <v-flex xs12>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Días a pagar por utilidades</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{empresa.dias_utilidades}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>

              <v-layout wrap>
                <v-flex xs12>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Dirección</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{empresa.direccion}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click.native="dialogVer = false">Cerrar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <!-- componente modal para editar empresa -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogUpd" persistent max-width="500">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Modificar Empresa</v-toolbar-title>
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
                      :color="errors.has('rif') ? 'error' : 'teal darken-1'"
                      v-model="empresa.rif"
                      name="rif"
                      label="RIF"
                      id="rif"
                      v-validate="{required: true, regex: '^([V|E|J]{1})([0-9]{9})$'}"
                    ></v-text-field>

                    <v-alert v-show="errors.has('rif')" type="error">{{errors.first('rif')}}</v-alert>
                  </v-flex>

                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('dias_utilidades') ? 'error' : 'teal darken-1'"
                      v-model="empresa.dias_utilidades"
                      name="dias_utilidades"
                      label="Dias de Utilidades"
                      id="dias_utilidades"
                      v-validate="'required|numeric|min_value:30|max_value:120'"
                    ></v-text-field>

                    <v-alert
                      v-show="errors.has('dias_utilidades')"
                      type="error"
                    >{{errors.first('dias_utilidades')}}</v-alert>
                  </v-flex>
                </v-layout>
                <v-layout wrap>
                  <v-flex xs12>
                    <v-text-field
                      :color="errors.has('razon_social') ? 'error' : 'teal darken-1'"
                      v-model="empresa.razon_social"
                      name="razon_social"
                      label="Razón Social"
                      id="razon_social"
                      v-validate="{required: true, regex: /[a-zA-Z0-9\.\-\,\sáéíóú]+$/}"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('razon_social')"
                      type="error"
                    >{{errors.first('razon_social')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs12>
                    <v-textarea
                      :color="errors.has('direccion') ? 'error' : 'teal darken-1'"
                      v-model="empresa.direccion"
                      name="direccion"
                      label="Dirección"
                      id="direccion"
                      rows="2"
                      v-validate="{required: true, regex: /[a-zA-Z0-9\.\,\#\/\sáéíóú]+$/}"
                    ></v-textarea>
                    <v-alert
                      v-show="errors.has('direccion')"
                      type="error"
                    >{{errors.first('direccion')}}</v-alert>
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
import {historial} from "../users/historial";

import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;

import logo from "../../assets/Logo-shadow.png";
import VerEmpresa from "./VerEmpresa.vue";
import EditEmpresa from "./EditEmpresa.vue";
const moment = require("moment");

export default {
  name: "AllEmpresas",
  data() {
    return {
      search: "",
      headers: [
        { text: "RIF", value: "rif" },
        { text: "Razón Social", value: "razon_social" },
        { text: "Direccion", value: "direccion" },
        { text: "Acciones", align: "center", value: "rif", sortable: false }
      ],
      empresas: [],
      idE: this.$store.state.empresa ? this.$store.state.empresa.id : null,
      dialogVer: false,
      dialogUpd: false,
      empresa: {},
      alertSuc: false,
      messageSuc: null,
      alertUpd: false,
      alertUpdMsg: ""
    };
  },
  watch: {
    empresa: {
      deep: true,
      handler(val) {
        if (!val) return "";
        val.rif = val.rif.toString();
        this.empresa.rif = val.rif.charAt(0).toUpperCase() + val.rif.slice(1);
      }
    }
  },
  components: {
    VerEmpresa,
    EditEmpresa
  },
  created() {
    this.allEmpresas();

    const dict = {
      custom: {
        rif: {
          required: "No debe ser vacio",
          regex: "Debe cumplir con el formato especificado. Ej: V123456789"
        },
        razon_social: {
          required: "No debe ser vacio",
          regex:
            "Solo se permite el uso de letras, numeros y los caracteres ( , . )"
        },
        direccion: {
          required: "No debe ser vacio",
          regex:
            "Solo se permite el uso de letras, numeros y los caracteres (, . / #) "
        },
        dias_utilidades: {
          required: "No debe ser vacio",
          numeric: "Solo se permite el ingreso de números",
          min_value: "El valor mínimo es de 30 y máximo 120",
          max_value: "El valor mínimo es de 30 y máximo 120"
        }
      }
    };
    // or use the instance method
    this.$validator.localize("es", dict);
  },
  mounted() {},
  computed: {
    empresaId() {
      let empresa = this.$store.state.empresa;
      if (empresa == null) {
        return empresa;
      } else {
        return empresa.id;
      }
    },
    empresaName() {
      let empresa = this.$store.state.empresa;
      if (empresa == null) {
        return empresa;
      } else {
        return empresa.nombre;
      }
    }
  },
  methods: {
    newEmpresa() {
      this.$router.push({ path: "/empresas/nueva" });
    },
    allEmpresas() {
      const vm = this;

      axios
        .get("http://payroll.com.local/api/empresas", {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.$data.empresas = res.data.empresas;
        })
        .catch(err => {
          console.log(err);
        });
    },
    verEmpresa(id) {
      this.dialogVer = true;

      const vm = this;

      axios
        .get(`http://payroll.com.local/api/empresas/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.$data.empresa = res.data.empresa;
        })
        .catch(err => {
          console.log(err);
        });
    },
    editEmpresa(id) {
      this.dialogUpd = true;

      const vm = this;

      axios
        .get(`http://payroll.com.local/api/empresas/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.$data.empresa = res.data.empresa;
        })
        .catch(err => {
          console.log(err);
        });
    },
    disableEmpresa(id) {
      let confirm = window.confirm(
        "¿Seguro que quiere inhabilitar la empresa?\n Al inhabilitarla no podra realizar mas funciones y no podra volverla habilitar."
      );

      if (confirm) {
        const vm = this;

        axios
          .put(
            `http://payroll.com.local/api/empresas/disable/${id}`,
            {},
            {
              headers: {
                Authorization: `Bearer ${vm.$store.state.currentUser.token}`
              }
            }
          )
          .then(res => {
            vm.alertSuc = true;
            vm.messageSuc = "Empresa Inhabilitada";

            vm.allEmpresas();
            historial(vm.$store.state.currentUser.token, "Inhabilitar empresa");

          })
          .catch(err => console.log(err));
      }
    },
    save() {
      const vm = this;
      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }

        axios
          .put(
            `http://payroll.com.local/api/empresas/${vm.empresa.id}`,
            vm.empresa,
            {
              headers: {
                Authorization: `Bearer ${vm.$store.state.currentUser.token}`
              }
            }
          )
          .then(res => {
            if (!res.data.error) {
              vm.alertSuc = true;
              vm.messageSuc = "Empresa Modificada";
              vm.dialogUpd = false;
              vm.allEmpresas();
              historial(vm.$store.state.currentUser.token, "Modificar Empresa");

            } else {
              vm.alertUpd = true;
              vm.alertUpdMsg = res.data.error;
              document.getElementById("rif").focus();
            }
          })
          .catch(err => {
            console.log(err);
          });
      });
    },
    toDataURL(url, callback) {
      var httpRequest = new XMLHttpRequest();
      httpRequest.onload = function() {
        var fileReader = new FileReader();
        fileReader.onloadend = function() {
          callback(fileReader.result);
        };
        fileReader.readAsDataURL(httpRequest.response);
      };
      httpRequest.open("GET", url);
      httpRequest.responseType = "blob";
      httpRequest.send();
    },
    async printAll() {
      const body = [
        [
          { text: "Rif", style: "tableHeader" },
          { text: "Razon Social", style: "tableHeader" },
          { text: "Direccion", style: "tableHeader" }
        ]
      ];

      this.empresas.forEach(item => {
        body.push([item.rif, item.razon_social, item.direccion]);
      });
      const fecha = moment().format("DD-MM-YYYY");

      this.toDataURL(logo, function(dataUrl) {
        let dd = {
          header: {
            margin: 10,
            columns: [
              {
                // usually you would use a dataUri instead of the name for client-side printing
                // sampleImage.jpg however works inside playground so you can play with it
                image: dataUrl,
                width: 200,
                height: 30
              },
              {
                alignment: "center",
                margin: [10, 0, 0, 10],
                text: "Empresas",
                fontSize: 18,
                bold: true
              },
              {
                alignment: "right",
                margin: [10, 0, 0, 10],
                text: fecha
              }
            ]
          },
          content: [
            {
              style: "table",
              table: {
                headerRows: 1,
                body: body
              },
              layout: "lightHorizontalLines"
            }
          ],
          styles: {
            table: {
              margin: [0, 40, 0, 15],
              borderTop: "none"
            },
            tableHeader: {
              bold: true,
              fontSize: 13,
              color: "black"
            }
          }
        };
        pdfMake.createPdf(dd).open();
      });
    },
    activarEmpresa(id, nombre, direccion, rif) {
      this.$store.commit("activarEmpresa", {
        id: id,
        nombre: nombre,
        direccion: direccion,
        rif: rif
      });

      this.messageSuc = `¡La empresa se ha cambiado! ${nombre}`;
      this.alertSuc = true;
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
    }
  }
};
</script>
