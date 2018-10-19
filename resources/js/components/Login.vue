<template>
  <v-layout align-center justify-center>
    <v-flex xs12 sm8 md4>
      <v-card class="elevation-12">
        <!-- Header card -->
        <v-toolbar dark color="teal accent-4">
          <v-toolbar-title><img src="../assets/Logo.png" height="40"></v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <!-- formulario -->
        <form @submit.prevent="auth">
          <v-card-text>
            <v-text-field color="teal accent-4" prepend-icon="fa-user" name="login" label="Usuario" type="text" autocomplete="off" v-model="form.username"></v-text-field>
            <v-text-field color="teal accent-4" id="password" prepend-icon="fa-lock" name="password" label="Contraseña" type="password" v-model="form.password"></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" dark color="teal accent-4">Login</v-btn>
          </v-card-actions>
        </form> <!-- Fin form-->
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'Login',
    data(){
      return {

        form:{
          username: '',
          password: ''
        }
      }
    },
    methods: {
      auth(){
        this.$store.dispatch('login');

        axios.post('http://localhost:3000/api/usuarios/login', this.$data.form)
          .then( (res) => {
            if (res.data.error) {
              console.log(res.data.error);
            }else{
              this.$store.commit('loginSuccess', {
                user: res.data.user,
                token: res.data.token
              });
              this.$router.push({path: '/'});
            }

          })
          .catch((error) => {
            this.$store.commit('loginFailed', {error})
          });
      }
    }
  }
</script>
