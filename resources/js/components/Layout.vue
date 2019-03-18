<template>
  <v-app id="inspire">
    <!-- Navbar -->
    <v-navigation-drawer dark v-model="drawer" fixed app clipped v-if="$store.state.empresa != null">
      <v-list dense>

        <v-list-tile @click="routHome">
          <v-list-tile-action>
            <v-icon>fa-home</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Inicio</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-group no-action>

          <v-list-tile slot="activator">
            <v-list-tile-action>
              <v-icon>fa-building</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title> Empresa </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile @click="routAllEmpresas">

            <v-list-tile-content>
              <v-list-tile-title>Todas</v-list-tile-title>
            </v-list-tile-content>

          </v-list-tile>

          <v-list-tile @click="">

            <v-list-tile-content>
              <v-list-tile-title>Actual</v-list-tile-title>
            </v-list-tile-content>

          </v-list-tile>

        </v-list-group>

        <!-- <v-list-group no-action>

          <v-list-tile slot="activator">
            <v-list-tile-action>
              <v-icon>fa-gavel</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title> Bases Legales </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile @click="routAllBases">

            <v-list-tile-content>
              <v-list-tile-title>Todas</v-list-tile-title>
            </v-list-tile-content>

          </v-list-tile>

          <v-list-tile @click="">

            <v-list-tile-content>
              <v-list-tile-title>Actual</v-list-tile-title>
            </v-list-tile-content>

          </v-list-tile>

        </v-list-group> -->

        <v-list-tile @click="routTrabajador">
          <v-list-tile-action>
            <v-icon>people</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Trabajadores</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile @click="routAllNominas">
          <v-list-tile-action>
            <v-icon>work</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Nomina</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

      </v-list>
    </v-navigation-drawer>
    <!-- Toolbar -->
    <v-toolbar color="teal darken-1" dark fixed app clipped-left v-if="$store.state.isLoggedIn">

      <v-toolbar-side-icon v-if="$store.state.empresa != null" @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title><img src="../assets/Logo.png" height="40"></v-toolbar-title>
      <v-spacer></v-spacer>
      <h1>{{nombreEmpresa}}</h1>
      <v-spacer></v-spacer>

      <v-toolbar-items>
        <v-menu offset-y>
         <v-btn slot="activator" flat>
          <v-icon>fa-user</v-icon>&nbsp Usuario
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
      <v-container fluid fill-height>
        <router-view/>
      </v-container>
    </v-content>
    <!-- Fin contenido -->


  </v-app>
</template>

<script>
  export default {
    name: 'Layout',
    data: () => ({
      drawer: null,
      loggedIn: false
    }),
    props: {
      source: String
    },
    created (){
      this.loggedIn = this.$store.state.isLoggedIn;
    },
    computed: {
      nombreEmpresa () {
        let empresa = this.$store.state.empresa;
        if (empresa == null) {
          return '';
        } else {
          return empresa.nombre;
        }
      }
    },
    methods: {
      routHome () {
        this.$router.push({path: '/'});
      },
      routAllEmpresas () {
        this.$router.push({path: '/empresas'})
      },
      routAllBases () {
        this.$router.push({path: '/bases'})
      },
      routTrabajador () {
        this.$router.push({path: '/trabajadores'})
      },
      routAllNominas () {
        this.$router.push({path: '/nominas'})
      },
      routActualNomina () {
        this.$router.push({path: '/nominas/actual'})
      },
      logout () {
        this.$store.commit('logout');
        this.$router.push({path: '/login'});
      }
    }
  }
</script>
