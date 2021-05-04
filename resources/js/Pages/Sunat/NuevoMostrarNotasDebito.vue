<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
          </li>
          <li class="breadcrumb-item">
            <inertia-link :href="route('notas-debito.iniciar')"
              >Lista de notas de debito</inertia-link
            >
          </li>
          <li class="breadcrumb-item active">Nuevo</li>
        </ol>
      </div>
      <div class="card-doby">
        <div class="container p-3">
          <div>
            <b-card bg-variant="light">
              <b-form-group
                label-cols-lg="3"
                label="Nota de Debito"
                label-size="lg"
                label-class="font-weight-bold pt-0"
                class="mb-0"
              >
                <b-row>
                  <label for="example-datepicker">Fecha de Emision</label>
                  <b-form-datepicker
                    id="example-datepicker"
                    v-model="formData.fechaEmision"
                    class="mb-2"
                  ></b-form-datepicker>
                </b-row>
                <b-row>
                  <label for="example-datepicker"
                    >Tipo de Nota de Credito</label
                  >
                  <b-form-select
                    v-model="formData.motivo_codigo"
                    :options="motivos"
                  >
                    <template v-slot:first>
                      <option :value="null" disabled>Seleccione...</option>
                    </template>
                  </b-form-select>
                </b-row>
                <b-row>
                  <label for="example-datepicker"
                    >Motivo o sustento por el cual se emitira la nota de
                    debito:</label
                  >
                  <b-form-input
                    v-model="formData.motivo_descripcion"
                    type="text"
                    placeholder="Ingrese descripción"
                  ></b-form-input>
                </b-row>
              </b-form-group>
            </b-card>
          </div>
          <div>
            <b-card bg-variant="light">
              <b-form-group
                label-cols-lg="3"
                label="Documento que modifica"
                label-size="lg"
                label-class="font-weight-bold pt-0"
                class="mb-0"
              >
                <b-row>
                  <label for="example-datepicker"
                    >Serie y numero de comprobante respecto de la cual se
                    emitira la nota de debito:</label
                  >
                  <b-form-input
                    v-model="formData.serie"
                    type="text"
                    placeholder="Ingrese serie"
                  ></b-form-input>
                  <b-form-input
                    v-model="formData.correlativo"
                    type="text"
                    placeholder="Ingrese correlativo"
                  ></b-form-input>
                </b-row>
              </b-form-group>
            </b-card>
          </div>
          <div>
            <b-card bg-variant="light">
              <b-form-group
                label-cols-lg="3"
                label="Detalle"
                label-size="lg"
                label-class="font-weight-bold pt-0"
                class="mb-0"
              >
              </b-form-group>
            </b-card>
          </div>
        </div>
      </div>
    </div>

    <b-button @click="registrar" variant="success">Registrar</b-button>
  </app-layout>
</template>
<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";
export default {
  name: "notas-debito.crear",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      formData: {
        id: 1,
        fechaEmision: "",
        motivo_codigo: "",
        motivo_descripcion: "",
        serie: "",
        correlativo: "",
      },
      motivos: [
        { text: "INTERES POR MORA", value: 1 },
        { text: "AUMENTO DE VALOR", value: 2 },
        { text: "PENALIDADES", value: 3 },
      ],
    };
  },
  methods: {
    registrar() {
      this.$inertia.post(route("notas-debito.enviar"), this.formData);
    },
  },
};
</script>
<style>
.codigo {
  width: 150px;
}
.concepto {
  width: 400px;
}
</style>
