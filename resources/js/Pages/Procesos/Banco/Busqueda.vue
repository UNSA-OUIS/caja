<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Procesar cobros del banco</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Procesar cobros del banco</span>
      </div>
      <div class="card-body">
        <div class="row justify-content-center mb-1">
          <b-form inline>
            <b-form-datepicker
              name="fecha_inicio"
              v-model="fecha_inicio"
              placeholder="Desde"
            ></b-form-datepicker>
            &nbsp;&nbsp;
            <b-form-datepicker
              name="fecha_fin"
              v-model="fecha_fin"
              placeholder="Hasta"
            ></b-form-datepicker>
            &nbsp;&nbsp;
            <!--v-if="!procesar_pagos"-->
            <b-button variant="outline-success" @click="consultar_pagos()">
              Consultar Pagos <b-icon icon="search"></b-icon>
            </b-button>
            <!--<b-button
              v-if="procesar_pagos"
              variant="outline-success"
              @click="procesar_pagos()"
            >
              Procesar Pagos <b-icon icon="search"></b-icon>
            </b-button>-->
            &nbsp;&nbsp;
            <b-button variant="outline-primary" @click="limpiar()">
              Limpiar <b-icon icon="arrow-clockwise"></b-icon>
            </b-button>
          </b-form>
        </div>
        <b-alert v-show="alerta" show variant="danger" dismissible>
          {{ alerta_mensaje }}
        </b-alert>
        <hr />
        <pagos
          :fecha_inicio="fecha_inicio"
          :fecha_fin="fecha_fin"
          :key="renderKey"
        >
        </pagos>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
import Pagos from "./Pagos";

export default {
  name: "procesos.banco",
  components: {
    AppLayout,
    Pagos,
  },
  data() {
    return {
      /*procesar_pagos: false,
      procesos: [
        { value: 1, text: "Admision" },
        { value: 2, text: "Extraordinario" },
        { value: 3, text: "Posgrado" },
        { value: 4, text: "2DA Especialidad" },
        { value: 5, text: "Matricula Pregrado" },
        { value: 6, text: "Regularizacion Expediente" },
        { value: 7, text: "Deuda - CEPREUNSA" },
        { value: 8, text: "Centro de Idiomas" },
        { value: 9, text: "Residentado Medicos" },
      ],
      proceso: null,*/
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
  },
  methods: {
    consultar_pagos() {
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
        //this.procesar_pagos = true;
        this.renderKey++;
        this.alerta = false;
        this.alerta_mensaje = "";
      }
    },
    /*procesar_pagos() {
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
        this.procesar_pagos = true;
        this.renderKey++;
        this.alerta = false;
        this.alerta_mensaje = "";
      }
    },*/
    limpiar() {
      this.mostrar_boletas = false;
      //this.procesar_pagos = false;
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
