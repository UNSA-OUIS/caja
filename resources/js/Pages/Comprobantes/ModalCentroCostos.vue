<template>
  <div>
    <b-button size="sm" v-b-modal="'' + index"  variant="info" title="Agregar">+ Centro Costo</b-button>

    <b-modal
      :id="'' + index"
      ref="modal"
      title="Agregar Centro de costos"
      @ok="handleOk"
      ok-only
    >    
        <v-select
            v-model="centroCosto"
            @search="buscarCentroCosto"
            :filterable="false"
            :options="centrosCosto"
            :reduce="centroCosto => centroCosto"
            label="vista_centroCosto"
            placeholder="Ingrese código o nombre del centro de costo"
        >
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!centroCosto"
                    v-bind="attributes"
                    v-on="events"
                    v-model="filtro"
                />
            </template>
            <template slot="no-options">
                Lo sentimos, no hay resultados de coincidencia.
            </template>
        </v-select>
    </b-modal>
  </div>
</template>

<script>
const axios = require('axios')
export default {
    props: ["comprobante", "index"],
    data() {
        return {
            centroCosto: null,
            centrosCosto: [],
            filtro: "",
            app_url: this.$root.app_url,
        }
    },
    watch : {
        filtro:function(val) {
            this.filtro = val.trim()
        },
        centroCosto:function(val) {
            this.filtro = ""
        },
    },
    methods: {
        buscarCentroCosto(search, loading) {
            loading(true)
            axios.get(`${this.app_url}/buscarCentroCosto?filtro=${search}`)
                .then(response => {
                    this.centrosCosto = response.data;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error)
                });
        },
        handleOk () {
            this.comprobante.detalles[this.index].codi_depe = this.centroCosto.codi_depe
        }
    }
}
</script>