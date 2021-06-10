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
      <template v-if="totalRows != ''" #table-caption
        >Se encontraron {{ totalRows }} registros
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
        { key: "insc_codi_web", label: "Codigo Bancario", class: "text-center" },
        { key: "apn", label: "Apellidos y Nombres", class: "text-center" },
        { key: "mont_calc", label: "Monto calculado", class: "text-center" },
        { key: "oper", label: "Registro", class: "text-center" },
        { key: "fvencimiento", label: "Fecha de Vencimiento", class: "text-center" },
        { key: "frecepcion", label: "Fecha de Recepcion", class: "text-center" },
      ],
      totalRows: "",
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
      const promise = axios.get(`${this.app_url}/banco/listar`, {
        params,
      });

      return promise.then((response) => {
        this.items = response.data;
        console.log(this.items);
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
