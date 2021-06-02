<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Boletas creadas el dia de hoy</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Boletas creadas el dia de hoy</span>
      </div>
      <div class="card-body">
        <b-table
          ref="tbl_resumen_diario"
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
          :filter="filter"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :sort-direction="sortDirection"
          @filtered="onFiltered"
          empty-text="No hay resumenes diarios para mostrar"
          empty-filtered-text="No hay resumenes diarios que coincidan con su búsqueda."
        >
          <template #table-caption
            >Se encontraron {{ totalRows }} boletas registradas que no se
            enviaron a sunat el dia de hoy</template
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
          </template>
          <template v-slot:cell(fecha)="row">
            <span>
              {{ row.item.created_at.substring(0, 10) }}
            </span>
          </template>
          <template v-slot:cell(usuario)="row">
            <span v-if="row.item.tipo_usuario === 'alumno'">
              {{ row.item.comprobanteable.apn.replace("/", " ") }}
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
          <template v-slot:cell(acciones)="row">
            <inertia-link
              v-if="!row.item.deleted_at"
              class="btn btn-primary btn-sm"
              :href="route('consulta.mostrar', row.item)"
            >
              <b-icon icon="eye"></b-icon>
            </inertia-link>
          </template>
        </b-table>
        <b-row>
          <b-button
            v-if="boletas != ''"
            variant="success"
            title="Enviar facturas a sunat"
            @click="enviar_boletas()"
          >
            Enviar boletas a Sunat
            <b-icon icon="cloud-arrow-up"></b-icon>
          </b-button>
        </b-row>
      </div>
    </div>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "resumen-diario.listar",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      boletas: "",
      fields: [
        {
          key: "tipo_usuario",
          label: "Tipo",
          class: "text-center",
        },
        {
          key: "codi_usuario",
          label: "Código",
          class: "text-center",
        },
        { key: "usuario", label: "Administrado" },
        { key: "serie", label: "Serie", class: "text-center" },
        {
          key: "correlativo",
          label: "Correlativo",
          class: "text-center",
        },
        {
          key: "fecha",
          label: "Fecha de Creacion",
          class: "text-center",
        },
        {
          key: "estado",
          label: "Estado",
          class: "text-center",
        },
        { key: "acciones", label: "Acciones", class: "text-center" },
      ],
      index: 1,
      totalRows: "",
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
    refreshTable() {
      this.$refs.tbl_resumen_diario.refresh();
    },
    myProvider() {
      const promise = axios.get(`${this.app_url}/sunat/listarBoletasActuales`);

      return promise.then((response) => {
        this.boletas = response.data;
        this.totalRows = response.data.length;

        return this.boletas || [];
      });
    },
    enviar_boletas() {
      console.log(this.boletas);
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
              .post(`${this.app_url}/sunat/resumenDiario`, this.boletas)
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
                if (error) {
                    console.log(error)
                }
              });
            this.refreshTable();
          }
        });
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
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
</style>
