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
          @click="impActivas"
        >Imprimir Todas las Empresas activas</v-btn>
      </v-flex>
      <v-flex xs12 md6>
        <v-btn
          color="teal darken-4"
          large
          dark
          @click="impInactivas"
        >Imprimir Todas las Empresas inactivas</v-btn>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: "ReportesEmpresa",
  methods: {
    impActivas() {

    },
    impInactivas(){

    }
  }
};
</script>
