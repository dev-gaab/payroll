<template>
  <v-container grid-list-md mt-0>
    <v-layout wrap mb-2>
      <h1>Reportes Empresa</h1>
    </v-layout>
    <v-layout wrap>
      <v-flex xs12 md6 justify-right>
        <v-btn
          color="teal darken-4"
          large
          dark
          @click="activas"
        >Imprimir Todas las Empresas activas</v-btn>
      </v-flex>
      <v-flex xs12 md6>
        <v-btn
          color="teal darken-4"
          large
          dark
          @click="inactivas"
        >Imprimir Todas las Empresas inactivas</v-btn>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import axios from "axios";
  const moment = require("moment");

export default {
  name: "ReportesEmpresa",
  data() {
    return {
      empresas_activas: [],
      empresas_inactivas: []
    }
  },
  methods: {
    activas() {
      axios
        .get(`http://payroll.com.local/api/reportes/empresas/activas`, {
          headers: {
            Authorization: `Bearer ${this.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          this.empresas_activas = res.data;
          this.impActivas();

          console.log(this.empresas_activas);
        })
        .catch(err => {
          console.log(err);
        });
    },
    inactivas(){
      axios
        .get(`http://payroll.com.local/api/reportes/empresas/inactivas`, {
          headers: {
            Authorization: `Bearer ${this.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          this.empresas_inactivas = res.data;
          this.impInactivas
        })
        .catch(err => {
          console.log(err);
        });
    },
    impActivas() {
      let dd = {
        content: {}
      }
    },
    impInactivas() {

    }
  }
};
</script>
