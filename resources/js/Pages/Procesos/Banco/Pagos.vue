<template>
  <div>
    <b-table
      ref="tbl_boletas"
      show-empty
      striped
      hover
      bordered
      small
      responsive
      stacked="md"
      :busy="isBusy"
      :items="myProvider"
      :fields="fields"
      empty-text="No hay boletas para mostrar"
      empty-filtered-text="No hay boletas que coincidan con su búsqueda."
    >
      <template #table-busy>
        <div class="text-center text-success my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong v-if="!enviado">Cargando ...</strong>
          <strong v-else>Enviando resumen diario a sunat</strong>
        </div>
      </template>
      <template v-slot:cell(frecepcion)="row">
        {{ row.item.frecepcion }}
      </template>
      <template v-slot:cell(fpago)="row">
        {{ row.item.fpago }}
      </template>

      <template v-if="totalRows != ''" #table-caption
        >Se encontraron {{ totalRows }} registros
      </template>
    </b-table>
    <div class="card-body">
      <div class="row justify-content-center mb-1">
        <b-form inline>
          <b-form-select
            v-if="items != ''"
            v-model="proceso"
            :options="procesos"
          >
            <template #first>
              <b-form-select-option :value="null" disabled
                >-- Elija un Proceso --</b-form-select-option
              >
            </template>
          </b-form-select>
          &nbsp;&nbsp;
          <b-button
            v-if="items != ''"
            variant="outline-success"
            @click="procesar_pagos()"
          >
            Procesar Pagos <b-icon icon="search"></b-icon>
          </b-button>
        </b-form>
      </div>
    </div>
  </div>
</template>
<script>
const axios = require("axios");

export default {
  name: "procesos.pagos",
  props: ["fecha_inicio", "fecha_fin"],
  data() {
    return {
      app_url: this.$root.app_url,
      items: [],
      selected: [],
      isBusy: false,
      enviado: false,
      procesos: [
        { value: 1, text: "Admision" },
        { value: 2, text: "Extraordinario" },
        { value: 3, text: "Posgrado" },
        { value: 4, text: "2DA Especialidad" },
        { value: 5, text: "Matricula Pregrado" },
        { value: 6, text: "Regularizacion Expediente" },
        { value: 7, text: "Deuda - CEPREUNSA" },
        { value: 8, text: "Centro de Idiomas" },
        { value: 9, text: "Residentado Medicos" },
      ],
      proceso: null,
      fields: [
        { key: "concepto", label: "Concepto", class: "text-center" },
        {
          key: "fecha_recepcion",
          label: "Fecha de recepcion",
          class: "text-center",
        },
        { key: "fecha_pago", label: "Fecha de pago", class: "text-center" },
        { key: "cantidad", label: "Cantidad", class: "text-center" },
        {
          key: "monto_acumulado",
          label: "Monto Acumulado",
          class: "text-center",
        },
      ],
      totalRows: "",
    };
  },
  methods: {
    refreshTable() {
      this.$refs.tbl_boletas.refresh();
    },
    myProvider() {
      let params = {
        fecha_inicio: this.fecha_inicio,
        fecha_fin: this.fecha_fin,
      };
      const promise = axios.get(`${this.app_url}/banco/listar`, {
        params,
      });

      return promise.then((response) => {
        this.items = response.data;
        this.totalRows = response.data.length;

        return this.items || [];
      });
    },
    procesar_pagos() {
      const promise = axios.post(`${this.app_url}/banco/procesar_pagos`, {
        fecha_inicio: this.fecha_inicio,
        fecha_fin: this.fecha_fin,
        proceso: this.proceso,
      });

      return promise.then((response) => {
        console.log(response.data);
        this.items = response.data;
        this.totalRows = response.data.length;

        return this.items || [];
      });
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    toggleBusy() {
      this.isBusy = !this.isBusy;
    },
  },
};
</script>
