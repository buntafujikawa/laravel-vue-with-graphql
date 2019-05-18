import Vue from 'vue'
import Vuex from 'vuex'
import router from './router'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    loggedIn: localStorage.getItem("vue_token") ? true : false,
    error: false
  },
  mutations: {
    loggedIn(state, payload) {
      state.loggedIn = true;
    },
    error(state, payload) {
      router.push("login");
    }
  },
  actions: {

  }
})
