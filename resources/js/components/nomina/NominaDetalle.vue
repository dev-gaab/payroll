<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-alert v-model="alert" dismissible :type="alertType">{{alertMsg}}</v-alert>
      <v-card>
        <v-card-title>
          <h3>Nomina Detalle</h3>
          <v-spacer></v-spacer>
          <v-text-field
            color="teal darken-4"
            v-model="search"
            append-icon="fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-data-table :headers="headers" :items="nominas" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre }} {{props.item.apellido}}</td>
            <td>{{ props.item.dias_trabajados }}</td>
            <td>{{ props.item.montos.pago_salario | numberFormat}}</td>
            <td>{{ props.item.montos.total_asignaciones | numberFormat}}</td>
            <td>{{ props.item.montos.total_deducciones | numberFormat}}</td>
            <td>{{ props.item.montos.cesta_ticket | numberFormat}}</td>
            <td>{{ props.item.montos.monto_total | numberFormat}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn @click="verNominaDetalle(props.item.id)" icon small color="primary">
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn @click="editNominaDetalleModal(props.item.id)" icon small color="warning">
                <v-icon small>fa-edit</v-icon>
              </v-btn>
            </td>
          </template>

          <v-alert
            slot="no-results"
            :value="true"
            color
            icon="fa-exclamation-triangle"
          >Tu busqueda "{{ search }}" no encontro resultados.</v-alert>
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
            <v-btn @click.native="dialogVer = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <v-container grid-list-md>
              <h3>Asignaciones</h3>
              <v-divider></v-divider>
              <v-layout wrap>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Dias Trabajados</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.dias_trabajados}} días = {{nominaDetalle.montos.pago_salario | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Dias Feriados</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.feriados}} días = {{nominaDetalle.montos.feriados | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-layout wrap mb-3>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Horas extras diurnas</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.he_diurnas}} horas = {{nominaDetalle.montos.he_diurnas | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Horas extras nocturnas</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.he_nocturnas}} horas = {{nominaDetalle.montos.he_nocturnas | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <h3>Deducciones</h3>
              <v-divider></v-divider>

              <v-layout wrap>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>IVSS</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.ivss ? 'Si' : 'No'}} = {{nominaDetalle.montos.ivss | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>FAOV</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.faov ? 'Si' : 'No'}} = {{nominaDetalle.montos.faov | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-layout wrap mb-3>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Paro Forzoso</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.paro_forzoso ? 'Si' : 'No'}} = {{nominaDetalle.montos.paro_forzoso | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <h3>Totales</h3>
              <v-divider></v-divider>

              <v-layout wrap>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Total Asignaciones</strong></v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{nominaDetalle.montos.total_asignaciones | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Total Deducciones</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.montos.total_deducciones | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-layout wrap>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Cesta Ticket</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{ nominaDetalle.montos.cesta_ticket | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>

                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title><strong>Total a pagar</strong></v-list-tile-title>

                      <v-list-tile-sub-title>

                        <span class="text--primary">{{nominaDetalle.montos.monto_total | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

            </v-container>
          </v-card-text>

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
            <v-toolbar-title>{{ nominaDetalleUpd.cedula }} - {{nominaDetalleUpd.nombre}} {{nominaDetalleUpd.apellido}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogUpd = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>

          <form @submit.prevent="updateNominaDetalle">
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      color="teal darken-1"
                      v-model="nominaDetalleUpd.dias_trabajados"
                      name="dias_trabajados"
                      label="Dias trabajados"
                      id="dias_trabajados"
                      v-validate="'required|numeric|min_value:0|max_value:15'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('dias_trabajados')"
                      type="error"
                    >{{errors.first('dias_trabajados')}}</v-alert>
                  </v-flex>

                  <v-flex xs6>
                    <v-text-field
                      color="teal darken-1"
                      v-model="nominaDetalleUpd.feriados"
                      name="feriados"
                      label="Dias Feriados"
                      id="feriados"
                      v-validate="'numeric|min_value:0|max_value:15'"
                    ></v-text-field>
                    <v-alert v-show="errors.has('feriados')" type="error">{{errors.first('feriados')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      color="teal darken-1"
                      v-model="nominaDetalleUpd.he_nocturnas"
                      name="he_nocturnas"
                      label="Horas extras nocturnas"
                      id="he_nocturnas"
                      v-validate="'numeric|min_value:0'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('he_nocturnas')"
                      type="error"
                    >{{errors.first('he_nocturnas')}}</v-alert>
                  </v-flex>

                  <v-flex xs6>
                    <v-text-field
                      color="teal darken-1"
                      v-model="nominaDetalleUpd.he_diurnas"
                      name="he_diurnas"
                      label="Horas extras diurnas"
                      id="he_diurnas"
                      v-validate="'numeric|min_value:0'"
                    ></v-text-field>
                    <v-alert v-show="errors.has('he_diurnas')" type="error">{{errors.first('he_diurnas')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs4>
                    <v-checkbox v-model="nominaDetalleUpd.ivss" label="IVSS"></v-checkbox>
                  </v-flex>

                  <v-flex xs4>
                    <v-checkbox v-model="nominaDetalleUpd.faov" label="FAOV"></v-checkbox>
                  </v-flex>

                  <v-flex xs4>
                    <v-checkbox v-model="nominaDetalleUpd.paro_forzoso" label="Paro Forzoso"></v-checkbox>
                  </v-flex>
                </v-layout>
              </v-container>

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
import axios from "axios";

export default {
  name: "NominaDetalle",
  data() {
    return {
      search: "",
      headers: [
        { text: "Cédula", value: "cedula" },
        { text: "Nombre", value: "nombre" },
        { text: "Dias Trab.", value: "dias" },
        { text: "Salario", value: "salario" },
        { text: "Total Asignaciones", value: "asignaciones" },
        { text: "Total Deducciones", value: "deducciones" },
        { text: "Cesta Ticket", value: "cesta_ticket" },
        { text: "Total a pagar", value: "total" },
        { text: "Acciones", align: "center", value: "codigo", sortable: false }
      ],
      nominas: [],
      idE: this.$store.state.empresa.id,
      idNomina: this.$route.params.id,
      nominaDetalle: {
        montos: {}
      },
      nominaDetalleUpd: {},
      dialogVer: false,
      dialogUpd: false,
      alert: false,
      alertType: 'error',
      alertMsg: ''
    };
  },
  components: {},
  created() {
    this.allNominasDetalle();
    const dict = {
      custom: {
        dias_trabajados: {
          required: "No debe ser vacio",
          numeric: "Solo se aceptan numeros",
          min_value: "Valor mínimo 0",
          max_value: "Valor máximo 15"
        },
        he_diurnas: {
          numeric: "Solo se aceptan numeros",
          min_value: "Valor minimo 0"
        },
        he_nocturnas: {
          numeric: "Solo se aceptan numeros",
          min_value: "Valor minimo 0"
        },
        feriados: {
          numeric: "Solo se aceptan numeros",
          min_value: "Valor minimo 0",
          max_value: "Valor máximo 15"
        }
      }
    };

    this.$validator.localize("es", dict);
  },
  methods: {
    allNominasDetalle() {
      const vm = this;

      axios
        .get(`/api/nominas/detalle/${vm.idNomina}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.nominas = res.data;
        })
        .catch(err => console.log(err));
    },
    verNominaDetalle(id) {
      const vm = this;
      axios
        .get(`/api/nominas/detalle/find/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.nominaDetalle = res.data.nomina;
          vm.dialogVer = true;
        })
        .catch(err => console.log(err));
    },
    editNominaDetalleModal(id) {
      const vm = this;
      axios
        .get(`/api/nominas/detalle/find/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.nominaDetalleUpd = res.data.nomina;
          vm.dialogUpd = true;
        })
        .catch(err => console.log(err));
    },
    updateNominaDetalle() {
      const vm = this;

      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }
        axios
          .put(
            `/api/nominas/detalle/${vm.nominaDetalleUpd.id}/${
              vm.nominaDetalleUpd.trabajador_id
            }`,
            vm.nominaDetalleUpd,
            {
              headers: {
                Authorization: `Bearer ${vm.$store.state.currentUser.token}`
              }
            }
          )
          .then(res => {
            vm.allNominasDetalle();
            vm.dialogUpd = false;
            vm.alert = true;
            vm.alertType = "success";
            vm.alertMsg = "Nómina modificada"
          })
          .catch(err => console.log(err));
      });
    }
  },
  filters: {
    capitalize(value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
    numberFormat(value) {
      value = new Intl.NumberFormat("es-VE", {
        style: "currency",
        currency: "VES"
      }).format(value);
      return value;
    }
  }
};
</script>
