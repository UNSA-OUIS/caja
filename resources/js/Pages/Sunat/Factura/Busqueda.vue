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
        <li class="breadcrumb-item active">Enviar facturas a sunat</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Enviar facturas a sunat</span>
      </div>
      <div class="card-body">
        <div>
          <label for="example-datepicker">Fecha Inicio</label>
          <b-form-datepicker
            name="fecha_inicio"
            v-model="fecha_inicio"
            class="mb-2"
          ></b-form-datepicker>
          <label for="example-datepicker">Fecha Fin</label>
          <b-form-datepicker
            name="fecha_fin"
            v-model="fecha_fin"
            class="mb-2"
          ></b-form-datepicker>
          <b-button
            variant="success"
            size="sm"
            title="Buscar Boletas"
            @click="buscarBoletas()"
          >
            Buscar Facturas <b-icon icon="search"></b-icon>
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
        <div class="row justify-content-center mb-1" v-if="mostrar_facturas">
          <facturas
            :fecha_inicio="fecha_inicio"
            :fecha_fin="fecha_fin"
            :key="renderKey"
          >
          </facturas>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
import Facturas from "./Facturas";

export default {
  name: "facturas.busqueda",
  components: {
    AppLayout,
    Facturas,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      alerta: false,
      alerta_mensaje: "",
      fecha_inicio: "",
      fecha_fin: "",
      mostrar_facturas: false,
      renderKey: 1,
    };
  },
  methods: {
    buscarBoletas() {
      if (!this.fecha_inicio && !this.fecha_fin) {
        this.alerta = true;
        this.alerta_mensaje = "Debe seleccionar una fecha de inicio y fin";
      } else if (!this.fecha_inicio) {
        this.alerta = true;
        this.alerta_mensaje = "Debe seleccionar una fecha de inicio";
      } else if (!this.fecha_fin) {
        this.alerta = true;
        this.alerta_mensaje = "Debe seleccionar una fecha de fin";
      } else {
        this.mostrar_facturas = true;
        this.renderKey++;
        this.alerta = false;
        this.alerta_mensaje = "";
      }
    },
    limpiar() {
      this.mostrar_facturas = false;
      this.filtrado = false;
      this.alerta = false;
      this.fecha_inicio = "";
      this.fecha_fin = "";
      this.alerta_mensaje = "";
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
