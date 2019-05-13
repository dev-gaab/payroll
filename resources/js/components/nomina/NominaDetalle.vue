<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-alert v-model="alert" dismissible :type="alertType">{{alertMsg}}</v-alert>
      <v-card>
        <v-card-title>
          <v-btn
            icon
            color="teal darken-4"
            small
            dark
            @click.native="$router.push({ path: '/nominas' })"
          >
            <v-icon>reply</v-icon>
          </v-btn>
          <h3>Nomina - {{codigo}}</h3>
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
          <v-btn
            @click="printAll()"
            icon
            medium
            color="teal darken-4"
            dark
            title="Imprimir recibo de pago"
          >
            <v-icon small>fa-print</v-icon>
          </v-btn>
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
              <v-btn
                @click="verNominaDetalle(props.item.id)"
                icon
                small
                color="primary"
                title="Ver Nomina Detalle"
              >
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn
                @click="editNominaDetalleModal(props.item.id)"
                icon
                small
                color="warning"
                title="Editar Nomina Detalle"
              >
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
            <v-btn
              @click="print(nominaDetalle.id)"
              icon
              medium
              color="light-green darken-1"
              dark
              title="Imprimir recibo de pago"
            >
              <v-icon small>fa-print</v-icon>
            </v-btn>
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
                      <v-list-tile-title>
                        <strong>Dias Trabajados</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.dias_trabajados}} días = {{nominaDetalle.montos.pago_salario | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Dias Feriados</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.feriados}} días = {{nominaDetalle.montos.feriados | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-layout wrap>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Horas extras diurnas</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.he_diurnas}} horas = {{nominaDetalle.montos.he_diurnas | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Horas extras nocturnas</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.he_nocturnas}} horas = {{nominaDetalle.montos.he_nocturnas | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-layout wrap mb-3>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Otras Asignaciones</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.otras_asignaciones | numberFormat}}</span>
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
                      <v-list-tile-title>
                        <strong>IVSS</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.ivss ? 'Si' : 'No'}} = {{nominaDetalle.montos.ivss | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>FAOV</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.faov ? 'Si' : 'No'}} = {{nominaDetalle.montos.faov | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-layout wrap mb-3>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Paro Forzoso</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.paro_forzoso ? 'Si' : 'No'}} = {{nominaDetalle.montos.paro_forzoso | numberFormat}}</span>
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
                      <v-list-tile-title>
                        <strong>Total Asignaciones</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.montos.total_asignaciones | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Total Deducciones</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.montos.total_deducciones | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-layout wrap mb-3>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Cesta Ticket</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{ nominaDetalle.montos.cesta_ticket | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>

                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Total a pagar</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span
                          class="text--primary"
                        >{{nominaDetalle.montos.monto_total | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <h3>Observaciones</h3>
              <v-divider></v-divider>
              <v-layout wrap>
                <v-flex xs12>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-sub-title>
                        <span class="text--primary">{{nominaDetalle.observaciones}}</span>
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
                      :color="errors.has('dias_trabajados') ? 'error' : 'teal darken-1'"
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
                      :color="errors.has('feriados') ? 'error' : 'teal darken-1'"
                      v-model="nominaDetalleUpd.feriados"
                      name="feriados"
                      label="Dias Feriados"
                      id="feriados"
                      v-validate="'numeric|min_value:0|max_value:15'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('feriados')"
                      type="error"
                    >{{errors.first('feriados')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('he_nocturnas') ? 'error' : 'teal darken-1'"
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
                      :color="errors.has('he_diurnas') ? 'error' : 'teal darken-1'"
                      v-model="nominaDetalleUpd.he_diurnas"
                      name="he_diurnas"
                      label="Horas extras diurnas"
                      id="he_diurnas"
                      v-validate="'numeric|min_value:0'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('he_diurnas')"
                      type="error"
                    >{{errors.first('he_diurnas')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('otras_asignaciones') ? 'error' : 'teal darken-1'"
                      v-model="nominaDetalleUpd.otras_asignaciones"
                      name="otras_asignaciones"
                      label="Otras Asignaciones"
                      id="otras_asignaciones"
                      v-validate="'required|decimal:2|min_value:0'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('otras_asignaciones')"
                      type="error"
                    >{{errors.first('otras_asignaciones')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs12>
                    <v-textarea
                      :color="errors.has('observaciones') ? 'error' : 'teal darken-1'"
                      v-model="nominaDetalleUpd.observaciones"
                      name="observaciones"
                      label="Observaciones"
                      id="observaciones"
                      rows="3"
                    ></v-textarea>
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
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;

const moment = require("moment");

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
      alertType: "error",
      alertMsg: "",
      codigo: ""
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
          vm.nominas = res.data.nominas;
          vm.codigo = res.data.codigo;
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
            vm.alertMsg = "Nómina modificada";
          })
          .catch(err => console.log(err));
      });
    },
    print(id) {
      const vm = this;

      axios
        .get(`/api/reportes/nomina/detalle/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          console.log(res.data);
          const date = moment().format("DD-MM-YYYY, h:mm:ss a");

          const nominaDesde = moment(res.data.desde).format("DD-MM-YYYY");
          const nominaHasta = moment(res.data.hasta).format("DD-MM-YYYY");
          const fechaIngreso = moment(res.data.fecha_ingreso).format(
            "DD-MM-YYYY"
          );
          const pago_salario = this.nmbFormat(res.data.montos.pago_salario);
          const he_diurnas = this.nmbFormat(res.data.montos.he_diurnas);
          const he_nocturnas = this.nmbFormat(res.data.montos.he_nocturnas);
          const feriados = this.nmbFormat(res.data.montos.feriados);
          const ivss = this.nmbFormat(res.data.montos.ivss);
          const paro_forzoso = this.nmbFormat(res.data.montos.paro_forzoso);
          const faov = this.nmbFormat(res.data.montos.faov);
          const total_asignaciones = this.nmbFormat(
            res.data.montos.total_asignaciones
          );
          const total_deducciones = this.nmbFormat(
            res.data.montos.total_deducciones
          );
          const monto_total = this.nmbFormat(res.data.montos.monto_total);
          const otras_asignaciones = this.nmbFormat(res.data.otras_asignaciones);

          let dd = {
            footer: {
              columns: [
                {
                  text: `${this.$store.state.empresa.direccion}`,
                  alignment: "center",
                  bold: true
                }
              ]
            },
            content: [
              {
                stack: [
                  `${vm.$store.state.empresa.nombre}`,
                  `RIF V-12312413534`
                ],
                style: "header"
              },
              {
                text: "Recibo de pago",
                bold: true,
                alignment: "center",
                fontSize: 16,
                margin: [0, 0, 0, 4]
              },
              {
                text: `Emitido el ${date}`,
                bold: true,
                alignment: "center",
                fontSize: 10,
                margin: [0, 0, 0, 4]
              },
              {
                text: `Nómina del ${nominaDesde} al ${nominaHasta}`,
                bold: true,
                alignment: "center",
                margin: [0, 0, 0, 15]
              },
              {
                text: `Código: ${res.data.trabajador_id}`,
                bold: true,
                margin: [0, 0, 0, 2]
              },
              {
                text: `${res.data.cedula} - ${res.data.nombre1} ${
                  res.data.apellido1
                }`,
                bold: true,
                margin: [0, 0, 0, 2]
              },
              {
                text: `Cargo: ${res.data.cargo}`,
                bold: true,
                margin: [0, 0, 0, 2]
              },
              {
                text: `Fecha de Ingreso: ${fechaIngreso}`,
                bold: true,
                margin: [0, 0, 0, 10]
              },
              {
                layout: "headerLineOnly", // optional
                table: {
                  // headers are automatically repeated if the table spans over multiple pages
                  // you can declare how many rows should be treated as headers
                  headerRows: 1,
                  widths: [200, "*", "*", "*"],

                  body: [
                    [
                      "Descripción",
                      { text: "D/H/Porc", alignment: "right" },
                      { text: "Asignaciones", alignment: "right" },
                      { text: "Deducciones", alignment: "right" }
                    ],
                    [
                      "Salario",
                      {
                        text: `${res.data.dias_trabajados} Días`,
                        alignment: "right"
                      },
                      {
                        text: `${pago_salario}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" }
                    ],
                    [
                      "Horas Extras Diurnas",
                      {
                        text: `${res.data.he_diurnas} Horas`,
                        alignment: "right"
                      },
                      {
                        text: `${he_diurnas}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" }
                    ],
                    [
                      "Horas Extras Nocturnas",
                      {
                        text: `${res.data.he_nocturnas} Horas`,
                        alignment: "right"
                      },
                      {
                        text: `${he_nocturnas}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" }
                    ],
                    [
                      "Domingo / Feriados",
                      { text: `${res.data.feriados} Días`, alignment: "right" },
                      {
                        text: `${feriados}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" }
                    ],
                    [
                      "IVSS",
                      { text: `4.00 %`, alignment: "right" },
                      { text: `0`, alignment: "right" },
                      { text: `${ivss}`, alignment: "right" }
                    ],
                    [
                      "Paro Forzoso",
                      { text: `0.50 %`, alignment: "right" },
                      { text: `0`, alignment: "right" },
                      {
                        text: `${paro_forzoso}`,
                        alignment: "right"
                      }
                    ],
                    [
                      { text: "FAOV" },
                      { text: `1.00 %`, alignment: "right" },
                      { text: `0`, alignment: "right" },
                      { text: `${faov}`, alignment: "right" }
                    ],
                    [
                      "Otras Asignaciones",
                      { text: ``, alignment: "right" },
                      {
                        text: `${otras_asignaciones}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" },
                    ],
                    [
                      { text: "TOTALES", bold: true },
                      { text: ``, alignment: "right" },
                      {
                        text: `${total_asignaciones}`,
                        bold: true,
                        alignment: "right"
                      },
                      {
                        text: `${total_deducciones}`,
                        bold: true,
                        alignment: "right"
                      }
                    ],
                    [
                      { text: "" },
                      { text: ``, alignment: "right" },
                      {
                        text: `Neto a Cobrar Bs.`,
                        bold: true,
                        alignment: "right"
                      },
                      {
                        text: `${monto_total}`,
                        bold: true,
                        alignment: "right"
                      }
                    ]
                  ]
                }
              },
              {
                text: `Observaciones: ${res.data.observaciones}`,
                fontSize: 10,
                bold: true,
                margin: [0, 20, 0, 50]
              },
              {
                text: `He recibido de la Empresa la cantidad especificada en este recibo, que comprende con la totalidad de mi salario al periodo que se indica en el mismo`,
                fontSize: 10,
                bold: true,
                margin: [0, 20, 0, 50]
              },
              {
                text: `Recibo Conforme`,
                bold: true,
                fontSize: 16,
                alignment: "center",
                margin: [0, 0, 0, 50]
              },
              {
                text: `C.I N° ${res.data.cedula}`,
                fontSize: 16,
                alignment: "center",
                bold: true,
                border: [false, true, false, false],
                margin: [2, 0, 0, 10]
              }
            ],
            styles: {
              header: {
                fontSize: 18,
                bold: true,
                alignment: "center",
                margin: [0, 0, 0, 20]
              }
            }
          };

          pdfMake.createPdf(dd).open();
        })
        .catch(err => console.log(err));
    },
    printAll() {},
    nmbFormat(value) {
      value = new Intl.NumberFormat("es-VE", {
        style: "decimal",
        minimumFractionDigits: 2
      }).format(value);
      return value;
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
