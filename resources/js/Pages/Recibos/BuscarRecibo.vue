<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Buscar recibo</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Buscar recibo</span>
      </div>
      <div class="card-body">
          <b-alert
                show
                dismissible
                variant="success"
                v-if="$page.props.successMessage"
                >{{ $page.props.successMessage }}</b-alert
                >
                <b-alert
                show
                dismissible
                variant="danger"
                v-if="$page.props.errorMessage"
                >{{ $page.props.errorMessage }}</b-alert
                >
        <div class="row justify-content-center mb-1">
          <fieldset class="col-12 col-md-7 px-3">
            <legend>Número de recibo:</legend>
            <div class="row justify-content-center">
              <b-form inline>
                <b-form-input
                  v-model="nroRecibo"
                  placeholder="Número de recibo"
                  size="sm"
                  id="inline-form-input-numero-recibo"
                  class="mb-2 mr-sm-2 mb-sm-0"
                  trim
                ></b-form-input>
                <template>
                  <div>
                    <b-button-group>
                      <b-button
                        size="sm"
                        variant="outline-success"
                        @click="buscar_numero_recibo"
                      >
                        Buscar <b-icon icon="search"></b-icon>
                      </b-button>
                      <b-button
                        size="sm"
                        variant="outline-primary"
                        @click="limpiar"
                      >
                        Limpiar <b-icon icon="arrow-clockwise"></b-icon>
                      </b-button>
                    </b-button-group>
                  </div>
                </template>
              </b-form>
            </div>
          </fieldset>
        </div>
        <div class="card-body" >
          <b-table
                    ref="tbl_clasificadores"
                    show-empty
                    striped
                    hover
                    sticky-header
                    bordered
                    small
                    responsive
                    :items="clasificadores"
                    :fields="fields"
                    empty-text="No hay clasificadores para mostrar"
                    empty-filtered-text="No hay clasificadores que coincidan con su búsqueda.">
                    <template v-slot:cell(descripcion)="row">
                        {{ row.item.descripcion }} ({{ row.item.cantidad }})
                    </template>
                    <template v-if="clasificadores.length" slot="bottom-row" slot-scope="">
                        <b-td class="text-right font-weight-bold">{{totalRegistros}} registros</b-td>
                        <b-td class="text-right font-weight-bold">TOTALES:</b-td>
                        <b-td class="text-right font-weight-bold">{{totalMontos}}</b-td>
                    </template>
                </b-table>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "sunat.listarBoletas",
  components: {
    AppLayout,
  },
  data() {
    return {
        app_url: this.$root.app_url,
        recibo: [],
        fields: [
                { key: "clasificador_id", label: "Código" },
                { key: "descripcion", label: "Concepto" },
                { key: "subtotal", label: "Importe" ,class: "text-right" },
            ],
        nroRecibo: "",
        clasificadores: [],
        totalRegistros: 0,
        totalMontos: 0,
        index: 1,
        totalRows: 1,
        currentPage: 1,
        perPage: 5,
        pageOptions: [5, 10, 15],
        sortBy: null,
        sortDesc: false,
        sortDirection: "asc",
        filter: null,
    };
  },
  methods: {
      async buscar_numero_recibo() {
            try {
                let params = "?nroRecibo=" + this.nroRecibo
                const response = await axios.get(`${this.app_url}/recibo-ingreso/buscar-recibo/${params}`)
                this.clasificadores = response.data.clasificadores
                this.totalRegistros = response.data.totalRegistros
                this.totalMontos = response.data.totalMontos
                
            } catch (error) {
                console.log(error)
            }
        },
    limpiar() {
        this.nroRecibo = ""
    },
  },
};
</script>

<style scoped>
fieldset {
  border-radius: 4px;
  border: 1px solid #ddd;
  background-color: #fff;
  padding-bottom: 10px;
  height: auto;
}

legend {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 600;
  padding: 3px 5px 3px 7px;
  width: auto;
}

.breadcrumb li a {
  color: blue;
}

.breadcrumb {
  margin-bottom: 0;
  background-color: white;
}
.breadcrumb li a {
  color: blue;
}
.breadcrumb {
  margin-bottom: 0;
  background-color: white;
}
</style>
