<template>
  <v-layout row wrap>
    <v-flex xs12 md7 class="mr">
      <v-card class="elevation-12">
        <!-- Header card -->
        <v-toolbar dark color="teal darken-1" dense>
          <v-btn icon color="teal darken-4" dark @click.native="$router.push({ path: '/users' })">
            <v-icon>reply</v-icon>
          </v-btn>
          <v-toolbar-title>Usuario</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <!-- Alertas -->
        <v-alert v-model="alert" dismissible :type="alertType">{{alertMessage}}</v-alert>

        <!-- formulario -->
        <form @submit.prevent="addUser">
          <v-card-text>
            <v-text-field
              :color="errors.has('username') ? 'error' : 'teal darken-1'"
              v-model="user.username"
              name="username"
              label="Nombre de Usuario"
              id="username"
              v-validate="'required|alpha_num|min:8|max:16'"
            ></v-text-field>
            <v-alert v-show="errors.has('username')" type="error">{{errors.first('username')}}</v-alert>

            <v-text-field
              :color="errors.has('nombre') ? 'error' : 'teal darken-1'"
              v-model="user.nombre"
              name="nombre"
              label="Nombre"
              id="nombre"
              v-validate="'required|alpha'"
            ></v-text-field>
            <v-alert v-show="errors.has('nombre')" type="error">{{errors.first('nombre')}}</v-alert>

            <v-text-field
              :color="errors.has('apellido') ? 'error' : 'teal darken-1'"
              v-model="user.apellido"
              name="apellido"
              label="Apellido"
              id="apellido"
              v-validate="'required|alpha'"
            ></v-text-field>
            <v-alert v-show="errors.has('apellido')" type="error">{{errors.first('apellido')}}</v-alert>

            <v-text-field
              :color="errors.has('email') ? 'error' : 'teal darken-1'"
              v-model="user.email"
              name="email"
              label="Correo"
              id="email"
              v-validate="'required|email'"
            ></v-text-field>
            <v-alert v-show="errors.has('email')" type="error">{{errors.first('email')}}</v-alert>

            <v-text-field
              :color="errors.has('password') ? 'error' : 'teal darken-1'"
              v-model="user.password"
              name="password"
              label="Contraseña"
              id="password"
              type="password"
              v-validate="'required|alpha_num|min:6|max:16'"
              ref="password"
            ></v-text-field>
            <v-alert v-show="errors.has('password')" type="error">{{errors.first('password')}}</v-alert>

            <v-text-field
              :color="errors.has('password_confirmation') ? 'error' : 'teal darken-1'"
              v-model="user.confirmNewPassword"
              name="password_confirmation"
              label="Confirmar contraseña"
              type="password"
              id="password_confirmation"
              v-validate="'required|confirmed:password'"
              data-vv-as="password"
            ></v-text-field>
            <v-alert v-show="errors.has('password_confirmation')" type="error">{{errors.first('password_confirmation')}}</v-alert>
            <v-select
              :items="roles"
              color="teal darken-1"
              v-model="user.rol"
              label="Rol"
              name="rol"
              id="rol"
              v-validate="'required|alpha'"
            ></v-select>
            <v-alert v-show="errors.has('rol')" type="error">{{errors.first('rol')}}</v-alert>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
          </v-card-actions>
        </form>
        <!-- Fin form-->
      </v-card>
    </v-flex>

    <v-flex xs12 md5 class="pt">
      <v-card>
        <v-card-title>
          <h3>Agregados</h3>
          <v-spacer></v-spacer>
          <v-text-field
            color="teal darken-1"
            v-model="search"
            append-icon="fa-search"
            label="Buscar.."
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="users" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.username }}</td>
            <td>{{ props.item.nombre }} {{props.item.apellido}}</td>
          </template>
          <v-alert
            slot="no-results"
            :value="true"
            color="error"
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
  data() {
    return {
      user: {
        rol: "Administrador"
      },
      roles: ["Administrador", "Operador"],
      alert: false,
      alertType: "success",
      alertMessage: "",
      search: "",
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Email", value: "email" }
      ],
      users: []
    };
  },
  created () {
    const dict = {
      custom: {
        username: {
          required: "No debe ser vacio",
          alpha_num: "Solo se permiten letras y números",
          max: "Debe tener un máximo de 16 caracteres",
          min: "Debe tener un mínimo de 6 caracteres"
        },
        nombre: {
          required: "No debe ser vacio",
          alpha: "Solo debe contener letras."
        },
        apellido: {
          required: "No debe ser vacio",
          alpha: "Solo debe contener letras."
        },
        email: {
          required: "No debe ser vacio",
          email: "Debe ser de tipo email. Ej: example@local.com"
        },
        password: {
          required: "No debe ser vacio",
          alpha_num: "Solo se permiten letras y números",
          max: "Debe tener un máximo de 16 caracteres",
          min: "Debe tener un mínimo de 6 caracteres"
        },
        password_confirmation: {
          required: "No debe ser vacio",
          confirmed: "Las contraseñas deben coincidir "
        },
        rol: {
          required: 'No debe ser vacio',
          alpha: 'Solo se permiten letras'
        }
      }
    };
    this.$validator.localize("es", dict);
  },
  methods: {
    addUser() {
      const vm = this;
      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }
        axios
          .post(
            `http://payroll.com.local/api/auth/signup`,
            vm.user,
            {
              headers: {
                Authorization: `Bearer ${vm.$store.state.currentUser.token}`
              }
            }
          )
          .then(res => {
            if (!res.data.error) {
              vm.$data.users.push({
                username: vm.$data.user.username,
                nombre: vm.$data.user.nombre,
                apellido: vm.$data.user.apellido
              });

              vm.$data.user = {
                rol: "Administrador"
              };

              vm.$data.alert = true;
              vm.$data.alertType = "success";
              vm.$data.alertMessage = "Usuario Agregado!";
            } else {
              vm.$data.alert = true;
              vm.$data.alertType = "error";
              vm.$data.alertMessage = res.data.error
            }

            document.getElementById("username").focus();
          })
          .catch(err => {
            console.log(err);
          });
      });
    }
  }
};
</script>

<style>
.mr {
  padding-right: 5px;
}
.pt {
  padding-top: 5px;
}
</style>
