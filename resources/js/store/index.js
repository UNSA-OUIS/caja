import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    array_estado_menu: [false, false, false, false],    
  },
  mutations: {
    SET_ESTADO_MENU(state, array_estado_menu) {
        state.array_estado_menu = array_estado_menu
    },    
  },
  actions: {
    setEstadoMenu({commit}, array_estado_menu) {
        commit('SET_ESTADO_MENU', array_estado_menu)
    },    
  },
  getters: {
    getEstadosMenu: state => {
      return state.array_estado_menu;
    },    
  },
  modules: {
  }
})
