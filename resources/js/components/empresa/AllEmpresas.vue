<!-- Componente que permitira ver
    todas las empresas que tenga acceso el usuario -->
<template>
  <v-layout row wrap>
    <!-- Tabla de datos -->
    <v-flex xs12>
      <v-alert
        v-model="alertSuc"
        dismissible
        type="success"
      >
        {{messageSuc}}
      </v-alert>
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
              <v-btn @click="activarEmpresa(props.item.id, props.item.razon_social)" v-if="props.item.id != empresaId" icon small color="success">
                <v-icon small>fa-check</v-icon>
              </v-btn>
              <v-btn @click="verEmpresa(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn @click="editEmpresa(props.item.id)" icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn v-if="props.item.estatus == 'habilitada'" icon small color="error">
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
    <v-layout row justify-center>
      <v-dialog v-model="dialogVer" persistent max-width="500">
        <v-card>
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>{{empresa.razon_social}}</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>

          <v-list two-line>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Rif</v-list-tile-title>

                  <v-list-tile-sub-title>
                    <span class='text--primary'>{{empresa.rif}}</span>
                  </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Dirección</v-list-tile-title>

                  <v-list-tile-sub-title>
                    <span class='text--primary'>{{empresa.direccion}}</span>
                  </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Riesgo IVSS</v-list-tile-title>

                  <v-list-tile-sub-title>
                    <span class='text--primary'>{{empresa.riesgo_ivss}}</span>
                  </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Num. Afiliación IVSS</v-list-tile-title>

                  <v-list-tile-sub-title>
                    <span class='text--primary'>{{empresa.num_afiliacion_ivss}}</span>
                  </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Fecha de Inscripción IVSS</v-list-tile-title>

                  <v-list-tile-sub-title>
                    <span class='text--primary'>{{empresa.fecha_inscripcion_ivss}}</span>
                  </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Num. Afiliación FAOV</v-list-tile-title>

                  <v-list-tile-sub-title>
                    <span class='text--primary'>{{empresa.num_afiliacion_faov}}</span>
                  </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>


            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Num. Afiliación INCES</v-list-tile-title>

                  <v-list-tile-sub-title>
                    <span class='text--primary'>{{empresa.num_afiliacion_inces}}</span>
                  </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Estatus</v-list-tile-title>

                  <v-list-tile-sub-title>
                    <span class='text--primary'>{{empresa.estatus}}</span>
                  </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>

          </v-list>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click.native="dialogVer = false">Cerrar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <!-- componente modal para editar empresa -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogUpd" persistent max-width="500">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Empresa</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogUpd = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <!-- formulario -->
          <form @submit.prevent="save">
            <v-card-text>
              <v-text-field
                color="teal darken-1"
                v-model="empresa.rif"
                name="rif"
                label="Rif"
                id="rif"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.razon_social"
                name="razon_social"
                label="Razon Social"
                id="razon_social"
              ></v-text-field>

              <v-textarea
                color="teal darken-1"
                v-model="empresa.direccion"
                name="direccion"
                label="Direccion"
                id="direccion"
                rows="2"
              ></v-textarea>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.num_afiliacion_ivss"
                name="num_ivss"
                label="Numero de IVSS"
                id="num_ivss"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.fecha_inscripcion_ivss"
                name="fecha_ivss"
                label="Fecha de inscripcion IVSS"
                id="fecha_ivss"
                type="date"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.num_afiliacion_faov"
                name="num_faov"
                label="Numero de FAOV"
                id="num_faov"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="empresa.num_afiliacion_inces"
                name="num_inces"
                label="Numero de INCES"
                id="num_inces"
              ></v-text-field>

            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="dialogUpd = false">Cerrar</v-btn>
              <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
            </v-card-actions>
          </form> <!-- Fin form-->
        </v-card>
      </v-dialog>
    </v-layout>

  </v-layout>

</template>

<script>
import axios from "axios";
import VerEmpresa from "./VerEmpresa.vue";
import EditEmpresa from "./EditEmpresa.vue";

export default {
  name: "AllEmpresas",
  data() {
    return {
      search: "",
      headers: [
        { text: "Rif", value: "rif" },
        { text: "Razon Social", value: "razon_social" },
        { text: "Direccion", value: "direccion" },
        { text: "Estatus", value: "estatus" },
        { text: "Acciones", align: "center", value: "rif", sortable: false }
      ],
      empresas: [],
      idE: this.$store.state.empresa ? this.$store.state.empresa.id : null,
      dialogVer: false,
      dialogUpd: false,
      empresa: {},
      alertSuc: false,
      messageSuc: null
    };
  },
  components: {
    VerEmpresa,
    EditEmpresa
  },
  created() {
    this.allEmpresas();
  },
  mounted() {

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
    newEmpresa() {
      this.$router.push({ path: "/empresas/nueva" });
    },
    allEmpresas() {
      const vm = this;

      axios.get("http://payroll.com.local/api/empresas",
      {headers: {'Authorization' : `Bearer ${vm.$store.state.currentUser.token}`}})
        .then(res => {
          vm.$data.empresas = res.data.empresas;
        })
        .catch(err => {
          console.log(err);
        });

    },
    verEmpresa(id) {
      this.idE = id;
      this.dialogVer = true;

      const vm = this;

      axios
        .get(`http://payroll.com.local/api/empresas/${vm.idE}`,
        {headers: {'Authorization' : `Bearer ${vm.$store.state.currentUser.token}`}})
        .then(res => {
          vm.$data.empresa = res.data.empresa;
        })
        .catch(err => {
          console.log(err);
        });
    },
    editEmpresa(id) {
      this.dialogUpd = true;

      const vm = this;

      axios
        .get(`http://payroll.com.local/api/empresas/${id}`,
        {headers: {'Authorization' : `Bearer ${vm.$store.state.currentUser.token}`}})
        .then(res => {
          vm.$data.empresa = res.data.empresa;
        })
        .catch(err => {
          console.log(err);
        });
    },
    save () {
      const vm = this;
      axios.put(`http://payroll.com.local/api/empresas/${vm.empresa.id}`, vm.empresa,
       {headers: {'Authorization' : `Bearer ${vm.$store.state.currentUser.token}`}})
        .then( (res) => {
          if (!res.data.error){
            vm.dialogUpd = false;
            vm.allEmpresas();
          } else {
            console.log(res.data.error);
          }

        })
        .catch((err) => {
          console.log(err);
        });
    },

    activarEmpresa (id, nombre) {
      this.$store.commit('activarEmpresa', {id: id, nombre: nombre});

      this.messageSuc = `¡La empresa se ha cambiado! ${nombre}`;
      this.alertSuc = true;
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
