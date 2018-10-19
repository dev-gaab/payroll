<template>
  <v-card class="elevation-12">
    <!-- Header card -->
    <v-toolbar dark color="teal darken-1" dense>
      <v-toolbar-title>Empresa</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <!-- formulario -->
    <form @submit.prevent="save">
      <v-card-text>
        <v-text-field
          color="teal darken-1"
          v-model="empresa.rif"
          name="rif"
          label="Rif"
          id="rif"
        ></v-text-field>

        <v-text-field
          color="teal darken-1"
          v-model="empresa.razon_social"
          name="razon_social"
          label="Razon Social"
          id="razon_social"
        ></v-text-field>

        <v-textarea
          color="teal darken-1"
          v-model="empresa.direccion"
          name="direccion"
          label="Direccion"
          id="direccion"
          rows="2"
        ></v-textarea>

        <v-text-field
          color="teal darken-1"
          v-model="empresa.num_ivss"
          name="num_ivss"
          label="Numero de IVSS"
          id="num_ivss"
        ></v-text-field>

        <v-text-field
          color="teal darken-1"
          v-model="empresa.fecha_ivss"
          name="fecha_ivss"
          label="Fecha de inscripcion IVSS"
          id="fecha_ivss"
          type="date"
        ></v-text-field>

        <v-text-field
          color="teal darken-1"
          v-model="empresa.num_faov"
          name="num_faov"
          label="Numero de FAOV"
          id="num_faov"
        ></v-text-field>

        <v-text-field
          color="teal darken-1"
          v-model="empresa.num_inces"
          name="num_inces"
          label="Numero de INCES"
          id="num_inces"
        ></v-text-field>

      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
      </v-card-actions>
    </form> <!-- Fin form-->
  </v-card>
</template>
<script>
import axios from 'axios';

export default {
  name: 'EditEmpresa',
  data () {
    empresa: {
      this.rif = '';
      this.razon_social = '';
      this.direccion = '';
      this.riesgo_ivss = '';
      this.num_ivss = '';
      this.num_faov = '';
      this.num_inces = '';
      this.fecha_ivss = '';
    }
  }
}
</script>
