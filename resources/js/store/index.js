import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    array_estado_menu: [false, false, false],
    noEnviado: 0,
    observado: 0,
    rechazado: 0,
    anulado: 0,
    aceptado: 0,
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
    getEstados({commit}) {
        let formData = new FormData()
        formData.append('idproc_origen')

        axios.get(`${config.API_URL}/Ruta/getRutasByProc`, formData)
        .then(response => {
          if (!response.data.error) {
              commit('SET_NOENVIADO', response.data.noEnviado);
              commit('SET_OBSERVADO', response.data.observado);
              commit('SET_RECHAZADO', response.data.rechazado);
              commit('SET_ANULADO', response.data.anulado);
              commit('SET_ACEPTADO', response.data.aceptado);
          }
          else {
              console.log(response.data.message)
          }
        })
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
