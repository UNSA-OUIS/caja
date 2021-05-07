<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <h1>Buscar documento</h1>
        <div>
          <label for="example-input">Ingrese serie</label>
          <b-form-input
            v-model="documento.serie"
            placeholder="Serie"
            class="mb-2"
          ></b-form-input>
          <label for="example-input">Ingrese correlativo</label>
          <b-form-input
            v-model="documento.correlativo"
            placeholder="Correlativo"
            class="mb-2"
          ></b-form-input>
          <b-button
            variant="success"
            size="sm"
            title="Buscar Boletas"
            @click="buscarDocumento()"
          >
            Buscar Documentos <b-icon icon="search"></b-icon>
          </b-button>
          <b-button
            variant="primary"
            size="sm"
            title="Limpiar"
            @click="limpiar()"
          >
            Limpiar <b-icon icon="arrow-clockwise"></b-icon>
          </b-button>
        </div>
      </div>
      <b-alert variant="danger" show v-show="alerta">
        {{ this.mensajeAlerta }}
      </b-alert>
      <div class="card-body" v-show="filtrado">
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
          ref="tbl_boletas"
          show-empty
          striped
          hover
          bordered
          small
          responsive
          stacked="md"
          :items="items"
          :fields="fields"
          :current-page="currentPage"
          :per-page="perPage"
          :filter="filter"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :sort-direction="sortDirection"
          @filtered="onFiltered"
          empty-text="No hay boletas para mostrar"
          empty-filtered-text="No hay boletas que coincidan con su búsqueda."
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
            <div v-if="row.item.estado == 'aceptado'">
              <b-badge variant="success">Aceptado</b-badge>
              <br />
              <a :href="`${app_url}/${row.item.url_xml}`" download>XML</a>
              <a :href="`${app_url}/${row.item.url_cdr}`" download>CDR</a>
            </div>
          </template>
          <template v-slot:cell(acciones)="row">
            <b-button
              v-if="
                row.item.estado == 'aceptado' || row.item.estado == 'observado'
              "
              target="_blank"
              variant="primary"
              size="sm"
              title="Ver"
              :href="`${app_url}/${row.item.url_pdf}`"
            >
              <b-icon icon="printer"></b-icon>
            </b-button>
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
              v-if="
                row.item.estado == 'observado' || row.item.estado == 'aceptado'
              "
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
            >Se encontraron {{ cantidad_items }} documentos</template
          >
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
            variant="success"
            size="sm"
            title="Enviar"
            @click="enviarResumenDiario()"
          >
            Enviar Resumen Diario <b-icon icon="cloud-arrow-up"></b-icon>
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
  name: "sunat.listarBoletas",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      filtrado: false,
      alerta: false,
      mensajeAlerta: "",
      documento: {
        serie: "",
        correlativo: "",
      },
      items: [],
      cantidad_items: 0,
      fields: [
        {
          key: "codi_usuario",
          label: "Código usuario",
          class: "text-center",
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
          key: "created_at",
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
      index: 1,
      totalRows: 1,
      currentPage: 1,
      perPage: 500,
      pageOptions: [500, 1000, 1500],
      sortBy: null,
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
    };
  },
  methods: {
    refreshTable() {
      this.$refs.tbl_boletas.refresh();
    },
    enviarResumenDiario() {
      console.log(this.items);
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer enviar el resumen diario?", {
          title: "Enviar resumen diario",
          okVariant: "success",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then(async (value) => {
          if (value) {
            this.$inertia.post(route("boletas.resumen-diario"), this.items);
            this.refreshTable();
          }
        });
    },
    buscarDocumento() {
      if (!this.documento.serie && !this.documento.correlativo) {
        this.alerta = true;
        this.mensajeAlerta = "Debe seleccionar una serie y correlativo";
      } else if (!this.documento.serie) {
        this.alerta = true;
        this.mensajeAlerta = "Debe seleccionar una serie";
      } else if (!this.documento.correlativo) {
        this.alerta = true;
        this.mensajeAlerta = "Debe seleccionar un correlativo";
      } else {
        this.filtrado = true;
        this.alerta = false;
        this.mensajeAlerta = "";

        const promise = axios.get(`${this.app_url}/comprobantes/consultas`, {
          params: {
            serie: this.documento.serie,
            correlativo: this.documento.correlativo,
          },
        });

        return promise
          .then((response) => {
            this.items = response.data.data;
            this.cantidad_items = this.items.length;
            console.log(this.items);
            this.totalRows = response.data.total;
            this.refreshTable();

            return this.items || [];
          })
          .catch((error) => {
            console.log(error.response);
          });
      }
    },
    limpiar() {
      this.filtrado = false;
      this.alerta = false;
      this.documento.serie = "";
      this.documento.correlativo = "";
      this.mensajeAlerta = "";
      this.refreshTable();
    },
    anular(boleta) {
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
            this.$inertia.post(route("boletas.anular", [boleta]));
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
