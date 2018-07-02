/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue'
import App from './backend.vue'
import router from './router/index.js'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

import axios from 'axios'
import VueAxios from 'vue-axios'

import VueQuillEditor from 'vue-quill-editor'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'

import '../css/global.css'
Vue.use(ElementUI)
Vue.use(VueQuillEditor)
Vue.use(VueAxios, axios)
Vue.use(ElementUI)


router.beforeEach((to, from, next) => {
	if (to.path == '/login'){
		localStorage.clear('token')
		localStorage.clear('tokenTime')
		next()
	}else{
		if(localStorage.token){
			var tokenTime = new Date()
			if((tokenTime.getTime() - localStorage.tokenTime) > 7200000){
				localStorage.clear('token')
				localStorage.clear('tokenTime')
				next({path:'/login'})
			}else{
				next()
			}
		}else{
			next({path:'/login'})
		}
	}
});

router.afterEach((transition) => {
	document.title = '欢迎光临，请问先生需要什么服务呢~(* ￣3)(ε￣ *)';
});

const app = new Vue({
    el: '#app',
    router,
    render: h=>h(App)
});