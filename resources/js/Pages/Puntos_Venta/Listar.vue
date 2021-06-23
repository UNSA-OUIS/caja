<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Lista de puntos de venta</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Lista de puntos de venta</span>
        <inertia-link
          class="btn btn-success ml-auto"
          :href="route('puntosVenta.crear')"
        >
          Nuevo
        </inertia-link>
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
          ref="tbl_puntosVenta"
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
          empty-text="No hay puntos de venta para mostrar"
          empty-filtered-text="No hay puntos de venta que coincidan con su búsqueda."
        >
          <template v-slot:cell(user)="row">
            {{ row.item.user.name }}
          </template>
          <template v-slot:cell(condicion)="row">
            <b-badge v-if="!row.item.deleted_at" variant="success"
              >Activo</b-badge
            >
            <b-badge v-else variant="secondary">Inactivo</b-badge>
          </template>
          <template v-slot:cell(acciones)="row">
            <inertia-link
              v-if="!row.item.deleted_at"
              class="btn btn-primary btn-sm"
              :href="route('puntosVenta.mostrar', row.item.id)"
            >
              <b-icon icon="eye"></b-icon>
            </inertia-link>
            <b-button
              v-if="!row.item.deleted_at"
              variant="danger"
              size="sm"
              title="Eliminar"
              @click="eliminar(row.item)"
            >
              <b-icon icon="trash"></b-icon>
            </b-button>
            <b-button
              v-else
              variant="success"
              size="sm"
              title="Restaurar"
              @click="restaurar(row.item)"
            >
              <b-icon icon="check"></b-icon>
            </b-button>
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
  name: "puntosVenta.listar",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      puntosVenta: [],
      fields: [
        { key: "id", label: "ID", sortable: true, class: "text-center" },
        {
          key: "nombre",
          label: "Punto de venta",
          sortable: true,
          class: "text-center",
        },
        {
          key: "ip",
          label: "IP",
          sortable: true,
          class: "text-center",
        },
        { key: "direccion", label: "Dirección", class: "text-left" },
        { key: "user", label: "Usuario", class: "text-left" },
        { key: "condicion", label: "Condición", class: "text-left" },
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
      this.$refs.tbl_puntosVenta.refresh();
    },
    myProvider(ctx) {
      let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

      if (ctx.filter !== "" && ctx.filter !== null) {
        params += "&filter=" + ctx.filter;
      }

      if (ctx.sortBy !== "" && ctx.sortBy !== null) {
        params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
      }

      const promise = axios.get(`${this.app_url}/puntos-venta/listar${params}`);

      return promise.then((response) => {
        const puntosVenta = response.data.data;
        this.totalRows = response.data.total;

        return puntosVenta || [];
      });
    },
    eliminar(puntoVenta) {
      this.$bvModal
        .msgBoxConfirm(
          "¿Esta seguro de querer desactivar este punto de venta?",
          {
            title: "Desactivar punto de venta",
            okVariant: "danger",
            okTitle: "SI",
            cancelTitle: "NO",
            centered: true,
          }
        )
        .then((value) => {
          if (value) {
            this.$inertia.delete(
              route("puntosVenta.eliminar", [puntoVenta.id])
            );
            this.refreshTable();
          }
        });
    },
    async restaurar(puntoVenta) {
      this.$bvModal
        .msgBoxConfirm(
          "¿Esta seguro de querer restaurar este punto de venta?",
          {
            title: "Restaurar punto de venta",
            okVariant: "success",
            okTitle: "SI",
            cancelTitle: "NO",
            centered: true,
          }
        )
        .then((value) => {
          if (value) {
            this.$inertia.post(route("puntosVenta.restaurar", [puntoVenta.id]));
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
.breadcrumb li a {
  color: blue;
}
.breadcrumb {
  margin-bottom: 0;
  background-color: white;
}
</style>
