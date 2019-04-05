<!-- Componente que permitira ver
    todas las empresas que tenga acceso el usuario -->
<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-alert v-model="alert" dismissible :type="alertType">{{alertMsg}}</v-alert>
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
          <v-btn v-if="!isVacioTrabajadores" color="teal accent-4" dark @click="generarNomina">
            Generar
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="nominas" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.codigo }}</td>
            <td>{{ props.item.desde }}</td>
            <td>{{ props.item.hasta }}</td>
            <td>{{ props.item.estatus | capitalize}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="verNomina(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
            </td>
          </template>

          <v-alert
            slot="no-results"
            :value="true"
            color
            icon="fa-exclamation-triangle"
          >Tu busqueda "{{ search }}" no encontro resultados.</v-alert>
        </v-data-table>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import axios from "axios";

export default {
  name: "AllNominas",
  data() {
    return {
      search: "",
      headers: [
        { text: "Código", value: "codigo" },
        { text: "Desde", value: "desde" },
        { text: "Hasta", value: "hasta" },
        { text: "Estatus", value: "estatus" },
        { text: "Acciones", align: "center", value: "codigo", sortable: false }
      ],
      nominas: [],
      isVacioTrabajadores: false,
      alert: false,
      alertType: 'success',
      alertMsg: '',
      idE: this.$store.state.empresa.id
    };
  },
  components: {},
  created() {
    this.listNominas();

    axios
      .get(
        `http://payroll.com.local/api/nominas/validar/trabajadores/${this.idE}`,
        {
          headers: {
            Authorization: `Bearer ${this.$store.state.currentUser.token}`
          }
        }
      )
      .then(res => {
        console.log(res.data);
        if (res.data.error) {
          this.isVacioTrabajadores = true;
          this.alert = true;
          this.alertType = "warning";
          this.alertMsg = `No se encontrarón registros de trabajadores activos.
            Para poder generar una nomina debe tener trabajadores activos.`;
        }
      });
  },
  methods: {
    listNominas() {
      const vm = this;
      axios
        .get(`http://payroll.com.local/api/nominas/${vm.idE}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.nominas = res.data;
        });
    },
    generarNomina() {
      const vm = this;
      // TODO: Realizar validacion de si la empresa posee trabajadores activos o no.
      axios
        .post(
          `http://payroll.com.local/api/nominas/generar/${vm.idE}`,
          {},
          {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          }
        )
        .then(res => {
          console.log(res.data);
          vm.listNominas();
        });
    },
    verNomina(id) {
      this.$router.push({ path: `nominas/detalle/${id}` });
    }
  },
  filters: {
    capitalize(value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    }
  }
};
</script>
