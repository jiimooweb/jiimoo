import Vue from 'vue'
import Vuex from 'vuex'
import state from './state.js';
import * as getters from './getters.js';
import mutations from './mutations.js';
import actions from './actions.js';
// import auth from './modules/auth'
Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';
Vue.config.debug = debug;
Vue.config.warnExpressionErrors = false;


export default new Vuex.Store({
    state,
    getters,
    mutations,
    actions,
    // modules: {
    //     auth
    // },
    strict: debug,
})
