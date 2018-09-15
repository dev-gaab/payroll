<template>
  <v-layout row wrap>
    <!-- Formulario de datos -->
    <v-flex xs12 md7 pr-4>
      <v-card class="elevation-12">
        <!-- Header card -->
        <v-toolbar dark color="teal darken-1" dense>
          <v-btn
            icon
            color="teal darken-4"
            dark
            @click.native="$router.push({ path: '/empresas' })"
          >
            <v-icon>reply</v-icon>
          </v-btn>
          <v-toolbar-title>Empresa</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <!-- Alerta -->
        <v-alert v-model="alert" dismissible :type="alertType">{{alertMsg}}</v-alert>
        <!-- formulario -->
        <form @submit.prevent="addEmpresa">
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
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

              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field
                    :color="errors.has('dias_utilidades') ? 'error' : 'teal darken-1'"
                    v-model="empresa.dias_utilidades"
                    name="dias_utilidades"
                    label="Días a pagar por utilidades"
                    id="dias_utilidades"
                    v-validate="'required|numeric|min_value:30|max_value:120'"
                  ></v-text-field>
                  <v-alert
                    v-show="errors.has('dias_utilidades')"
                    type="error"
                  >{{errors.first('dias_utilidades')}}</v-alert>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
          </v-card-actions>
        </form>
        <!-- Fin form-->
      </v-card>
    </v-flex>

    <v-flex xs12 md5 pt-2>
      <v-card>
        <v-card-title>
          <h3>Agregadas</h3>
          <v-spacer></v-spacer>
          <v-text-field
            color="teal darken-1"
            v-model="search"
            append-icon="fa-search"
            label="Buscar.."
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="empresas" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.rif }}</td>
            <td>{{ props.item.razon_social }}</td>
          </template>
          <v-alert
            slot="no-results"
            :value="true"
            color="error"
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

class Empresa {
  constructor(
    rif,
    razon_social,
    direccion,
    riesgo_ivss,
    num_afiliacion_ivss,
    num_afiliacion_faov,
    num_afiliacion_inces,
    fecha_inscripcion_ivss
  ) {
    this.rif = rif != null ? rif : "";
    this.razon_social = razon_social != null ? razon_social : "";
    this.direccion = direccion != null ? direccion : "";
    this.riesgo_ivss = riesgo_ivss != null ? riesgo_ivss : "";
    this.num_afiliacion_ivss =
      num_afiliacion_ivss != null ? num_afiliacion_ivss : "";
    this.num_afiliacion_faov =
      num_afiliacion_faov != null ? num_afiliacion_faov : "";
    this.num_afiliacion_inces =
      num_afiliacion_inces != null ? num_afiliacion_inces : "";
    this.fecha_inscripcion_ivss =
      fecha_inscripcion_ivss != null ? fecha_inscripcion_ivss : "";
  }
}

export default {
  name: "NewEmpresa",
  data() {
    return {
      search: "",
      empresa: new Empresa(),
      riesgoIvss: ["Mínimo", "Medio", "Máximo"],
      headers: [
        { text: "RIF", value: "rif" },
        { text: "Razon Social", value: "razon_social" }
      ],
      empresas: [],
      alert: false,
      alertType: "error",
      alertMsg: ""
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
  created() {
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
  methods: {
    addEmpresa() {
      const vm = this;

      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }
        axios
          .post("http://payroll.com.local/api/empresas", vm.$data.empresa, {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          })
          .then(res => {
            if (!res.data.error) {
              vm.alert = true;
              vm.alertType = "success";
              vm.alertMsg = "Empresa Registrada";

              vm.$data.empresas.push({
                rif: vm.$data.empresa.rif,
                razon_social: vm.$data.empresa.razon_social
              });

              vm.$data.empresa = new Empresa();
              vm.$validator.reset();
            } else {
              vm.alert = true;
              vm.alertType = "error";
              vm.alertMsg = res.data.error;
            }
          })
          .catch(err => {
            console.log(err);
          });
      });
    }
  }
};
</script>
