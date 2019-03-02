<!-- Componente que permitira ver
    todas las empresas que tenga acceso el usuario -->
<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          <h3>Nominas</h3>
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
          <v-btn icon color="teal accent-4" dark @click="newNomina"><v-icon>add</v-icon></v-btn>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="nominas"
          :search="search"
        >
          <template slot="items" slot-scope="props">

            <td>{{ props.item.codigo }}</td>
            <td>{{ props.item.desde }} </td>
            <td>{{ props.item.hasta }} </td>
            <td>{{ props.item.estatus | capitalize}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="verNomina(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
            </td>
          </template>

          <v-alert slot="no-results" :value="true" color="" icon="fa-exclamation-triangle">
            Tu busqueda "{{ search }}" no encontro resultados.
          </v-alert>
        </v-data-table>
      </v-card>
    </v-flex>

  </v-layout>

</template>

<script>
  import axios from 'axios';


  export default {
    name: 'AllNominas',
    data () {
      return {
        search: '',
        headers: [
          { text: 'Código', value: 'codigo' },
          { text: 'Desde', value: 'desde' },
          { text: 'Hasta', value: 'hasta' },
          { text: 'Tipo', value: 'tipo' },
          { text: 'Estatus', value: 'estatus' },
          { text: 'Acciones', align: 'center', value: 'codigo', sortable: false}
        ],
        nominas: [],
        idE: 1
      }
    },
    components: {
    },
    created () {
      this.allTrabajadores();
    },
    methods: {
      newNomina () {
        this.$router.push({path: '/nominas/nueva'});
      },
      allTrabajadores () {
        const vm = this;

        axios.get(`http://payroll.com.local/api/nomina/${vm.idE}`)
          .then((res) => {
            vm.$data.nominas = res.data.nominas;

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
