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
        <li class="breadcrumb-item active">Enviar resumen diario a sunat</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Enviar resumen diario a sunat</span>
      </div>
      <div class="card-body">
        <div class="row justify-content-center mb-30">
          <fieldset class="col-20 col-md-30 px-3">
            <legend>Opciones de búsqueda:</legend>
            <div class="row justify-content-center">
              <b-form inline>
                <label>Desde:</label>
                &nbsp;&nbsp;
                <b-form-datepicker
                  name="fecha_inicio"
                  v-model="fecha_inicio"
                  class="mb-2 mr-sm-2 mb-sm-0"
                ></b-form-datepicker>
                <label>Hasta:</label>
                &nbsp;&nbsp;
                <b-form-datepicker
                  name="fecha_fin"
                  v-model="fecha_fin"
                  class="mb-2 mr-sm-2 mb-sm-0"
                ></b-form-datepicker>
                <b-button
                  class="mb-2 mr-sm-2 mb-sm-0"
                  variant="outline-success"
                  @click="buscar_boletas()"
                >
                  Buscar Boletas <b-icon icon="search"></b-icon>
                </b-button>
                <b-button
                  class="mb-2 mr-sm-2 mb-sm-0"
                  variant="outline-primary"
                  @click="limpiar()"
                >
                  Limpiar <b-icon icon="arrow-clockwise"></b-icon>
                </b-button>
              </b-form>
              <b-alert v-show="alerta" show variant="danger" dismissible>
                {{ alerta_mensaje }}
              </b-alert>
            </div>
          </fieldset>
        </div>
        <div v-if="mostrar_boletas">
          <boletas
            :fecha_inicio="fecha_inicio"
            :fecha_fin="fecha_fin"
            :key="renderKey"
          >
          </boletas>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
import Boletas from "./Boletas";

export default {
  name: "facturas.busqueda",
  components: {
    AppLayout,
    Boletas,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      alerta: false,
      alerta_mensaje: "",
      fecha_inicio: "",
      fecha_fin: "",
      mostrar_boletas: false,
      renderKey: 1,
    };
  },
  created() {
    const now = new Date();
    this.fecha_inicio = new Date(
      now.getFullYear(),
      now.getMonth(),
      now.getDate()
    );
    this.fecha_fin = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    this.buscar_boletas();
  },
  methods: {
    buscar_boletas() {
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
        this.mostrar_boletas = true;
        this.renderKey++;
        this.alerta = false;
        this.alerta_mensaje = "";
      }
    },
    limpiar() {
      this.mostrar_boletas = false;
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
