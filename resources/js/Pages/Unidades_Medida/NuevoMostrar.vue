<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <inertia-link :href="route('dashboard')">Inicio</inertia-link>
          </li>
          <li class="breadcrumb-item">
            <inertia-link :href="route('unidades-medida.iniciar')"
              >Lista de unidades de medida</inertia-link
            >
          </li>
          <li class="breadcrumb-item active">{{ accion }} unidad de medida</li>
        </ol>
      </div>
      <div class="card-body">
        <b-form>
          <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
            <b-form-input
              id="input-1"
              v-model="unidadMedida.nombre"
              placeholder="Nombre de unidad de medida"
              :readonly="accion == 'Mostrar'"
            ></b-form-input>
            {{ this.$store.getters.getEntradaAntigua }}
            <div v-if="$page.props.errors.nombre" class="text-danger">
              {{ $page.props.errors.nombre[0] }}
            </div>
          </b-form-group>
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

export default {
  name: "unidades-medida.mostrar",
  props: ["unidadMedida"],
  components: {
    AppLayout,
  },
  data() {
    return {
      accion: "",
      entrada_antigua: this.$store.getters.getEntradaAntigua,
    };
  },
  created() {
    if (!this.unidadMedida.id) {
      this.accion = "Crear";
      const entrada_antigua = "";
      this.$store.dispatch("setEntradaAntigua", entrada_antigua);
    } else {
      this.accion = "Mostrar";
    }
  },
  methods: {
    registrar() {
      this.$inertia.post(route("unidades-medida.registrar"), this.unidadMedida);
      const entrada_antigua = this.unidadMedida.nombre;
      this.$store.dispatch("setEntradaAntigua", entrada_antigua);
    },
    actualizar() {
      this.$inertia.post(
        route("unidades-medida.actualizar", [this.unidadMedida.id]),
        this.unidadMedida
      );
    },
  },
};
</script>
