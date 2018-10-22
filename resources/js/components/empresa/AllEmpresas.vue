<!-- Componente que permitira ver
    todas las empresas que tenga acceso el usuario -->
<template>
  <v-layout row wrap>
    <v-flex xs12>
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
          <v-btn icon color="teal accent-4" dark @click="newEmpresa"><v-icon>add</v-icon></v-btn>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="empresas"
          :search="search"
        >
          <template slot="items" slot-scope="props">

            <td>{{ props.item.rif }}</td>
            <td>{{ props.item.razon_social }}</td>
            <td>{{ props.item.direccion }}</td>
            <td>{{ props.item.estatus | capitalize}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn v-if="props.item.id != id" icon small color="success">
                <v-icon small>fa-check</v-icon>
              </v-btn>
              <v-btn @click="verEmpresa(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn v-if="props.item.estatus == 'habilitada'" icon small color="error">
                <v-icon small>fa-lock</v-icon>
              </v-btn>
              <v-btn v-if="props.item.estatus != 'habilitada'" icon small color="success">
                <v-icon small>fa-unlock</v-icon>
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
    <VerEmpresa :id="idE" :dialog="dialogVer" :empresa="empresa"></VerEmpresa>
  </v-layout>

</template>

<script>
  import axios from 'axios';
  import VerEmpresa from './VerEmpresa.vue';

  export default {
    name: 'AllEmpresas',
    data () {
      return {
        id: 1,
        search: '',
        headers: [
          { text: 'Rif', value: 'rif' },
          { text: 'Razon Social', value: 'razon_social' },
          { text: 'Direccion', value: 'direccion' },
          { text: 'Estatus', value: 'estatus' },
          { text: 'Acciones', align: 'center', value: 'rif', sortable: false}
        ],
        empresas: [],
        idE: 0,
        dialogVer: false,
        empresa: {}
      }
    },
    components: {
      VerEmpresa
    },
    created () {
      this.allEmpresas();
    },
    methods: {
      newEmpresa () {
        this.$router.push({path: '/empresas/nueva'});
      },
      allEmpresas () {
        const vm = this;

        axios.get('http://payroll.com.local/api/empresas')
          .then((res) => {
            vm.$data.empresas = res.data.empresas;
          })
          .catch((err) => {
            console.log(err);
          });
      },
      verEmpresa (id) {
        this.idE = id;
        this.dialogVer = true;

        const vm = this;

        axios.get(`http://payroll.com.local/api/empresas/${vm.idE}`)
          .then((res) => {
              vm.$data.empresa = res.data.empresa;
          })
          .catch((err) => {
              console.log(err);
          });
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
