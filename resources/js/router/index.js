import Vue from "vue";
import Router from "vue-router";
import Home from "../components/Home";
import Login from "../components/Login";
import AllEmpresas from "../components/empresa/AllEmpresas";
import NewEmpresa from "../components/empresa/NewEmpresa";
import AllBases from "../components/bases/AllBases";
import AllTrabajadores from "../components/trabajador/AllTrabajadores";
import NewTrabajador from "../components/trabajador/NewTrabajador";
import AllNominas from "../components/nomina/AllNominas";
import NominaDetalle from "../components/nomina/NominaDetalle";
import User from "../components/users/User";
import NewUser from "../components/users/NewUser";
import Vacaciones from "../components/vacaciones/Vacaciones";
import NewVacaciones from "../components/vacaciones/NewVacaciones";
import VacacionesFrac from "../components/vacaciones/VacacionesFrac";
import Utilidades from "../components/utilidades/Utilidades";
import Prestaciones from "../components/prestaciones/Prestaciones";
import ReportesEmpresa from "../components/reportes/ReportesEmpresa";
import Help from "../components/Help";

Vue.use(Router);
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
      path: "/",
      name: "Home",
      component: Home,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/login",
      name: "Login",
      component: Login
    },
    {
      path: "/empresas",
      name: "Empresas",
      component: AllEmpresas,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/empresas/nueva",
      name: "NewEmpresa",
      component: NewEmpresa,
      meta: {
        requiresAuth: true
      }
    },
    // rutas de bases legales
    {
      path: "/bases",
      name: "Bases",
      component: AllBases,
      meta: {
        requiresAuth: true
      }
    },
    // rutas para trabajadores.
    {
      path: "/trabajadores",
      name: "Trabajadores",
      component: AllTrabajadores,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/trabajadores/nuevo",
      name: "NewTrabajador",
      component: NewTrabajador,
      meta: {
        requiresAuth: true
      }
    },
    // Rutas para nomin
    {
      path: "/nominas",
      name: "Nominas",
      component: AllNominas,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/nominas/detalle/:id",
      name: "NominaDetalle",
      component: NominaDetalle,
      meta: {
        requiresAuth: true
      }
    },
    // users
    {
      path: "/users",
      name: "Users",
      component: User,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/users/nuevo",
      name: "NewUser",
      component: NewUser,
      meta: {
        requiresAuth: true
      }
    },
    // Vacaciones
    {
      path: "/vacaciones",
      name: "Vacaciones",
      component: Vacaciones,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/vacaciones/nueva",
      name: "NewVacaciones",
      component: NewVacaciones,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/vacaciones/fraccionadas",
      name: "VacacionesFrac",
      component: VacacionesFrac,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/utilidades",
      name: "Utilidades",
      component: Utilidades,
      meta: {
        requiresAuth: true
      }
    },
    // {
    //   path: '/prestaciones',
    //   name: 'Prestaciones',
    //   component: Prestaciones,
    //   meta: {
    //     requiresAuth: true
    //   }
    // },
    // reportes
    {
      path: "/reportes/empresa",
      name: "ReportesEmpresa",
      component: ReportesEmpresa,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/ayuda",
      name: "Ayuda",
      component: Help,
      meta: {
        requiresAuth: true
      }
    }
  ],
  mode: "history"
});
