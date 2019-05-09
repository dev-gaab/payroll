<template>
  <v-layout align-center justify-center>
    <v-flex xs12 sm8 md4>
      <v-alert v-model="alert" dismissible :type="alertType">{{alertMessage}}</v-alert>
      <v-card class="elevation-12">
        <!-- Header card -->
        <v-toolbar dark color="teal darken-4">
          <v-toolbar-title id="toolbar">
            <img src="../assets/Logo.png" height="40">
          </v-toolbar-title>
        </v-toolbar>
        <!-- formulario -->
        <form @submit.prevent="auth">
          <v-card-text>
            <v-text-field
              autofocus
              color="teal darken-4"
              prepend-icon="fa-user"
              name="username"
              label="Usuario"
              type="text"
              autocomplete="off"
              v-model="form.username"
              v-validate="'required|alpha_num|min:8'"
            ></v-text-field>
            <v-alert v-show="errors.has('username')" type="error">{{errors.first('username')}}</v-alert>
            <v-text-field
              color="teal darken-4"
              id="password"
              prepend-icon="fa-lock"
              name="password"
              label="Contraseña"
              type="password"
              v-validate="'required|alpha_num|min:8|max:16'"
              v-model="form.password"
            ></v-text-field>
            <v-alert v-show="errors.has('password')" type="error">{{errors.first('password')}}</v-alert>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" dark color="teal darken-4">Login</v-btn>
          </v-card-actions>
        </form>
        <!-- Fin form-->
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import axios from "axios";

export default {
  name: "Login",
  data() {
    return {
      form: {
        username: "",
        password: ""
      },
      alert: false,
      alertType: "error",
      alertMessage: "",
      username: ""
    };
  },
  created() {
    const dict = {
      custom: {
        username: {
          required: "No debe ser vacio",
          alpha_num: "Solo se permiten letras y números",
          max: "Debe tener un máximo de 16 caracteres",
          min: "Debe tener un mínimo de 8 caracteres"
        },
        password: {
          required: "No debe ser vacio",
          alpha_num: "Solo se permiten letras y números",
          max: "Debe tener un máximo de 16 caracteres",
          min: "Debe tener un mínimo de 6 caracteres"
        }
      }
    };
    this.$validator.localize("es", dict);
  },

  methods: {
    auth() {
      this.$store.dispatch("login");
      const vm = this;

      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }
        axios
          .post("http://payroll.com.local/api/auth/login", this.$data.form)
          .then(async res => {
            if (res.data.error) {
              console.log(res.data.error);
            } else {
              await this.$store.commit("loginSuccess", {
                user: res.data.user,
                token: res.data.user.token,
                isAdmin: res.data.isAdmin
              });

              vm.$router.push({ path: "/" });
            }
          })
          .catch(error => {
            console.log(error);
            this.alertMessage = "¡Usuario o Contraseña incorrecta!";
            this.alert = true;
            this.$store.commit("loginFailed", { error });
            this.form.password = "";
          });
      });
    }
  }
};
</script>

<style>
#toolbar {
  margin-left: 15%;
  padding: 0;
}
</style>
