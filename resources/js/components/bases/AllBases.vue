<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          <h3>Bases Legales</h3>
        </v-card-title>

        <v-tabs
          fixed-tabs
          v-model="tab"
          color="teal"
          dark
          slider-color="white"
        >

          <v-tab
            class="tabsBase"
            v-for="title in tabTitles"
            :key="title"
            :href="'#tab-' + title"
          >
            {{title}}
          </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
          <v-tab-item
            v-for="title in tabTitles"
            :value="'tab-' + title"
            :key="title"
          >
            <v-card flat>
              <v-card-text>
                <!-- COlocar aqui los componentes a utilizar
                dependiendo de la tab selecionada-->
                <h2>{{ title }}</h2>
                <AllSalarioMinimo v-if="title == 'Salario Mínimo'"/>
              </v-card-text>
            </v-card>
          </v-tab-item>
        </v-tabs-items>

      </v-card>
    </v-flex>

  </v-layout>

</template>

<script>
import axios from 'axios';
import AllSalarioMinimo from './salario_minimo/AllSalariosM';

export default {
    name: 'AllBases',
    data () {
      return {
        tab: null,
        tabTitles: [
          'Asignaciones',
          'Cestaticket',
          'Deducciones',
          'Salario Mínimo',
          'Tabla Salarial'
        ]
      }
    },
    components: {
      AllSalarioMinimo
    }
}
</script>

<style scoped>
  .tabsBase a:link {
    text-decoration: none !important;
  }
</style>
