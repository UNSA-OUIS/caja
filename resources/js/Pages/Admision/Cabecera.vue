<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
                    <inertia-link :href="route('dashboard')">Inicio</inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('admision.iniciar')">
                        Lista
                    </inertia-link>
                </li>
                <li class="breadcrumb-item active">
                    {{ accion }}
                </li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Admision</span>
      </div>
      <div class="card-doby">
        <div class="container p-3">
          <b-container class="bv-example-row">
            <b-row>
              <b-col>
                <b-form-select
                  :disabled="accion == 'Mostrar'"
                  v-model="admision.proceso_id"
                  :options="procesos"
                  class="mb-3"
                >
                  <template #first>
                    <b-form-select-option :value="null" disabled
                      >Seleccione un proceso</b-form-select-option
                    >
                  </template>
                </b-form-select>
              </b-col>
              <b-col
                ><b-form-input
                  :readonly="accion == 'Mostrar'"
                  type="number"
                  v-model="admision.monto_total"
                  placeholder="Ingrese monto total"
                ></b-form-input
              ></b-col>
              <b-col
                ><b-form-select
                  :disabled="accion == 'Mostrar'"
                  v-model="admision.tipo_colegio"
                  :options="tipos_colegio"
                  class="mb-3"
                >
                  <template #first>
                    <b-form-select-option :value="null" disabled
                      >Seleccione un tipo de colegio</b-form-select-option
                    >
                  </template>
                </b-form-select></b-col
              >
            </b-row>
          </b-container>
          <template>
            <detalle :admision="admision"></detalle>
          </template>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
import Detalle from "./Detalle";

export default {
  name: "admision.cabecera",
  props: ["admision"],
  components: {
    AppLayout,
    Detalle,
  },
  data() {
    return {
      accion: "",
      app_url: this.$root.app_url,
      procesos: [
        { value: 1, text: "Reintegro admision" },
        { value: 2, text: "Inscripcion admision" },
        { value: 3, text: "Pension cepreunsa" },
        { value: 4, text: "Cambio de Carrera" },
      ],
      tipos_colegio: [
        { value: "nacional", text: "Nacional" },
        { value: "parroquial", text: "Parroquial" },
        { value: "particular", text: "Particular" },
      ],
    };
  },
  created() {
    if (!this.admision.id) {
      this.accion = "Crear";
    } else {
      this.accion = "Mostrar";
    }
  },
};
</script>
<style scoped>
.lbl-data {
  text-align: center;
  border: 0;
  padding: 0;
  display: block;
  width: 100%;
  font-size: 1rem;
  margin-bottom: 0;
  font-weight: 400;
}
.breadcrumb li a {
  color: blue;
}
.breadcrumb {
  margin-bottom: 0;
  background-color: white;
}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
