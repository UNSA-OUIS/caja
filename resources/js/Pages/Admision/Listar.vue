<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Lista</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Admision</span>
        <inertia-link
          class="btn btn-success ml-auto"
          :href="route('admision.crear')"
        >
          Nuevo
        </inertia-link>
      </div>
      <div class="card-body">
        <b-alert show variant="success" v-if="$page.props.successMessage">{{
          $page.props.successMessage
        }}</b-alert>
        <b-alert show variant="danger" v-if="$page.props.errorMessage">{{
          $page.props.errorMessage
        }}</b-alert>
        <b-row>
          <b-col sm="12" md="4" lg="4" class="my-1">
            <b-form-group
              label="Registros por página: "
              label-cols-sm="6"
              label-align-sm="right"
              label-size="sm"
              label-for="perPageSelect"
              class="mb-0"
            >
              <b-form-select
                v-model="perPage"
                id="perPageSelect"
                size="sm"
                :options="pageOptions"
              ></b-form-select>
            </b-form-group>
          </b-col>
          <b-col sm="12" offset-md="3" md="5" lg="5" class="my-1">
            <b-form-group
              label="Buscar: "
              label-cols-sm="3"
              label-align-sm="right"
              label-size="sm"
              label-for="filterInput"
              class="mb-0"
            >
              <b-input-group size="sm">
                <b-form-input
                  v-model="filter"
                  type="search"
                  id="filterInput"
                  placeholder="Escriba el texto a buscar..."
                ></b-form-input>
                <b-input-group-append>
                  <b-button :disabled="!filter" @click="filter = ''"
                    >Limpiar</b-button
                  >
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>
        <b-table
          ref="tbl_clasificadores"
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
          empty-text="No hay clasificadores para mostrar"
          empty-filtered-text="No hay clasificadores que coincidan con su búsqueda."
        >
          <template v-slot:cell(monto_total)="row">
            S/ {{ row.item.monto_total }}
          </template>
          <template v-slot:cell(proceso_id)="row">
            <span v-if="row.item.proceso_id == 1" class="font-weight"
              >Reintegro admision</span
            >
            <span v-else-if="row.item.proceso_id == 2" class="font-weight"
              >Inscripcion admision</span
            >
            <span v-else-if="row.item.proceso_id == 3" class="font-weight"
              >Pension cepreunsa</span
            >
            <span v-else-if="row.item.proceso_id == 4" class="font-weight"
              >Cambio de carrera</span
            >
          </template>
          <template v-slot:cell(tipo_colegio)="row">
            <span v-if="row.item.tipo_colegio == 'nacional'" class="font-weight"
              >Nacional</span
            >
            <span
              v-else-if="row.item.tipo_colegio == 'parroquial'"
              class="font-weight"
              >Parroquial</span
            >
            <span
              v-else-if="row.item.tipo_colegio == 'particular'"
              class="font-weight"
              >Particular</span
            >
          </template>
          <template v-slot:cell(acciones)="row">
            <inertia-link
              v-if="!row.item.deleted_at"
              class="btn btn-primary btn-sm"
              :href="route('admision.mostrar', row.item.id)"
            >
              <b-icon icon="eye"></b-icon>
            </inertia-link>
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
      </div>
    </div>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "admision.listar",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      fields: [
        {
          key: "proceso_id",
          label: "Proceso",
          sortable: true,
          class: "text-center",
        },
        { key: "monto_total", label: "Monto total", class: "text-center" },
        { key: "tipo_colegio", label: "Tipo de colegio", class: "text-center" },
        { key: "acciones", label: "Acciones", class: "text-center" },
      ],
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
    refreshTable() {
      this.$refs.tbl_clasificadores.refresh();
    },
    myProvider(ctx) {
      let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

      if (ctx.filter !== "" && ctx.filter !== null) {
        params += "&filter=" + ctx.filter;
      }

      if (ctx.sortBy !== "" && ctx.sortBy !== null) {
        params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
      }

      const promise = axios.get(`${this.app_url}/admision/listar${params}`);

      return promise.then((response) => {
        const clasificadores = response.data.data;
        this.totalRows = response.data.total;

        return clasificadores || [];
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
.breadcrumb li a {
  color: blue;
}
.breadcrumb {
  margin-bottom: 0;
  background-color: white;
}
</style>
