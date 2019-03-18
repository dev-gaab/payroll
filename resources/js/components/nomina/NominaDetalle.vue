<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          <h3>Nomina Detalle</h3>
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
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="nominas"
          :search="search"
        >
          <template slot="items" slot-scope="props">

            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre }} {{props.item.apellido}} </td>
            <td>{{ props.item.dias_trabajados }} </td>
            <td>{{ props.item.montos.pago_salario | numberFormat}} </td>
            <td>{{ props.item.montos.total_asignaciones | numberFormat}}</td>
            <td>{{ props.item.montos.total_deducciones | numberFormat}}</td>
            <td>{{ props.item.montos.cesta_ticket | numberFormat}}</td>
            <td>{{ props.item.montos.monto_total | numberFormat}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="verNominaDetalle(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn @click="editNominaDetalle(props.item.id)" icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
            </td>
          </template>

          <v-alert slot="no-results" :value="true" color="" icon="fa-exclamation-triangle">
            Tu busqueda "{{ search }}" no encontro resultados.
          </v-alert>
        </v-data-table>
      </v-card>
    </v-flex>
    <!-- Componente para ver modal de ver Nomina -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogVer" persistent max-width="500">
        <v-card>
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>{{nominaDetalle.cedula}} - {{nominaDetalle.nombre}} {{nominaDetalle.apellido}}</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>

          <v-list two-line>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Dias Trabajados</v-list-tile-title>

                <v-list-tile-sub-title>
                  <v-layout row>
                    <v-flex xs6 >
                      <span class='text--primary'>{{nominaDetalle.dias_trabajados}}</span>
                    </v-flex>
                    <v-flex xs6>
                      <span class='text--primary'>Monto: {{nominaDetalle.montos.pago_salario | numberFormat}}</span>
                    </v-flex>
                  </v-layout>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Horas extras diurnas</v-list-tile-title>

                <v-list-tile-sub-title>
                  <v-layout row>
                    <v-flex xs6 >
                      <span class='text--primary'>{{nominaDetalle.he_diurnas}}</span>
                    </v-flex>
                    <v-flex xs6>
                      <span class='text--primary'>Monto: {{nominaDetalle.montos.he_diurnas | numberFormat}}</span>
                    </v-flex>
                  </v-layout>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Horas extras nocturnas</v-list-tile-title>

                <v-list-tile-sub-title>
                  <v-layout row>
                    <v-flex xs6 >
                      <span class='text--primary'>{{nominaDetalle.he_nocturnas}}</span>
                    </v-flex>
                    <v-flex xs6>
                      <span class='text--primary'>Montos: {{nominaDetalle.montos.he_nocturnas | numberFormat}}</span>
                    </v-flex>
                  </v-layout>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Días feriados</v-list-tile-title>

                <v-list-tile-sub-title>
                  <v-layout row>
                    <v-flex xs6 >
                      <span class='text--primary'>{{nominaDetalle.feriados}}</span>
                    </v-flex>
                    <v-flex xs6>
                      <span class='text--primary'>Monto: {{ nominaDetalle.montos.feriados | numberFormat}}</span>
                    </v-flex>
                  </v-layout>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>IVSS</v-list-tile-title>

                <v-list-tile-sub-title>
                  <v-layout row>
                    <v-flex xs6 >
                      <span class='text--primary'>{{nominaDetalle.ivss ? 'Si' : 'No'}}</span>
                    </v-flex>
                    <v-flex xs6>
                      <span class='text--primary'>Monto: {{ nominaDetalle.montos.ivss |  numberFormat }}</span>
                    </v-flex>
                  </v-layout>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>FAOV</v-list-tile-title>

                <v-list-tile-sub-title>
                  <v-layout row>
                    <v-flex xs6 >
                      <span class='text--primary'>{{nominaDetalle.faov ? 'Si' : 'No'}}</span>
                    </v-flex>
                    <v-flex xs6>
                      <span class='text--primary'>Monto: {{ nominaDetalle.montos.faov | numberFormat}}</span>
                    </v-flex>
                  </v-layout>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>


            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Paro Forzoso</v-list-tile-title>

                <v-list-tile-sub-title>
                  <v-layout row>
                    <v-flex xs6 >
                      <span class='text--primary'>{{nominaDetalle.paro_forzoso ? 'Si' : 'No'}}</span>
                    </v-flex>
                    <v-flex xs6>
                      <span class='text--primary'>Monto: {{nominaDetalle.montos.paro_forzoso | numberFormat}}</span>
                    </v-flex>
                  </v-layout>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Cesta Ticket</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{ nominaDetalle.montos.cesta_ticket | numberFormat}}</span>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>

            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Total asignaciones</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{nominaDetalle.montos.total_asignaciones | numberFormat}}</span>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>

            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Total deducciones</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{nominaDetalle.montos.total_deducciones | numberFormat}}</span>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>

            <v-divider></v-divider>

            <v-list-tile>

              <v-list-tile-content>

                <v-list-tile-title>Total a pagar</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{nominaDetalle.montos.monto_total | numberFormat}}</span>
                </v-list-tile-sub-title>

              </v-list-tile-content>

            </v-list-tile>
          </v-list>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click.native="dialogVer = false">Cerrar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <!-- componente modal para editar nomina -->
    <v-layout row justify-center>
     <v-dialog v-model="dialogUpd" persistent max-width="500">
      <v-card class="elevation-12">
        
        <v-toolbar dark color="teal darken-1" dense>
          <v-toolbar-title>Empresa</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn @click.native="dialogUpd = false" icon flat>
            <v-icon medium>fa-times-circle</v-icon>
          </v-btn>
        </v-toolbar>
        
        <form @submit.prevent="save">
          <v-card-text>
            <v-text-field
              color="teal darken-1"
              v-model="empresa.rif" 
              name="rif"
              label="Rif"
              id="rif">
            </v-text-field> 

            <v-text-field
              color="teal darken-1"
              v-model="empresa.razon_social"
              name="razon_social"
              label="Razon Social"
              id="razon_social">
            </v-text-field>

            <v-textarea
              color="teal darken-1"
              v-model="empresa.direccion"
              name="direccion"
              label="Direccion"
              id="direccion"
              rows="2">
            </v-textarea>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_ivss"
              name="num_ivss"
              label="Numero de IVSS"
              id="num_ivss">
            </v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.fecha_inscripcion_ivss"
              name="fecha_ivss"
              label="Fecha de inscripcion IVSS"
              id="fecha_ivss"
              type="date">
            </v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_faov"
              name="num_faov"
              label="Numero de FAOV"
              id="num_faov">
            </v-text-field>

            <v-text-field
              color="teal darken-1"
              v-model="empresa.num_afiliacion_inces"
              name="num_inces"
              label="Numero de INCES"
              id="num_inces">
            </v-text-field>

          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click.native="dialogUpd = false">Cerrar</v-btn>
            <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
          </v-card-actions>
        </form>
      </v-card>
    </v-dialog>
  </v-layout> 
  </v-layout>

</template>

<script>
  import axios from 'axios';


  export default {
    name: 'NominaDetalle',
    data () {
      return {
        search: '',
        headers: [
          { text: 'Cédula', value: 'cedula' },
          { text: 'Nombre', value: 'nombre' },
          { text: 'Dias Trab.', value: 'dias' },
          { text: 'Salario', value: 'salario' },
          { text: 'Total Asignaciones', value: 'asignaciones' },
          { text: 'Total Deducciones', value: 'deducciones' },
          { text: 'Cesta Ticket', value: 'cesta_ticket' },
          { text: 'Total a pagar', value: 'total' },
          { text: 'Acciones', align: 'center', value: 'codigo', sortable: false}
        ],
        nominas: [],
        idE: this.$store.state.empresa.id,
        idNomina: this.$route.params.id,
        nominaDetalle: {
        	montos: {}
        },
        dialogVer: false,
        dialogUpd: false,
      }
    },
    components: {
    },
    created () {
      this.allNominasDetalle();
    },
    methods: {
      allNominasDetalle () {
        const vm = this;

        axios.get(`/api/nominas/detalle/${vm.idNomina}`,
	        {headers: {'Authorization' : `Bearer ${vm.$store.state.currentUser.token}`}})
          .then(res => {
          	vm.nominas = res.data;
          })
          .catch(err => console.log(err));
      },
      verNominaDetalle(id) {
      	const vm = this;
      	axios.get(`/api/nominas/detalle/find/${id}`,
		      {headers: {'Authorization' : `Bearer ${vm.$store.state.currentUser.token}`}})
          .then(res => {
            vm.nominaDetalle = res.data.nomina;
            vm.dialogVer = true;
          })
          .catch(err => console.log(err));
      },
      editNominaDetalle(id) {

      }
    },
    filters: {
      capitalize (value) {
        if (!value) return ''
        value = value.toString()
        return value.charAt(0).toUpperCase() + value.slice(1)
      },
	    numberFormat(value) {
		    value = new Intl.NumberFormat("es-VE", {
			    style: "currency",
			    currency: "VES"
		    }).format(value);
		    return value;
	    }
    }
  }
</script>
