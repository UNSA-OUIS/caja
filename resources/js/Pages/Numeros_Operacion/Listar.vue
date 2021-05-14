<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">                    
            <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Lista de números de operación</li>
      </ol>
    </nav>  
    <div class="card">
      <div class="card-header d-flex align-items-center">                
        <span class="font-weight-bold">Lista de números de operación</span>
        <inertia-link class="btn btn-success ml-auto" :href="route('numerosOperacion.crear')">
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
          ref="tbl_numerosOperacion"
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
          empty-text="No hay números de operación para mostrar"
          empty-filtered-text="No hay números de operación que coincidan con su búsqueda."
        >
          <template v-slot:cell(punto_venta)="row">
            {{ row.item.punto_venta.nombre }}
          </template>
          <template v-slot:cell(tipo_comprobante)="row">
            {{ row.item.tipo_comprobante.nombre }}
          </template>
          <template v-slot:cell(condicion)="row">
            <span
              v-if="!row.item.deleted_at"
              class="badge badge-pill badge-soft-success font-size-12"
              >Activo</span
            >
            <span v-else class="badge badge-pill badge-soft-danger font-size-12"
              >Inactivo</span
            >
          </template>
          <template v-slot:cell(acciones)="row">
            <inertia-link
              v-if="!row.item.deleted_at"
              class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
              :href="route('numerosOperacion.mostrar', row.item.id)"
            >
              Ver
            </inertia-link>
            <b-button
              v-if="!row.item.deleted_at"
              class="btn btn-danger btn-sm btn-rounded waves-effect waves-light"
              @click="eliminar(row.item)"
            >
              Desactivar
            </b-button>
            <b-button
              v-else
              class="btn btn-success btn-sm btn-rounded waves-effect waves-light"
              @click="restaurar(row.item)"
            >
              Restaurar
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
  name: "numerosOperacion.listar",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      numerosOperacion: [],
      fields: [
        { key: "id", label: "ID", sortable: true, class: "text-center" },
        {
          key: "serie",
          label: "Serie",
          sortable: true,
          class: "text-center",
        },
        {
          key: "correlativo",
          label: "Correlativo",
          sortable: true,
          class: "text-center",
        },
        { key: "punto_venta", label: "Punto de venta", class: "text-left" },
        { key: "tipo_comprobante", label: "Tipo de comprobante", class: "text-left" },
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
      this.$refs.tbl_numerosOperacion.refresh();
    },
    myProvider(ctx) {
      let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

      if (ctx.filter !== "" && ctx.filter !== null) {
        params += "&filter=" + ctx.filter;
      }

      if (ctx.sortBy !== "" && ctx.sortBy !== null) {
        params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
      }

      const promise = axios.get(`${this.app_url}/numeros-operacion/listar${params}`);

      return promise.then((response) => {
        const numerosOperacion = response.data.data;
        this.totalRows = response.data.total;

        return numerosOperacion || [];
      });
    },
    eliminar(numeroOperacion) {
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer desactivar este número de operación?", {
          title: "Desactivar número de operación",
          okVariant: "danger",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then((value) => {
          if (value) {
            this.$inertia.delete(route("numerosOperacion.eliminar", [numeroOperacion.id]));
            this.refreshTable();
          }
        });
    },
    async restaurar(numeroOperacion) {
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer restaurar este número de operación?", {
          title: "Restaurar número de operación",
          okVariant: "success",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then((value) => {
          if (value) {
            this.$inertia.post(route("numerosOperacion.restaurar", [numeroOperacion.id]));
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