<template>
  <v-container fluid fill-height>
    <v-layout v-if="$store.state.empresa != null" justify-center></v-layout>
    <ActivateEmpresa v-else></ActivateEmpresa>
  </v-container>
</template>

<script>
import ActivateEmpresa from "./empresa/ActivateEmpresa";

export default {
  name: "Home",
  components: {
    ActivateEmpresa
  }
};
</script>

<style>
</style>
