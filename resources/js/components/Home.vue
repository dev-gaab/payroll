<template>
  <v-container fluid fill-height>
    <v-layout v-if="$store.state.empresa != null" justify-center >
        <v-card class="elevation-12" width="100%">
          <v-toolbar dark color="teal darken-1" dense>
            <v-toolbar-title>
              <h3 class="text-md-center">Acerca del Sistema</h3>
            </v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>

          <v-card-text>
            <v-container grid-list-md>
              <v-list two-line>
                <v-layout wrap>
                  <v-flex xs12 md4>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong>Nombre del sistema</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                  <v-flex xs12 md8>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong>  Sistema de Gestión de Nómina (PAYROLL)</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>
                <v-divider></v-divider>
                <v-layout wrap>
                  <v-flex xs12 md4>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong>Versión</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                  <v-flex xs12 md8>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong> 2.0</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>

                <v-divider></v-divider>
                <v-layout wrap>
                  <v-flex xs12 md4>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong>Desarrolladores</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                  <v-flex xs12 md8>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong> Adriana Martinez; Karen Lugo; Gabriel Arias</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>

                <v-divider></v-divider>
                <v-layout wrap>
                  <v-flex xs12 md4>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong>Nombre Empresa</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                  <v-flex xs12 md8>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong> Luis Napoleón Barrios Buccé & Asociados - Barrios Buccé & Asociados</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>

                <v-divider></v-divider>
                <v-layout wrap>
                  <v-flex xs12 md4>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong>Dirección</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                  <v-flex xs12 md8>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong> Centro Comercial Porto 2000 Local N° 7, Calle Independencia entre Calle Pichincha y Calle Bolívar frente a la Plaza Bolívar de Carúpano, Parroquia Santa Catalina del Municipio Bermúdez</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>

                <v-divider></v-divider>
                <v-layout wrap>
                  <v-flex xs12 md4>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong>Teléfono</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                  <v-flex xs12 md8>
                    <v-list-tile>
                      <v-list-tile-content>
                        <strong> 0294-3321938</strong>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-flex>
                </v-layout>

                <v-divider></v-divider>
                <v-layout justify-center >
                
                   <a href="/images/manual.pdf" target="_blank">--------------------- Manual de Usuario ---------------------</a>
              
                </v-layout>
              </v-list>
            </v-container>
          </v-card-text>
        </v-card>
    </v-layout>
    <ActivateEmpresa v-else></ActivateEmpresa>
  </v-container>
</template>

<script>
import ActivateEmpresa from './empresa/ActivateEmpresa';

export default {
  name: 'Home',
  components: {
    ActivateEmpresa
  }
}
</script>

<style>
</style>
