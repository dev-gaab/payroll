<template>
  <v-layout wrap>
    <v-flex xs12>
      <h2>Bases de Cálculo</h2>
      <br>
      <v-alert v-model="alert" dismissible type="success">{{alertMsg}}</v-alert>
      <v-layout wrap>
        <v-flex xs12 md5 mt-2>
          <v-card class="elevation-12">
            <v-card-title>
              <h4>Salario Mínimo</h4>
            </v-card-title>
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12>
                    <v-list-tile>
                      <v-list-tile-content>
                        <v-list-tile-title>
                          <strong>Monto:</strong>
                          {{salarioMinimo.monto | numberFormat}}
                        </v-list-tile-title>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs12>
                    <v-list-tile>
                      <v-list-tile-content>
                        <v-list-tile-title>
                          <strong>Desde:</strong>
                          {{salarioMinimo.desde | dateFormat}}
                        </v-list-tile-title>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" @click="historialSalario">Ver Historial</v-btn>
              <v-btn dark color="teal darken-1" @click="modalModificar('salario')">Modificar</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>

        <v-flex xs12 md5 offset-md1 mt-2>
          <v-card class="elevation-12">
            <v-card-title>
              <h4>Cesta Ticket</h4>
            </v-card-title>
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12>
                    <v-list-tile>
                      <v-list-tile-content>
                        <v-list-tile-title>
                          <strong>Monto:</strong>
                          {{cestaTicket.cantidad | numberFormat}}
                        </v-list-tile-title>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs12>
                    <v-list-tile>
                      <v-list-tile-content>
                        <v-list-tile-title>
                          <strong>Desde:</strong>
                          {{cestaTicket.desde | dateFormat}}
                        </v-list-tile-title>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" @click="historialCesta">Ver Historial</v-btn>
              <v-btn dark color="teal darken-1" @click="modalModificar('cesta')">Modificar</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
    <!-- historial -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogHisto" persistent max-width="700">
        <v-card>
          <v-card-title>
            <h3>{{ headerTable }}</h3>
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
            <v-btn @click.native="dialogHisto = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-card-title>
          <v-data-table :headers="headers" :items="items" :search="search">
            <template slot="items" slot-scope="props">
              <td>{{ props.item.monto ? props.item.monto : props.item.cantidad | numberFormat}}</td>
              <td>{{ props.item.desde | dateFormat }}</td>
              <td>{{ props.item.hasta | dateFormat }}</td>
              <td>{{ props.item.estatus | capitalize }}</td>
            </template>

            <v-alert
              slot="no-results"
              :value="true"
              color
              icon="fa-exclamation-triangle"
            >Tu busqueda "{{ search }}" no encontro resultados.</v-alert>
          </v-data-table>
        </v-card>
      </v-dialog>
    </v-layout>
    <!-- Modiciar -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogUpd" persistent max-width="300">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Modicar {{ opc == 'salario' ? 'Salario Mínimo' : 'Cesta Ticket'}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogUpd = false; monto = 0" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <v-alert v-model="alertUpd" dismissible type="warning">{{alertUpdMsg}}</v-alert>

            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field
                    v-model="monto"
                    name="monto"
                    label="Monto"
                    id="monto"
                    v-validate="'required|decimal:2'"
                  ></v-text-field>
                  <v-alert v-show="errors.has('monto')" type="error">{{errors.first('monto')}}</v-alert>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn dark color="teal darken-1" @click="save">Modificar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
  </v-layout>
</template>

<script>
import axios from "axios";
const moment = require("moment");

export default {
  name: "AllBases",
  data() {
    return {
      search: "",
      headers: [
        { text: "Monto", value: "monto" },
        { text: "Desde", value: "desde" },
        { text: "Vencimiento", value: "hasta" },
        { text: "Estatus", value: "estatus" }
      ],
      items: [],
      headerTable: "",
      salarioMinimo: {},
      cestaTicket: {},
      dialogHisto: false,
      dialogUpd: false,
      opc: "",
      monto: 0,
      alert: false,
      alertMsg: "",
      alertUpd: false,
      alertUpdMsg: ""
    };
  },
  created() {
    this.getBases();
    const dict = {
      custom: {
        monto: {
          required: "No debe ser vacio",
          decimal: "Debe ser un monto con solo dos decimales. Ej: xxxx.xx"
        }
      }
    };

    this.$validator.localize("es", dict);
  },
  methods: {
    getBases() {
      const vm = this;

      axios
        .get("http://payroll.com.local/api/bases", {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          if (!res.data.error) {
            vm.salarioMinimo = res.data.salario_minimo;
            vm.cestaTicket = res.data.cesta_ticket;
          }
        })
        .catch(error => console.log(error));
    },
    historialSalario() {
      const vm = this;

      axios
        .get("http://payroll.com.local/api/bases/salarios", {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          if (!res.data.error) {
            vm.items = res.data;
            vm.headerTable = "Historial Salarios Mínimos";
            vm.dialogHisto = true;
          }
        })
        .catch(error => console.log(error));
    },
    modalModificar(opc) {
      this.opc = opc;
      this.monto =
        opc == "salario" ? this.salarioMinimo.monto : this.cestaTicket.cantidad;
      this.dialogUpd = true;
    },
    save() {
      if (this.opc == "salario") {
        this.modificarSalario();
      } else {
        this.modificarCesta();
      }
    },
    modificarSalario() {
      const data = {
        monto: this.monto
      };
      const vm = this;

      axios
        .post("http://payroll.com.local/api/bases/salarios", data, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          if (!res.data.error) {
            vm.monto = 0;
            vm.dialogUpd = false;
            vm.alert = true;
            vm.alertMsg = "Salario Mínimo modificado";

            vm.getBases();
          } else {
            vm.alertUpd = true;
            vm.alertUpdMsg = res.data.error;
          }
        })
        .catch(err => console.log(err));
    },
    modificarCesta() {
      const data = {
        monto: this.monto
      };
      const vm = this;

      axios
        .post("http://payroll.com.local/api/bases/cesta", data, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          if (!res.data.error) {
            vm.monto = 0;
            vm.dialogUpd = false;
            vm.alert = true;
            vm.alertMsg = "Cesta Ticket modificada";

            vm.getBases();
          } else {
            vm.alertUpd = true;
            vm.alertUpdMsg = res.data.error;
          }
        })
        .catch(err => console.log(err));
    },
    historialCesta() {
      const vm = this;

      axios
        .get("http://payroll.com.local/api/bases/cesta", {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          if (!res.data.error) {
            vm.items = res.data;
            vm.headerTable = "Historial Cesta Tickets";
            vm.dialogHisto = true;
          }
        })
        .catch(error => console.log(error));
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

<style scoped>
.tabsBase a:link {
  text-decoration: none !important;
}
</style>
