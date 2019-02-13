import Vue from 'vue'
import Main from './Main'
import router from './router'
import storeData from './store'
import Vuex from 'vuex'

// Vuetify
import Vuetify from 'vuetify'
// css vuetify
import 'vuetify/dist/vuetify.min.css'
// roboto fontface
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
// // Bootstrap y jquery
// import 'bootstrap/dist/css/bootstrap.min.css'
// import 'jquery/dist/jquery.min.js'
// import 'bootstrap/dist/js/bootstrap.min.js'
// fontawesome icons..
import '@fortawesome/fontawesome-free/css/all.css'
// // custom css
// import './assets/css/index.css'

Vue.use(Vuex);
Vue.use(Vuetify);

const store = new Vuex.Store(storeData);

router.beforeEach( (to, from, next) => {
	const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
	const currentUser = store.state.currentUser;

	if (requiresAuth && !currentUser) {
		next('/login');
	} else if(to.path == '/login' && currentUser){
		next('/');
	} else{
		next();
	}

})

/* eslint-disable no-new */
new Vue({
	el: '#app',
	router,
	components: { Main },
	template: '<Main/>',
	store
})
