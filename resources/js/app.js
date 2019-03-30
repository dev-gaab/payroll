import Vue from 'vue'
import Main from './Main'
import router from './router'
import storeData from './store'
import Vuex from 'vuex'
import VeeValidate from 'vee-validate'
// Vuetify
import Vuetify from 'vuetify'
// css vuetify
import 'vuetify/dist/vuetify.min.css'
// roboto fontface
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

import '@fortawesome/fontawesome-free/css/all.css'
// // custom css
// import './assets/css/index.css'

Vue.use(Vuex);
Vue.use(Vuetify);
Vue.use(VeeValidate);

const store = new Vuex.Store(storeData);

router.beforeEach((to, from, next) => {
	const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
	const currentUser = store.state.currentUser;

	if (requiresAuth && !currentUser) {
		next('/login');
	} else if(to.path == '/login' && currentUser){
		next('/');
	} else {
		next();
	}

});

router.afterEach((to, from) => {
	const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
	const currentUser = store.state.currentUser;
	const empresa =  store.state.empresa;
	if(requiresAuth
		&& currentUser
		&& to.path != '/login'
		&& to.path != '/'
		&& !empresa) {
		// si es una ruta diferente de "login", diferente de la ruta "/", ya esta logueado pero no existe una empresa activa.
		router.push({path: '/'})
	}

});
/* eslint-disable no-new */
new Vue({
	el: '#app',
	router,
	components: { Main },
	template: '<Main/>',
	store
})
