<template>
  <v-layout row wrap>
    <!-- Tabla de datos -->
    <v-flex xs12>
      <!-- Alertas -->
      <v-alert v-model="alertTable" dismissible :type="alertTableType">{{alertTableMessage}}</v-alert>

      <v-card>
        <v-card-title>
          <h3>Usuarios</h3>
          <v-spacer></v-spacer>
          <v-text-field
            color="teal darken-4"
            v-model="search"
            append-icon="fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-btn icon color="teal darken-4" dark @click="newUser">
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="usuarios" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.nombre }} {{props.item.apellido}}</td>
            <td>{{ props.item.username }}</td>
            <td>{{ props.item.email }}</td>
            <td>{{props.item.rol | capitalize}}</td>
            <td>{{ props.item.estatus | capitalize}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="editUserModal(props.item.id)" icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn
                @click="disableUser(props.item.id)"
                v-if="props.item.estatus == 'activo'"
                icon
                small
                color="error"
              >
                <v-icon small>fa-lock</v-icon>
              </v-btn>
              <v-btn
                @click="enableUser(props.item.id)"
                v-if="props.item.estatus != 'activo'"
                icon
                small
                color="success"
              >
                <v-icon small>fa-unlock</v-icon>
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

    <v-layout row justify-center>
      <v-dialog v-model="dialogUpd" persistent max-width="500">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Usuario</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogUpd = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>

          <v-alert v-model="alertModal" dismissible :type="alertModalType">{{alertModalMessage}}</v-alert>
          <!-- formulario -->
          <form @submit.prevent="updUser" id="userForm">
            <v-card-text>
              <v-text-field
                :color="errors.has('nombre') ? 'error' : 'teal darken-1'"color="teal darken-1"
                v-model="user.nombre"
                name="nombre"
                label="Nombre"
                id="nombre"
                v-validate="'required|alpha'"
              ></v-text-field>
              <v-alert v-show="errors.has('nombre')" :value="true" type="error">{{errors.first('nombre')}}</v-alert>

              <v-text-field
                :color="errors.has('apellido') ? 'error' : 'teal darken-1'"
                v-model="user.apellido"
                name="apellido"
                label="Apellido"
                id="apellido"
                v-validate="'required|alpha'"
              ></v-text-field>
              <div class="red">
                <span class="text-light subheading">{{ errors.first('apellido') }}</span>
              </div>

              <v-text-field
                :color="errors.has('email') ? 'error' : 'teal darken-1'"
                v-model="user.email"
                name="email"
                label="Correo"
                id="email"
                v-validate="'required|email'"
              ></v-text-field>
              <div class="red">
                <span class="text-light subheading">{{ errors.first('email') }}</span>
              </div>

              <v-checkbox v-model="password.isChangingPassword" label="Cambiar Contraseña?"></v-checkbox>

              <v-text-field
                v-if="password.isChangingPassword"
                :color="errors.has('password') ? 'error' : 'teal darken-1'"
                v-model="password.newPassword"
                name="password"
                label="Contraseña Nueva"
                type="password"
                id="password"
                v-validate="'required'"
                ref="password"
              ></v-text-field>
              <div class="red">
                <span class="text-light subheading">{{ errors.first('password') }}</span>
              </div>

              <v-text-field
                v-if="password.isChangingPassword"
                :color="errors.has('password_confirmation') ? 'error' : 'teal darken-1'"
                v-model="password.confirmNewPassword"
                name="password_confirmation"
                label="Confirmar contraseña nueva"
                type="password"
                id="password_confirmation"
                v-validate="'required|confirmed:password'"
                data-vv-as="password"
              ></v-text-field>
              <div class="red">
                <span class="text-light subheading">{{ errors.first('password_confirmation') }}</span>
              </div>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="dialogUpd = false">Cerrar</v-btn>
              <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
            </v-card-actions>
          </form>
          <!-- Fin form-->
        </v-card>
      </v-dialog>
    </v-layout>
  </v-layout>
</template>

<script>
// TODO: validar formularios con VeeValidate, integrarlo al proyecto.
import axios from "axios";
const moment = require("moment");

export default {
  name: "User",
  data() {
    return {
      search: "",
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Nombre de usuario", value: "username" },
        { text: "Correo", value: "email" },
        { text: "Rol", value: "rol" },
        { text: "Estatus", value: "estatus" },
        { text: "Acciones", align: "center", value: "actions", sortable: false }
      ],
      usuarios: [],
      idE: this.$store.state.empresa.id,
      dialogUpd: false,
      user: {},
      password: {
        isChangingPassword: false,
        oldPassword: "",
        newPassword: "",
        confirmNewPassword: ""
      },
      alertTable: false,
      alertTableMessage: "",
      alertTableType: "success",
      alertModal: false,
      alertModalMessage: "",
      alertModalType: "error"
    };
  },
  async created() {
    this.allUsers();
    // ! TODO: modularizar este diccionario y exportarlo para utilizarlo en cualquier parte..
    const dict = {
      custom: {
        nombre: {
          required: "No puede ser vacio",
          alpha: "Solo letras"
        },
        apellido: {
          required: "No puede ser vacio",
          alpha: "Solo letras"
        },
        email: {
          required: "No puede ser vacio",
          email: "Debe tener un formato de correo valido"
        },
        password: {
          required: "No puede ser vacio"
        },
        password_confirmation: {
          required: "No puede ser vacio",
          confirmed: "Las contraseñas deben coincidir"
        }
      }
    };
    // or use the instance method
    this.$validator.localize("es", dict);
  },
  methods: {
    newUser() {
      this.$router.push({path: '/users/nuevo'})
    },
    allUsers() {
      const vm = this;

      axios
        .get(`http://payroll.com.local/api/users`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.usuarios = res.data;
        })
        .catch(err => console.log(err));
    },
    editUserModal(id) {
      const vm = this;
      axios
        .get(`http://payroll.com.local/api/users/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.user = res.data;
          vm.dialogUpd = true;
        })
        .catch(err => console.log(err));
    },
    updUser() {
      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }

        axios
          .put(`http://payroll.com.local/api/users/${user.id}`, vm.user, {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          })
          .then(res => {
            if (res.data.error) {
              vm.alertModalMessage = res.data.error;
              vm.alertModal = true;
              return;
            }

            vm.dialogUpd = false;
            vm.user = {};
            vm.alertTableMessage = "Usuario modificado!";
            vm.alertTable = true;
          })
          .catch(err => console.log(err));
      });
    },
    disableUser(id) {
      const vm = this;

      if(window.confirm('¿Esta seguro que desea inhabilitar al usuario?')) {
        axios
          .put(`http://payroll.com.local/api/users/disable/${id}`, {}, {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          })
          .then(res => {
            vm.alertTable = true;
            vm.alertTableMessage = 'Usuario inhabilitado.';
            vm.alertTableType = 'success';
            vm.allUsers();
          })
          .catch(err => console.log(err));
      }
    },
    enableUser(id) {
      const vm = this;

      if(window.confirm('¿Esta seguro que desea habilitar al usuario?')) {
        axios
          .put(`http://payroll.com.local/api/users/enable/${id}`, {}, {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          })
          .then(res => {
            vm.alertTable = true;
            vm.alertTableMessage = 'Usuario habilitado.';
            vm.alertTableType = 'success';
            vm.allUsers();
          })
          .catch(err => console.log(err));
      }
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
