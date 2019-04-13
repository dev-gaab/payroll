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
import NominaDetalle from '../components/nomina/NominaDetalle'
import User from '../components/users/User'
import NewUser from '../components/users/NewUser'

Vue.use(Router)
/**
 * [routes description]
 * @type {Array}
 */

/**
 * TODO:
 * [x] La ruta "/" sera donde se seleccione la primera vez la empresa a usar.
 * [x] Cambiar ruta "/" por home
 */
export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,

    },
    {
      path: '/empresas',
      name: 'Empresas',
      component: AllEmpresas,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/empresas/nueva',
      name: 'NewEmpresa',
      component: NewEmpresa,
      meta: {
        requiresAuth: true
      }
    },
    // rutas de bases legales
    {
      path: '/bases',
      name: 'Bases',
      component: AllBases,
      meta: {
        requiresAuth: true
      }
    },
    // rutas para trabajadores.
    {
      path: '/trabajadores',
      name: 'Trabajadores',
      component: AllTrabajadores,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/trabajadores/nuevo',
      name: 'NewTrabajador',
      component: NewTrabajador,
      meta: {
        requiresAuth: true
      }
    },
    // Rutas para nomin
    {
      path: '/nominas',
      name: 'Nominas',
      component: AllNominas,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/nominas/detalle/:id',
      name: 'NominaDetalle',
      component: NominaDetalle,
      meta: {
        requiresAuth: true
      }
    },
    // users
    {
      path: '/users',
      name: 'Users',
      component: User,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/users/nuevo',
      name: 'NewUser',
      component: NewUser,
      meta: {
        requiresAuth: true
      }
    }
  ],
  mode: 'history'
})
