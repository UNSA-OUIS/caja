<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item">
          <inertia-link :href="route('cobros.iniciar')">
            Lista de cobros
          </inertia-link>
        </li>
        <li class="breadcrumb-item active">
          Enviar de notas de debito
        </li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Envio de nota de debito</span>
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
          ref="tbl_notas_credito"
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
          empty-text="No hay notas de credito para mostrar"
          empty-filtered-text="No hay notas de credito que coincidan con su búsqueda."
        >
          <template v-slot:cell(condicion)="row">
            <b-badge v-if="!row.item.deleted_at" variant="success"
              >Activo</b-badge
            >
            <b-badge v-else variant="secondary">Inactivo</b-badge>
          </template>
          <template v-slot:cell(codigo_nota)="row">
            <span v-if="row.item.tipo_nota == '01'"> Interés por mora </span>
            <span v-else-if="row.item.tipo_nota == '02'">
              Aumento en el valor
            </span>
            <span v-else-if="row.item.tipo_nota == '03'"> Penalidades </span>
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
  name: "notas-credito.listar",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      fields: [
        { key: "tipo_usuario", label: "Tipo usuario", class: "text-center" },
        { key: "codi_usuario", label: "Código usuario", class: "text-center" },
        { key: "usuario", label: "Administrado", sortable: true },
        { key: "serie", label: "Serie", class: "text-center" },
        { key: "correlativo", label: "Correlativo", class: "text-center" },
        { key: "codigo_nota", label: "Motivo", class: "text-center" },
        { key: "motivo", label: "Descripcion Motivo", class: "text-center" },
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
      this.$refs.tbl_notas_credito.refresh();
    },
    myProvider(ctx) {
      let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

      if (ctx.filter !== "" && ctx.filter !== null) {
        params += "&filter=" + ctx.filter;
      }

      if (ctx.sortBy !== "" && ctx.sortBy !== null) {
        params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
      }

      const promise = axios.get(`${this.app_url}/notas-debito/listar${params}`);

      return promise.then((response) => {
        const notaCredito = response.data.data;
        this.totalRows = response.data.total;

        return notaCredito || [];
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
            this.$inertia.post(route("comprobantes.anular", [comprobante]));
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
