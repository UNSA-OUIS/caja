import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);
//const axios = require("axios");
import config from "../config";

export default new Vuex.Store({
  state: {
    show_sidebar: true,
    array_estado_menu: [false, false, false, false, false],        
    noEnviado: 0,
    observado: 0,
    rechazado: 0,
    anulado: 0,
    aceptado: 0
  },
  mutations: {
    SET_SHOW_SIDEBAR(state, show_sidebar) {
        state.show_sidebar = show_sidebar
    },    
    SET_ESTADO_MENU(state, array_estado_menu) {
        state.array_estado_menu = array_estado_menu
    },    
    SET_NOENVIADO(state, noEnviado) {
      state.noEnviado = noEnviado;
    }
  },
  actions: {
    setShowSideBar({commit}, show_sidebar) {
        commit('SET_SHOW_SIDEBAR', show_sidebar)
    },  
    setEstadoMenu({commit}, array_estado_menu) {
        commit('SET_ESTADO_MENU', array_estado_menu)
    },  
    getEstados({ commit }) {
      this.axios.get(`${config.APP_URL}/getEstados`).then(response => {
        console.log(response);
        commit("SET_NOENVIADO", response.data);
        /*commit('SET_OBSERVADO', response.data.observado);
        commit('SET_RECHAZADO', response.data.rechazado);
        commit('SET_ANULADO', response.data.anulado);
        commit('SET_ACEPTADO', response.data.aceptado); */
      });
    }	
  },
  getters: {
    getShowSideBar: state => {
      return state.show_sidebar;
    },    
    getEstadosMenu: state => {
      return state.array_estado_menu;
    },    
  },
  modules: {
  }
})
