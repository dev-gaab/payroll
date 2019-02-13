<template>
  <v-container fluid fill-height>
    <v-layout row wrap>
      <v-flex xs12>
        Esto es el componente Home
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import Layout from './Layout.vue'

export default {
  name: 'Home',
  components: {
    Layout
  }
}
</script>
