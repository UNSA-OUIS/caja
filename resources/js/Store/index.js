import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

import config from "../config";
const axios = require("axios");

export default new Vuex.Store({
  state: {
    show_sidebar: true,
    array_estado_menu: [false, false, false, false, false, false],
    envio: {},
    entrada_antigua: "",
  },
  mutations: {
    SET_SHOW_SIDEBAR(state, show_sidebar) {
        state.show_sidebar = show_sidebar
    },
    SET_ESTADO_MENU(state, array_estado_menu) {
        state.array_estado_menu = array_estado_menu
    },
    SET_ENVIO(state, envio) {
      state.envio = envio;
    },
    SET_ENTRADA_ANTIGUA(state, entrada_antigua) {
        state.entrada_antigua = entrada_antigua;
      },
  },
  actions: {
    setShowSideBar({commit}, show_sidebar) {
        commit('SET_SHOW_SIDEBAR', show_sidebar)
    },
    setEstadoMenu({commit}, array_estado_menu) {
        commit('SET_ESTADO_MENU', array_estado_menu)
    },
    setEntradaAntigua({commit}, entrada_antigua) {
        commit('SET_ENTRADA_ANTIGUA', entrada_antigua)
    },
    getEstados({commit}) {
      axios.get(`${config.APP_URL}/getEstados`)
        .then(response => {
          commit('SET_ENVIO', response.data)
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
    getEntradaAntigua: state => {
        return state.entrada_antigua;
      },
  },
  modules: {
  }
})
