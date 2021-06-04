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
      :current-page="currentPage"
      :per-page="perPage"
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
      <template v-slot:cell(fecha)="row">
        <span>
          {{ row.item.created_at.substring(0, 10) }}
        </span>
      </template>
      <template v-slot:cell(usuario)="row">
        <span v-if="row.item.tipo_usuario === 'alumno'">
          {{ row.item.comprobanteable.apn }}
        </span>
        <span v-else-if="row.item.tipo_usuario === 'empresa'">
          {{ row.item.comprobanteable.razon_social }}
        </span>
        <span v-else-if="row.item.tipo_usuario === 'particular'">
          {{ row.item.comprobanteable.apellidos }},
          {{ row.item.comprobanteable.nombres }}
        </span>
        <span v-else-if="row.item.tipo_usuario === 'docente'">
          {{ row.item.comprobanteable.apn }}
        </span>
        <span v-else-if="row.item.tipo_usuario === 'dependencia'">
          {{ row.item.comprobanteable.nomb_depe }}
        </span>
      </template>
      <template v-slot:cell(enviado)="row">
        <p v-if="row.item.enviado" class="h4 mb-2">
          <b-icon icon="check-circle" variant="success"></b-icon>
        </p>
        <p v-else class="h4 mb-2">
          <b-icon icon="x-circle" variant="danger"></b-icon>
        </p>
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
          <!--<br />
          <a :href="`${app_url}/${row.item.url_xml}`" download>XML</a>
          <a :href="`${app_url}/${row.item.url_cdr}`" download>CDR</a>-->
        </div>
      </template>

      <template v-slot:cell(acciones)="row">
        <b-button
          v-if="row.item.enviado == false"
          variant="danger"
          size="sm"
          title="Anular"
          @click="anular(row.item)"
        >
          <b-icon icon="x-circle"></b-icon>
        </b-button>
      </template>
      <template #table-caption
        >Se encontraron {{ totalRows }} boletas
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
        @click="enviar_boletas()"
      >
        Enviar boletas a Sunat
        <b-icon icon="cloud-arrow-up"></b-icon>
      </b-button>
    </b-row>
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
      isBusy: false,
      enviado: false,
      fields: [
        { key: "tipo_usuario", label: "Tipo usuario", class: "text-center" },
        { key: "codi_usuario", label: "Código usuario", class: "text-center" },
        { key: "usuario", label: "Administrado", sortable: true },
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
          key: "enviado",
          label: "Enviado",
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
      this.$refs.tbl_boletas.refresh();
    },
    myProvider(ctx) {
      let params = {
        fecha_inicio: this.fecha_inicio,
        fecha_fin: this.fecha_fin,
        page: ctx.currentPage,
        size: ctx.perPage,
      };
      const promise = axios.get(`${this.app_url}/sunat/listarBoletas`, {
        params,
      });

      return promise.then((response) => {
        this.items = response.data.data;
        this.totalRows = response.data.total;

        return this.items || [];
      });
    },
    enviar_boletas() {
      console.log(this.items);
      this.enviado = true;
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer enviar este resumen diario?", {
          title: "Enviar resumen diario",
          okVariant: "success",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then(async (value) => {
          if (value) {
            axios
              .post(`${this.app_url}/sunat/resumenDiario`, this.items)
              .then((response) => {
                console.log(response.data);
                if (response.data.error == false && response.data.successMessage == 'Resumen diario enviado con exito') {
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
                  this.$bvToast.toast("Hubo un error al enviar las boletas", {
                    title: "Error al enviar las boletas",
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
            this.$inertia.post(route("boletas.anular", [comprobante]));
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
