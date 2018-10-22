<template>
    <v-layout row wrap>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          <h3>Bases Legales</h3>
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
          <v-btn v-if="bases == null" icon color="teal accent-4" dark @click="newBases">
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="bases"
          :search="search"
        >
          <template slot="items" slot-scope="props">

            <td>{{ props.item.estatus | capitalize}}</td>
            <td>{{ props.item.desde }}</td>
            <td>{{ props.item.hasta }}</td>
            
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="verBase(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn v-if="props.item.estatus == 'activa'" @click="editBase(props.item.id)" icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
            </td>
          </template>

          <v-alert slot="no-results" :value="true" color="" icon="fa-exclamation-triangle">
            Tu busqueda "{{ search }}" no encontro resultados.
          </v-alert>
        </v-data-table>
      </v-card>
    </v-flex>
    <!-- Componente para ver modal de ver empresa -->
    <!-- <VerEmpresa :id="idE" :dialog="dialogVer" :empresa="empresa"></VerEmpresa> -->
    <!-- componente modal para editar empresa -->
    <!-- <EditEmpresa :dialog="dialogUpd" :empresa="empresa" v-on:updSuccess='updSuc'></EditEmpresa> -->
  </v-layout>

</template>

<script>
import axios from 'axios';

export default {
    name: 'AllBases',
    data () {
      return { 
        search: '',
        headers: [
          { text: 'Estatus', value: 'estatus' },
          { text: 'Desde', value: 'desde' },
          { text: 'Hasta', value: 'hasta' },
          { text: 'Acciones', align: 'center', value: 'id', sortable: false}
        ],
        bases: [],
        idEmpresa: 1
      }
    },
    created () {
      this.allBases();
    },
    methods: {
      allBases () {
        const vm = this;

        axios.get('http://payroll.com.local/api/bases')
          .then( (res) => {
            vm.$data.bases = res.data.bases;
          })
          .catch( (err) => {
            console.log(err);
          });
      },
      newBases () {

      }
    },
    filters: {
      capitalize (value) {
        if (!value) return ''
        value = value.toString()
        return value.charAt(0).toUpperCase() + value.slice(1)
      }
    }
}
</script>
