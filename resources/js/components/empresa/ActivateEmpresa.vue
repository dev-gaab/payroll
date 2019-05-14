<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          <h3>Empresas</h3>
          <v-spacer></v-spacer>
          <v-text-field
            color="teal darken-4"
            v-model="search"
            append-icon="fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="empresas" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.rif }}</td>
            <td>{{ props.item.razon_social }}</td>
            <td>{{ props.item.estatus | capitalize}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn
                @click="activarEmpresa(props.item.id, props.item.razon_social, props.item.direccion, props.item.rif)"
                icon
                small
                color="success"
              >
                <v-icon small>fa-check</v-icon>
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
  name: "ActivateEmpresa",
  data() {
    return {
      search: "",
      headers: [
        { text: "Rif", value: "rif" },
        { text: "Razon Social", value: "razon_social" },
        { text: "Estatus", value: "estatus" },
        { text: "Acciones", align: "center", value: "rif", sortable: false }
      ],
      empresas: []
    };
  },
  created() {
    this.allEmpresas();
  },
  methods: {
    allEmpresas() {
      const vm = this;

      axios
        .get("http://payroll.com.local/api/empresas", {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.$data.empresas = res.data.empresas;
        })
        .catch(err => {
          console.log(err);
        });
    },
    activarEmpresa(id, nombre, direccion, rif) {
      this.$store.commit("activarEmpresa", {
        id: id,
        nombre: nombre,
        direccion: direccion,
        rif
      });
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
