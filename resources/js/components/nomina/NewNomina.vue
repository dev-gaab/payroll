<template>
  <v-layout row wrap>
    <!-- Formulario de datos -->
    <v-flex xs6>
      <v-card class="elevation-12">
        <!-- Header card -->
        <v-toolbar dark color="teal darken-1" dense>
        <v-toolbar-title>Nomina</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <!-- formulario -->
        <form @submit.prevent="generar">
          <v-card-text>
           <v-text-field
              color="teal darken-1"
              v-model="nomina.desde"
              label="Desde"
              name="desde"
              id="desde"
              type="month"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="nomina.hasta"
              label="Hasta"
              name="hasta"
              id="hasta"
              type="week"
            ></v-text-field>

           <v-select
              :items="tipo"
              color="teal darken-1"
              v-model="nomina.tipo"
              label="Tipo de Pago"
              name="tipo"
              id="tipo"
            ></v-select>

          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" dark color="teal darken-1">Generar</v-btn>
          </v-card-actions>
        </form> <!-- Fin form-->
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import axios from 'axios';

  class Nomina {
    constructor () {
      this.desde = '';
      this.hasta = '';
      this.tipo = '';
    }
  }

  export default {
    name: 'NewNomina',
    data () {
      return {
        idE: 1,
        nomina: new Nomina(),
        tipo: ['Semanal', 'Quincenal', 'Mensual']
      }
    },
    methods: {
      generar () {
        const vm = this;
        axios.post(`http://payroll.com.local/api/nominas/generar/${vm.idE}`, vm.$data.nomina)
          .then((res) => {
            
            if(!res.data.error){

              vm.$data.nomina = new Trabajador();
              id = res.data.id;
              vm.$router.push({path: `/nominas/detalle/${id}`});
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
