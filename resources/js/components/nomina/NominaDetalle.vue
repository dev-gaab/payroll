<!-- Componente que permitira ver
    todas las empresas que tenga acceso el usuario -->
<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          <h3>Nomina Detalle</h3>
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
          <!-- <v-btn icon color="teal accent-4" dark @click="newNomina"><v-icon>add</v-icon></v-btn> -->
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="nominas"
          :search="search"
        >
          <template slot="items" slot-scope="props">

            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre }} </td>
            <td>{{ props.item.dias_trabajados }} </td>
            <td>{{props.item.total}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              
            </td>
          </template>

          <v-alert slot="no-results" :value="true" color="" icon="fa-exclamation-triangle">
            Tu busqueda "{{ search }}" no encontro resultados.
          </v-alert>
        </v-data-table>
      </v-card>
    </v-flex>
    <!-- Componente para ver modal de ver empresa -->
    <!-- <VerTrabajador :dialog="dialogVer" :trabajador="trabajador"></VerTrabajador> -->
    <!-- componente modal para editar empresa -->
    <!-- <EditTrabajador :dialog="dialogUpd" :trabajador="trabajador" v-on:updSuccess='updSuc'></EditTrabajador> -->
  </v-layout>

</template>

<script>
  import axios from 'axios';


  export default {
    name: 'NominaDetalle',
    data () {
      return {
        search: '',
        headers: [
           { text: 'Cédula', value: 'cedula' },
          { text: 'Nombre', value: 'nombre' },
          { text: 'Dias Trab.', value: 'dias' },
          { text: 'Total', value: 'total' },
          { text: 'Acciones', align: 'center', value: 'codigo', sortable: false}
        ],
        nominas: [],
        idE: this.$store.state.empresa.id,
      }
    },
    components: {
    },
    created () {
      this.allNominasDetalle();
    },
    methods: {
      allNominasDetalle () {
        const vm = this;

        axios.get(`http://payroll.com.local/api/nominas/detalle/all/${vm.idE}`)
          .then((res) => {
            vm.nominas = res.data;
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
