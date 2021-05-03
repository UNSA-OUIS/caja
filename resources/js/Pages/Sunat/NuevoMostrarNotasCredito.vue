<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
          </li>
          <li class="breadcrumb-item">
            <inertia-link :href="route('notas-credito.iniciar')"
              >Lista de notas de credito</inertia-link
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
                label="Nota de Credito"
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
                    credito:</label
                  >
                  <b-form-input
                    id="input-2"
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
                    emitira la nota de credito:</label
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
  name: "notas-credito.crear",
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
        { text: "ANULACION DE OPERACION", value: 1 },
        { text: "ANULACION DE ERROR DE RUC", value: 2 },
        { text: "CORRECION POR ERROR EN LA DESCRIPCION", value: 3 },
        { text: "DESCUENTO GLOBAL", value: 4 },
        { text: "DESCUENTO POR ITEM", value: 5 },
        { text: "DEVOLUCION TOTAL", value: 6 },
        { text: "DEVOLUCION POR ITEM", value: 7 },
        { text: "BONIFICACION", value: 8 },
        { text: "DISMINUCION DEL VALOR", value: 9 },
        { text: "OTROS CONCEPTOS", value: 10 },
      ],
    };
  },
  methods: {
    registrar() {
      this.$inertia.post(route("notas-credito.enviar"), this.formData);
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
