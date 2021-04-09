import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);
//const axios = require("axios");
import config from "../config";

export default new Vuex.Store({
    state: {
        array_estado_menu: [false, false, false],
        noEnviado: 0,
        observado: 0,
        rechazado: 0,
        anulado: 0,
        aceptado: 0
    },
    mutations: {
        SET_ESTADO_MENU(state, array_estado_menu) {
            state.array_estado_menu = array_estado_menu;
        },
        SET_NOENVIADO(state, noEnviado) {
            state.noEnviado = noEnviado;
        }
    },
    actions: {
        setEstadoMenu({ commit }, array_estado_menu) {
            commit("SET_ESTADO_MENU", array_estado_menu);
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
        getEstadosMenu: state => {
            return state.array_estado_menu;
        }
    },
    modules: {}
});
