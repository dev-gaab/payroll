<template>
  <v-layout row wrap>
    <v-flex xs12>
			<!-- Alerta -->
			<v-alert
				v-model="alertSuc"
				dismissible
				type="success"
			>
				{{messageSuc}}
      </v-alert>
			<!--Tabla de salarios minimos  -->
      <v-card>
        <v-card-title>
          <h3>Salarios Mínimos
					</h3>
          <v-spacer></v-spacer>

          <v-text-field
            color="teal accent-4"
            v-model="search"
            append-icon="fa-search"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>

          <v-spacer></v-spacer>

          <v-btn icon color="teal accent-4" dark @click="newSalarioMinimoModal">
						<v-icon>add</v-icon>
					</v-btn>
        </v-card-title>

        <v-data-table
          :headers="headers"
          :items="salarios"
          :search="search"
        >
          <template slot="items" slot-scope="props">

            <td>{{ props.item.monto }}</td>
            <td>{{ props.item.desde }}</td>
            <td>{{ props.item.hasta }}</td>
            <td>{{ props.item.estatus | capitalize}}</td>
         
          </template>

          <v-alert slot="no-results" :value="true" color="" icon="fa-exclamation-triangle">
            Tu busqueda "{{ search }}" no encontro resultados.
          </v-alert>
        </v-data-table>
      </v-card>
			<!-- Formulario para ingresar un nuevo salario minimo -->
			<v-layout row justify-center>
				<v-dialog v-model="dialog" persistent width="500">
					<v-card class="elevation-12">
						<!-- Header card -->
						<v-toolbar dark color="teal darken-1" dense>
							<v-toolbar-title>Salario Mínimo</v-toolbar-title>
							<v-spacer></v-spacer>
						</v-toolbar>
						<!-- formulario -->
						<form @submit.prevent="newSalarioMinimo">
							<v-card-text>
								<v-text-field
									color="teal darken-1"
									v-model="newSalario"
									name="salarioMinimo"
									label="Salario Mínimo"
									id="salarioMinimo"
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
    </v-flex>
  </v-layout>
</template>

<script>
	import axios from "axios";

	export default {
		name: "AllSalariosMinimo",
		data () {
			return {
				messageSuc: '',
				search: '',
				headers: [
					{ text: 'Monto', value: 'monto'},
					{ text: 'Desde', value: 'desde'},
					{ text: 'Vencimiento', value: 'hasta'},
					{ text: 'Estatus', value: 'estatus'},
				],
				salarios: [],
				idEmpresa: this.$store.state.empresa.id,
				newSalario: null,
				dialog: false,
				alertSuc: false,
			}
		},
		created () {
			this.allSalariosMinimos();
		},
		methods: {

			allSalariosMinimos () {

			},

			newSalarioMinimoModal () {

			},

			newSalarioMinimo () {

			}
		}
		
	}
</script>
