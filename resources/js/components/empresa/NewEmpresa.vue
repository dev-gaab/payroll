<template>
  <v-layout row wrap>
    <!-- Formulario de datos -->
    <v-flex xs6>
      <v-card class="elevation-12">
        <!-- Header card -->
        <v-toolbar dark color="teal darken-1" dense>
          <v-toolbar-title>Empresa</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <!-- Alerta -->
        <v-alert v-model="alert" dismissible :type="alertType">{{alertMessage}}</v-alert>
        <!-- formulario -->
        <form @submit.prevent="addEmpresa">
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
              label="Numero de afiliación IVSS"
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
            ></v-text-field>

            <v-select
              :items="riesgoIvss"
              color="teal darken-1"
              v-model="empresa.riesgo_ivss"
              label="Riesgo IVSS"
              name="riesgo_ivss"
              id="riesgo_ivss"
            ></v-select>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_faov"
              name="num_faov"
              label="Numero de afiliación FAOV"
              id="num_faov"
            v-validate="{regex: /[a-zA-z0-9]+$/}"
              ></v-text-field>
              <v-alert v-show="errors.has('num_faov')" type="error">{{errors.first('num_faov')}}</v-alert>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_inces"
              name="num_inces"
              label="Numero de afiliación INCES"
              id="num_inces"
            v-validate="{regex: /[a-zA-z0-9]+$/}"
              ></v-text-field>
              <v-alert v-show="errors.has('num_inces')" type="error">{{errors.first('num_inces')}}</v-alert>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
          </v-card-actions>
        </form>
        <!-- Fin form-->
      </v-card>
    </v-flex>

    <v-flex xs5 offset-xs1>
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
        { text: "Rif", value: "rif" },
        { text: "Razon Social", value: "razon_social" }
      ],
      empresas: [],
      alert: false,
      alertType: 'error',
      alertMsg: ''
    };
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
              vm.alert= true;
              vm.alertType = 'success';
              vm.alertMsg = 'Empresa Registrada';

              vm.$data.empresas.push({
                rif: vm.$data.empresa.rif,
                razon_social: vm.$data.empresa.razon_social
              });

              vm.$data.empresa = new Empresa();
            } else {
              vm.alert= true;
              vm.alertType = 'error';
              vm.alertMsg = vm.data.error;
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
