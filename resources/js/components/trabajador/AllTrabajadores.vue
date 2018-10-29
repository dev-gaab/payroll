<!-- Componente que permitira ver
    todas las empresas que tenga acceso el usuario -->
<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          <h3>Trabajadores</h3>
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
          <v-btn icon color="teal accent-4" dark @click="newTrabajador"><v-icon>add</v-icon></v-btn>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="trabajadores"
          :search="search"
        >
          <template slot="items" slot-scope="props">

            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre1 }} {{props.item.nombre2}}</td>
            <td>{{ props.item.apellido1 }} {{props.item.apellido2}}</td>
            <td>{{props.item.cargo}}</td>
            <td>{{props.item.fecha_ingreso}}</td>
            <td>{{props.item.fecha_egreso}}</td>
            <td>{{ props.item.estatus | capitalize}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="verTrabajador(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn @click="editTrabajador(props.item.id)" icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn @click="inaTrabajador(props.item.id)" v-if="props.item.estatus == 'activo'" icon small color="error">
                <v-icon small>fa-lock</v-icon>
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
    <!-- <VerTrabajador :dialog="dialogVer" :trabajador="trabajador"></VerTrabajador> -->
    <!-- componente modal para editar empresa -->
    <!-- <EditTrabajador :dialog="dialogUpd" :trabajador="trabajador" v-on:updSuccess='updSuc'></EditTrabajador> -->
  </v-layout>

</template>

<script>
  import axios from 'axios';


  export default {
    name: 'AllTrabajadores',
    data () {
      return {
        search: '',
        headers: [
          { text: 'Cédula', value: 'cedula' },
          { text: 'Nombres', value: 'nombres' },
          { text: 'Apellidos', value: 'apellidos' },
          { text: 'Cargo', value: 'cargo' },
          { text: 'Fecha Ingreso', value: 'fecha_ingreso' },
          { text: 'Fecha Egreso', value: 'fecha_greso' },
          { text: 'Estatus', value: 'estatus' },
          { text: 'Acciones', align: 'center', value: 'cedula', sortable: false}
        ],
        trabajadores: [],
        idE: 1,
        dialogVer: false,
        dialogUpd: false,
        trabajador: {}
      }
    },
    components: {
    },
    created () {
      this.allTrabajadores();
    },
    computed: {
      empresaId () {
        let empresa = this.$store.state.empresa;
        if (empresa == null) {
          return empresa;
        } else {
          return empresa.id;
        }
      },
      empresaName () {
        let empresa = this.$store.state.empresa;
        if (empresa == null) {
          return empresa;
        } else {
          return empresa.nombre;
        }
      }
    },
    methods: {
      newTrabajador () {
        this.$router.push({path: '/trabajadores/nuevo'});
      },
      allTrabajadores () {
        const vm = this;

        axios.get(`http://payroll.com.local/api/trabajadores/all/${vm.empresaId}`)
          .then((res) => {
            vm.$data.trabajadores = res.data.trabajadores;
            
          })
          .catch((err) => {
            console.log(err);
          });
      },
      verTrabajador (id) {
        this.dialogVer = true;

        const vm = this;

        axios.get(`http://payroll.com.local/api/trabajadores/${id}`)
          .then((res) => {
              vm.$data.trabajador = res.data.trabajador;
          })
          .catch((err) => {
              console.log(err);
          });
      },
      editEmpresa (id) {
        this.dialogUpd = true;

        const vm = this;

        axios.get(`http://payroll.com.local/api/trabajadores/ver/${id}`)
          .then((res) => {
              vm.$data.trabajador = res.data.trabajador;
          })
          .catch((err) => {
              console.log(err);
          });
      },
      updSuc () {
        console.log('se modifico');
        this.dialogUpd = false;
        this.allTrabajadores();
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
