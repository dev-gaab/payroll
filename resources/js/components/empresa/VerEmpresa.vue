<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500">
      <v-card>
        <v-card-title class="headline">{{empresa.razon_social}}</v-card-title>
        <v-list two-line>

          <v-list-tile>

            <v-list-tile-content>

              <v-list-tile-title>Rif</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{empresa.rif}}</span>
                </v-list-tile-sub-title>

            </v-list-tile-content>

          </v-list-tile>
          <v-divider></v-divider>

          <v-list-tile>

            <v-list-tile-content>

              <v-list-tile-title>Dirección</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{empresa.direccion}}</span>
                </v-list-tile-sub-title>
                
            </v-list-tile-content>

          </v-list-tile>
          <v-divider></v-divider>

           <v-list-tile>

            <v-list-tile-content>

              <v-list-tile-title>Riesgo IVSS</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{empresa.riesgo_ivss}}</span>
                </v-list-tile-sub-title>
                
            </v-list-tile-content>

          </v-list-tile>
          <v-divider></v-divider>

          <v-list-tile>

            <v-list-tile-content>

              <v-list-tile-title>Num. Afiliación IVSS</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{empresa.num_afiliacion_ivss}}</span>
                </v-list-tile-sub-title>
                
            </v-list-tile-content>

          </v-list-tile>
          <v-divider></v-divider>

          <v-list-tile>

            <v-list-tile-content>

              <v-list-tile-title>Fecha de Inscripción IVSS</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{empresa.fecha_inscripcion_ivss}}</span>
                </v-list-tile-sub-title>
                
            </v-list-tile-content>

          </v-list-tile>
          <v-divider></v-divider>

          <v-list-tile>

            <v-list-tile-content>

              <v-list-tile-title>Num. Afiliación FAOV</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{empresa.num_afiliacion_faov}}</span>
                </v-list-tile-sub-title>
                
            </v-list-tile-content>

          </v-list-tile>
          <v-divider></v-divider>


          <v-list-tile>

            <v-list-tile-content>

              <v-list-tile-title>Num. Afiliación INCES</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{empresa.num_afiliacion_inces}}</span>
                </v-list-tile-sub-title>
                
            </v-list-tile-content>
            
          </v-list-tile>
          <v-divider></v-divider>

           <v-list-tile>

            <v-list-tile-content>

              <v-list-tile-title>Estatus</v-list-tile-title>

                <v-list-tile-sub-title>
                  <span class='text--primary'>{{empresa.estatus}}</span>
                </v-list-tile-sub-title>
                
            </v-list-tile-content>
            
          </v-list-tile>

        </v-list>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click.native="dialog = false">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import axios from 'axios';

export default {
    name: 'VerEmpresa',
    props: ['id','dialog', 'empresa']
}
</script>
