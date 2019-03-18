<template>
  <v-container fluid fill-height>
    <v-layout row wrap v-if="$store.state.empresa != null">
      <v-flex xs12>
        Esto es el componente Home
      </v-flex>
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
