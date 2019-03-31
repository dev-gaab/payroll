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
            color="teal accent-4"
            v-model="search"
            append-icon="fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-btn icon color="teal accent-4" dark @click="newEmpresa">
            <v-icon>add</v-icon>
          </v-btn>
          <v-btn icon color="teal accent-4" dark @click="printAll">
            <v-icon>print</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="empresas" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.rif }}</td>
            <td>{{ props.item.razon_social }}</td>
            <td>{{ props.item.direccion }}</td>
            <td>{{ props.item.estatus | capitalize}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn
                @click="activarEmpresa(props.item.id, props.item.razon_social)"
                v-if="props.item.id != empresaId"
                icon
                small
                color="success"
              >
                <v-icon small>fa-check</v-icon>
              </v-btn>
              <v-btn @click="verEmpresa(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn @click="editEmpresa(props.item.id)" icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn v-if="props.item.estatus == 'habilitada'" icon small color="error">
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
            <v-toolbar-title>{{empresa.razon_social}}</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>

          <v-list two-line>
            <v-list-tile>
              <v-list-tile-content>
                <v-list-tile-title>Rif</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class="text--primary">{{empresa.rif}}</span>
                </v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>
              <v-list-tile-content>
                <v-list-tile-title>Dirección</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class="text--primary">{{empresa.direccion}}</span>
                </v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>
              <v-list-tile-content>
                <v-list-tile-title>Riesgo IVSS</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class="text--primary">{{empresa.riesgo_ivss}}</span>
                </v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>
              <v-list-tile-content>
                <v-list-tile-title>Num. Afiliación IVSS</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class="text--primary">{{empresa.num_afiliacion_ivss}}</span>
                </v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>
              <v-list-tile-content>
                <v-list-tile-title>Fecha de Inscripción IVSS</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class="text--primary">{{empresa.fecha_inscripcion_ivss}}</span>
                </v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>
              <v-list-tile-content>
                <v-list-tile-title>Num. Afiliación FAOV</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class="text--primary">{{empresa.num_afiliacion_faov}}</span>
                </v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>
              <v-list-tile-content>
                <v-list-tile-title>Num. Afiliación INCES</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class="text--primary">{{empresa.num_afiliacion_inces}}</span>
                </v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>
              <v-list-tile-content>
                <v-list-tile-title>Estatus</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class="text--primary">{{empresa.estatus}}</span>
                </v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list>
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
            <v-toolbar-title>Empresa</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogUpd = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <!-- formulario -->
          <form @submit.prevent="save">
            <v-card-text>
              <v-text-field
                color="teal darken-1"
                v-model="empresa.rif"
                name="rif"
                label="Rif"
                id="rif"
                v-validate="{required: true, regex: '^([V|E|J]{1})([0-9]{9})$'}"
              ></v-text-field>

              <v-alert v-show="errors.has('rif')" type="error">{{errors.first('rif')}}</v-alert>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.razon_social"
                name="razon_social"
                label="Razon Social"
                id="razon_social"
                v-validate="{required: true, regex: /[a-zA-Z0-9\.\,\sáéíóú]+$/}"
              ></v-text-field>
              <v-alert
                v-show="errors.has('razon_social')"
                type="error"
              >{{errors.first('razon_social')}}</v-alert>

              <v-textarea
                color="teal darken-1"
                v-model="empresa.direccion"
                name="direccion"
                label="Direccion"
                id="direccion"
                rows="2"
                v-validate="{required: true, regex: /[a-zA-Z0-9\.\,\#\/\sáéíóú]+$/}"
              ></v-textarea>
              <v-alert v-show="errors.has('direccion')" type="error">{{errors.first('direccion')}}</v-alert>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.num_afiliacion_ivss"
                name="num_ivss"
                label="Numero de IVSS"
                id="num_ivss"
                v-validate="{regex: /[a-zA-z0-9]+$/}"
              ></v-text-field>
              <v-alert v-show="errors.has('num_ivss')" type="error">{{errors.first('num_ivss')}}</v-alert>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.fecha_inscripcion_ivss"
                name="fecha_ivss"
                label="Fecha de inscripcion IVSS"
                id="fecha_ivss"
                type="date"
                v-validate="'required'"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.num_afiliacion_faov"
                name="num_faov"
                label="Numero de FAOV"
                id="num_faov"
                v-validate="{regex: /[a-zA-z0-9]+$/}"
              ></v-text-field>
              <v-alert v-show="errors.has('num_faov')" type="error">{{errors.first('num_faov')}}</v-alert>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.num_afiliacion_inces"
                name="num_inces"
                label="Numero de INCES"
                id="num_inces"
                v-validate="{regex: /[a-zA-z0-9]+$/}"
              ></v-text-field>
              <v-alert v-show="errors.has('num_inces')" type="error">{{errors.first('num_inces')}}</v-alert>
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
        { text: "Rif", value: "rif" },
        { text: "Razon Social", value: "razon_social" },
        { text: "Direccion", value: "direccion" },
        { text: "Estatus", value: "estatus" },
        { text: "Acciones", align: "center", value: "rif", sortable: false }
      ],
      empresas: [],
      idE: this.$store.state.empresa ? this.$store.state.empresa.id : null,
      dialogVer: false,
      dialogUpd: false,
      empresa: {},
      alertSuc: false,
      messageSuc: null
    };
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
        fecha_ivss: {
          required: "No debe ser vacio"
        },
        num_ivss: {
          regex: "Solo se permite el uso de numeros y letras"
        },
        num_faov: {
          regex: "Solo se permite el uso de numeros y letras"
        },
        num_inces: {
          regex: "Solo se permite el uso de numeros y letras"
        }
      }
    };
    // or use the instance method
    this.$validator.localize("es", dict);
  },
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
              vm.dialogUpd = false;
              vm.allEmpresas();
            } else {
              console.log(res.data.error);
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

      this.toDataURL(
        logo,
        function(dataUrl) {
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
                  alignment: 'center',
                  margin: [10, 0, 0, 10],
                  text: 'Empresas',
                  fontSize: 18,
                  bold: true
                },
                {
                  alignment: 'right',
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
        }
      );
    },
    activarEmpresa(id, nombre) {
      this.$store.commit("activarEmpresa", { id: id, nombre: nombre });

      this.messageSuc = `¡La empresa se ha cambiado! ${nombre}`;
      this.alertSuc = true;
    }
  },
  filters: {
    capitalize(value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    }
  }
};
</script>
