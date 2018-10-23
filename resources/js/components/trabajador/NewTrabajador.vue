<template>
  <v-layout row wrap>
    <!-- Formulario de datos -->
    <v-flex xs6>
      <v-card class="elevation-12">
        <!-- Header card -->
        <v-toolbar dark color="teal darken-1" dense>
        <v-toolbar-title>Trabajador</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <!-- formulario -->
        <form @submit.prevent="addTrabajador">
          <v-card-text>
            <v-text-field
              color="teal darken-1"
              v-model="trabajador.cedula"
              name="cedula"
              label="Cédula"
              id="cedula"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="trabajador.nombre1"
              name="nombre1"
              label="Primer Nombre"
              id="nombre1"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="trabajador.nombre2"
              name="nombre2"
              label="Segundo Nombre"
              id="nombre2"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="trabajador.apellido1"
              name="apellido1"
              label="Primer Apellido"
              id="apellido1"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="trabajador.apellido2"
              name="apellido2"
              label="Segundo Apellido"
              id="apellido2"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="trabajador.cargo"
              name="cargo"
              label="Cargo"
              id="cargo"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="trabajador.fecha_nacimiento"
              label="Fecha de Nacimiento"
              name="fecha_nacimiento"
              id="fecha_nacimiento"
              type="date"
            ></v-text-field>

            <v-select
              :items="sexo"
              color="teal darken-1"
              v-model="trabajador.sexo"
              label="Sexo"
              name="sexo"
              id="sexo"
            ></v-select>

            <v-textarea
              color="teal darken-1"
              v-model="trabajador.direccion"
              name="direccion"
              label="Direccion"
              id="direccion"
              rows="2"
            ></v-textarea>

            <v-text-field
              color="teal darken-1"
              v-model="trabajador.telefono_fijo"
              name="telefono_fijo"
              label="Telefono Fijo"
              id="telefono_fijo"
            ></v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="trabajador.telefono_celular"
              name="telefono_celular"
              label="Telefono Celular"
              id="telefono_celular"
            ></v-text-field>

           <v-text-field
              color="teal darken-1"
              v-model="trabajador.fecha_ingreso"
              label="Fecha de ingreso"
              name="fecha_ingreso"
              id="fecha_ingreso"
              type="date"
            ></v-text-field>

            <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Salario</v-toolbar-title>
            <v-spacer></v-spacer>
            </v-toolbar>
             <v-checkbox
                v-model="trabajador.salario_minimo"
                label="Salario Minimo?"
                required
            ></v-checkbox>

            <v-text-field
              v-if="trabajador.salario_minimo == false"
              color="teal darken-1"
              v-model="trabajador.salario"
              label="Salario mensual"
              name="salario"
              id="salario"
            ></v-text-field>

            <v-select
              :items="tipoSalario"
              color="teal darken-1"
              v-model="trabajador.tipo_salario"
              label="Tipo de Pago"
              name="tipo_salario"
              id="tipo_salario"
            ></v-select>

          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
          </v-card-actions>
        </form> <!-- Fin form-->
      </v-card>
    </v-flex>

    <v-flex xs5 offset-xs1>
      <v-card>
        <v-card-title>
          <h3>Agregados</h3>
          <v-spacer></v-spacer>
          <v-text-field
            color="teal darken-1"
            v-model="search"
            append-icon="fa-search"
            label="Buscar.."
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="trabajadores"
          :search="search"
        >
          <template slot="items" slot-scope="props">
            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre1 }} {{props.item.apellido1}} </td>
          </template>
          <v-alert slot="no-results" :value="true" color="error" icon="fa-exclamation-triangle">
            Tu busqueda "{{ search }}" no encontro resultados.
          </v-alert>
        </v-data-table>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import axios from 'axios';

  class Trabajador {
    constructor () {
      this.cedula = '';
      this.nombre1 = '';
      this.nombre2 = '';
      this.apellido1 = '';
      this.apellido2 = '';
      this.cargo = '';
      this.fecha_nacimiento = '';
      this.sexo = '';
      this.direccion = '';
      this.telefono_fijo = '';
      this.telefono_celular = '';
      this.fecha_ingreso = '';
      this.salario_minimo = 'true'; 
      this.salario = '';
      this.tipo_salario = '';
    }
  }

  export default {
    name: 'NewTrabajador',
    data () {
      return {
        idE: 1,
        search: '',
        trabajador: new Trabajador(),
        sexo: ['Masculino', 'Femenino'],
        tipoSalario: ['Semanal', 'Quincenal', 'Mensual'],
        headers: [
          { text: 'Cedula', value: 'cedula' },
          { text: 'Nombre', value: 'nombre1' },
        ],
        trabajadores: []
      }
    },
    methods: {
      addTrabajador () {
        const vm = this;
        axios.post(`http://payroll.com.local/api/trabajadores/${vm.idE}`, vm.$data.trabajador)
          .then((res) => {
            
            if(!res.data.error){

              vm.$data.trabajadores.push({
                cedula: vm.$data.trabajador.cedula,
                nombre1: vm.$data.trabajador.nombre1,
                apellido1: vm.$data.trabajador.apellido1
              });

              vm.$data.trabajador = new Trabajador();

              console.log(res.data.message);
            } else {
              console.log(res.data.error);
            }
          })
          .catch((err) => {
            console.log(err);
          });
      }
    }
  }
</script>
