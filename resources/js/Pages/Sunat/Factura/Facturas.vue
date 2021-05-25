<template>
  <div>
    <b-table
      ref="tbl_facturas"
      show-empty
      striped
      hover
      bordered
      small
      responsive
      stacked="md"
      :items="myProvider"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
      empty-text="No hay fac para mostrar"
      empty-filtered-text="No hay usuarios que coincidan con su búsqueda."
    >
      <template v-slot:cell(estado)="row">
        <b-badge v-if="row.item.estado == 'noEnviado'" variant="primary"
          >No Enviado</b-badge
        >
        <b-badge v-if="row.item.estado == 'observado'" variant="warning"
          >Observado
        </b-badge>
        <b-badge v-if="row.item.estado == 'rechazado'" variant="danger"
          >Rechazado</b-badge
        >
        <b-badge v-if="row.item.estado == 'anulado'" variant="secondary"
          >Anulado</b-badge
        >
        <div v-if="row.item.estado == 'aceptado'">
          <b-badge variant="success">Aceptado</b-badge>
          <br />
          <a :href="`${app_url}/${row.item.url_xml}`" download>XML</a>
          <a :href="`${app_url}/${row.item.url_cdr}`" download>CDR</a>
        </div>
      </template>

      <template v-slot:cell(acciones)="row">
        <b-button
          v-if="row.item.estado == 'noEnviado'"
          variant="danger"
          size="sm"
          title="Anular"
          @click="anular(row.item)"
        >
          <b-icon icon="x-circle"></b-icon>
        </b-button>
        <b-button
          v-if="row.item.estado == 'observado' || row.item.estado == 'aceptado'"
          size="sm"
          @click="row.toggleDetails"
        >
          <b-icon v-if="row.detailsShowing" icon="dash-circle"></b-icon>
          <b-icon v-else icon="plus-circle"></b-icon>
        </b-button>
      </template>
      <template #row-details="row">
        <b-card>
          <ul>
            <li>
              {{ row.item.observaciones }}
            </li>
          </ul>
        </b-card>
      </template>
      <template #table-caption
        >Se encontraron {{ totalRows }} facturas</template
      >
    </b-table>
    <b-row>
      <b-col class="ml-auto">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          align="fill"
          size="sm"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>
  </div>
</template>
<script>
const axios = require("axios");

export default {
  name: "facturas.listar",
  props: ["fecha_inicio", "fecha_fin"],
  data() {
    return {
      app_url: this.$root.app_url,
      fields: [
        {
          key: "codi_usuario",
          label: "RUC",
          class: "text-center",
          sortable: true,
        },
        {
          key: "comprobanteable.razon_social",
          label: "Razon Social",
          class: "text-left",
          sortable: true,
        },
        { key: "serie", label: "Serie", class: "text-center", sortable: true },
        {
          key: "correlativo",
          label: "Correlativo",
          class: "text-center",
          sortable: true,
        },
        {
          key: "created_at",
          label: "Fecha de Creacion",
          class: "text-center",
          sortable: true,
        },
        {
          key: "estado",
          label: "Estado",
          class: "text-center",
          sortable: true,
        },
        { key: "acciones", label: "Acciones", class: "text-center" },
      ],
      totalRows: 1,
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 15],
    };
  },
  methods: {
    refreshTable() {
      this.$refs.tbl_facturas.refresh();
    },
    myProvider(ctx) {
      let params = {
        fecha_inicio: this.fecha_inicio,
        fecha_fin: this.fecha_fin,
        page: ctx.currentPage,
        size: ctx.perPage,
      };
      const promise = axios.get(`${this.app_url}/sunat/listarFacturas`, {
        params,
      });

      return promise.then((response) => {
        const facturas = response.data.data;
        this.totalRows = response.data.total;

        return facturas || [];
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
