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
      selectable
      @row-selected="onRowSelected"
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
      <template v-slot:cell(estado)="row">
        <b-badge v-if="row.item.estado == 'no_enviado'" variant="primary"
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
        </div>
      </template>

      <template v-slot:cell(acciones)="row">
        <b-button
          v-if="row.item.estado == 'no_enviado'"
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
      <b-button
        v-if="items != ''"
        variant="success"
        title="Enviar facturas a sunat"
        @click="enviar_boletas()"
      >
        Enviar resumen diario a sunat
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
      selected: [],
      isBusy: false,
      enviado: false,
      fields: [
        { key: "tipo_usuario", label: "Tipo usuario", class: "text-center" },
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
          key: "estado",
          label: "Estado",
          class: "text-center",
          sortable: true,
        },
        { key: "acciones", label: "Acciones", class: "text-center" },
      ],
      totalRows: 1,
    };
  },
  methods: {
    refreshTable() {
      this.$refs.tbl_boletas.refresh();
    },
    onRowSelected(items) {
      this.selected = items;
    },
    myProvider() {
      let params = {
        fecha_inicio: this.fecha_inicio,
        fecha_fin: this.fecha_fin,
      };
      const promise = axios.get(`${this.app_url}/sunat/listarBoletasCuenta33`, {
        params,
      });

      return promise.then((response) => {
        this.items = response.data;
        this.totalRows = response.data.length;

        return this.items || [];
      });
    },
    enviar_boletas() {
      this.enviado = true;
      if (this.selected == "") {
        this.selected = this.items;
      }
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
              .post(`${this.app_url}/sunat/resumenDiario`, this.selected)
              .then((response) => {
                console.log(response.data);
                if (response.data.error == false) {
                  console.log(response.data.error);
                  console.log(response.data.successMessage);
                  this.$bvToast.toast("El resumen diario de envio de manera exitosa", {
                    title: "Envio de resumen diario a sunat",
                    variant: "success",
                    toaster: "b-toaster-bottom-right",
                    solid: true,
                  });
                } else {
                  console.log(response.data.error);
                  console.log(response.data.errorMessage);
                  this.$bvToast.toast("Ocurrio un error al enviar el resumen diario", {
                    title: "Envio de resumen diario a sunat",
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
        .msgBoxConfirm("¿Esta seguro de querer anular esta boleta?", {
          title: "Anular boleta",
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
