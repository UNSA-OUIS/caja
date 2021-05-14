<template>
  <app-layout>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item ml-auto">
                <inertia-link :href="route('dashboard')">Inicio</inertia-link>
            </li>
            <li class="breadcrumb-item">
                <inertia-link :href="route('puntosVenta.iniciar')">
                    Lista de puntos de venta
                </inertia-link>
            </li>
            <li class="breadcrumb-item active">
                {{ accion }} punto de venta
            </li>
        </ol>
    </nav> 
    <div class="card">
      <div class="card-header d-flex align-items-center">
          <span class="font-weight-bold">{{ accion }} punto de venta</span>
      </div>      
      <div class="card-body">
        <b-form @submit.prevent="enviar">
          <b-row>
            <b-col cols="6">
              <b-form-group
                id="input-group-6"
                label="Nombre:"
                label-for="input-6"
              >
                <b-form-input
                  id="input-6"
                  v-model="formData.nombre"
                  placeholder="Ingrese nombre"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.nombre" class="text-danger">
                  {{ $page.props.errors.nombre[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group
                id="input-group-1"
                label="Dirección:"
                label-for="input-1"
              >
                <b-form-input
                  id="input-1"
                  v-model="formData.direccion"
                  type="text"
                  placeholder="Ingrese dirección"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.direccion" class="text-danger">
                  {{ $page.props.errors.direccion[0] }}
                </div>
              </b-form-group>
            </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <b-form-group
                        id="input-group-2"
                        label="IP:"
                        label-for="input-2"
                    >
                        <b-form-input
                        id="input-2"
                        v-model="formData.ip"
                        type="text"
                        placeholder="Ingrese IP"
                        :readonly="accion == 'Mostrar'"
                        ></b-form-input>
                        <div v-if="$page.props.errors.ip" class="text-danger">
                        {{ $page.props.errors.ip[0] }}
                        </div>
                    </b-form-group>
                </b-col>
              
            <b-col>
              <b-form-group
                id="input-group-3"
                label="Usuario:"
                label-for="input-3"
              >
                <b-form-select
                  id="input-3"
                  v-model="formData.user_id"
                  :options="usuarios"
                  :disabled="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.user_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.user_id[0] }}
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
  name: "puntosVenta.nuevo-mostrar",
  props: ["puntoVenta", "usuarios"],
  components: {
    AppLayout,
  },
  remember: "formData",
  data() {
    return {
      app_url: this.$root.app_url,
      formData: this.puntoVenta,
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
      this.$inertia.post(route("puntosVenta.registrar"), this.formData);
    },
    actualizar() {
      this.$inertia.post(
        route("puntosVenta.actualizar", [this.formData.id]),
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
