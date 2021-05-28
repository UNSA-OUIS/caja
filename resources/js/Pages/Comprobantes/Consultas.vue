<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Buscar comprobante</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header">
        <div class="card-header d-flex align-items-center">
          <span class="font-weight-bold">Buscar comprobante</span>
        </div>
        <div>
          <b-container class="bv-example-row">
            <b-row>
              <b-col></b-col>
              <b-col cols="8">
                Buscar Por:
                <b-form-select
                  size="sm"
                  class="mb-2 mr-sm-2 mb-sm-0"
                  v-model="selected"
                  :options="options"
                ></b-form-select>
                <b-form v-show="selected == 2" inline>
                  <label class="sr-only" for="inline-form-input-serie"
                    >Serie</label
                  >
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
                <b-form v-show="selected == 1" inline>
                  <label class="sr-only" for="inline-form-input-serie"
                    >Serie</label
                  >
                  <b-form-input
                    v-model="documento.numero_operacion"
                    id="inline-form-input-serie"
                    class="mb-2 mr-sm-2 mb-sm-0"
                    placeholder="Numero de Operacion"
                  ></b-form-input>

                  <label class="sr-only" for="inline-form-input-correlativo"
                    >Correlativo</label
                  >
                  <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                    <b-form-datepicker
                      name="fecha_inicio"
                      v-model="documento.fecha"
                      class="mb-2 mr-sm-2 mb-sm-0"
                      placeholder="Fecha"
                    ></b-form-datepicker>
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
                {{documento}}
                <b-alert
                  class="mb-2 mr-sm-2 mb-sm-0"
                  variant="danger"
                  show
                  v-show="alerta"
                >
                  {{ this.mensajeAlerta }}
                </b-alert></b-col
              >
              <b-col></b-col>
            </b-row>
          </b-container>
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
              v-if="row.item.tipo_comprobante == 2"
              class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
              size="sm"
              @click="visualizar_xml(row.item.url_xml)"
            >
              XML
            </b-button>
            <b-button
              v-if="row.item.tipo_comprobante == 2"
              class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
              size="sm"
              @click="visualizar_cdr(row.item.url_cdr)"
            >
              CDR
            </b-button>
            <b-button
              class="btn btn-warning btn-sm btn-rounded waves-effect waves-light"
              size="sm"
              @click="reenviar()"
            >
              Reenviar
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
        numero_operacion: "",
        fecha: "",
      },
      selected: null,
      options: [
        { value: 1, text: "Buscar por numero de operacion" },
        { value: 2, text: "Buscar por serie y correlativo" },
      ],
      comprobante: [],
      fields: [
        {
          key: "tipo_usuario",
          label: "Tipo usuario",
          class: "text-left",
          sortable: true,
        },
        {
          key: "codi_usuario",
          label: "Código usuario",
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
          key: "email",
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
    reenviar() {
      axios
        .get(`${this.app_url}/enviarCorreo`, {
          params: {
            comprobanteId: this.items[0].id,
          },
        })
        .then((response) => {
          var success = response.data;
          consolo.log(success);
        })
        .catch(function (error) {
          console.log(error);
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
    visualizar_cdr(url_cdr) {
      window.open(
        `${this.app_url}/sunat/facturaCDR?url_pdf=${url_cdr}`,
        "_blanck"
      );
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
