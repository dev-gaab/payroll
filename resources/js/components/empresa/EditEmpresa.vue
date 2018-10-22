<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500">
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
              v-model="empresa.num_afiliacion_ivss"
              name="num_ivss"
              label="Numero de IVSS"
              id="num_ivss"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.fecha_inscripcion_ivss"
              name="fecha_ivss"
              label="Fecha de inscripcion IVSS"
              id="fecha_ivss"
              type="date"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_faov"
              name="num_faov"
              label="Numero de FAOV"
              id="num_faov"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_inces"
              name="num_inces"
              label="Numero de INCES"
              id="num_inces"
            ></v-text-field>

          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click.native="dialog = false">Cerrar</v-btn>
            <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
          </v-card-actions>
        </form> <!-- Fin form-->
      </v-card>
    </v-dialog>
  </v-layout>
</template>
<script>
import axios from 'axios';

export default {
  name: 'EditEmpresa',
  props: ['dialog', 'empresa'],
  methods: {
    save () {
      const vm = this;
      axios.put(`http://payroll.com.local/api/empresas/${vm.empresa.id}`, vm.empresa)
        .then( (res) => {
          if (res.data.res == 'Modificado'){
            vm.$emit('updSuccess');
            vm.dialog = false;
          } else {
            console.log('error');
          }
          
        })
        .catch((err) => {
          console.log(err);
        });
    }
  }
}
</script>
