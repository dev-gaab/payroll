let user = sessionStorage.getItem('user');
let currentEmpresa = sessionStorage.getItem('empresa');

if (!user) {
  user = null;
} else {
  user = JSON.parse(user);
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

    async loginSuccess(state, payload) {
      state.auth_error = null;
      state.isLoggedIn = true;
      state.loading = false;

      state.currentUser = {
        user: payload.user,
        token: payload.token,
        isAdmin: payload.isAdmin
      };

      await sessionStorage.setItem("user", JSON.stringify(state.currentUser));
    },

    loginFailed(state, payload) {
      state.loading = false
      state.auth_error = payload.error
    },

    logout(state) {
      sessionStorage.clear()
      state.isLoggedIn = false
      state.currentUser = null
      state.empresa = null
    },

    activarEmpresa(state, payload) {
      state.empresa = {
        id: payload.id,
        nombre: payload.nombre
      };

      sessionStorage.setItem("empresa", JSON.stringify(state.empresa));
    }
  },
  actions: {
    login(context) {
      context.commit('login');
    }
  }
};
