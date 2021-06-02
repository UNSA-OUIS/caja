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
      :busy="isBusy"
      :items="myProvider"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
      empty-text="No hay facturas para mostrar"
      empty-filtered-text="No hay facturas que coincidan con su búsqueda."
    >
      <template #table-busy>
        <div class="text-center text-success my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong v-if="!enviado">Cargando ...</strong>
          <strong v-else>Enviando facturas a sunat</strong>
        </div>
      </template>
      <template v-slot:cell(fecha)="row">
            <span>
              {{ row.item.created_at.substring(0,10) }}
            </span>
          </template>
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
        >Se encontraron {{ totalRows }} facturas
      </template>
    </b-table>
    <b-row>
      <b-col offset-md="8" md="4" class="my-1">
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
    <b-row>
      <b-button
        v-if="items != ''"
        variant="success"
        title="Enviar facturas a sunat"
        @click="enviar_facturas()"
      >
        Enviar Facturas a Sunat
        <b-icon icon="cloud-arrow-up"></b-icon>
      </b-button>
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
      items: [],
      isBusy: false,
      enviado: false,
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
          key: "fecha",
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
      perPage: 50,
      pageOptions: [50, 100, 150],
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
        this.items = response.data.data;
        this.totalRows = response.data.total;

        return this.items || [];
      });
    },
    enviar_facturas() {
      console.log(this.items);
      this.enviado = true;
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer enviar estas facturas?", {
          title: "Enviar facturas",
          okVariant: "success",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then(async (value) => {
          if (value) {
            axios
              .post(`${this.app_url}/sunat/enviarFacturas`, this.items)
              .then((response) => {
                console.log(response.data);
                if (!response.data.error) {
                  console.log(response.data.error);
                  console.log(response.data.successMessage);
                  this.$bvToast.toast("Facturas enviadas con exito", {
                    title: "Envio de facturas a sunat",
                    variant: "success",
                    toaster: "b-toaster-bottom-right",
                    solid: true,
                  });
                } else {
                  console.log(response.data.error);
                  console.log(response.data.errorMessage);
                  this.$bvToast.toast("Hubo un error al enviar las facturas", {
                    title: "Error al enviar las facturas",
                    variant: "danger",
                    toaster: "b-toaster-bottom-right",
                    solid: true,
                  });
                }
              })
              .catch(function (error) {
                console.log(error);
              });
            this.refreshTable();
          }
        });
    },
    anular(comprobante) {
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer anular este comprobante?", {
          title: "Anular comprobante",
          okVariant: "danger",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then(async (value) => {
          if (value) {
            this.$inertia.post(route("facturas.anular", [comprobante]));
            this.refreshTable();
          }
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
