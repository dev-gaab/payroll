import {
    LOGIN,
    LOGOUT,
    ACTIVATE_EMPRESA
} from './actions';

/**
 *  ** Estructura basica del store **
 * 
 *  {
 *      isLoggedIn: false,
 *      isAdmin: false,
 *      user: {
 *          name: '',
 *          token: '',
 *          email: ''
 *      },
 *      empresa: {
 *          id: 1,
 *          name: ''
 *      }
 * }
 * 
 */

const iState = {
    isLoggedIn: false,
    isAdmin: false,
    user: {
        name: '',
        token: '',
        email: ''
    },
    empresa: {}
};
/**
 * Se maneja el sessionStorage para no perder
 * los datos del store de redux al recargar la pagina.
 */
if (!sessionStorage.getItem('state')){
    sessionStorage.setItem('state', JSON.stringify(iState));
}

const initialState = JSON.parse(sessionStorage.getItem('state'));

export default function appReducers (state = initialState, action) {

    switch (action.type) {

        case LOGIN:
            sessionStorage.setItem('state',
                JSON.stringify({...state,
                    isLoggedIn: true,
                    isAdmin: action.isAdmin,
                    user: action.user
                }));

            return { 
                ...state, 
                isLoggedIn: true,
                isAdmin: action.isAdmin,
                user: action.user
            }; 

        case LOGOUT:
            sessionStorage.setItem('state',
                JSON.stringify({...state,
                    isLoggedIn: false,
                    isAdmin: false,
                    user: {},
                    empresa: {}
                }));

            return {
                ...state,
                isLoggedIn: false,
                isAdmin: false,
                user: {},
                empresa: {}
            };

        case ACTIVATE_EMPRESA:
            sessionStorage.setItem('state', JSON.stringify({...state, empresa: action.empresa}));

            return { 
                ...state, 
                empresa: action.empresa
            };
    
        default:
            return state
    }
}