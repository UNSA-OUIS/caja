<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <inertia-link :href="route('dashboard')">Inicio</inertia-link>
          </li>
          <li class="breadcrumb-item">
            <inertia-link :href="route('usuarios.asignar')"
              >Lista de usuarios</inertia-link
            >
          </li>
          <li class="breadcrumb-item active">Asignar serie y correlativo</li>
        </ol>
      </div>
      <div class="card-body">
        <b-form>
          <b-row>
            <div>
              <b-table
                striped
                hover
                :items="asignaciones"
                :fields="fields"
              ></b-table>
            </div>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group
                id="input-group-1"
                label="Nombre:"
                label-for="input-1"
              >
                <b-form-input
                  id="input-1"
                  v-model="usuario.name"
                  placeholder="Nombre de usuario"
                  readonly
                ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-2"
                label="Email:"
                label-for="input-2"
              >
                <b-form-input
                  id="input-2"
                  v-model="usuario.email"
                  placeholder="Correo eletrónico"
                  readonly
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group
                id="input-group-3"
                label="Tipo de Comprobante Electronico:"
                label-for="input-3"
              >
                <b-form-select
                  id="input-3"
                  v-model="asignacion.tipo_comprobante_id"
                  :options="tipos_comprobante"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <b-button @click="buscar" variant="primary"
                  ><b-icon icon="search" aria-hidden="true"></b-icon
                ></b-button>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-4"
                label="Serie:"
                label-for="input-4"
              >
                <b-form-input
                  id="input-4"
                  v-model="asignacion.serie"
                  placeholder="Serie"
                  readonly
                ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-5"
                label="Correlativo:"
                label-for="input-5"
              >
                <b-form-input
                  id="input-5"
                  v-model="asignacion.correlativo"
                  placeholder="Correlativo"
                  readonly
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
          <b-button @click="asignar" variant="success">Asignar</b-button>
        </b-form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "usuarios.mostrar",
  props: ["asignacion", "usuario", "asignaciones"],
  components: {
    AppLayout,
  },
  data() {
    return {
      tipos_comprobante: [
        { value: 1, text: "Boleta" },
        { value: 2, text: "Factura" },
        { value: 3, text: "Nota de credito" },
        { value: 4, text: "Nota de debito" },
      ],
      fields: [
        {
          key: "tipo_comprobante.nombre",
          label: "Tipo de comprobante",
          sortable: true,
          class: "text-center",
        },
        {
          key: "serie",
          label: "Serie",
          sortable: true,
          class: "text-center",
        },
        {
          key: "correlativo",
          label: "Correlativo",
          sortable: true,
          class: "text-left",
        },
      ],
    };
  },
  methods: {
    asignar() {
      this.$inertia.post(route("asignar.registrar"), this.asignacion);
    },
    buscar() {
      this.$inertia.post(route("asignar.buscar"), this.asignacion);
    },
  },
};
</script>
<style>
.categoria {
  width: 175px;
  font-weight: bold;
}
</style>
