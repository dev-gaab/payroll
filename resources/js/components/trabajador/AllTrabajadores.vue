<!-- Componente que permitira ver
    todas las empresas que tenga acceso el usuario -->
<template>
  <v-layout row wrap>
    <!-- Tabla de datos -->
    <v-flex xs12>
      <v-alert v-model="alertSuc" dismissible type="success">{{messageSuc}}</v-alert>
      <v-card>
        <v-card-title>
          <h3>Trabajadores</h3>
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
            icon
            color="teal darken-4"
            dark
            @click="newTrabajador"
            v-if="$store.state.currentUser.isAdmin"
            title="Nuevo Trabajador"
          >
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="trabajadores" :search="search">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.cedula }}</td>
            <td>{{ props.item.nombre1 }} {{props.item.nombre2}}</td>
            <td>{{ props.item.apellido1 }} {{props.item.apellido2}}</td>
            <td>{{props.item.cargo}}</td>
            <td>{{props.item.fecha_ingreso | dateFormat}}</td>
            <!-- Acciones -->
            <td class="justify-center layout px-0">
              <v-btn
                @click="verTrabajador(props.item.id)"
                icon
                small
                color="primary"
                title="Ver Trabajador"
              >
                <v-icon small>fa-eye</v-icon>
              </v-btn>
              <v-btn
                @click="editTrabajadorModal(props.item.id)"
                icon
                small
                color="warning"
                v-if="$store.state.currentUser.isAdmin"
                title="Editar modal"
              >
                <v-icon small>fa-edit</v-icon>
              </v-btn>
              <v-btn
                @click="disable(props.item.id)"
                v-if="props.item.estatus == 'activo' && $store.state.currentUser.isAdmin"
                icon
                small
                color="error"
                title="Inhabilitar Trabajador"
              >
                <v-icon small>fa-lock</v-icon>
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
    <!-- Componente para ver modal de trabajadores -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogVer" persistent max-width="500">
        <v-card>
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title
              class="align-center"
            >{{trabajador.cedula}} - {{trabajador.nombre1}} {{trabajador.apellido1}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogVer = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Cargo</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.cargo | capitalize}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Salario</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{salario.salario | numberFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout wrap>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Fecha Nacimiento</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.fecha_nacimiento | dateFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Sexo</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.sexo | capitalize}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-divider></v-divider>
              <v-layout wrap>
                <v-flex xs12>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Dirección</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.direccion}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-divider></v-divider>
              <v-layout wrap>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Teléfono Fijo</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.telefono_fijo}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs6>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Teléfono Celular</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.telefono_celular}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
              </v-layout>

              <v-divider></v-divider>
              <v-layout wrap>
                <v-flex xs4>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Fecha Ingreso</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.fecha_ingreso | dateFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>
                <v-flex xs4>
                  <v-list-tile v-if="trabajador.fecha_egreso">
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Fecha Egreso</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.fecha_egreso | dateFormat}}</span>
                      </v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-flex>

                <v-flex xs4>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        <strong>Estatus</strong>
                      </v-list-tile-title>

                      <v-list-tile-sub-title>
                        <span class="text--primary">{{trabajador.estatus | capitalize}}</span>
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

    <!-- componente modal para editar empresa -->
    <v-layout row justify-center>
      <v-dialog v-model="dialogUpd" persistent max-width="500">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Modificar Trabajador</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogUpd = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <v-alert v-model="alertUpd" dismissible type="error">{{alertUpdMsg}}</v-alert>
          <!-- formulario -->
          <form @submit.prevent="editTrabajador">
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('cedula') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.cedula"
                      name="cedula"
                      label="Cédula"
                      id="cedula"
                      v-validate="{required: true, regex: '^([V|E]{1})([0-9]{7,8})$'}"
                    ></v-text-field>
                    <v-alert v-show="errors.has('cedula')" type="error">{{errors.first('cedula')}}</v-alert>
                  </v-flex>

                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('fecha_ingreso') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.fecha_ingreso"
                      label="Fecha de ingreso"
                      name="fecha_ingreso"
                      id="fecha_ingreso"
                      type="date"
                      v-validate="'required'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('fecha_ingreso')"
                      type="error"
                    >{{errors.first('fecha_ingreso')}}</v-alert>
                  </v-flex>
                </v-layout>
                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('nombre1') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.nombre1"
                      name="nombre1"
                      label="Primer Nombre"
                      id="nombre1"
                      v-validate="'required|alpha'"
                    ></v-text-field>
                    <v-alert v-show="errors.has('nombre1')" type="error">{{errors.first('nombre1')}}</v-alert>
                  </v-flex>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('nombre2') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.nombre2"
                      name="nombre2"
                      label="Segundo Nombre"
                      id="nombre2"
                      v-validate="'alpha'"
                    ></v-text-field>
                    <v-alert v-show="errors.has('nombre2')" type="error">{{errors.first('nombre2')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('apellido1') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.apellido1"
                      name="apellido1"
                      label="Primer Apellido"
                      id="apellido1"
                      v-validate="'required|alpha'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('apellido1')"
                      type="error"
                    >{{errors.first('apellido1')}}</v-alert>
                  </v-flex>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('apellido2') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.apellido2"
                      name="apellido2"
                      label="Segundo Apellido"
                      id="apellido2"
                      v-validate="'alpha'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('apellido2')"
                      type="error"
                    >{{errors.first('apellido2')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs12>
                    <v-text-field
                      :color="errors.has('cargo') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.cargo"
                      name="cargo"
                      label="Cargo"
                      id="cargo"
                      v-validate="{required: true, regex: /[a-zA-Z0-9\.\,\#\-\_\/\sáéíóú]+$/}"
                    ></v-text-field>
                    <v-alert v-show="errors.has('cargo')" type="error">{{errors.first('cargo')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('fecha_nacimiento') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.fecha_nacimiento"
                      label="Fecha de Nacimiento"
                      name="fecha_nacimiento"
                      id="fecha_nacimiento"
                      type="date"
                      v-validate="'required'"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('fecha_nacimiento')"
                      type="error"
                    >{{errors.first('fecha_nacimiento')}}</v-alert>
                  </v-flex>
                  <v-flex xs6>
                    <v-select
                      :items="sexo"
                      :color="errors.has('sexo') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.sexo"
                      label="Sexo"
                      name="sexo"
                      id="sexo"
                      v-validate="'required'"
                    ></v-select>
                    <v-alert v-show="errors.has('sexo')" type="error">{{errors.first('sexo')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs12>
                    <v-textarea
                      :color="errors.has('direccion') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.direccion"
                      name="direccion"
                      label="Direccion"
                      id="direccion"
                      rows="2"
                      v-validate="{required: true, regex: /[a-zA-Z0-9\.\,\#\/\sáéíóú]+$/}"
                    ></v-textarea>
                    <v-alert
                      v-show="errors.has('direccion')"
                      type="error"
                    >{{errors.first('direccion')}}</v-alert>
                  </v-flex>
                </v-layout>

                <v-layout wrap>
                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('telefono_fijo') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.telefono_fijo"
                      name="telefono_fijo"
                      label="Telefono Fijo"
                      id="telefono_fijo"
                      v-validate="{regex: /(02)([1-9]{2})(-[0-9]{7})$/}"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('telefono_fijo')"
                      type="error"
                    >{{errors.first('telefono_fijo')}}</v-alert>
                  </v-flex>

                  <v-flex xs6>
                    <v-text-field
                      :color="errors.has('telefono_celular') ? 'error' : 'teal darken-1'"
                      v-model="trabajador.telefono_celular"
                      name="telefono_celular"
                      label="Telefono Celular"
                      id="telefono_celular"
                      v-validate="{regex: /(04)(12|14|24|16|26)(-[0-9]{7})$/}"
                    ></v-text-field>
                    <v-alert
                      v-show="errors.has('telefono_celular')"
                      type="error"
                    >{{errors.first('telefono_celular')}}</v-alert>
                  </v-flex>
                </v-layout>
                <v-toolbar dark color="teal darken-1" dense>
                  <v-toolbar-title>Salario</v-toolbar-title>
                  <v-spacer></v-spacer>
                </v-toolbar>
                <v-checkbox v-model="isSalarioMinimo" label="Salario Minimo?"></v-checkbox>

                <v-text-field
                  v-if="isSalarioMinimo == false"
                  :color="errors.has('salario') ? 'error' : 'teal darken-1'"
                  v-model="salario.salario"
                  label="Salario mensual"
                  name="salario"
                  id="salario"
                  v-validate="`${isSalarioMinimo ? '' : 'required'}|decimal:2`"
                ></v-text-field>

                <v-alert v-show="errors.has('salario')" type="error">{{errors.first('salario')}}</v-alert>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="dialogUpd = false">Cerrar</v-btn>
              <v-btn type="submit" dark color="teal darken-1">Guardar</v-btn>
            </v-card-actions>
          </form>
          <!-- Fin form-->
        </v-card>
      </v-dialog>
    </v-layout>

    <v-layout row justify-center>
      <v-dialog v-model="dialogDis" persistent max-width="300">
        <v-card class="elevation-12">
          <!-- Header card -->
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>Inhabilitar Trabajador</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click.native="dialogDis = false" icon flat>
              <v-icon medium>fa-times-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <v-text-field
              color="teal darken-1"
              v-model="fechaEgreso"
              name="fecha_disable"
              label="Fecha Egreso"
              id="fecha_disable"
              type="date"
              required
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="inaTrabajador()" dark color="teal darken-1">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
  </v-layout>
</template>

<script>
import axios from "axios";
const moment = require("moment");

export default {
  name: "AllTrabajadores",
  data() {
    return {
      search: "",
      headers: [
        { text: "Cédula", value: "cedula" },
        { text: "Nombres", value: "nombres" },
        { text: "Apellidos", value: "apellidos" },
        { text: "Cargo", value: "cargo" },
        { text: "Fecha Ingreso", value: "fecha_ingreso" },
        { text: "Acciones", align: "center", value: "cedula", sortable: false }
      ],
      trabajadores: [],
      idE: this.$store.state.empresa.id,
      dialogVer: false,
      dialogUpd: false,
      trabajador: {},
      idTrabajadorEdit: null,
      salario: {},
      isSalarioMinimo: null,
      sexo: ["Masculino", "Femenino"],
      alertSuc: false,
      messageSuc: null,
      alertUpd: false,
      alertUpdMsg: "",
      idDisable: null,
      fechaEgreso: null,
      dialogDis: false
    };
  },
  created() {
    const dict = {
      custom: {
        cedula: {
          required: "No debe ser vacio",
          regex:
            "Debe cumplir con el formato especificado. Ej: Vxxxxxxxx, Exxxxxxxx"
        },
        nombre1: {
          required: "No debe ser vacio",
          alpha: "Solo debe contener letras."
        },
        nombre2: {
          required: "No debe ser vacio",
          alpha: "Solo debe contener letras."
        },
        apellido1: {
          required: "No debe ser vacio",
          alpha: "Solo debe contener letras."
        },
        apellido2: {
          required: "No debe ser vacio",
          alpha: "Solo debe contener letras."
        },
        direccion: {
          required: "No debe ser vacio",
          regex:
            "Solo se permite el uso de letras, numeros y los caracteres (, . / #) "
        },
        fecha_nacimiento: {
          required: "No debe ser vacio"
        },
        cargo: {
          required: "No debe ser vacio",
          regex: "No se permiten caracteres especiales."
        },
        sexo: {
          required: "No debe ser vacio"
        },
        telefono_fijo: {
          regex: "Escriba un formato de telefóno válido. Ej: 02xx-xxxxxxx"
        },
        telefono_celular: {
          regex: "Escriba un formato de telefóno válido. Ej: 04xx-xxxxxxx"
        },
        fecha_ingreso: {
          required: "No debe ser vacio"
        },
        salario: {
          required: "No debe ser vacio",
          decimal: "Debe ser un monto con solo dos decimales. Ej: xxxx.xx"
        }
      }
    };
    // or use the instance method
    this.$validator.localize("es", dict);
    this.allTrabajadores();
  },
  mounted() {
    let fechaNacimiento = moment()
      .subtract(14, "years")
      .format("YYYY-MM-DD");
    document.getElementById("fecha_nacimiento").max = fechaNacimiento;

    let fechaIngreso = moment().format("YYYY-MM-DD");
    document.getElementById("fecha_ingreso").max = fechaIngreso;

    let fechaActual = moment().format("YYYY-MM-DD");
    let fechaInicioDisable = moment()
      .subtract(7, "days")
      .format("YYYY-MM-DD");

    document.getElementById("fecha_disable").min = fechaInicioDisable;
    document.getElementById("fecha_disable").max = fechaActual;
  },
  methods: {
    newTrabajador() {
      this.$router.push({ path: "/trabajadores/nuevo" });
    },
    allTrabajadores() {
      const vm = this;

      axios
        .get(`http://payroll.com.local/api/trabajadores/all/${vm.idE}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.$data.trabajadores = res.data.trabajadores;
        })
        .catch(err => {
          console.log(err);
        });
    },
    verTrabajador(id) {
      this.dialogVer = true;

      const vm = this;

      axios
        .get(`http://payroll.com.local/api/trabajadores/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.$data.trabajador = res.data.trabajador;
          vm.$data.salario = res.data.salario;
        })
        .catch(err => {
          console.log(err);
        });
    },
    editTrabajadorModal(id) {
      this.idTrabajadorEdit = id;
      const vm = this;

      axios
        .get(`http://payroll.com.local/api/trabajadores/${id}`, {
          headers: {
            Authorization: `Bearer ${vm.$store.state.currentUser.token}`
          }
        })
        .then(res => {
          vm.$data.trabajador = res.data.trabajador;
          vm.$data.salario = res.data.salario;
          vm.$data.isSalarioMinimo = res.data.isSalarioMinimo;

          vm.$data.dialogUpd = true;
        })
        .catch(err => {
          console.log(err);
        });
    },
    editTrabajador() {
      const vm = this;
      const { trabajador, isSalarioMinimo, salario } = vm.$data;
      const data = {
        ...trabajador,
        salario: salario.salario,
        salario_minimo: isSalarioMinimo
      };
      const id = vm.$data.idTrabajadorEdit;
      this.$validator.validate().then(valid => {
        if (!valid) {
          // do stuff if not valid.
          return;
        }
        axios
          .put(`http://payroll.com.local/api/trabajadores/${id}`, data, {
            headers: {
              Authorization: `Bearer ${vm.$store.state.currentUser.token}`
            }
          })
          .then(res => {
            if (!res.data.error) {
              vm.alertSuc = true;
              vm.messageSuc = "Trabajador Modificado";
              vm.allTrabajadores();
              vm.$data.dialogUpd = false;
            } else {
              vm.alertUpd = true;
              vm.alertUpdMsg = res.data.error;
              document.getElementById("cedula").focus();
            }
          })
          .catch(err => console.log(err));
      });
    },
    updSuc() {
      console.log("se modifico");
      this.dialogUpd = false;
      this.allTrabajadores();
    },
    inaTrabajador() {
      let confirm = window.confirm(
        "¿Seguro que quiere inhabilitar al trabajador?\n Al inhabilitarlo no podra volverlo habilitar."
      );

      if (confirm) {
        const vm = this;

        axios
          .put(
            `http://payroll.com.local/api/trabajadores/disable/${vm.idDisable}`,
            {
              fecha_egreso: vm.fechaEgreso
            },
            {
              headers: {
                Authorization: `Bearer ${vm.$store.state.currentUser.token}`
              }
            }
          )
          .then(res => {
            vm.alertSuc = true;
            vm.messageSuc = "Trabajador Inhabilitado";

            vm.allTrabajadores();
          })
          .catch(err => console.log(err));

        this.dialogDis = false;
      }
    },
    disable(id) {
      this.dialogDis = true;
      this.idDisable = id;
    }
  },
  filters: {
    capitalize(value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
    dateFormat(value) {
      if (!value) return "";

      value = moment(value, "YYYY-MM-DD").format("DD/MM/YYYY");
      return value;
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

