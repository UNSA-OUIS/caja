<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Mostrar tipo de concepto</h3>
      </div>
      <div class="card-body">
        <b-form @submit.prevent="actualizar">
          <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
            <b-form-input
              id="input-1"
              v-model="tiposConcepto.nombre"
              placeholder="Nombre de tipo de concepto"
              :disabled="!editar"
            ></b-form-input>
          </b-form-group>
          <b-button :href="route('tipos-concepto.iniciar')" variant="primary"
            ><b-icon icon="arrow-left"></b-icon
            >Atras</b-button
          >
          <b-button v-show="!editar" @click="editar = true" variant="warning"
            >Editar</b-button
          >
          <b-button type="submit" v-show="editar" variant="success"
            >Actualizar</b-button
          >
          
        </b-form>
      </div>
    </div>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "tipos-concepto.mostrar",
  props: ["tiposConcepto"],
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      editar: false,
    };
  },
  methods: {
    async actualizar() {
      try {
        const response = await axios.post(
          `${this.app_url}/tipos-concepto/${this.tiposConcepto.id}`,
          this.tiposConcepto
        );

        if (!response.data.error) {
          this.makeToast(response.data.successMessage, "success");
        } else {
          this.makeToast(response.data.errorMessage, "danger");
        }

        this.editar = false;
      } catch (error) {
        console.log(error);
        this.makeToast(
          "Se ha producido un error, vuelve a intentarlo más tarde",
          "danger"
        );
      }
    },
    makeToast(message, variant = null) {
      this.$bvToast.toast(message, {
        title: `Tipos de Concepto`,
        variant: variant,
        solid: true,
      });
    },
  },
};
</script>
