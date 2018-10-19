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
              v-model="empresa.num_ivss"
              name="num_ivss"
              label="Numero de IVSS"
              id="num_ivss"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.fecha_ivss"
              name="fecha_ivss"
              label="Fecha de inscripcion IVSS"
              id="fecha_ivss"
              type="date"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_faov"
              name="num_faov"
              label="Numero de FAOV"
              id="num_faov"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_inces"
              name="num_inces"
              label="Numero de INCES"
              id="num_inces"
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
    constructor (rif, razon_social, direccion, riesgo_ivss, num_ivss, num_faov, num_inces, fecha_ivss) {
      this.rif = rif != null ? rif :'';
      this.razon_social = razon_social != null ? razon_social :'';
      this.direccion = direccion != null ? direccion :'';
      this.riesgo_ivss = riesgo_ivss != null ? riesgo_ivss :'';
      this.num_ivss = num_ivss != null ? num_ivss :'';
      this.num_faov = num_faov != null ? num_faov :'';
      this.num_inces = num_inces != null ? num_inces :'';
      this.fecha_ivss = fecha_ivss != null ? fecha_ivss :'';
    }
  }

  export default {
    name: 'NewEmpresa',
    data () {
      return {
        search: '',
        empresa: new Empresa(),
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
        axios.post('http://localhost:3000/api/empresas', {empresa: vm.$data.empresa})
          .then((res) => {
            console.log(res.data);
            if(!res.data.error){

              vm.$data.empresas.push({
                rif: vm.$data.empresa.rif,
                razon_social: vm.$data.empresa.razon_social
              });

              vm.$data.empresa = new Empresa();

              console.log(res.data.message);
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
