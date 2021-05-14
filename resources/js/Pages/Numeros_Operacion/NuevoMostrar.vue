<template>
  <app-layout>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item ml-auto">
                <inertia-link :href="route('dashboard')">Inicio</inertia-link>
            </li>
            <li class="breadcrumb-item">
                <inertia-link :href="route('puntosVenta.iniciar')">
                    Lista de números de operación
                </inertia-link>
            </li>
            <li class="breadcrumb-item active">
                {{ accion }} número de operación
            </li>
        </ol>
    </nav> 
    <div class="card">
      <div class="card-header d-flex align-items-center">
          <span class="font-weight-bold">{{ accion }} número de operación</span>
      </div>      
      <div class="card-body">
        <b-form @submit.prevent="enviar">
                <b-row>
                <b-col cols="6">
                    <b-form-group
                        id="input-group-2"
                        label="Punto de venta:"
                        label-for="input-2"
                    >
                        <b-form-select
                        id="input-2"
                        v-model="formData.punto_venta_id"
                        :options="puntosVenta"
                        :disabled="accion == 'Mostrar'"
                        >
                            <template v-slot:first>
                                <option :value="null" disabled>Seleccione...</option>
                            </template>
                        </b-form-select>
                        <div v-if="$page.props.errors.punto_venta_id" class="text-danger">
                        {{ $page.props.errors.punto_venta_id[0] }}
                        </div>
                    </b-form-group>
                </b-col>
              
            <b-col>
              <b-form-group
                id="input-group-3"
                label="Tipo de comprobante:"
                label-for="input-3"
              >
                <b-form-select
                  id="input-3"
                  v-model="formData.tipo_comprobante_id"
                  :options="tiposComprobante"
                  :disabled="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.tipo_comprobante_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.tipo_comprobante_id[0] }}
                </div>
              </b-form-group>
            </b-col>
            </b-row>
            <b-row>
            <b-col cols="6">
              <b-form-group
                id="input-group-6"
                label="Nombre:"
                label-for="input-6"
              >
                <b-form-input
                  id="input-6"
                  v-model="formData.serie"
                  placeholder="Ingrese serie"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.serie" class="text-danger">
                  {{ $page.props.errors.serie[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group
                id="input-group-1"
                label="Correlativo:"
                label-for="input-1"
              >
                <b-form-input
                  id="input-1"
                  v-model="formData.correlativo"
                  type="text"
                  placeholder="Ingrese correlativo"
                  :readonly="true"
                ></b-form-input>
                <div v-if="$page.props.errors.correlativo" class="text-danger">
                  {{ $page.props.errors.correlativo[0] }}
                </div>
              </b-form-group>
            </b-col>
            </b-row>

          <b-button
            v-if="accion == 'Crear'"
            @click="registrar"
            variant="success"
            >Registrar</b-button
          >
          <b-button
            v-else-if="accion == 'Mostrar'"
            @click="accion = 'Editar'"
            variant="warning"
            >Editar</b-button
          >
          <b-button
            v-else-if="accion == 'Editar'"
            @click="actualizar"
            variant="success"
            >Actualizar</b-button
          >
        </b-form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
const axios = require("axios");

export default {
  name: "numerosOperacion.nuevo-mostrar",
  props: ["numeroOperacion", "puntosVenta", "tiposComprobante"],
  components: {
    AppLayout,
  },
  remember: "formData",
  data() {
    return {
      app_url: this.$root.app_url,
      formData: this.numeroOperacion,
      accion: "",
    };
  },
  created() {
    if (!this.formData.id) {
      this.accion = "Crear";
    } else {
      this.accion = "Mostrar";
    }
  },
  methods: {
    enviar() {
      if (this.accion == "Crear") {
        this.registrar();
      } else if (this.accion == "Mostrar") {
        this.accion = "Editar";
      } else if (this.accion == "Editar") {
        this.actualizar();
      }
    },
    registrar() {
      this.$inertia.post(route("numerosOperacion.registrar"), this.formData);
    },
    actualizar() {
      this.$inertia.post(
        route("numerosOperacion.actualizar", [this.formData.id]),
        this.formData
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
