let user = localStorage.getItem('user');
let currentEmpresa = localStorage.getItem('empresa');

if (!user) {
  user = null;
}

if (!currentEmpresa) {
  currentEmpresa = null;
} else {
  currentEmpresa = JSON.parse(currentEmpresa);
}

export default {

  state: {
    currentUser: user,
    isLoggedIn: !!user,
    loading: false,
    auth_error: null,
    empresa: currentEmpresa
  },
  getters: {
    isLoading(state) {
      return state.loading
    },

    isLoggedIn(state) {
      return state.isLoggedIn
    },

    currentUser(state) {
      return state.currentUser
    },

    authError(state) {
      return state.auth_error
    },

    empresa(state) {
      return state.empresa
    }
  },
  mutations: {
    login(state) {
      state.loading = true;
      state.auth_error = null;
    },

    loginSuccess(state, payload) {
      state.auth_error = null;
      state.isLoggedIn = true;
      state.loading = false;

      state.currentUser = {
        user: payload.user,
        token: payload.token
      };

      localStorage.setItem("user", JSON.stringify(state.currentUser));
    },

    loginFailed(state, payload) {
      state.loading = false
      state.auth_error = payload.error
    },

    logout(state) {
      localStorage.removeItem("user")
      state.isLoggedIn = false
      state.currentUser = null
    },
    
    activarEmpresa(state, payload) {
      state.empresa = {
        id: payload.id,
        nombre: payload.nombre
      };

      localStorage.setItem("empresa", JSON.stringify(state.empresa));
    }
  },
  actions: {
    login(context) {
      context.commit('login');
    }
  }
};