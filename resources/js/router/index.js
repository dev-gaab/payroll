import Vue from 'vue'
import Router from 'vue-router'
import Home from '../components/Home'
import Login from '../components/Login'
import AllEmpresas from '../components/empresa/AllEmpresas'
import NewEmpresa from '../components/empresa/NewEmpresa'
import AllBases from '../components/bases/AllBases'
import AllTrabajadores from '../components/trabajador/AllTrabajadores'
import NewTrabajador from '../components/trabajador/NewTrabajador'
import AllNominas from '../components/nomina/AllNominas'
import NewNomina from '../components/nomina/NewNomina'
import NominaDetalle from '../components/nomina/NominaDetalle'

Vue.use(Router)
/**
 * [routes description]
 * @type {Array}
 *
 * #TODO
 * *Crear rutas para empresas y agregarlas en el layout..
 */
export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      // meta: {
      //   requiresAuth: true
      // }
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/empresas',
      name: 'Empresas',
      component: AllEmpresas
    },
    {
      path: '/empresas/nueva',
      name: 'NewEmpresa',
      component: NewEmpresa
    },
    // rutas de bases legales
    {
      path: '/bases',
      name: 'Bases',
      component: AllBases
    },
    // rutas para trabajadores.
    {
      path: '/trabajadores',
      name: 'Trabajadores',
      component: AllTrabajadores
    },
    {
      path: '/trabajadores/nuevo',
      name: 'NewTrabajador',
      component: NewTrabajador
    },
    // Rutas para nomina
    {
      path: '/nominas/nueva',
      name: 'NewNominas',
      component: NewNomina
    },
    {
      path: '/nominas',
      name: 'Nominas',
      component: AllNominas
    },
    {
      path: '/nominas/detalle/:id',
      name: 'NominaDetalle',
      component: NominaDetalle
    }
  ],
  mode: 'history'
})
