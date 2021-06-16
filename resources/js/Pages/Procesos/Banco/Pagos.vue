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
        {{ row.item.fpago}}
      </template>

      <template v-if="totalRows != ''" #table-caption
        >Se encontraron {{ totalRows }} registros
      </template>
    </b-table>
  </div>
</template>
<script>
const axios = require("axios");

export default {
  name: "boletas.listar",
  props: ["fecha_inicio", "fecha_fin"],
  data() {
    return {
      app_url: this.$root.app_url,
      items: [],
      selected: [],
      isBusy: false,
      enviado: false,
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
        console.log(response);
        this.items = response.data;
        console.log(this.items);
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
