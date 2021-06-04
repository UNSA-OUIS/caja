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
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Buscar comprobante</span>
      </div>
      <div class="card-body">
        <div class="row justify-content-center mb-1">
          <fieldset class="col-12 col-md-6 px-3">
            <legend>Buscar Por:</legend>
            <div class="row justify-content-center mb-2">
              <div class="col col-lg-6">
                <b-form-select size="sm" v-model="selected" :options="options">
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione uno ...</option>
                  </template>
                </b-form-select>
              </div>
            </div>
          </fieldset>
        </div>
        <div class="row justify-content-center mb-1" v-show="selected == 1">
          <fieldset class="col-12 col-md-7 px-3">
            <legend>Fecha y Número de Operación</legend>
            <div class="row justify-content-center">
              <b-form inline>
                <b-form-input
                  v-model="documento.numero_operacion"
                  placeholder="Número de Operación"
                  size="sm"
                  id="inline-form-input-numero-operacion"
                  class="mb-2 mr-sm-2 mb-sm-0"
                  trim
                ></b-form-input>
                <b-form-datepicker
                  placeholder="Fecha"
                  v-model="documento.fecha"
                  size="sm"
                  id="inline-form-custom-select-fecha"
                  class="mb-2 mr-sm-2 mb-sm-0"
                >
                </b-form-datepicker>

                <template>
                  <div>
                    <b-button-group>
                      <b-button
                        size="sm"
                        variant="outline-success"
                        @click="buscar_numero_operacion"
                      >
                        Buscar <b-icon icon="search"></b-icon>
                      </b-button>
                      <b-button
                        size="sm"
                        variant="outline-primary"
                        @click="limpiar"
                      >
                        Limpiar <b-icon icon="arrow-clockwise"></b-icon>
                      </b-button>
                    </b-button-group>
                  </div>
                </template>
              </b-form>
            </div>
          </fieldset>
        </div>

        <div class="row justify-content-center mb-1" v-show="selected == 2">
          <fieldset class="col-12 col-md-6 px-6">
            <legend>Serie y Correlativo</legend>
            <div class="row justify-content-center">
              <b-form inline>
                <b-form-input
                  v-model="documento.serie"
                  placeholder="Serie"
                  size="sm"
                  id="inline-form-input-serie"
                  class="mb-2 mr-sm-2 mb-sm-0"
                  trim
                ></b-form-input>
                <b-form-input
                  placeholder="Correlativo"
                  v-model="documento.correlativo"
                  size="sm"
                  id="inline-form-custom-select-correlativo"
                  class="mb-2 mr-sm-2 mb-sm-0"
                >
                </b-form-input>

                <template>
                  <div>
                    <b-button-group>
                      <b-button
                        size="sm"
                        variant="outline-success"
                        @click="buscar_serie_correlativo"
                      >
                        Buscar <b-icon icon="search"></b-icon>
                      </b-button>
                      <b-button
                        size="sm"
                        variant="outline-primary"
                        @click="limpiar"
                      >
                        Limpiar <b-icon icon="arrow-clockwise"></b-icon>
                      </b-button>
                    </b-button-group>
                  </div>
                </template>
              </b-form>
            </div>
          </fieldset>
        </div>
        <b-alert
          class="mb-2 mr-sm-2 mb-sm-0"
          variant="danger"
          show
          v-show="alerta"
        >
          {{ this.alerta_mensaje }}
        </b-alert>
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
              <inertia-link
                class="btn btn-secondary btn-sm btn-rounded waves-effect waves-light"
                :href="`${app_url}/${row.item.url_xml}`"
                download
              >
                XML
              </inertia-link>
              <inertia-link
                class="btn btn-secondary btn-sm btn-rounded waves-effect waves-light"
                :href="`${app_url}/${row.item.url_cdr}`"
                download
              >
                CDR
              </inertia-link>
              <!--<b-button
                v-if="
                  (row.item.tipo_comprobante_id == 2 &&
                    row.item.estado == 'aceptado') ||
                  row.item.estado == 'observado'
                "
                class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                size="sm"
                @click="visualizar_xml(row.item.url_xml)"
              >
                XML
              </b-button>
              <b-button
                v-if="
                  (row.item.tipo_comprobante_id == 2 &&
                    row.item.estado == 'aceptado') ||
                  row.item.estado == 'observado'
                "
                class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                size="sm"
                @click="visualizar_cdr(row.item.url_cdr)"
              >
                CDR
              </b-button>-->
              <b-button
                v-if="
                  row.item.estado == 'aceptado' ||
                  row.item.estado == 'observado'
                "
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
      alerta_mensaje: "",
      consulta: true,
      usuario: "",
      documento: {
        serie: "",
        correlativo: "",
        numero_operacion: "",
        fecha: "",
      },
      selected: null,
      options: [
        { value: 1, text: "Buscar por número de operación" },
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
          label: "Estado Sunat",
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
    reenviar() {
      axios
        .get(`${this.app_url}/enviarCorreo`, {
          params: {
            comprobanteId: this.comprobante[0].id,
          },
        })
        .then((response) => {
          var success = response.data.successMessage;
          this.$bvToast.toast(response.data.successMessage, {
            title: `Correo reenviado`,
            variant: "success",
            solid: true,
          });
          //console.log(success);
        })
        .catch(function (error) {
          //console.log(error);
        });
    },
    buscar_numero_operacion() {
      if (!this.documento.fecha && !this.documento.numero_operacion) {
        this.alerta = true;
        this.alerta_mensaje =
          "Debe seleccionar un numero de operacion y una fecha";
      } else if (!this.documento.fecha) {
        this.alerta = true;
        this.alerta_mensaje = "Debe seleccionar una fecha";
      } else if (!this.documento.numero_operacion) {
        this.alerta = true;
        this.alerta_mensaje = "Debe seleccionar un numero de operacion";
      } else {
        this.filtrado = true;
        this.alerta = false;
        this.alerta_mensaje = "";

        const promise = axios.get(
          `${this.app_url}/comprobantes/consultas/numero_operacion`,
          {
            params: {
              numero_operacion: this.documento.numero_operacion,
              fecha: this.documento.fecha,
            },
          }
        );

        return promise
          .then((response) => {
            if (this.comprobante != "") {
              this.totalRows = response.data.total;
              this.refreshTable();

              return this.comprobante || [];
            } else {
              this.alerta = true;
              this.alerta_mensaje = "No se encontro ningun comprobante";
            }

            return this.comprobante || [];
          })
          .catch((error) => {
            //console.log(error.response);
          });
      }
    },
    buscar_serie_correlativo() {
      if (!this.documento.serie && !this.documento.correlativo) {
        this.alerta = true;
        this.alerta_mensaje = "Debe seleccionar una serie y correlativo";
      } else if (!this.documento.serie) {
        this.alerta = true;
        this.alerta_mensaje = "Debe seleccionar una serie";
      } else if (!this.documento.correlativo) {
        this.alerta = true;
        this.alerta_mensaje = "Debe seleccionar un correlativo";
      } else {
        this.filtrado = true;
        this.alerta = false;
        this.alerta_mensaje = "";

        const promise = axios.get(
          `${this.app_url}/comprobantes/consultas/serie_correlativo`,
          {
            params: {
              serie: this.documento.serie,
              correlativo: this.documento.correlativo,
            },
          }
        );

        return promise
          .then((response) => {
            this.comprobante = response.data.data;
            if (this.comprobante != "") {
              this.totalRows = response.data.total;
              this.refreshTable();

              return this.comprobante || [];
            } else {
              this.alerta = true;
              this.alerta_mensaje = "No se encontro ningun comprobante";
            }
          })
          .catch((error) => {
            //console.log(error.response);
          });
      }
    },
    limpiar() {
      this.filtrado = false;
      this.alerta = false;
      this.documento.serie = "";
      this.documento.correlativo = "";
      this.documento.numero_operacion = "";
      this.documento.fecha = "";
      this.alerta_mensaje = "";
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
.breadcrumb li a {
  color: blue;
}
.breadcrumb {
  margin-bottom: 0;
  background-color: white;
}
</style>
