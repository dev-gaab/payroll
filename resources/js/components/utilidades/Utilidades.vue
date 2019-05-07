<template>
  <v-layout wrap>
    <v-flex xs12>
      <v-alert v-model="alert" dismissible type="success">{{alertMsg}}</v-alert>
      <v-card>
        <v-card-title>
          <h3>Utilidades</h3>
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
          <v-btn icon color="teal darken-4" dark @click="newUtilidades" title="Nuevas Utilidades">
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="trabajadores" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre1 }} {{ props.item.apellido1 }}</td>
            <td>{{ props.item.fecha_ingreso | dateFormat }}</td>
            <td>{{ props.item.fecha | dateFormat }}</td>
            <td>{{ props.item.dias }}</td>
            <td>{{ props.item.meses_calculados }}</td>
            <td>{{ props.item.tipo | capitalize }}</td>
            <td>{{ props.item.monto | numberFormat }}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="deleteU(props.item.id)" icon small color="error" title="Eliminar utilidades">
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

    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent>
        <v-flex xs12>
          <v-card>
            <v-card-title>
              <h3>Calcular Utilidades - Trabajadores disponibles</h3>
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
              <v-btn @click.native="dialog = false" icon flat>
                <v-icon medium>fa-times-circle</v-icon>
              </v-btn>
            </v-card-title>
            <v-data-table :headers="headersDis" :items="disponibles" :search="searchDis">
              <template slot="items" slot-scope="props">
                <td>{{ props.item.cedula }}</td>
                <td>{{ props.item.nombre1 }} {{ props.item.apellido1 }}</td>
                <td>{{ props.item.fecha_ingreso | dateFormat }}</td>
                <!-- Acciones -->
                <td class="justify-center layout px-0">
                  <v-btn @click="calcularModal(props.item.id)" icon small color="success">
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
      </v-dialog>
    </v-layout>

     <v-layout row justify-center>
      <v-dialog v-model="dialogCal" persistent max-width="300">
        <v-card class="elevation-12">
          <!-- formulario -->
          <form @submit.prevent="calcular">
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12>
                    <v-text-field
                      :color="errors.has('utilidades') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.dias_utilidades"
                      name="utilidades"
                      label="Días Utilidades"
                      id="utilidades"
                      v-validate="'required|numeric|min_value:30|max_value:120'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('utilidades')"
                      type="error"
                    >{{errors.first('utilidades')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs12>
                    <v-checkbox v-model="trabajador.fraccionada" label="Fraccionada?"></v-checkbox>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                flat
                @click.native="dialogCal = false; trabajador = {}"
              >Cerrar</v-btn>
              <v-btn type="submit" dark color="teal darken-1">Calcular</v-btn>
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
  name: "Utilidades",
  data() {
    return {
      headers: [
        { text: "Cedula", value: "cedula" },
        { text: "Nombre", value: "nombre" },
        { text: "Fecha Ingreso", value: "fecha_ingreso" },
        { text: "Fecha de cálculo ", value: "fecha" },
        { text: "Días", value: "dias" },
        { text: "Meses Calculados", value: "meses" },
        { text: "Tipo", value: "tipo" },
        { text: "Total a pagar", value: "total_pagar" },
        {
          text: "Acciones",
          value: "acciones",
          align: "center",
          sortable: false
        }
      ],
      headersDis: [
        { text: "Cedula", value: "cedula" },
        { text: "Nombre", value: "nombre" },
        { text: "Fecha Ingreso", value: "fecha_ingreso" },
        {
          text: "Acciones",
          value: "acciones",
          align: "center",
          sortable: false
        }
      ],
      searchDis: "",
      trabajadores: [],
      disponibles: [],
      search: "",
      trabajador: {},
      empresa_id: this.$store.state.empresa.id,
      alert: false,
      alertMsg: "",
      dialog: false,
      dialogCal: false
    };
  },
   created() {
    this.allUtilidades();

    const dict = {
      custom: {
        utilidades: {
          required: "No debe ser vacio",
          numeric: "Solo se permite el ingreso de números",
          min_value: "El valor mínimo es de 30 y máximo 120",
          max_value: "El valor mínimo es de 30 y máximo 120"
        }
      }
    };

    this.$validator.localize("es", dict);
  },
  methods: {
    allUtilidades() {
      const vm = this;

      axios
        .get(`http://payroll.com.local/api/utilidades/${vm.empresa_id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.trabajadores = res.data;
        })
        .catch(err => console.log(err));
    },
    allDisponibles() {
      const vm = this;

      axios
        .get(
          `http://payroll.com.local/api/utilidades/disponibles/${
            vm.empresa_id
          }`,
          {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          }
        )
        .then(res => {
          vm.disponibles = res.data;
        })
        .catch(err => console.log(err));
    },
    newUtilidades() {
      this.allDisponibles();

      this.dialog = true;
    },
    calcularModal(id) {
      this.dialogCal = true;
      this.trabajador = {
        id,
        dias_utilidades: 30,
        fraccionada: false
      };
    },
    calcular(id) {
      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }

        const vm = this;

        axios
          .post(
            `http://payroll.com.local/api/utilidades/calcular`,
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
              vm.alertMsg = "Utilidades calculadas.";
              vm.dialog = false;
              vm.dialogCal = false;
              vm.allUtilidades();
            }
          })
          .catch(err => console.log(err));
      });
    },
     deleteU(id) {

      let confirm = window.confirm(
        "¿Seguro que quiere eliminar?"
      );
       const vm = this;

      if (confirm) {
          axios.delete(`http://payroll.com.local/api/utilidades/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          this.alertMsg = "Utilidades Eliminada";
          this.alert = true;
          this.allUtilidades();
        })
        .catch(err => console.log(err));
      }
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
