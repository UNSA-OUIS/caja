<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item">
          <inertia-link :href="route('admision.iniciar')">
            Lista de procesos de admision
          </inertia-link>
        </li>
        <li class="breadcrumb-item active">{{ accion }} proceso de admision</li>
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
                <b-form-group
                  id="select-group-1"
                  label="Proceso de admision:"
                  label-for="select-1"
                >
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
                  <div v-if="$page.props.errors.proceso_id" class="text-danger">
                    {{ $page.props.errors.proceso_id[0] }}
                  </div>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group
                  id="input-group-1"
                  label="Monto total:"
                  label-for="input-1"
                  ><b-form-input
                    readonly
                    class="mb-3"
                    v-model="admision.monto_total"
                    >S/. {{ precioTotal | currency }}</b-form-input
                  >
                  <div
                    v-if="$page.props.errors.monto_total"
                    class="text-danger"
                  >
                    {{ $page.props.errors.monto_total[0] }}
                  </div>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group
                  id="select-group-2"
                  label="Tipo de colegio:"
                  label-for="select-2"
                >
                  <b-form-select
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
                  </b-form-select>
                  <div
                    v-if="$page.props.errors.tipo_colegio"
                    class="text-danger"
                  >
                    {{ $page.props.errors.tipo_colegio[0] }}
                  </div>
                </b-form-group>
              </b-col>
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
  computed: {
    precioTotal() {
      if (this.accion == "Crear") {
        this.admision.monto_total = this.admision.detalles.reduce(
          (acc, item) => acc + item.subtotal,
          0
        );
        return this.admision.monto_total;
      } else {
        return this.admision.monto_total;
      }
    },
  },
  filters: {
    currency(value) {
      return value ? value.toFixed(2) : null;
    },
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
