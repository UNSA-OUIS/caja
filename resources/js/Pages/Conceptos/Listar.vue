<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <ol class="breadcrumb float-left">
          <li class="breadcrumb-item">
            <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
          </li>
          <li class="breadcrumb-item active">Lista de conceptos</li>
        </ol>
        <inertia-link
          class="btn btn-success float-right"
          :href="route('conceptos.crear')"
          >Nuevo</inertia-link
        >
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
          ref="tbl_conceptos"
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
          empty-text="No hay conceptos para mostrar"
          empty-filtered-text="No hay conceptos que coincidan con su búsqueda."
        >
          <template v-slot:cell(tipo_concepto)="row">
            {{ row.item.tipo_concepto.nombre }}
          </template>
          <template v-slot:cell(clasificador)="row">
            {{ row.item.clasificador.nombre }}
          </template>
          <template v-slot:cell(unidad_medida)="row">
            {{ row.item.unidad_medida.nombre }}
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
              :href="route('conceptos.mostrar', row.item.id)"
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
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4">Latest Transaction</h4>
            <div class="table-responsive">
              <table class="table table-centered table-nowrap mb-0">
                <thead class="thead-light">
                  <tr>
                    <th style="width: 20px">
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck1"
                        />
                        <label class="custom-control-label" for="customCheck1"
                          >&nbsp;</label
                        >
                      </div>
                    </th>
                    <th>ID</th>
                    <th>Codigo</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Condicion</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="concepto in conceptos">
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck2"
                        />
                        <label class="custom-control-label" for="customCheck2"
                          >&nbsp;</label
                        >
                      </div>
                    </td>
                    <td>
                      <a
                        href="javascript: void(0);"
                        class="text-body font-weight-bold"
                        >{{ concepto.id }}</a
                      >
                    </td>
                    <td>{{ concepto.codigo }}</td>
                    <td>{{ concepto.descripcion }}</td>
                    <td>{{ concepto.precio }}</td>
                    <td>
                      <span
                        v-if="concepto.estado == true"
                        class="badge badge-pill badge-soft-success font-size-12"
                        >Activo</span
                      ><span
                        v-else
                        class="badge badge-pill badge-soft-danger font-size-12"
                        >Inactivo</span
                      >
                    </td>
                    <td>
                      <!-- Button trigger modal -->
                      <button
                        type="button"
                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                      >
                        View Details
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- end table-responsive -->
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "conceptos.listar",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      conceptos: [],
      fields: [
        { key: "id", label: "ID", sortable: true, class: "text-center" },
        { key: "codigo", label: "Código", sortable: true },
        { key: "descripcion", label: "Descripción", sortable: true },
        { key: "precio", label: "Precio", class: "text-center" },
        { key: "tipo_concepto", label: "Tipo concepto", class: "text-center" },
        { key: "clasificador", label: "Clasificador", class: "text-center" },
        { key: "unidad_medida", label: "Unidad medida", class: "text-center" },
        { key: "condicion", label: "Condición", class: "text-center" },
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
      this.$refs.tbl_conceptos.refresh();
    },
    myProvider(ctx) {
      let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

      if (ctx.filter !== "" && ctx.filter !== null) {
        params += "&filter=" + ctx.filter;
      }

      if (ctx.sortBy !== "" && ctx.sortBy !== null) {
        params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
      }

      const promise = axios.get(`${this.app_url}/conceptos/listar${params}`);

      return promise.then((response) => {
        const conceptos = response.data.data;
        this.totalRows = response.data.total;

        return conceptos || [];
      });
    },
    eliminar(concepto) {
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer eliminar este concepto?", {
          title: "Eliminar concepto",
          okVariant: "danger",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then((value) => {
          if (value) {
            this.$inertia.delete(route("conceptos.eliminar", [concepto.id]));
            this.refreshTable();
          }
        });
    },
    async restaurar(concepto) {
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer restaurar este concepto?", {
          title: "Restaurar concepto",
          okVariant: "primary",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then((value) => {
          if (value) {
            this.$inertia.post(route("conceptos.restaurar", [concepto.id]));
            this.refreshTable();
          }
        });
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
  },
  created: function () {
    axios
      .get(`${this.app_url}/conceptos/listar`)
      .then((response) => (this.conceptos = response.data.data));
  },
};
</script>
