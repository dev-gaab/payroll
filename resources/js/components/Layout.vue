<template>
  <v-app id="inspire">
    <!-- Navbar -->
    <v-navigation-drawer
      dark
      v-model="drawer"
      fixed
      app
      clipped
      v-if="$store.state.empresa != null"
    >
      <v-list dense>
        <v-list-tile @click="routHome">
          <v-list-tile-action>
            <v-icon>fa-home</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Inicio</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile @click="routAllNominas">
          <v-list-tile-action>
            <v-icon>work</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Nómina</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile @click="routAllEmpresas">
          <v-list-tile-action>
            <v-icon>fa-building</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Empresas</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-group no-action>
          <v-list-tile slot="activator">
            <v-list-tile-action>
              <v-icon>flight_takeoff</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>Vacaciones</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile @click="routAllVacaciones">
            <v-list-tile-content>
              <v-list-tile-title>Ver Vacaciones</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile @click="routCalcVacaciones">
            <v-list-tile-content>
              <v-list-tile-title>Vacaciones Anuales</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile @click="routFracVacaciones">
            <v-list-tile-content>
              <v-list-tile-title>Vacaciones Fraccionadas</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list-group>

        <v-list-tile @click="routTrabajador">
          <v-list-tile-action>
            <v-icon>people</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Trabajador</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile @click="routUtilidades">
          <v-list-tile-action>
            <v-icon>redeem</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Utilidades</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-group no-action>
          <v-list-tile slot="activator">
            <v-list-tile-action>
              <v-icon>print</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>Reportes</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile @click="routReportesEmpresa">
            <v-list-tile-content>
              <v-list-tile-title>Empresas</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list-group>

        <v-list-tile @click="routUsers" v-if="$store.state.currentUser.isAdmin">
          <v-list-tile-action>
            <v-icon>account_box</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Usuarios</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <!-- Toolbar -->
    <v-toolbar color="teal darken-4" dark fixed app clipped-left v-if="$store.state.isLoggedIn">
      <v-toolbar-side-icon v-if="$store.state.empresa != null" @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>
        <img src="../assets/Logo.png" height="40">
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <h3>{{nombreEmpresa}}</h3>
      <v-spacer></v-spacer>

      <v-toolbar-items>
        <v-menu offset-y>
          <v-btn slot="activator" flat>
            <v-icon>fa-user</v-icon>
            &nbsp
            {{$store.state.currentUser.user.name}}
          </v-btn>

          <v-list>
            <v-list-tile @click="logout">
              <v-list-tile-title>Cerrar Sesion</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </v-toolbar-items>
    </v-toolbar>

    <!-- Contenido -->
    <v-content>
      <v-container fluid fill-height id="container">
        <router-view/>
      </v-container>
    </v-content>
    <!-- Fin contenido -->
  </v-app>
</template>

<script>
import axios from "axios";

export default {
  name: "Layout",
  data: () => ({
    drawer: null,
    loggedIn: false
  }),
  props: {
    source: String
  },
  created() {
    this.loggedIn = this.$store.state.isLoggedIn;
  },
  computed: {
    nombreEmpresa() {
      let empresa = this.$store.state.empresa;
      if (empresa == null) {
        return "";
      } else {
        return empresa.nombre;
      }
    }
  },
  methods: {
    routHome() {
      this.$router.push({ path: "/" });
    },
    routAllEmpresas() {
      this.$router.push({ path: "/empresas" });
    },
    routAllBases() {
      this.$router.push({ path: "/bases" });
    },
    routTrabajador() {
      this.$router.push({ path: "/trabajadores" });
    },
    routAllNominas() {
      this.$router.push({ path: "/nominas" });
    },
    routActualNomina() {
      this.$router.push({ path: "/nominas/actual" });
    },
    routUsers() {
      this.$router.push({ path: "/users" });
    },
    routAllVacaciones() {
      this.$router.push({ path: "/vacaciones" });
    },
    routCalcVacaciones() {
      this.$router.push({ path: "/vacaciones/nueva" });
    },
    routFracVacaciones() {
      this.$router.push({ path: "/vacaciones/fraccionadas" });
    },
    routPrestaciones() {
      this.$router.push({ path: "/prestaciones" });
    },
    routUtilidades() {
      this.$router.push({ path: "/utilidades" });
    },
    // Reportes
    routReportesEmpresa() {
      this.$router.push({ path: "/reportes/empresa" });
    },
    logout() {
      const vm = this;

      axios
        .get("/api/auth/logout", {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          if (!res.data.error) {
            this.$store.commit("logout");
            this.$router.push({ path: "/login" });
          }
        })
        .catch(err => console.log(err));
    }
  }
};
</script>

<style>
#container {
  /* background-image: url("../assets/fondo.png"); */
  /* background-size: cover; */
  background: #e8f5e9; /* fallback for old browsers */
}
</style>
