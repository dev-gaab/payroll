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
        <!-- formulario -->
        <form @submit.prevent="addEmpresa">
          <v-card-text>
            <v-text-field
              color="teal darken-1"
              v-model="empresa.rif"
              name="rif"
              label="Rif"
              id="rif"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.razon_social"
              name="razon_social"
              label="Razon Social"
              id="razon_social"
            ></v-text-field>

            <v-textarea
              color="teal darken-1"
              v-model="empresa.direccion"
              name="direccion"
              label="Direccion"
              id="direccion"
              rows="2"
            ></v-textarea>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_ivss"
              name="num_afiliacion_ivss"
              label="Numero de afiliación IVSS"
              id="num_afiliacion_ivss"
            ></v-text-field>

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
              name="num_afiliacion_faov"
              label="Numero de afiliación FAOV"
              id="num_afiliacion_faov"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_inces"
              name="num_afiliacion_inces"
              label="Numero de afiliación INCES"
              id="num_afiliacion_inces"
            ></v-text-field>

          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
          </v-card-actions>
        </form> <!-- Fin form-->
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
        <v-data-table
          :headers="headers"
          :items="empresas"
          :search="search"
        >
          <template slot="items" slot-scope="props">
            <td>{{ props.item.rif }}</td>
            <td>{{ props.item.razon_social }}</td>
          </template>
          <v-alert slot="no-results" :value="true" color="error" icon="fa-exclamation-triangle">
            Tu busqueda "{{ search }}" no encontro resultados.
          </v-alert>
        </v-data-table>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import axios from 'axios';

  class Empresa {
    constructor (rif, razon_social, direccion, riesgo_ivss, num_afiliacion_ivss, num_afiliacion_faov, num_afiliacion_inces, fecha_inscripcion_ivss) {
      this.rif = rif != null ? rif :'';
      this.razon_social = razon_social != null ? razon_social :'';
      this.direccion = direccion != null ? direccion :'';
      this.riesgo_ivss = riesgo_ivss != null ? riesgo_ivss :'';
      this.num_afiliacion_ivss = num_afiliacion_ivss != null ? num_afiliacion_ivss :'';
      this.num_afiliacion_faov = num_afiliacion_faov != null ? num_afiliacion_faov :'';
      this.num_afiliacion_inces = num_afiliacion_inces != null ? num_afiliacion_inces :'';
      this.fecha_inscripcion_ivss = fecha_inscripcion_ivss != null ? fecha_inscripcion_ivss :'';
    }
  }

  export default {
    name: 'NewEmpresa',
    data () {
      return {
        search: '',
        empresa: new Empresa(),
        riesgoIvss: ['Mínimo', 'Medio', 'Máximo'],
        headers: [
          { text: 'Rif', value: 'rif' },
          { text: 'Razon Social', value: 'razon_social' },
        ],
        empresas: []
      }
    },
    methods: {
      addEmpresa () {
        const vm = this;
        axios.post('http://payroll.com.local/api/empresas', vm.$data.empresa,
        {headers: {'Authorization' : `Bearer ${vm.$store.state.currentUser.token}`}})
          .then((res) => {
      
            if(!res.data.error){

              vm.$data.empresas.push({
                rif: vm.$data.empresa.rif,
                razon_social: vm.$data.empresa.razon_social
              });

              vm.$data.empresa = new Empresa();

            } else {
              console.log(res.data.error);
            }
          })
          .catch((err) => {
            console.log(err);
          });
      }
    }
  }
</script>
