<template>
  <v-container id="cont" pa-0 ma-0>
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
   margin: 0;
   padding: 0;
   width: 100%;
 }
</style>
