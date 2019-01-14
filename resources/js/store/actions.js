// actions types

export const LOGIN = 'LOGIN';
export const LOGOUT = 'LOGOUT';
export const ACTIVATE_EMPRESA= 'ACTIVE_EMPRESA';

// actions creators

export function login(isAdmin, user) {
    return {
        type: LOGIN,
        isAdmin,
        user
    }
}

export function logout() {
    return {
        type: LOGOUT
    }
}

export function activateEmpresa(empresa){
    return {
        type: ACTIVATE_EMPRESA,
        empresa
    }
}