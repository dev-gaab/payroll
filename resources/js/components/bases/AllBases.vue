<template>
    <v-layout row wrap>
    <v-flex xs12>
      <!-- Alerta Success -->
       <v-alert
        v-model="alertSuc"
        dismissible
        type="success"
      >
        {{msgSuc}}
      </v-alert>

      <v-card>
        <v-card-title>
          <h3>Bases Legales</h3>
          <v-spacer></v-spacer>
          <v-text-field
            color="teal accent-4"
            v-model="search"
            append-icon="fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-btn v-if="bases == ''" icon color="teal accent-4" dark @click="newBases">
            <v-icon>add</v-icon>
          </v-btn>
          <v-btn v-if="bases != ''" icon color="warning" dark @click="editBases">
            <v-icon>fa-edit</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="bases"
          :search="search"
        >
          <template slot="items" slot-scope="props">

            <td>{{ props.item.estatus | capitalize}}</td>
            <td>{{ props.item.desde }}</td>
            <td>{{ props.item.hasta }}</td>
            
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="verBase(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
            </td>
          </template>

          <v-alert slot="no-results" :value="true" color="" icon="fa-exclamation-triangle">
            Tu busqueda "{{ search }}" no encontro resultados.
          </v-alert>
        </v-data-table>
      </v-card>
    </v-flex>
    <!-- componente modal para agregar una nueva base legal -->
    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="600">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Bases legales</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <br>
          <!-- formulario -->
          <form @submit.prevent="save">
          
            <v-card-text>
              <v-toolbar dark color="teal darken-1" dense>
                <v-toolbar-title>Salario Minimo</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <br>
              <v-text-field
                color="teal darken-1"
                v-model="base.salario_minimo"
                name="salario_minimo"
                label="Salario Mínimo"
                id="salario_minimo"
              >
             </v-text-field>

             <v-toolbar dark color="teal darken-1" dense>
                <v-toolbar-title>Cesta Ticket</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <br>
              <v-text-field
                mt-2
                color="teal darken-1"
                v-model="base.unidad_tributaria"
                name="unidad_tributaria"
                label="Unidad Tributaria"
                id="unidad_tributaria"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.cantidad"
                name="cantidad"
                label="Cantidad de unidades tributarias por día"
                id="cantidad"
              ></v-text-field>
            
            <v-toolbar dark color="teal darken-1" dense>
              <v-toolbar-title>Asignaciones</v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
              <br>
              <v-text-field
                color="teal darken-1"
                v-model="base.he_diurnas"
                name="he_diurnas"
                label="Horas Extras Diurnas"
                id="he_diurnas"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.he_nocturnas"
                name="he_nocturnas"
                label="Horas Extras Nocturnas"
                id="he_nocturnas"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.feriados"
                name="feriados"
                label="Feriados"
                id="feriados"
              ></v-text-field>

              <v-toolbar dark color="teal darken-1" dense>
                <v-toolbar-title>Deducciones</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <br>
              <v-text-field
                color="teal darken-1"
                v-model="base.ivss"
                name="ivss"
                label="IVSS"
                id="ivss"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.faov"
                name="faov"
                label="FAOV"
                id="faov"
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.paro_forzoso"
                name="paro_forzoso"
                label="Paro Forzoso"
                id="paro_forzoso"
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
    <!-- Componente para ver bases -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogVer" persistent max-width="600">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Bases legales</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <br>
          <!-- formulario -->
          <form>
          
            <v-card-text>
              <v-toolbar dark color="teal darken-1" dense>
                <v-toolbar-title>Salario Minimo</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <br>
              <v-text-field
                color="teal darken-1"
                v-model="base.salario_minimo"
                name="salario_minimo"
                label="Salario Mínimo"
                id="salario_minimo"
                readonly
              >
             </v-text-field>

             <v-toolbar dark color="teal darken-1" dense>
                <v-toolbar-title>Cesta Ticket</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <br>
              <v-text-field
                mt-2
                color="teal darken-1"
                v-model="base.unidad_tributaria"
                name="unidad_tributaria"
                label="Unidad Tributaria"
                id="unidad_tributaria"
                readonly
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.cantidad"
                name="cantidad"
                label="Cantidad de unidades tributarias por día"
                id="cantidad"
                readonly
              ></v-text-field>
            
            <v-toolbar dark color="teal darken-1" dense>
              <v-toolbar-title>Asignaciones</v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
              <br>
              <v-text-field
                color="teal darken-1"
                v-model="base.he_diurnas"
                name="he_diurnas"
                label="Horas Extras Diurnas"
                id="he_diurnas"
                readonly
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.he_nocturnas"
                name="he_nocturnas"
                label="Horas Extras Nocturnas"
                id="he_nocturnas"
                readonly
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.feriados"
                name="feriados"
                label="Feriados"
                id="feriados"
                readonly
              ></v-text-field>

              <v-toolbar dark color="teal darken-1" dense>
                <v-toolbar-title>Deducciones</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <br>
              <v-text-field
                color="teal darken-1"
                v-model="base.ivss"
                name="ivss"
                label="IVSS"
                id="ivss"
                readonly
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.faov"
                name="faov"
                label="FAOV"
                id="faov"
                readonly
              ></v-text-field>

              <v-text-field
                color="teal darken-1"
                v-model="base.paro_forzoso"
                name="paro_forzoso"
                label="Paro Forzoso"
                id="paro_forzoso"
                readonly
              ></v-text-field>

            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="dialogVer = false">Cerrar</v-btn>
            </v-card-actions>
            </form> <!-- Fin form-->
          </v-card>
      </v-dialog>
    </v-layout>

  </v-layout>

</template>

<script>
import axios from 'axios';

export default {
    name: 'AllBases',
    data () {
      return { 
        search: '',
        headers: [
          { text: 'Estatus', value: 'estatus' },
          { text: 'Desde', value: 'desde' },
          { text: 'Hasta', value: 'hasta' },
          { text: 'Acciones', align: 'center', value: 'id', sortable: false}
        ],
        bases: [],
        base: {},
        dialog: false,
        dialogVer: false,
        isMod: false,
        alertErr: false,
        msgErr: null,
        alertSuc: false,
        msgSuc: null
      }
    },
    created () {
      this.allBases();
    },
    computed: {
      empresaId () {
        let empresa = this.$store.state.empresa;
        if (empresa == null) {
          return empresa;
        } else {
          return empresa.id;
        }
      },
      empresaName () {
        let empresa = this.$store.state.empresa;
        if (empresa == null) {
          return empresa;
        } else {
          return empresa.nombre;
        }
      }
    },
    methods: {
      allBases () {
        const vm = this;

        axios.get(`http://payroll.com.local/api/bases/all/${vm.empresaId}`)
          .then( (res) => {
            vm.$data.bases = res.data.bases;
          })
          .catch( (err) => {
            console.log(err);
          });
      },
      newBases () {
        this.dialog = true;
      },
      verBase(id) {
        const vm = this;

        axios.get(`http://payroll.com.local/api/bases/ver/${id}`)
          .then( (res) => {
            vm.dialogVer = true;
            vm.$data.base = res.data.base;
          })
          .catch( (err) => {
            console.log(err);
          });
      },
      editBases () {
        this.dialog = true;
        this.isMod = true;

        const vm = this;

        axios.get(`http://payroll.com.local/api/bases/${vm.empresaId}`)
          .then( (res) => {
            vm.$data.base = res.data.base;
          })
          .catch( (err) => {
            console.log(err);
          });
      },
      save () {
        if(this.isMod == true) {
          
          const vm  = this;

          axios.put(`http://payroll.com.local/api/bases/${vm.empresaId}`, vm.base)
            .then( (res) => {
              
              vm.dialog = false;

              vm.allBases();

              if (res.data.error) {
                vm.alertErr = true;
                vm.msgErr = "Ocurrio un error al modificar las bases legales."
              } else {
                vm.alertSuc = true;
                vm.msgSuc = "Bases Legales modificadas."
              }

            })
            .catch( (err) => {
              console.log(err);
            });

        } else {
          
          const vm  = this;

          axios.post(`http://payroll.com.local/api/bases/${vm.empresaId}`, vm.base)
            .then( (res) => {
              
              vm.dialog = false;
              vm.allBases();

              if (res.data.error) {
                alertErr = true;
                msgErr = "Ocurrio un error al registrar las bases legales."
              } else {
                alertSuc = true;
                msgSuc = "Bases Legales registradas."
              }

            })
            .catch( (err) => {
              console.log(err);
            });

        }
      }
    },
    filters: {
      capitalize (value) {
        if (!value) return ''
        value = value.toString()
        return value.charAt(0).toUpperCase() + value.slice(1)
      }
    }
}
</script>
