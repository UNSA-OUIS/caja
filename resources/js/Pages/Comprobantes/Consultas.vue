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
        <b-table
          stacked
          :items="comprobante"
          :fields="fields"
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
              class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
              :href="route('consulta.comprobante', row.item.id)"
            >
              Detalles
            </inertia-link>
            <b-button
              class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
              size="sm"
              @click="visualizar_pdf(row.item.url_pdf)"
            >
              PDF
            </b-button>
            <b-button
              v-if="row.item.tipo_comprobante === 2"
              class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
              size="sm"
              @click="visualizar_xml(row.item.url_xml)"
            >
              XML
            </b-button>
          </template>
        </b-table>
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
      consulta: true,
      documento: {
        serie: "",
        correlativo: "",
      },
      comprobante: [],
      fields: [
        {
          key: "codi_usuario",
          label: "Código usuario",
          class: "text-left",
          sortable: true,
        },
        {
          key: "tipo_usuario",
          label: "Tipo usuario",
          class: "text-left",
          sortable: true,
        },
        {
          key: "usuario",
          label: "Administrado",
          class: "text-left",
          sortable: true,
        },
        {
          key: "comprobanteable.email",
          label: "Email",
          class: "text-left",
          sortable: true,
        },
        { key: "serie", label: "Serie", class: "text-left", sortable: true },
        {
          key: "correlativo",
          label: "Correlativo",
          class: "text-left",
          sortable: true,
        },
        {
          key: "estado",
          label: "Estado",
          class: "text-left",
          sortable: true,
        },
        { key: "acciones", label: "Acciones", class: "text-left" },
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
            this.comprobante = response.data.data;
            console.log(this.comprobante);
            this.totalRows = response.data.total;
            this.refreshTable();

            return this.comprobante || [];
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

    visualizar_pdf(url_pdf) {
      window.open(
        `${this.app_url}/sunat/facturaPDF?url_pdf=${url_pdf}`,
        "_blanck"
      );
    },
    visualizar_xml(url_xml) {
      window.open(
        `${this.app_url}/sunat/facturaXML?url_pdf=${url_xml}`,
        "_blanck"
      );
    },
  },
};
</script>
