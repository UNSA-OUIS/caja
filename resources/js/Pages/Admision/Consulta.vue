<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Busqueda por postulante</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Busqueda por postulante</span>
      </div>
      <div class="card-body">
        <div class="row justify-content-center mb-1">
          <fieldset class="col-12 col-md-8 px-3">
            <legend>Codigo web:</legend>
            <div class="row justify-content-center mb-2">
              <div class="col col-lg-6">
                <b-form inline>
                  <b-form-input
                    v-model="codigo_web"
                    placeholder="Codigo web"
                    id="inline-form-input-codigo-web"
                    class="mb-2 mr-sm-2 mb-sm-0"
                    trim
                  ></b-form-input>
                  <b-button variant="outline-success" @click="buscar">
                    Buscar <b-icon icon="search"></b-icon>
                  </b-button>
                  <b-button variant="outline-primary" @click="limpiar">
                    Limpiar <b-icon icon="arrow-clockwise"></b-icon>
                  </b-button>
                </b-form>
              </div>
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
      </div>
      <template>
        <div>
          <b-table
            stacked
            :items="comprobante"
            :fields="fields"
            empty-text="No hay documentos para mostrar"
            empty-filtered-text="No hay documentos que coincidan con su búsqueda."
          ></b-table>
        </div>
      </template>
    </div>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "consulta.admision",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      codigo_web: "",
      alerta: false,
      alerta_mensaje: "",
      consulta: false,
      comprobante: [],
      fields: [
        {
          key: "id",
          label: "ID",
          class: "text-left",
          sortable: true,
        },
        {
          key: "cod_bancario",
          label: "Código bancario",
          class: "text-left",
          sortable: true,
        },
        {
          key: "apn",
          label: "Administrado",
          class: "text-left",
          sortable: true,
        },
        {
          key: "ndoc",
          label: "Numero de documento",
          class: "text-left",
          sortable: true,
        },
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
    buscar() {
      if (this.codigo_web == "") {
        this.alerta = true;
        this.alerta_mensaje = "Debe ingresar un codigo web";
      } else {
        const promise = axios.get(
          `${this.app_url}/admision/consulta/codigo web`,
          {
            params: {
              codigo_web: this.codigo_web,
            },
          }
        );

        return promise
          .then((response) => {
            this.comprobante = response.data.data;
            if (this.comprobante != "") {
              this.consulta = true;
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
      this.alerta = false;
      this.alerta_mensaje = "";
      this.codigo_web = "";
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
