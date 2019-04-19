<template>
  <v-container fluid fill-height>
    <v-layout v-if="$store.state.empresa != null">
        <img src="../assets/fondo.png" id="background_img" alt="imagen de fondo">
    </v-layout>
    <ActivateEmpresa v-else></ActivateEmpresa>
  </v-container>
</template>

<script>
import ActivateEmpresa from './empresa/ActivateEmpresa';

export default {
  name: 'Home',
  components: {
    ActivateEmpresa
  }
}
</script>

<style>
 #background_img {
   width: 100%;
 }
</style>
