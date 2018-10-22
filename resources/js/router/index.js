import Vue from 'vue'
import Router from 'vue-router'
import Home from '../components/Home'
import Login from '../components/Login'
import AllEmpresas from '../components/empresa/AllEmpresas'
import NewEmpresa from '../components/empresa/NewEmpresa'
import AllBases from '../components/bases/AllBases'

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
    }
  ],
  mode: 'history'
})
