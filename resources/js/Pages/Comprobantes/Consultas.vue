<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <h1>Buscar documento</h1>
        <div>
          <b-form inline>
            <label class="sr-only" for="inline-form-input-serie">Serie</label>
            <b-form-input
              v-model="documento.serie"
              id="inline-form-input-serie"
              class="mb-2 mr-sm-2 mb-sm-0"
              placeholder="Serie"
            ></b-form-input>

            <label class="sr-only" for="inline-form-input-correlativo"
              >Correlativo</label
            >
            <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
              <b-form-input
                v-model="documento.correlativo"
                id="inline-form-input-correlativo"
                placeholder="Correlativo"
              ></b-form-input>
            </b-input-group>

            <template>
              <div>
                <b-button-group>
                  <b-button variant="outline-success" @click="buscar()">
                    Buscar <b-icon icon="search"></b-icon>
                  </b-button>
                  <b-button variant="outline-primary" @click="limpiar()">
                    Limpiar <b-icon icon="arrow-clockwise"></b-icon>
                  </b-button>
                </b-button-group>
              </div>
            </template>
          </b-form>
          <b-alert
            class="mb-2 mr-sm-2 mb-sm-0"
            variant="danger"
            show
            v-show="alerta"
          >
            {{ this.mensajeAlerta }}
          </b-alert>
        </div>
      </div>
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
          empty-text="No hay documentos para mostrar"
          empty-filtered-text="No hay documentos que coincidan con su búsqueda."
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
          <template v-slot:cell(usuario)="row">
                        <span v-if="row.item.tipo_usuario === 'alumno'">
                            {{ row.item.comprobanteable.apn }}
                        </span>
                        <span v-else-if="row.item.tipo_usuario === 'empresa'">
                            {{ row.item.comprobanteable.razon_social }}
                        </span>
                        <span v-else-if="row.item.tipo_usuario === 'particular'">
                            {{ row.item.comprobanteable.apellidos }}, {{ row.item.comprobanteable.nombres }}
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
              class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
              :href="route('consulta.mostrar', row.item.id)"
            >
              Detalles
            </inertia-link>
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
        {
          key: "tipo_usuario",
          label: "Tipo usuario",
          class: "text-center",
          sortable: true,
        },
        { key: "usuario", label: "Usuario", sortable: true },
        { key: "serie", label: "Serie", class: "text-center", sortable: true },
        {
          key: "correlativo",
          label: "Correlativo",
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
    buscar() {
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
            console.log(this.items);
            this.cantidad_items = this.items.length;
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
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
  },
};
</script>
